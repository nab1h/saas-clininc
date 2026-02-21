<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicBranch extends Model
{
    use HasFactory;

    protected $fillable = ['clinic_id', 'name', 'address', 'phone', 'is_main'];

    protected function casts(): array
    {
        return ['is_main' => 'boolean'];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'clinic_branch_id');
    }
}
