<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $clinic = Clinic::firstOrCreate(
            ['slug' => 'main-clinic'],
            [
                'name' => 'العيادة الرئيسية',
                'email' => 'info@clinic.com',
                'phone' => '0123456789',
                'address' => 'العنوان',
                'is_active' => true,
            ]
        );

        $patient = Patient::firstOrCreate(
            [
                'clinic_id' => $clinic->id,
                'phone' => '01001234567',
            ],
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'is_active' => true,
            ]
        );

        $service = Service::firstOrCreate(
            [
                'clinic_id' => $clinic->id,
                'name' => 'كشف عام',
            ],
            [
                'name_ar' => 'كشف عام',
                'price' => 100,
                'duration_minutes' => 30,
                'is_active' => true,
            ]
        );

        Appointment::firstOrCreate(
            [
                'clinic_id' => $clinic->id,
                'patient_id' => $patient->id,
                'appointment_date' => now()->addDays(2),
                'start_time' => '10:00',
            ],
            [
                'service_id' => $service->id,
                'status' => 'scheduled',
                'notes' => 'حجز تجريبي من السيدر',
            ]
        );
    }
}
