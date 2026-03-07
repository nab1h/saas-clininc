<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist first
        $this->call(RoleSeeder::class);

        // Get roles
        $ownerRole = Role::where('slug', 'owner')->first();

        // Create or get test clinics
        $clinicA = Clinic::firstOrCreate(
            ['slug' => 'clinic-a'],
            [
                'name' => 'عيادة أ',
                'email' => 'info@clinic-a.com',
                'phone' => '0123456789',
                'address' => 'القاهرة - التجمع الخامس',
                'subscription_plan' => 'premium',
                'is_active' => true,
            ]
        );

        $clinicB = Clinic::firstOrCreate(
            ['slug' => 'clinic-b'],
            [
                'name' => 'عيادة ب',
                'email' => 'info@clinic-b.com',
                'phone' => '0198765432',
                'address' => 'القاهرة - مدينة نصر',
                'subscription_plan' => 'premium',
                'is_active' => true,
            ]
        );

        // Define users to create
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@clinic.com',
                'password' => 'admin123',
                'clinics' => [], // No clinics = Super Admin
            ],
            [
                'name' => 'مدير عيادة أ',
                'email' => 'manager.a@clinicA.com',
                'password' => 'manager123',
                'clinics' => [
                    ['clinic' => $clinicA, 'role' => $ownerRole, 'is_default' => true],
                ],
            ],
            [
                'name' => 'مدير عيادة ب',
                'email' => 'manager.b@clinicB.com',
                'password' => 'manager123',
                'clinics' => [
                    ['clinic' => $clinicB, 'role' => $ownerRole, 'is_default' => true],
                ],
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                ]
            );

            // Link user to clinics
            $user->clinics()->detach();

            foreach ($userData['clinics'] as $clinicData) {
                $user->clinics()->attach($clinicData['clinic']->id, [
                    'role_id' => $clinicData['role']->id,
                    'is_default' => $clinicData['is_default'] ?? false,
                ]);
            }

            $this->command->info("Created user: {$user->name} ({$user->email})");
        }

        $this->command->info(PHP_EOL . 'Login credentials:');
        $this->command->info('  1. Super Admin: admin@clinic.com / admin123');
        $this->command->info('  2. مدير عيادة أ: manager.a@clinicA.com / manager123');
        $this->command->info('  3. مدير عيادة ب: manager.b@clinicB.com / manager123');
    }
}
