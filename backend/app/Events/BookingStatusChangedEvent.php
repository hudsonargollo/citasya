<?php
/*
 * File name: BookingStatusChangedEvent.php
 * Last modified: 2022.02.16 at 18:16:51
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2022
 */

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingStatusChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    /**
     * BookingChangedEvent constructor.
     * @param $booking
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }


}
