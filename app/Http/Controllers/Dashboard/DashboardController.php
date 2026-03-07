<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Article;
use App\Models\Clinic;
use App\Models\ClinicBranch;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $currentClinic = session('current_clinic_id') ? Clinic::find(session('current_clinic_id')) : null;
        $user = Auth::user();
        $isAdmin = $user && $user->clinics->count() === 0; // User not assigned to any clinic is treated as admin

        if ($currentClinic && !$isAdmin) {
            // إحصائيات العيادة الحالية فقط
            $totalClinics = $user->clinics->count();
            $activeClinics = $user->clinics->where('is_active', true)->count();
            $totalUsers = $currentClinic->users()->count();
            $totalPatients = $currentClinic->patients()->count();
            $totalServices = $currentClinic->services()->count();
            $totalArticles = $currentClinic->articles()->count();
            $totalInvoices = $currentClinic->invoices()->count();
            $totalAppointments = $currentClinic->appointments()->count();

            // إحصائيات المواعيد
            $pendingAppointments = $currentClinic->appointments()->where('status', 'scheduled')->count();
            $confirmedAppointments = $currentClinic->appointments()->where('status', 'confirmed')->count();
            $completedAppointments = $currentClinic->appointments()->where('status', 'completed')->count();
            $cancelledAppointments = $currentClinic->appointments()->where('status', 'cancelled')->count();

            // إحصائيات الفواتير
            $paidInvoices = $currentClinic->invoices()->where('status', 'paid')->count();
            $pendingInvoices = $currentClinic->invoices()->where('status', 'pending')->count();
            $totalRevenue = $currentClinic->invoices()->where('status', 'paid')->sum('total');

            // إحصائيات المقالات
            $publishedArticles = $currentClinic->articles()->where('is_published', true)->count();
            $draftArticles = $currentClinic->articles()->where('is_published', false)->count();
            $favoriteArticles = $currentClinic->articles()->where('is_favorite', true)->count();

            // إحصائيات العيادات حسب الاشتراك (للسوبر أدمن فقط)
            $clinicsByPlan = collect();
            $recentClinics = collect();

            // نمو المواعيد
            $thisMonthAppointments = $currentClinic->appointments()
                ->whereYear('appointment_date', Carbon::now()->year)
                ->whereMonth('appointment_date', Carbon::now()->month)
                ->count();

            $lastMonthAppointments = $currentClinic->appointments()
                ->whereYear('appointment_date', Carbon::now()->subMonth()->year)
                ->whereMonth('appointment_date', Carbon::now()->subMonth()->month)
                ->count();

            $appointmentsGrowth = $lastMonthAppointments > 0
                ? round((($thisMonthAppointments - $lastMonthAppointments) / $lastMonthAppointments) * 100, 2)
                : 0;

            // المواعيد لآخر 7 أيام (للرسم البياني)
            $last7Days = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $last7Days->push([
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d/m'),
                    'count' => $currentClinic->appointments()->whereDate('created_at', $date->format('Y-m-d'))->count(),
                ]);
            }

            // المواعيد القادمة
            $upcomingAppointments = $currentClinic->appointments()->with(['patient', 'service', 'clinic'])
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->where('appointment_date', '>=', Carbon::today())
                ->orderBy('appointment_date')
                ->orderBy('start_time')
                ->take(5)
                ->get();

            // المقالات الأخيرة
            $recentArticles = $currentClinic->articles()->with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            // إحصائيات النظام SaaS العامة (للأدمن)
            $totalClinics = Clinic::count();
            $activeClinics = Clinic::where('is_active', true)->count();
            $totalUsers = User::count();
            $totalPatients = Patient::count();
            $totalServices = Service::count();
            $totalArticles = Article::count();
            $totalInvoices = Invoice::count();
            $totalAppointments = Appointment::count();

            // إحصائيات المواعيد
            $pendingAppointments = Appointment::where('status', 'scheduled')->count();
            $confirmedAppointments = Appointment::where('status', 'confirmed')->count();
            $completedAppointments = Appointment::where('status', 'completed')->count();
            $cancelledAppointments = Appointment::where('status', 'cancelled')->count();

            // إحصائيات الفواتير
            $paidInvoices = Invoice::where('status', 'paid')->count();
            $pendingInvoices = Invoice::where('status', 'pending')->count();
            $totalRevenue = Invoice::where('status', 'paid')->sum('total');

            // إحصائيات المقالات
            $publishedArticles = Article::where('is_published', true)->count();
            $draftArticles = Article::where('is_published', false)->count();
            $favoriteArticles = Article::where('is_favorite', true)->count();

            // إحصائيات العيادات حسب الاشتراك
            $clinicsByPlan = Clinic::select('subscription_plan', DB::raw('count(*) as count'))
                ->groupBy('subscription_plan')
                ->get()
                ->pluck('count', 'subscription_plan');

            // المواعيد لآخر 7 أيام (للرسم البياني)
            $last7Days = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $last7Days->push([
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d/m'),
                    'count' => Appointment::whereDate('created_at', $date->format('Y-m-d'))->count(),
                ]);
            }

            // العيادات النشطة مؤخراً
            $recentClinics = Clinic::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['id', 'name', 'created_at', 'is_active', 'subscription_plan']);

            // المواعيد القادمة
            $upcomingAppointments = Appointment::with(['patient', 'service', 'clinic'])
                ->whereIn('status', ['scheduled', 'confirmed'])
                ->where('appointment_date', '>=', Carbon::today())
                ->orderBy('appointment_date')
                ->orderBy('start_time')
                ->take(5)
                ->get();

            // المقالات الأخيرة
            $recentArticles = Article::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact(
            'totalClinics',
            'activeClinics',
            'totalUsers',
            'totalPatients',
            'totalServices',
            'totalArticles',
            'totalInvoices',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'completedAppointments',
            'cancelledAppointments',
            'paidInvoices',
            'pendingInvoices',
            'totalRevenue',
            'publishedArticles',
            'draftArticles',
            'favoriteArticles',
            'clinicsByPlan',
            'thisMonthAppointments',
            'lastMonthAppointments',
            'appointmentsGrowth',
            'last7Days',
            'recentClinics',
            'upcomingAppointments',
            'recentArticles',
        ));
    }
}
