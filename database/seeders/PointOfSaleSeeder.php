<?php

namespace Database\Seeders;

use App\Models\PointOfSale;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PointOfSaleSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the point_of_sale role exists
        $role = Role::firstOrCreate([
            'name' => 'point_of_sale',
            'guard_name' => 'web',
        ]);

        // Create POS 1
        $user1 = User::create([
            'name' => 'POS User 1',
            'email' => 'pos1@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user1->assignRole('point_of_sale');

        $pos1 = PointOfSale::create([
            'id' => 1,
            'user_id' => $user1->id,
            'name_en' => 'Point of Sale 1',
            'name_ar' => 'نقطة البيع 1',
            'city' => 'Dubai',
            'address' => '123 Main Street, Dubai',
            'phone_number' => '+971501234567',
            'email' => 'pos1@gmail.com',
            'website' => 'https://pos1.gmail.com',
            'postal_code' => '12345',
            'latitude' => 25.2048,
            'longitude' => 55.2708,
            'is_active' => true,
        ]);

        // Create POS 2
        $user2 = User::create([
            'name' => 'POS User 2',
            'email' => 'pos2@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user2->assignRole('point_of_sale');

        $pos2 = PointOfSale::create([
            'id' => 2,
            'user_id' => $user2->id,
            'name_en' => 'Point of Sale 2',
            'name_ar' => 'نقطة البيع 2',
            'city' => 'Abu Dhabi',
            'address' => '456 Side Street, Abu Dhabi',
            'phone_number' => '+971502345678',
            'email' => 'pos2@gmail.com',
            'website' => 'https://pos2.gmail.com',
            'postal_code' => '54321',
            'latitude' => 24.4539,
            'longitude' => 54.3773,
            'is_active' => true,
        ]);
    }
}
