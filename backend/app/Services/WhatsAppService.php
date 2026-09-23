<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class WhatsAppService
{
    /**
     * Clean and format phone number with country code.
     * Default to Bolivia (+591) for 8-digit mobile numbers if no country code is present.
     */
    public static function formatPhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Strip all non-digit characters except leading plus
        $cleaned = preg_replace('/[^\d]/', '', $phone);

        if (empty($cleaned)) {
            return null;
        }

        // If local 8-digit Bolivian number (starts with 6 or 7, or 8 digits long)
        if (strlen($cleaned) === 8) {
            return '591' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Build customer-facing confirmation message
     */
    public static function getCustomerBookingMessage(Booking $booking): string
    {
        $userName = $booking->user ? $booking->user->name : 'Cliente';
        $salonName = $booking->salon ? $booking->salon->name : 'CitasYa';
        $salonAddress = $booking->salon && $booking->salon->address ? $booking->salon->address->address : 'En el local';
        
        $services = [];
        if (!empty($booking->e_services)) {
            foreach ($booking->e_services as $service) {
                $services[] = is_object($service) && isset($service->name) ? (is_array($service->name) ? ($service->name['es'] ?? $service->name['en'] ?? reset($service->name)) : $service->name) : 'Servicio';
            }
        }
        $serviceList = count($services) > 0 ? implode(', ', $services) : 'Servicio agendado';

        $dateFormatted = 'Fecha por confirmar';
        if (!empty($booking->booking_at)) {
            try {
                $carbon = Carbon::parse($booking->booking_at)->locale('es');
                $dateFormatted = $carbon->isoFormat('D [de] MMMM [a las] HH:mm');
            } catch (\Exception $e) {
                $dateFormatted = (string)$booking->booking_at;
            }
        }

        $total = number_format($booking->getTotal(), 2);

        $msg = "✨ *¡Reserva Confirmada en {$salonName}!* — *CitasYa* 🌿\n\n";
        $msg .= "👤 *Cliente:* {$userName}\n";
        $msg .= "📅 *Horario:* {$dateFormatted}\n";
        $msg .= "✂️ *Servicios:* {$serviceList}\n";
        $msg .= "💰 *Total:* Bs. {$total}\n";
        $msg .= "📍 *Dirección:* {$salonAddress}\n";
        $msg .= "🔖 *ID de Reserva:* #{$booking->id}\n\n";
        $msg .= "¡Te esperamos puntual! Ante cualquier consulta o reprogramación, comunícate con nosotros.";

        return $msg;
    }

    /**
     * Build salon/owner-facing notification message
     */
    public static function getSalonBookingMessage(Booking $booking): string
    {
        $userName = $booking->user ? $booking->user->name : 'Cliente';
        $userPhone = $booking->user ? ($booking->user->phone_number ?? $booking->user->custom_fields['phone_number']['value'] ?? 'No especificado') : 'No especificado';
        $salonName = $booking->salon ? $booking->salon->name : 'Tu Local';

        $services = [];
        if (!empty($booking->e_services)) {
            foreach ($booking->e_services as $service) {
                $services[] = is_object($service) && isset($service->name) ? (is_array($service->name) ? ($service->name['es'] ?? $service->name['en'] ?? reset($service->name)) : $service->name) : 'Servicio';
            }
        }
        $serviceList = count($services) > 0 ? implode(', ', $services) : 'Servicio agendado';

        $dateFormatted = 'Fecha por confirmar';
        if (!empty($booking->booking_at)) {
            try {
                $carbon = Carbon::parse($booking->booking_at)->locale('es');
                $dateFormatted = $carbon->isoFormat('D [de] MMMM [a las] HH:mm');
            } catch (\Exception $e) {
                $dateFormatted = (string)$booking->booking_at;
            }
        }

        $total = number_format($booking->getTotal(), 2);
        $status = $booking->bookingStatus ? $booking->bookingStatus->status : 'Recibida';

        $msg = "🔔 *¡NUEVA CITA REGISTRADA!* — *CitasYa*\n\n";
        $msg .= "📍 *Local:* {$salonName}\n";
        $msg .= "👤 *Cliente:* {$userName}\n";
        $msg .= "📞 *Teléfono:* {$userPhone}\n";
        $msg .= "📅 *Fecha y Hora:* {$dateFormatted}\n";
        $msg .= "✂️ *Servicios:* {$serviceList}\n";
        $msg .= "💰 *Monto:* Bs. {$total}\n";
        $msg .= "📋 *Estado:* {$status}\n";
        $msg .= "🔖 *ID Reserva:* #{$booking->id}\n\n";
        $msg .= "Gestiona esta cita desde tu panel CitasYa.";

        return $msg;
    }

    /**
     * Generate WhatsApp deep link
     */
    public static function generateDeepLink(?string $phone, string $message): string
    {
        $formattedPhone = self::formatPhone($phone);
        $encodedMessage = rawurlencode($message);

        if ($formattedPhone) {
            return "https://wa.me/{$formattedPhone}?text={$encodedMessage}";
        }

        return "https://api.whatsapp.com/send?text={$encodedMessage}";
    }

    /**
     * Get direct WhatsApp link for salon to message the customer
     */
    public static function getCustomerWhatsAppLink(Booking $booking): string
    {
        $phone = $booking->user ? ($booking->user->phone_number ?? $booking->user->custom_fields['phone_number']['value'] ?? null) : null;
        $message = self::getCustomerBookingMessage($booking);
        return self::generateDeepLink($phone, $message);
    }

    /**
     * Get direct WhatsApp link for customer to message the salon
     */
    public static function getSalonWhatsAppLink(Booking $booking): string
    {
        $phone = $booking->salon ? ($booking->salon->phone_number ?? $booking->salon->mobile_number ?? null) : null;
        $message = self::getSalonBookingMessage($booking);
        return self::generateDeepLink($phone, $message);
    }
}
