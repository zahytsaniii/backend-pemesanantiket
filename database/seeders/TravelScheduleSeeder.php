<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TravelSchedule;

class TravelScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TravelSchedule::create([
            'destination' => 'Bandung',
            'departure_datetime' => '2025-02-01 08:00:00',
            'quota' => 10,
            'price' => 120000,
            'category' => 'reguler'
        ]);

        TravelSchedule::create([
            'destination' => 'Jakarta',
            'departure_datetime' => '2025-02-05 09:00:00',
            'quota' => 12,
            'price' => 150000,
            'category' => 'vip'
        ]);

        TravelSchedule::create([
            'destination' => 'Bogor',
            'departure_datetime' => '2025-02-10 07:30:00',
            'quota' => 8,
            'price' => 90000,
            'category' => 'reguler'
        ]);
    }
}
