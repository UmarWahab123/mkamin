<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\TimeInterval;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffMembers = [
            // Staff for Point of Sale 1
            [
                'id' => 1,
                'name_en' => 'John Smith',
                'name_ar' => 'جون سميث',
                'position_en' => 'Hair Stylist',
                'position_ar' => 'مصفف شعر',
                'email' => 'john.smith@example.com',
                'phone_number' => '+971501111111',
                'address' => '123 Staff Avenue, Dubai',
                'is_active' => true,
                'point_of_sale_id' => 1,
            ],
            [
                'id' => 2,
                'name_en' => 'Emma Johnson',
                'name_ar' => 'إيما جونسون',
                'position_en' => 'Color Specialist',
                'position_ar' => 'أخصائي ألوان',
                'email' => 'emma.johnson@example.com',
                'phone_number' => '+971502222222',
                'address' => '124 Staff Avenue, Dubai',
                'is_active' => true,
                'point_of_sale_id' => 1,
            ],
            [
                'id' => 3,
                'name_en' => 'Michael Chen',
                'name_ar' => 'مايكل تشن',
                'position_en' => 'Barber',
                'position_ar' => 'حلاق',
                'email' => 'michael.chen@example.com',
                'phone_number' => '+971503333333',
                'address' => '125 Staff Avenue, Dubai',
                'is_active' => true,
                'point_of_sale_id' => 1,
            ],
            [
                'id' => 4,
                'name_en' => 'Sophia Rodriguez',
                'name_ar' => 'صوفيا رودريغيز',
                'position_en' => 'Nail Technician',
                'position_ar' => 'فني أظافر',
                'email' => 'sophia.rodriguez@example.com',
                'phone_number' => '+971504444444',
                'address' => '126 Staff Avenue, Dubai',
                'is_active' => true,
                'point_of_sale_id' => 1,
            ],
            [
                'id' => 5,
                'name_en' => 'Aiden Taylor',
                'name_ar' => 'ايدن تايلور',
                'position_en' => 'Receptionist',
                'position_ar' => 'موظف استقبال',
                'email' => 'aiden.taylor@example.com',
                'phone_number' => '+971505555555',
                'address' => '127 Staff Avenue, Dubai',
                'is_active' => true,
                'point_of_sale_id' => 1,
            ],

            // Staff for Point of Sale 2
            [
                'id' => 6,
                'name_en' => 'Olivia Brown',
                'name_ar' => 'أوليفيا براون',
                'position_en' => 'Senior Stylist',
                'position_ar' => 'مصفف أول',
                'email' => 'olivia.brown@example.com',
                'phone_number' => '+971506666666',
                'address' => '123 Staff Street, Abu Dhabi',
                'is_active' => true,
                'point_of_sale_id' => 2,
            ],
            [
                'id' => 7,
                'name_en' => 'William Davis',
                'name_ar' => 'ويليام ديفيس',
                'position_en' => 'Hair Extensions Specialist',
                'position_ar' => 'أخصائي تمديد الشعر',
                'email' => 'william.davis@example.com',
                'phone_number' => '+971507777777',
                'address' => '124 Staff Street, Abu Dhabi',
                'is_active' => true,
                'point_of_sale_id' => 2,
            ],
            [
                'id' => 8,
                'name_en' => 'Ava Martinez',
                'name_ar' => 'آفا مارتينيز',
                'position_en' => 'Hair Treatment Specialist',
                'position_ar' => 'أخصائي علاج الشعر',
                'email' => 'ava.martinez@example.com',
                'phone_number' => '+971508888888',
                'address' => '125 Staff Street, Abu Dhabi',
                'is_active' => true,
                'point_of_sale_id' => 2,
            ],
            [
                'id' => 9,
                'name_en' => 'Noah Wilson',
                'name_ar' => 'نوح ويلسون',
                'position_en' => 'Salon Manager',
                'position_ar' => 'مدير صالون',
                'email' => 'noah.wilson@example.com',
                'phone_number' => '+971509999999',
                'address' => '126 Staff Street, Abu Dhabi',
                'is_active' => true,
                'point_of_sale_id' => 2,
            ],
            [
                'id' => 10,
                'name_en' => 'Isabella Anderson',
                'name_ar' => 'إيزابيلا أندرسون',
                'position_en' => 'Makeup Artist',
                'position_ar' => 'خبير مكياج',
                'email' => 'isabella.anderson@example.com',
                'phone_number' => '+971500000000',
                'address' => '127 Staff Street, Abu Dhabi',
                'is_active' => true,
                'point_of_sale_id' => 2,
            ],
        ];

        // Create staff members and their time intervals
        foreach ($staffMembers as $staffMember) {
            $staff = Staff::create($staffMember);
            $this->createTimeIntervalsForStaff($staff);
        }
    }

    /**
     * Create time intervals for a staff member for the next 30 days
     */
    private function createTimeIntervalsForStaff(Staff $staff): void
    {
        // Work schedule by day of week
        $workSchedule = [
            // Sunday
            0 => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'is_closed' => false],
            // Monday
            1 => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'is_closed' => false],
            // Tuesday
            2 => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'is_closed' => false],
            // Wednesday
            3 => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'is_closed' => false],
            // Thursday
            4 => ['start_time' => '09:00:00', 'end_time' => '17:00:00', 'is_closed' => false],
            // Friday
            5 => ['start_time' => '14:00:00', 'end_time' => '20:00:00', 'is_closed' => true],
            // Saturday
            6 => ['start_time' => '14:00:00', 'end_time' => '20:00:00', 'is_closed' => false],
        ];


        // Create time intervals for the next 30 days
        $startDate = Carbon::now();
        for ($day = 0; $day < 30; $day++) {
            $date = $startDate->copy()->addDays($day);
            $dayOfWeek = $date->dayOfWeek;

            $schedule = $workSchedule[$dayOfWeek];

            // Create the time interval
            TimeInterval::create([
                'date' => $date->format('Y-m-d'),
                'day_of_week' => $dayOfWeek,
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
                'is_closed' => $schedule['is_closed'],
                'timeable_id' => $staff->id,
                'timeable_type' => Staff::class,
            ]);
        }
    }
}
