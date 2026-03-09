<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = Clinic::all();

        if ($clinics->isEmpty()) {
            return;
        }

        foreach ($clinics as $clinic) {
            Doctor::create([
                'clinic_id' => $clinic->id,
                'name' => 'د. أحمد محمد علي',
                'job' => 'طبيب قلبية',
                'description' => 'خبرة 15 سنة في مجال أمراض القلب والأوعية الدموية. متخصص في تشخيص وعلاج أمراض القلب المختلفة.',
                'image' => null,
            ]);

            Doctor::create([
                'clinic_id' => $clinic->id,
                'name' => 'د. سارة أحمد خالد',
                'job' => 'طبيبة أطفال',
                'description' => 'طبيبة أطفال خبرة 10 سنوات في العناية بصحة الطفل والتطعيمات ومتابعة النمو.',
                'image' => null,
            ]);

            Doctor::create([
                'clinic_id' => $clinic->id,
                'name' => 'د. محمد إبراهيم حسن',
                'job' => 'طبيب عيون',
                'description' => 'أخصائي طب وجراحة العيون. خبير في عمليات الماء الأبيض والليز.',
                'image' => null,
            ]);

            Doctor::create([
                'clinic_id' => $clinic->id,
                'name' => 'د. فاطمة محمود سعيد',
                'job' => 'طبيبة أسنان',
                'description' => 'طبيبة أسنان متخصصة في تبييض الأسنان وتقويمها وزراعة الأسنان.',
                'image' => null,
            ]);
        }
    }
}
