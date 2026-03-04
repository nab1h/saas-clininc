<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'user_id',
        'title',
        'slug',
        'image',
        'excerpt',
        'body',
        'is_published',
        'published_at',
        'views_count',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_favorite' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** التعليقات المعتمدة فقط (للعرض العام) */
    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('is_approved', true);
    }

    /** التعليقات الرئيسية (بدون الردود) */
    public function topLevelComments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(FavoriteArticle::class);
    }

    public function isFavoredBy($userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}
