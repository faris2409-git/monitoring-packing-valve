<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'supervisor@fni.com'],
            [
                'name' => 'Supervisor FNI',
                'username' => 'supervisor',
                'npk' => 'SP-001',
                'password' => 'password123',
                'role' => 'supervisor',
                'shift' => 'Shift 1',
            ]
        );

        User::updateOrCreate(
            ['email' => 'operator@fni.com'],
            [
                'name' => 'Operator FNI',
                'username' => 'operator',
                'npk' => 'OP-001',
                'password' => 'password123',
                'role' => 'operator',
                'shift' => 'Shift 1',
            ]
        );
    }
}
