<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function clinics(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Clinic::class, 'clinic_user')
            ->withPivot(['role_id', 'clinic_branch_id', 'is_default'])
            ->withTimestamps();
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'clinic_user')
            ->withPivot(['clinic_id', 'clinic_branch_id', 'is_default'])
            ->withTimestamps();
    }

    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Appointment::class);
    }

    public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Article::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function favoriteArticles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Article::class, 'favorite_articles')
            ->withTimestamps()
            ->orderBy('favorite_articles.created_at', 'desc');
    }

    public function isFavoriteArticle($articleId): bool
    {
        return $this->favoriteArticles()->where('article_id', $articleId)->exists();
    }

    /**
     * Check if user has a specific role in a clinic
     */
    public function hasRoleInClinic(string $roleSlug, int $clinicId): bool
    {
        return $this->clinics()
            ->where('clinic_id', $clinicId)
            ->whereHas('roles', function ($query) use ($roleSlug) {
                $query->where('slug', $roleSlug);
            })
            ->exists();
    }

    /**
     * Get user's role for a specific clinic
     */
    public function getRoleInClinic(int $clinicId): ?\App\Models\Role
    {
        $clinic = $this->clinics()->find($clinicId);
        if (!$clinic) {
            return null;
        }

        return \App\Models\Role::find($clinic->pivot->role_id);
    }

    /**
     * Check if user is Super Admin (no clinics assigned or has SuperAdmin role)
     */
    public function isSuperAdmin(): bool
    {
        // Super Admin is defined as having no clinics assigned
        return $this->clinics()->count() === 0;
    }

    /**
     * Check if user has access to a specific clinic
     */
    public function hasAccessToClinic(int $clinicId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->clinics()->where('clinics.id', $clinicId)->exists();
    }

    /**
     * Get all clinics user has access to
     */
    public function accessibleClinics(): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->isSuperAdmin()) {
            return \App\Models\Clinic::all();
        }

        return $this->clinics;
    }
}
