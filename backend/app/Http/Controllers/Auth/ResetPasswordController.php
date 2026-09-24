<?php
/*
 * File name: ResetPasswordController.php
 * Last modified: 2024.04.10 at 12:41:02
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @return string
     */
    public function redirectTo(): string
    {
        $user = auth()->user();
        if ($user) {
            if ($user->hasRole('admin') || $user->hasRole('salon owner') || $user->hasRole('provider')) {
                return '/dashboard';
            }
            if ($user->hasRole('customer')) {
                return '/bookings';
            }
        }
        return '/dashboard';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }
}
