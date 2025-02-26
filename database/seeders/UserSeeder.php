<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $users = [
            ['name' => 'Super Admin', 'email' => 'superuser@gotick.com', 'phone' => '085156719832', 'role' => 'super_user'],
            ['name' => 'Administrator', 'email' => 'admin@gotick.com', 'phone' => '0895604255942', 'role' => 'administrator'],
            ['name' => 'Tenant Owner', 'email' => 'tenant@playground.com', 'phone' => '0876556474456', 'role' => 'playground_owner'],
            ['name' => 'Supervisor', 'email' => 'supervisor@playground.com', 'phone' => '0876578344445', 'role' => 'playground_supervisor'],
            ['name' => 'Operator', 'email' => 'operator@playground.com', 'phone' => '08766383444556', 'role' => 'playground_operator'],
            ['name' => 'Visitor', 'email' => 'visitor@playground.com', 'phone' => '08764738455667', 'role' => 'visitor_member'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'password' => Hash::make('password'), // Default password: "password"
                'role' => $user['role'],
            ]);
        }
    }
}
