<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doctor::create([
            'clinic_id' => 1,
            'name' => 'Dr. Ahmed Ali',
            'descreption' => 'Cardiologist',
            'job' => 'Cardiologist',
        ]);

        Doctor::create([
            'clinic_id' => 1,
            'name' => 'Dr. Sara Mohamed',
            'descreption' => 'Dermatologist',
            'job' => 'Dermatologist',
        ]);
    }
}

