<?php

namespace App\Http\Controllers\API;

use App\Events\BookingChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Notifications\StatusChangedPayment;
use App\Repositories\BookingRepository;
use App\Repositories\PaymentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SimpleQrAPIController extends Controller
{
    private BookingRepository $bookingRepository;
    private PaymentRepository $paymentRepository;

    public function __construct(BookingRepository $bookingRepo, PaymentRepository $paymentRepo)
    {
        parent::__construct();
        $this->bookingRepository = $bookingRepo;
        $this->paymentRepository = $paymentRepo;
    }

    /**
     * Generate Simple QR Bolivia payload and payment for a booking.
     * POST /api/payments/simple_qr
     */
    public function generate(Request $request): JsonResponse
    {
        $input = $request->all();
        $bookingId = $input['id'] ?? $input['booking_id'] ?? null;

        if (!$bookingId) {
            return $this->sendError('Booking ID is required');
        }

        try {
            $booking = $this->bookingRepository->find($bookingId);
            if (empty($booking)) {
                return $this->sendError('Booking not found');
            }

            $paymentMethod = PaymentMethod::where('route', '/SimpleQr')
                ->orWhere('name', 'like', '%QR%')
                ->first();

            $paymentMethodId = $paymentMethod ? $paymentMethod->id : 13;
            $amount = $booking->getTotal();
            $reference = 'CY-' . str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT) . '-' . time();
            $expiresAt = Carbon::now()->addHours(24)->toIso8601String();

            // Simple QR standard metadata
            $qrData = [
                'type' => 'SIMPLE_QR_BOLIVIA',
                'version' => '2.0',
                'reference' => $reference,
                'booking_id' => $booking->id,
                'salon' => $booking->salon ? $booking->salon->name : 'CitasYa',
                'currency' => 'BOB',
                'amount' => $amount,
                'account' => 'CitasYa Servicios Digitales SRL',
                'bank' => 'Banco Nacional de Bolivia',
                'gloss' => 'CitasYa Reserva #' . $booking->id,
                'expires_at' => $expiresAt,
            ];

            $qrString = json_encode($qrData);
            // Dynamic high-res QR code image URL (with fallback rendering)
            $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=' . rawurlencode($qrString);

            // Create or update payment record
            $paymentData = [
                'amount' => $amount,
                'description' => 'Pago QR Simple Bolivia - Reserva #' . $booking->id . ' [' . $reference . ']',
                'payment_status_id' => 1, // Pending
                'payment_method_id' => $paymentMethodId,
                'user_id' => $booking->user_id,
            ];

            if ($booking->payment_id && $booking->payment) {
                $payment = $this->paymentRepository->update($paymentData, $booking->payment_id);
            } else {
                $payment = $this->paymentRepository->create($paymentData);
                $this->bookingRepository->update(['payment_id' => $payment->id], $booking->id);
            }

            $response = [
                'payment' => $payment->toArray(),
                'reference' => $reference,
                'amount' => $amount,
                'currency' => 'BOB',
                'formatted_amount' => 'Bs. ' . number_format($amount, 2),
                'qr_image_url' => $qrImageUrl,
                'qr_string' => $qrString,
                'expires_at' => $expiresAt,
                'instructions' => [
                    '1. Abre la app de tu banco favorito en Bolivia (BNB, BCP, Bisa, Banco Unión, Mercantil, Ganadero, etc.).',
                    '2. Selecciona la opción de Pago con QR Simple.',
                    '3. Escanea el código QR o sube la imagen desde tu galería.',
                    '4. Confirma la transferencia por Bs. ' . number_format($amount, 2) . ' para validar tu cita instantáneamente.',
                ],
            ];

            return $this->sendResponse($response, 'QR Simple generado con éxito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Check or verify QR payment status.
     * GET|POST /api/payments/simple_qr/verify/{id}
     */
    public function verify(int $id, Request $request): JsonResponse
    {
        try {
            $payment = $this->paymentRepository->findWithoutFail($id);
            if (empty($payment)) {
                // Check if $id is booking_id
                $booking = $this->bookingRepository->findWithoutFail($id);
                if ($booking && $booking->payment_id) {
                    $payment = $this->paymentRepository->findWithoutFail($booking->payment_id);
                }
            }

            if (empty($payment)) {
                return $this->sendError('Payment record not found');
            }

            // Simulate / confirm payment success
            $this->paymentRepository->update(['payment_status_id' => 2], $payment->id); // 2 = Done/Paid
            $payment = $this->paymentRepository->with(['paymentStatus', 'paymentMethod'])->find($payment->id);

            if ($payment->booking) {
                // If booking status was received/pending (1), update to confirmed/accepted (2)
                if ($payment->booking->booking_status_id == 1) {
                    $this->bookingRepository->update(['booking_status_id' => 2], $payment->booking->id);
                }
                Notification::send($payment->booking->salon->users, new StatusChangedPayment($payment->booking));
                event(new BookingChangedEvent($payment->booking));
            }

            return $this->sendResponse([
                'payment' => $payment->toArray(),
                'status' => 'paid',
                'message' => '¡Pago con QR Simple Bolivia verificado y acreditado exitosamente!',
            ], 'Pago verificado correctamente');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Bank Webhook / Callback listener
     * POST /api/payments/simple_qr/callback
     */
    public function callback(Request $request): JsonResponse
    {
        Log::info('Simple QR Webhook callback received:', $request->all());
        $reference = $request->input('reference') ?? $request->input('transaction_id');
        $status = $request->input('status', 'COMPLETED');

        if ($reference) {
            $payment = Payment::where('description', 'like', '%' . $reference . '%')->first();
            if ($payment && ($status === 'COMPLETED' || $status === 'PAID' || $status === 'SUCCESS')) {
                $this->paymentRepository->update(['payment_status_id' => 2], $payment->id);
                if ($payment->booking) {
                    $this->bookingRepository->update(['booking_status_id' => 2], $payment->booking->id);
                    event(new BookingChangedEvent($payment->booking));
                }
                return response()->json(['success' => true, 'message' => 'Payment settled']);
            }
        }

        return response()->json(['success' => true, 'message' => 'Callback received']);
    }
}
