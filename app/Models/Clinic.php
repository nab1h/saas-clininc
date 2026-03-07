<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'settings',
        'subscription_plan',
        'trial_ends_at',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($clinic) {
            if (empty($clinic->slug) && !empty($clinic->name)) {
                $clinic->slug = static::generateUniqueSlug($clinic->name);
            }
        });

        static::updating(function ($clinic) {
            if ($clinic->isDirty('name') && !empty($clinic->name)) {
                $clinic->slug = static::generateUniqueSlug($clinic->name);
            }
        });
    }

    protected static function generateUniqueSlug(string $name): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        $query = static::where('slug', $slug);
        $clinicId = request()->route('clinic');
        if ($clinicId) {
            $query->where('id', '!=', $clinicId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter++;
            $query = static::where('slug', $slug);
            if ($clinicId) {
                $query->where('id', '!=', $clinicId);
            }
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'trial_ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function branches(): HasMany
    {
        return $this->hasMany(ClinicBranch::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'clinic_user')
            ->withPivot(['role_id', 'clinic_branch_id', 'is_default'])
            ->withTimestamps();
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->orderBy('order');
    }
}
