<?php
/*
 * File name: FaqsTableSeeder.php
 * Last modified: 2024.04.18 at 17:53:52
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use App\Models\Faq;
use DB;
use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {

        DB::table('faqs')->truncate();
        Faq::factory()->count(30)->create();
    }
}
