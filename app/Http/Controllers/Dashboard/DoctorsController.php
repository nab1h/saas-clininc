<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Clinic;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected function getCurrentClinic(): ?Clinic
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Get current clinic from session
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId) {
            return Clinic::find($currentClinicId);
        }

        // Fallback to user's first clinic
        return $user->clinics->first();
    }

    public function index()
    {
        $clinic = $this->getCurrentClinic();

        if (!$clinic) {
            return view('dashboard.doctors.index', ['doctors' => collect()]);
        }

        $doctors = $clinic->doctors()
            ->orderBy('name')
            ->get();

            dd($doctors);
        return view('dashboard.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
