<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DemoDataSeeder::class);
        $this->call(DoctorsTableSeeder::class);
        $this->call(LinkDoctorsTableSeeder::class);
        $this->call(DoctorSeeder::class);
        $this->call(ScriptSeeder::class);
    }
}
