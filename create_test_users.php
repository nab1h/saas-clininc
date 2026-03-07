<?php

// Create test users and link them to clinics
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Clinic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Creating test users..." . PHP_EOL;
echo PHP_EOL;

// Get or create roles using RoleSeeder slugs
$roles = Role::all();
$roleSlugs = [
    'owner' => 'مالك العيادة',
    'admin' => 'مدير',
    'doctor' => 'طبيب',
    'nurse' => 'ممرض/ة',
    'receptionist' => 'موظف استقبال',
];

echo "Roles found: " . $roles->pluck('name')->implode(', ') . PHP_EOL;
echo PHP_EOL;

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

echo "Clinics ready:" . PHP_EOL;
echo "  - {$clinicA->name} (ID: {$clinicA->id})" . PHP_EOL;
echo "  - {$clinicB->name} (ID: {$clinicB->id})" . PHP_EOL;
echo PHP_EOL;

// Define users to create
$usersToCreate = [
    [
        'name' => 'Super Admin',
        'email' => 'admin@clinic.com',
        'password' => 'admin123',
        'clinics' => [], // No clinics = Super Admin
        'description' => 'Super Admin - Full access to everything',
    ],
    [
        'name' => 'مدير عيادة أ',
        'email' => 'manager.a@clinicA.com',
        'password' => 'manager123',
        'clinics' => [
            ['clinic_id' => 'clinic-a', 'role_slug' => 'owner', 'is_default' => true],
        ],
        'description' => 'Owner of Clinic A',
    ],
    [
        'name' => 'مدير عيادة ب',
        'email' => 'manager.b@clinicB.com',
        'password' => 'manager123',
        'clinics' => [
            ['clinic_id' => 'clinic-b', 'role_slug' => 'owner', 'is_default' => true],
        ],
        'description' => 'Owner of Clinic B',
    ],
];

$createdUsers = [];

foreach ($usersToCreate as $userData) {
    $user = User::firstOrCreate(
        ['email' => $userData['email']],
        [
            'name' => $userData['name'],
            'password' => Hash::make($userData['password']),
        ]
    );

    echo "Created/Updated user: {$user->name} (ID: {$user->id})" . PHP_EOL;
    echo "  Email: {$user->email}" . PHP_EOL;
    echo "  Password: {$userData['password']}" . PHP_EOL;
    echo "  Description: {$userData['description']}" . PHP_EOL;

    // Link user to clinics
    if (empty($userData['clinics'])) {
        // Super Admin - remove all clinic associations
        $user->clinics()->detach();
        echo "  Role: Super Admin (No clinic assigned - full access to all clinics)" . PHP_EOL;
    } else {
        foreach ($userData['clinics'] as $clinicData) {
            $clinicSlug = $clinicData['clinic_id'];
            $roleSlug = $clinicData['role_slug'];

            $clinic = Clinic::where('slug', $clinicSlug)->first();
            $role = Role::where('slug', $roleSlug)->first();

            if (!$clinic || !$role) {
                echo "  ERROR: Could not find clinic '{$clinicSlug}' or role '{$roleSlug}'" . PHP_EOL;
                continue;
            }

            // Detach first to avoid duplicates
            $user->clinics()->detach($clinic->id);

            // Attach with role
            $user->clinics()->attach($clinic->id, [
                'role_id' => $role->id,
                'is_default' => $clinicData['is_default'] ?? false,
            ]);

            echo "  Linked to: {$clinic->name} as {$role->name}" . PHP_EOL;
        }
    }

    echo PHP_EOL;
    $createdUsers[] = $user;
}

echo "========================================" . PHP_EOL;
echo "Summary:" . PHP_EOL;
echo "  Total users created: " . count($createdUsers) . PHP_EOL;
echo PHP_EOL;
echo "Login credentials:" . PHP_EOL;
echo "  1. Super Admin (Full Access):" . PHP_EOL;
echo "     Email: admin@clinic.com" . PHP_EOL;
echo "     Password: admin123" . PHP_EOL;
echo PHP_EOL;
echo "  2. مدير عيادة أ (Clinic A Owner):" . PHP_EOL;
echo "     Email: manager.a@clinicA.com" . PHP_EOL;
echo "     Password: manager123" . PHP_EOL;
echo PHP_EOL;
echo "  3. مدير عيادة ب (Clinic B Owner):" . PHP_EOL;
echo "     Email: manager.b@clinicB.com" . PHP_EOL;
echo "     Password: manager123" . PHP_EOL;
echo "========================================" . PHP_EOL;
echo "Done!" . PHP_EOL;
