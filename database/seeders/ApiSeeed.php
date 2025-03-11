<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\ApiAccess;

class ApiSeeed extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        ApiAccess::create([
            'api_login' => env('API_KEY'),
            'secret_key' => env('SECRET_KEY'),
            'true_key' => env('TRUE_KEY'),
            'key' => Hash::make(env('TRUE_KEY')),
        ]);
    }
}
