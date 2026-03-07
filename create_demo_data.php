<?php

// Create demo data for clinic testing
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Article;
use App\Models\Link;
use App\Models\Service;
use App\Models\Clinic;
use Illuminate\Support\Str;

echo "Creating demo data for clinics..." . PHP_EOL;
echo PHP_EOL;

// Get clinic A
$clinicA = Clinic::where('slug', 'clinic-a')->first();

if (!$clinicA) {
    echo "ERROR: Clinic A not found. Please run UserSeeder first." . PHP_EOL;
    exit(1);
}

echo "Clinic: {$clinicA->name}" . PHP_EOL;
echo PHP_EOL;

// === Create Services ===
echo "Creating Services..." . PHP_EOL;

$servicesData = [
    [
        'name' => 'كشف عام',
        'description' => 'فحص شامل للجسم يتضمن قياس الضغط والوزن والفحوصات الأساسية',
        'price' => 150,
        'duration' => 30,
        'is_active' => true,
        'order' => 1,
    ],
    [
        'name' => 'كشوف أسنان',
        'description' => 'فحص الأسنان وتشخيص المشاكل وتخطيط علاج شامل',
        'price' => 200,
        'duration' => 45,
        'is_active' => true,
        'order' => 2,
    ],
    [
        'name' => 'علاج عيون',
        'description' => 'فحص العيون الشامل وتحديد النظارة المناسبة',
        'price' => 300,
        'duration' => 60,
        'is_active' => true,
        'order' => 3,
    ],
    [
        'name' => 'علاج جلدية',
        'description' => 'علاج مشاكل البشرة والجهاز الدوري',
        'price' => 250,
        'duration' => 45,
        'is_active' => true,
        'order' => 4,
    ],
    [
        'name' => 'فحص مخبري شامل',
        'description' => 'تحاليل دم كاملة وصور أشعة',
        'price' => 500,
        'duration' => 90,
        'is_active' => true,
        'order' => 5,
    ],
    [
        'name' => 'طوارئ',
        'description' => 'استقبال الحالات الطارئة على مدار الساعة',
        'price' => 400,
        'duration' => 60,
        'is_active' => true,
        'order' => 6,
    ],
];

// Clear existing services for clinic
$clinicA->services()->delete();

foreach ($servicesData as $serviceData) {
    $service = $clinicA->services()->create($serviceData);
    echo "  ✓ {$service->name} - {$service->price} ج.م" . PHP_EOL;
}

echo PHP_EOL . "Created " . count($servicesData) . " services." . PHP_EOL;
echo PHP_EOL;

// === Create Articles ===
echo "Creating Articles..." . PHP_EOL;

$articlesData = [
    [
        'title' => 'أهمية الفحوصات الدورية للصحة',
        'content' => '<p>الفحوصات الدورية هي حجر الزاوية للحفاظ على صحة جيدة على المدى الطويل. تتيح لك هذه الفحوصات الكشف عن المشاكل الصحية في مراحلها المبكرة قبل أن تصبح خطيرة.</p>
        <h3>متى يجب إجراء الفحوصات؟</h3>
        <ul>
        <li>الشباب: كل سنتين</li>
        <li>منتصف العمر: كل سنة</li>
        <li>كبار السن: كل 6 أشهر</li>
        </ul>
        <h3>الفحوصات الأساسية</h3>
        <p>تتضمن الفحوصات الأساسية قياس الضغط، فحص السكر، فحص الكوليسترول، وغيرها من الفحوصات الدموية.</p>',
        'excerpt' => 'الفحوصات الدورية هي حجر الزاوية للحفاظ على صحة جيدة على المدى الطويل.',
        'is_published' => true,
        'is_favorite' => true,
        'order' => 1,
    ],
    [
        'title' => 'نصائح للحفاظ على صحة الأسنان',
        'content' => '<p>صحة الأسنان جزء مهم من الصحة العامة. إليك بعض النصائح للحفاظ على أسنان صحية:</p>
        <h3>تنظيف الأسنان</h3>
        <p>يجب تنظيف الأسنان بالفرشاة مرتين يومياً على الأقل، واستخدام خيط الأسنان مرة واحدة يومياً.</p>
        <h3>الزيارات الدورية للطبيب</h3>
        <p>يجب زيارة طبيب الأسنان كل 6 أشهر للفحص الدوري والتلطيف.</p>
        <h3>تجنب الأطعمة الضارة</h3>
        <p>قلل من تناول السكريات والمشروبات الغازية التي تضر بالأسنان.</p>',
        'excerpt' => 'صحة الأسنان جزء مهم من الصحة العامة. إليك بعض النصائح للحفاظ على أسنان صحية.',
        'is_published' => true,
        'is_favorite' => false,
        'order' => 2,
    ],
    [
        'title' => 'فوائد الرياضة للصحة',
        'content' => '<p>الرياضة هي أفضل طريقة للحفاظ على صحة جيدة ولياقة بدنية عالية. إليك أهم الفوائد:</p>
        <h3>تحسين صحة القلب</h3>
        <p>الرياضة تقوي عضلة القلب وتحسن الدورة الدموية.</p>
        <h3>التحكم في الوزن</h3>
        <p>الرياضة تساعد على حرق السعرات الحرارية والحفاظ على وزن صحي.</p>
        <h3>تحسين المزاج</h3>
        <p>الرياضة تفرز هرمونات السعادة وتحسن المزاج العام.</p>
        <h3>نصيحة مهمة</h3>
        <p>ابدأ بتمارين خفيفة وزداد تدريجياً. استشر طبيبك قبل البدء بأي برنامج رياضي جديد.</p>',
        'excerpt' => 'الرياضة هي أفضل طريقة للحفاظ على صحة جيدة ولياقة بدنية عالية.',
        'is_published' => true,
        'is_favorite' => true,
        'order' => 3,
    ],
    [
        'title' => 'التغذية الصحية لجسم قوي',
        'content' => '<p>التغذية السليمة هي أساس صحة جيدة. إليك أهم القواعد:</p>
        <h3>تناول الفواكه والخضروات</h3>
        <p>احرص على تناول 5 حصص على الأقل من الفواكه والخضروات يومياً.</p>
        <h3>شرب الماء</h3>
        <p>اشرب 8 أكواب من الماء يومياً للحفاظ على رطوبة الجسم.</p>
        <h3>تجنب الوجبات السريعة</h3>
        <p>الوجبات السريعة غنية بالسعرات الحرارية والدهون غير الصحية.</p>',
        'excerpt' => 'التغذية السليمة هي أساس صحة جيدة. إليك أهم القواعد.',
        'is_published' => true,
        'is_favorite' => false,
        'order' => 4,
    ],
    [
        'title' => 'أهمية النوم الجيد',
        'content' => '<p>النوم الجيد ضروري للصحة العقلية والجسدية. إليك كيفية تحسين جودة النوم:</p>
        <h3>روتين نوم ثابت</h3>
        <p>حاول النوم والاستيقاظ في نفس الوقت يومياً.</p>
        <h3>تجنب الشاشات قبل النوم</h3>
        <p>الضوء الأزرق من الشاشات يؤثر على هرمون النوم.</p>
        <h3>بيئة نوم مريحة</h3>
        <p>اجعل غرفة النوم مظلمة وهادئة ومناسبة للراحة.</p>',
        'excerpt' => 'النوم الجيد ضروري للصحة العقلية والجسدية.',
        'is_published' => true,
        'is_favorite' => false,
        'order' => 5,
    ],
    [
        'title' => 'مقالة تحت المراجعة - سيتم نشرها قريباً',
        'content' => '<p>هذا مقال تم كتابته وسيتم نشره قريباً بعد المراجعة والتدقيق.</p>',
        'excerpt' => 'هذا مقال تم كتابته وسيتم نشره قريباً.',
        'is_published' => false,
        'is_favorite' => false,
        'order' => 6,
    ],
];

// Clear existing articles for clinic
$clinicA->articles()->delete();

$adminUser = \App\Models\User::where('email', 'manager.a@clinicA.com')->first();

foreach ($articlesData as $articleData) {
    $article = $clinicA->articles()->create(array_merge($articleData, [
        'user_id' => $adminUser ? $adminUser->id : null,
    ]));
    echo "  ✓ {$article->title}" . PHP_EOL;
}

echo PHP_EOL . "Created " . count($articlesData) . " articles." . PHP_EOL;
echo PHP_EOL;

// === Create Links ===
echo "Creating Links..." . PHP_EOL;

$linksData = [
    [
        'title' => 'فيسبوك',
        'url' => 'https://facebook.com',
        'icon' => 'fab fa-facebook',
        'is_active' => true,
        'order' => 1,
    ],
    [
        'title' => 'تويتر',
        'url' => 'https://twitter.com',
        'icon' => 'fab fa-twitter',
        'is_active' => true,
        'order' => 2,
    ],
    [
        'title' => 'انستجرام',
        'url' => 'https://instagram.com',
        'icon' => 'fab fa-instagram',
        'is_active' => true,
        'order' => 3,
    ],
    [
        'title' => 'واتساب',
        'url' => 'https://wa.me/20123456789',
        'icon' => 'fab fa-whatsapp',
        'is_active' => true,
        'order' => 4,
    ],
    [
        'title' => 'يوتيوب',
        'url' => 'https://youtube.com',
        'icon' => 'fab fa-youtube',
        'is_active' => true,
        'order' => 5,
    ],
    [
        'title' => 'موقعنا',
        'url' => 'https://clinic-a.com',
        'icon' => 'fas fa-globe',
        'is_active' => true,
        'order' => 6,
    ],
];

// Clear existing links for clinic
$clinicA->links()->delete();

foreach ($linksData as $linkData) {
    $link = $clinicA->links()->create($linkData);
    echo "  ✓ {$link->title}" . PHP_EOL;
}

echo PHP_EOL . "Created " . count($linksData) . " links." . PHP_EOL;
echo PHP_EOL;

// Also create for clinic B
$clinicB = Clinic::where('slug', 'clinic-b')->first();
if ($clinicB) {
    echo "Also creating data for Clinic B ({$clinicB->name})..." . PHP_EOL;

    // Create some services for clinic B
    $clinicB->services()->create([
        'name' => 'كشف عام',
        'description' => 'فحص شامل للجسم',
        'price' => 120,
        'duration' => 30,
        'is_active' => true,
        'order' => 1,
    ]);

    $clinicB->services()->create([
        'name' => 'علاج عيون',
        'description' => 'فحص وعلاج مشاكل العيون',
        'price' => 280,
        'duration' => 60,
        'is_active' => true,
        'order' => 2,
    ]);

    // Create some articles for clinic B
    $managerB = \App\Models\User::where('email', 'manager.b@clinicB.com')->first();

    $clinicB->articles()->create([
        'title' => 'أهلاً بكم في عيادة ب',
        'content' => '<p>نسعد بوجودكم معنا في عيادة ب. نقدم أفضل خدمات طبية.</p>',
        'excerpt' => 'نسعد بوجودكم معنا في عيادة ب.',
        'user_id' => $managerB ? $managerB->id : null,
        'is_published' => true,
        'is_favorite' => true,
        'order' => 1,
    ]);

    // Create links for clinic B
    $clinicB->links()->create([
        'title' => 'فيسبوك',
        'url' => 'https://facebook.com',
        'icon' => 'fab fa-facebook',
        'is_active' => true,
        'order' => 1,
    ]);

    $clinicB->links()->create([
        'title' => 'واتساب',
        'url' => 'https://wa.me/20198765432',
        'icon' => 'fab fa-whatsapp',
        'is_active' => true,
        'order' => 2,
    ]);

    echo "  ✓ Created data for Clinic B" . PHP_EOL;
}

echo PHP_EOL;
echo "========================================" . PHP_EOL;
echo "Done! Demo data created successfully." . PHP_EOL;
echo "========================================" . PHP_EOL;
