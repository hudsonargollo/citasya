<?php

namespace App\Http\Controllers\API\SalonOwner;

use App\Events\BookingChangedEvent;
use App\Events\BookingStatusChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Salon;
use App\Repositories\BookingRepository;
use App\Repositories\BookingStatusRepository;
use App\Repositories\PaymentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;

class BookingAPIController extends Controller
{
    private BookingRepository $bookingRepository;
    private BookingStatusRepository $bookingStatusRepository;
    private PaymentRepository $paymentRepository;

    public function __construct(
        BookingRepository $bookingRepo,
        BookingStatusRepository $bookingStatusRepo,
        PaymentRepository $paymentRepo
    ) {
        parent::__construct();
        $this->bookingRepository = $bookingRepo;
        $this->bookingStatusRepository = $bookingStatusRepo;
        $this->paymentRepository = $paymentRepo;
    }

    /**
     * Display a listing of bookings for the salons of the authenticated owner.
     * GET /api/salon_owner/bookings
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $salonIds = $user->salons()->pluck('salons.id')->toArray();

            $query = Booking::whereIn('salon->id', $salonIds)
                ->orderBy('booking_at', 'desc');

            if ($request->has('status_id')) {
                $query->where('booking_status_id', $request->get('status_id'));
            }

            if ($request->has('date')) {
                $date = Carbon::parse($request->get('date'));
                $query->whereDate('booking_at', $date->toDateString());
            }

            $bookings = $query->paginate($request->get('per_page', 20));

            return $this->sendResponse($bookings->toArray(), 'Salon bookings retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified Booking.
     * GET /api/salon_owner/bookings/{id}
     */
    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $booking = $this->bookingRepository->findWithoutFail($id);
            if (empty($booking)) {
                return $this->sendError('Booking not found');
            }

            // Verify salon owner owns this salon
            $user = auth()->user();
            $salonIds = $user->salons()->pluck('salons.id')->toArray();
            if ($booking->salon && !in_array($booking->salon->id, $salonIds) && !$user->hasRole('admin')) {
                return $this->sendError('Unauthorized access to this booking');
            }

            return $this->sendResponse($booking->toArray(), 'Booking retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Update the specified Booking in storage (status change, employee assignment).
     * PUT /api/salon_owner/bookings/{id}
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $oldBooking = $this->bookingRepository->findWithoutFail($id);
        if (empty($oldBooking)) {
            return $this->sendError('Booking not found');
        }

        $input = $request->all();
        try {
            if (isset($input['cancel']) && $input['cancel'] == '1') {
                $input['payment_status_id'] = 3;
                $input['booking_status_id'] = 7;
            }

            $booking = $this->bookingRepository->update($input, $id);

            if (isset($input['payment_status_id']) && $booking->payment_id) {
                $this->paymentRepository->update(
                    ['payment_status_id' => $input['payment_status_id']],
                    $booking->payment_id
                );
                event(new BookingChangedEvent($booking));
            }

            if (isset($input['booking_status_id']) && $input['booking_status_id'] != $oldBooking->booking_status_id) {
                event(new BookingStatusChangedEvent($booking));
            }

            return $this->sendResponse($booking->toArray(), __('lang.saved_successfully', ['operator' => __('lang.booking')]));
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Calendar view of bookings for the owner's salons.
     * GET /api/salon_owner/calendar
     */
    public function calendar(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $salonIds = $user->salons()->pluck('salons.id')->toArray();

            $startDate = $request->has('start_date') 
                ? Carbon::parse($request->get('start_date'))->startOfDay()
                : Carbon::now()->startOfMonth();

            $endDate = $request->has('end_date')
                ? Carbon::parse($request->get('end_date'))->endOfDay()
                : Carbon::now()->endOfMonth();

            $bookings = Booking::whereIn('salon->id', $salonIds)
                ->whereBetween('booking_at', [$startDate, $endDate])
                ->where('cancel', '<>', '1')
                ->orderBy('booking_at')
                ->get();

            $events = $bookings->map(function ($b) {
                return [
                    'id' => $b->id,
                    'title' => ($b->user ? $b->user->name : 'Cliente') . ' (' . ($b->bookingStatus ? $b->bookingStatus->status : '') . ')',
                    'start' => $b->booking_at,
                    'status_id' => $b->booking_status_id,
                    'total' => $b->getTotal(),
                    'employee' => $b->employee ? $b->employee->name : 'Sin asignar',
                    'services' => $b->e_services,
                    'whatsapp_customer' => $b->whatsapp_customer_link,
                ];
            });

            return $this->sendResponse($events->toArray(), 'Calendar events retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
