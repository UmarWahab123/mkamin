<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\ReservationSetting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default settings
        Setting::create([
            'key' => 'salon_location',
            'value' => 'Main Street, Riyadh, Saudi Arabia'
        ]);

        Setting::create([
            'key' => 'vat_percentage',
            'value' => '15' // 15% VAT
        ]);

        // Create default business hours for the next 30 days
        $startDate = now();
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;

            ReservationSetting::create([
                'date' => $date->format('Y-m-d'),
                'day_of_week' => $dayOfWeek,
                'opening_time' => '09:00:00',
                'closing_time' => '21:00:00',
                'workers_count' => 3,
                'is_closed' => in_array($dayOfWeek, [5]), // Closed on Friday (5)
            ]);
        }
    }
}
