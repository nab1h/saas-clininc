<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Link;
use App\Models\Service;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $clinicA = Clinic::where('slug', 'clinic-a')->first();
        $clinicB = Clinic::where('slug', 'clinic-b')->first();

        if (!$clinicA || !$clinicB) {
            $this->command->error('Clinics not found. Please run UserSeeder first.');
            return;
        }

        $managerA = User::where('email', 'manager.a@clinicA.com')->first();
        $managerB = User::where('email', 'manager.b@clinicB.com')->first();

        $this->command->info('Creating demo data for ' . $clinicA->name);
        $this->command->newLine();

        // === Create Services for Clinic A ===
        $this->command->info('Creating Services for Clinic A...');

        $clinicA->services()->delete();

        $servicesData = [
            [
                'name' => 'كشف عام',
                'description' => 'فحص شامل للجسم يتضمن قياس الضغط والوزن والفحوصات الأساسية',
                'price' => 150,
                'duration_minutes' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'كشوف أسنان',
                'description' => 'فحص الأسنان وتشخيص المشاكل وتخطيط علاج شامل',
                'price' => 200,
                'duration_minutes' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'علاج عيون',
                'description' => 'فحص العيون الشامل وتحديد النظارة المناسبة',
                'price' => 300,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'علاج جلدية',
                'description' => 'علاج مشاكل البشرة والجهاز الدوري',
                'price' => 250,
                'duration_minutes' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'فحص مخبري شامل',
                'description' => 'تحاليل دم كاملة وصور أشعة',
                'price' => 500,
                'duration_minutes' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'طوارئ',
                'description' => 'استقبال الحالات الطارئة على مدار الساعة',
                'price' => 400,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
        ];

        foreach ($servicesData as $serviceData) {
            $service = $clinicA->services()->create($serviceData);
            $this->command->line("  ✓ {$service->name} - {$service->price} ج.م");
        }

        $this->command->newLine();

        // === Create Articles for Clinic A ===
        $this->command->info('Creating Articles for Clinic A...');

        $clinicA->articles()->delete();

        $articlesData = [
            [
                'title' => 'أهمية الفحوصات الدورية للصحة',
                'slug' => 'importance-of-regular-checkups',
                'body' => '<p>الفحوصات الدورية هي حجر الزاوية للحفاظ على صحة جيدة على المدى الطويل. تتيح لك هذه الفحوصات الكشف عن المشاكل الصحية في مراحلها المبكرة قبل أن تصبح خطيرة.</p>
                <h3>متى يجب إجراء الفحوصات؟</h3>
                <ul>
                <li>الشباب: كل سنتين</li>
                <li>منتصف العمر: كل سنة</li>
                <li>كبار السن: كل 6 أشهر</li>
                </ul>
                <h3>الفحوصات الأساسية</h3>
                <p>تتضمن الفحوصات الأساسية قياس الضغط، فحص السكر، فحص الكوليسترول، وغيرها من الفحوصات الدموية.</p>',
                'excerpt' => 'الفحوصات الدورية هي حجر الزاوية للحفاظ على صحة جيدة على المدى الطويل.',
                'user_id' => $managerA ? $managerA->id : null,
                'is_published' => true,
                'is_favorite' => true,
            ],
            [
                'title' => 'نصائح للحفاظ على صحة الأسنان',
                'slug' => 'dental-health-tips',
                'body' => '<p>صحة الأسنان جزء مهم من الصحة العامة. إليك بعض النصائح للحفاظ على أسنان صحية:</p>
                <h3>تنظيف الأسنان</h3>
                <p>يجب تنظيف الأسنان بالفرشاة مرتين يومياً على الأقل، واستخدام خيط الأسنان مرة واحدة يومياً.</p>
                <h3>الزيارات الدورية للطبيب</h3>
                <p>يجب زيارة طبيب الأسنان كل 6 أشهر للفحص الدوري والتلطيف.</p>
                <h3>تجنب الأطعمة الضارة</h3>
                <p>قلل من تناول السكريات والمشروبات الغازية التي تضر بالأسنان.</p>',
                'excerpt' => 'صحة الأسنان جزء مهم من الصحة العامة. إليك بعض النصائح للحفاظ على أسنان صحية.',
                'user_id' => $managerA ? $managerA->id : null,
                'is_published' => true,
                'is_favorite' => false,
            ],
            [
                'title' => 'فوائد الرياضة للصحة',
                'slug' => 'benefits-of-exercise',
                'body' => '<p>الرياضة هي أفضل طريقة للحفاظ على صحة جيدة ولياقة بدنية عالية. إليك أهم الفوائد:</p>
                <h3>تحسين صحة القلب</h3>
                <p>الرياضة تقوي عضلة القلب وتحسن الدورة الدموية.</p>
                <h3>التحكم في الوزن</h3>
                <p>الرياضة تساعد على حرق السعرات الحرارية والحفاظ على وزن صحي.</p>
                <h3>تحسين المزاج</h3>
                <p>الرياضة تفرز هرمونات السعادة وتحسن المزاج العام.</p>
                <h3>نصيحة مهمة</h3>
                <p>ابدأ بتمارين خفيفة وزداد تدريجياً. استشر طبيبك قبل البدء بأي برنامج رياضي جديد.</p>',
                'excerpt' => 'الرياضة هي أفضل طريقة للحفاظ على صحة جيدة ولياقة بدنية عالية.',
                'user_id' => $managerA ? $managerA->id : null,
                'is_published' => true,
                'is_favorite' => true,
            ],
            [
                'title' => 'التغذية الصحية لجسم قوي',
                'slug' => 'healthy-nutrition',
                'body' => '<p>التغذية السليمة هي أساس صحة جيدة. إليك أهم القواعد:</p>
                <h3>تناول الفواكه والخضروات</h3>
                <p>احرص على تناول 5 حصص على الأقل من الفواكه والخضروات يومياً.</p>
                <h3>شرب الماء</h3>
                <p>اشرب 8 أكواب من الماء يومياً للحفاظ على رطوبة الجسم.</p>
                <h3>تجنب الوجبات السريعة</h3>
                <p>الوجبات السريعة غنية بالسعرات الحرارية والدهون غير الصحية.</p>',
                'excerpt' => 'التغذية السليمة هي أساس صحة جيدة. إليك أهم القواعد.',
                'user_id' => $managerA ? $managerA->id : null,
                'is_published' => true,
                'is_favorite' => false,
            ],
            [
                'title' => 'أهمية النوم الجيد',
                'slug' => 'importance-of-good-sleep',
                'body' => '<p>النوم الجيد ضروري للصحة العقلية والجسدية. إليك كيفية تحسين جودة النوم:</p>
                <h3>روتين نوم ثابت</h3>
                <p>حاول النوم والاستيقاظ في نفس الوقت يومياً.</p>
                <h3>تجنب الشاشات قبل النوم</h3>
                <p>الضوء الأزرق من الشاشات يؤثر على هرمون النوم.</p>
                <h3>بيئة نوم مريحة</h3>
                <p>اجعل غرفة النوم مظلمة وهادئة ومناسبة للراحة.</p>',
                'excerpt' => 'النوم الجيد ضروري للصحة العقلية والجسدية.',
                'user_id' => $managerA ? $managerA->id : null,
                'is_published' => true,
                'is_favorite' => false,
            ],
            [
                'title' => 'مقالة تحت المراجعة - سيتم نشرها قريباً',
                'slug' => 'draft-article-pending-review',
                'body' => '<p>هذا مقال تم كتابته وسيتم نشره قريباً بعد المراجعة والتدقيق.</p>',
                'excerpt' => 'هذا مقال تم كتابته وسيتم نشره قريباً.',
                'user_id' => $managerA ? $managerA->id : null,
                'is_published' => false,
                'is_favorite' => false,
            ],
        ];

        foreach ($articlesData as $articleData) {
            $article = $clinicA->articles()->create($articleData);
            $this->command->line("  ✓ {$article->title}");
        }

        $this->command->newLine();

        // === Create Links for Clinic A ===
        $this->command->info('Creating Links for Clinic A...');

        $clinicA->links()->delete();

        $linksData = [
            [
                'type' => 'facebook',
                'label' => 'فيسبوك',
                'url' => 'https://facebook.com',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'type' => 'twitter',
                'label' => 'تويتر',
                'url' => 'https://twitter.com',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'type' => 'instagram',
                'label' => 'انستجرام',
                'url' => 'https://instagram.com',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'type' => 'whatsapp',
                'label' => 'واتساب',
                'url' => 'https://wa.me/20123456789',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'type' => 'youtube',
                'label' => 'يوتيوب',
                'url' => 'https://youtube.com',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'type' => 'website',
                'label' => 'موقعنا',
                'url' => 'https://clinic-a.com',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($linksData as $linkData) {
            $link = $clinicA->links()->create($linkData);
            $this->command->line("  ✓ {$link->label}");
        }

        $this->command->newLine();

        // === Create Data for Clinic B ===
        $this->command->info('Creating demo data for ' . $clinicB->name);

        // Services
        $clinicB->services()->delete();
        $clinicB->services()->create([
            'name' => 'كشف عام',
            'description' => 'فحص شامل للجسم',
            'price' => 120,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $clinicB->services()->create([
            'name' => 'علاج عيون',
            'description' => 'فحص وعلاج مشاكل العيون',
            'price' => 280,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $this->command->line('  ✓ Created 2 services');

        // Articles
        $clinicB->articles()->delete();
        $clinicB->articles()->create([
            'title' => 'أهلاً بكم في عيادة ب',
            'slug' => 'welcome-to-clinic-b',
            'body' => '<p>نسعد بوجودكم معنا في عيادة ب. نقدم أفضل خدمات طبية.</p>',
            'excerpt' => 'نسعد بوجودكم معنا في عيادة ب.',
            'user_id' => $managerB ? $managerB->id : null,
            'is_published' => true,
            'is_favorite' => true,
        ]);

        $clinicB->articles()->create([
            'title' => 'ساعات العمل',
            'slug' => 'working-hours',
            'body' => '<p>نعمل من الساعة 9 صباحاً حتى 10 مساءً جميع أيام الأسبوع ما عدا الجمعة.</p>',
            'excerpt' => 'نعمل من الساعة 9 صباحاً حتى 10 مساءً.',
            'user_id' => $managerB ? $managerB->id : null,
            'is_published' => true,
            'is_favorite' => false,
        ]);

        $this->command->line('  ✓ Created 2 articles');

        // Links
        $clinicB->links()->delete();
        $clinicB->links()->create([
            'type' => 'facebook',
            'label' => 'فيسبوك',
            'url' => 'https://facebook.com',
            'is_active' => true,
            'order' => 1,
        ]);

        $clinicB->links()->create([
            'type' => 'whatsapp',
            'label' => 'واتساب',
            'url' => 'https://wa.me/20198765432',
            'is_active' => true,
            'order' => 2,
        ]);

        $this->command->line('  ✓ Created 2 links');

        $this->command->newLine();
        $this->command->info('========================================');
        $this->command->info('Done! Demo data created successfully.');
        $this->command->info('========================================');
    }
}
