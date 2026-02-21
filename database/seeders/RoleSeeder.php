<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'مالك العيادة', 'slug' => 'owner', 'description' => 'صلاحيات كاملة على العيادة'],
            ['name' => 'مدير', 'slug' => 'admin', 'description' => 'إدارة العيادة والموظفين'],
            ['name' => 'طبيب', 'slug' => 'doctor', 'description' => 'المواعيد والكشوف والوصفات'],
            ['name' => 'ممرض/ة', 'slug' => 'nurse', 'description' => 'المساعدة في الكشوف والمتابعة'],
            ['name' => 'موظف استقبال', 'slug' => 'receptionist', 'description' => 'حجز المواعيد والمرضى والفواتير'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
