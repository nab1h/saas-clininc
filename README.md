# Clinic Management System

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.0-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?logo=php)
![License](https://img.shields.io/badge/License-MIT-green)

[English](#english) | [العربية](#arabic)

</div>

---

## English

### Overview

Clinic Management System is a comprehensive web application built with Laravel 12 designed to streamline clinic operations and enhance patient care. This multi-tenant SaaS platform enables clinics to manage appointments, patients, doctors, services, invoices, and more through an intuitive dashboard interface.

### Features

- **Multi-Tenant Architecture**: Support for multiple clinics with dedicated workspaces
- **User Management**: Role-based access control (Admin, Manager, Staff)
- **Patient Management**: Complete patient profiles with medical history
- **Appointment Booking**: Online booking system with calendar integration
- **Doctor Management**: Manage doctor profiles, specialties, and schedules
- **Service Catalog**: Organize and manage clinic services with pricing
- **Invoice System**: Generate and track invoices with payment status
- **Article Management**: Publish health articles and educational content
- **FAQ Section**: Frequently asked questions management
- **Social Authentication**: Google, Facebook, and Apple login integration
- **Customer Reviews**: Collect and display patient testimonials
- **Custom Scripts & Links**: Add custom JavaScript and navigation links
- **Settings Panel**: Comprehensive clinic customization options

### Tech Stack

- **Backend**: Laravel 12.0
- **Frontend**: Blade Templates, Vite, JavaScript
- **Database**: SQLite (configurable for MySQL/PostgreSQL)
- **Authentication**: Laravel Sanctum
- **Social Auth**: Laravel Socialite

### Installation

#### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite, MySQL, or PostgreSQL

#### Setup

1. **Clone the repository**

```bash
git clone https://github.com/yourusername/clinic.git
cd clinic
```

2. **Install dependencies**

```bash
composer install
npm install
```

3. **Environment setup**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Database configuration**

```bash
php artisan migrate
```

5. **Build assets**

```bash
npm run build
```

6. **Start development server**

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

### Configuration

#### Social Authentication

Add your social media credentials to `.env`:

```env
# Google
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Facebook
FACEBOOK_CLIENT_ID=your_client_id
FACEBOOK_CLIENT_SECRET=your_client_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback

# Apple
APPLE_CLIENT_ID=your_client_id
APPLE_CLIENT_SECRET=your_client_secret
APPLE_REDIRECT_URI=http://localhost:8000/auth/apple/callback
```

#### Database Configuration

To use MySQL or PostgreSQL instead of SQLite, update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Project Structure

```
clinic/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Dashboard/
│   │   │   └── Auth/
│   │   └── Middleware/
│   ├── Models/
│   └── Providers/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   └── views/
└── routes/
```

### Available Commands

```bash
# Run all tests
composer test

# Run development server with queue and logs
composer dev

# Code style checking
./vendor/bin/pint

# Clear application cache
php artisan cache:clear
```

### Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## العربية

### نظرة عامة

نظام إدارة العيادات هو تطبيق ويب شامل مبني بإطار عمل Laravel 12، مصمم لتبسيط عمليات العيادة وتحسين رعاية المرضى. هذه المنصة السحابية متعددة المستأجرين تمكن العيادات من إدارة المواعيد والمرضى والأطباء والخدمات والفواتير والمزيد من خلال واجهة لوحة تحكم بديهية.

### المميزات

- **بنية متعددة المستأجرين**: دعم عيادات متعددة مع مساحات عمل مخصصة
- **إدارة المستخدمين**: نظام صلاحيات قائم على الأدوار (مدير، مشرف، موظف)
- **إدارة المرضى**: ملفات مرضى كاملة مع السجلات الطبية
- **حجز المواعيد**: نظام حجز إلكتروني مع تكامل التقويم
- **إدارة الأطباء**: إدارة ملفات الأطباء والتخصصات والجداول الزمنية
- **كتالوج الخدمات**: تنظيم وإدارة خدمات العيادة مع الأسعار
- **نظام الفواتير**: إنشاء وتتبع الفواتير مع حالة الدفع
- **إدارة المقالات**: نشر مقالات صحية ومحتوى تعليمي
- **قسم الأسئلة الشائعة**: إدارة الأسئلة المتكررة
- **المصادقة الاجتماعية**: تكامل تسجيل الدخول عبر جوجل وفيسبوك وأبل
- **مراجعات العملاء**: جمع وعرض شهادات المرضى
- **سكريبتات وروابط مخصصة**: إضافة JavaScript وروابط تنقل مخصصة
- **لوحة الإعدادات**: خيارات تخصيص شاملة للعيادة

### التقنيات المستخدمة

- **الواجهة الخلفية**: Laravel 12.0
- **الواجهة الأمامية**: قوالب Blade، Vite، JavaScript
- **قاعدة البيانات**: SQLite (قابل للتكوين لـ MySQL/PostgreSQL)
- **المصادقة**: Laravel Sanctum
- **المصادقة الاجتماعية**: Laravel Socialite

### التثبيت

#### المتطلبات

- PHP 8.2 أو أحدث
- Composer
- Node.js و NPM
- SQLite، MySQL، أو PostgreSQL

#### الإعداد

1. **استنساخ المستودع**

```bash
git clone https://github.com/yourusername/clinic.git
cd clinic
```

2. **تثبيت التبعيات**

```bash
composer install
npm install
```

3. **إعداد البيئة**

```bash
cp .env.example .env
php artisan key:generate
```

4. **تكوين قاعدة البيانات**

```bash
php artisan migrate
```

5. **بناء الأصول**

```bash
npm run build
```

6. **تشغيل خادم التطوير**

```bash
php artisan serve
```

زُر `http://localhost:8000` في المتصفح.

### التكوين

#### المصادقة الاجتماعية

أضف بيانات الاعتماد لوسائل التواصل الاجتماعي في `.env`:

```env
# جوجل
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# فيسبوك
FACEBOOK_CLIENT_ID=your_client_id
FACEBOOK_CLIENT_SECRET=your_client_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback

# أبل
APPLE_CLIENT_ID=your_client_id
APPLE_CLIENT_SECRET=your_client_secret
APPLE_REDIRECT_URI=http://localhost:8000/auth/apple/callback
```

#### تكوين قاعدة البيانات

لاستخدام MySQL أو PostgreSQL بدلاً من SQLite، حدّث `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### هيكل المشروع

```
clinic/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Dashboard/
│   │   │   └── Auth/
│   │   └── Middleware/
│   ├── Models/
│   └── Providers/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   └── views/
└── routes/
```

### الأوامر المتاحة

```bash
# تشغيل جميع الاختبارات
composer test

# تشغيل خادم التطوير مع قائمة الانتظار والسجلات
composer dev

# فحص نمط الكود
./vendor/bin/pint

# مسح ذاكرة التخزين المؤقت للتطبيق
php artisan cache:clear
```

### المساهمة

المساهمات مرحب بها! لا تتردد في إرسال طلب سحب (Pull Request).

### الترخيص

هذا المشروع هو برمجيات مفتوحة المصدر مرخصة تحت [ترخيص MIT](https://opensource.org/licenses/MIT).

---

<div align="center">
Made with ❤️ for healthcare providers
</div>
