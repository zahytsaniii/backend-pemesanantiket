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
            'title' => 'Schedule1',
            'destination' => 'Bandung',
            'departure_datetime' => '2025-02-01 08:00:00',
            'quota' => 10,
            'price' => 120000
        ]);

        TravelSchedule::create([
            'title' => 'Schedule2',
            'destination' => 'Jakarta',
            'departure_datetime' => '2025-02-05 09:00:00',
            'quota' => 12,
            'price' => 150000
        ]);

        TravelSchedule::create([
            'title' => 'Schedule3',
            'destination' => 'Bogor',
            'departure_datetime' => '2025-02-10 07:30:00',
            'quota' => 8,
            'price' => 90000
        ]);
    }
}
