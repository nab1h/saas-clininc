<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LinkDoctor;

class LinkDoctorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LinkDoctor::create([
            'doctor_id' => 1,
            'linkOne' => 'https://facebook.com/dr.ahmed',
            'linkTwo' => 'https://twitter.com/dr.ahmed',
            'linkThree' => 'https://linkedin.com/in/dr.ahmed',
        ]);

        LinkDoctor::create([
            'doctor_id' => 2,
            'linkOne' => 'https://facebook.com/dr.sara',
            'linkTwo' => 'https://instagram.com/dr.sara',
            'linkThree' => 'https://linkedin.com/in/dr.sara',
        ]);

        LinkDoctor::create([
            'doctor_id' => 3,
            'linkOne' => 'https://facebook.com/dr.mostafa',
            'linkTwo' => '',
            'linkThree' => '',
        ]);
    }
}
