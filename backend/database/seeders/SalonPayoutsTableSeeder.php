<?php
/*
 * File name: SalonPayoutsTableSeeder.php
 * Last modified: 2024.04.18 at 17:53:52
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SalonPayoutsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('salon_payouts')->truncate();
    }
}
