<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Script extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'type',
        'name',
        'code',
        'position',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const TYPES = [
        'google_analytics' => 'Google Analytics',
        'google_tag_manager' => 'Google Tag Manager',
        'google_ads' => 'Google Ads',
        'facebook_pixel' => 'Facebook Pixel',
        'meta_pixel' => 'Meta Pixel',
        'hotjar' => 'Hotjar',
        'custom' => 'سكريبت مخصص',
    ];

    public const POSITIONS = [
        'head' => 'في الرأس (Head)',
        'body_start' => 'بداية الجسم (Body Start)',
        'body_end' => 'نهاية الجسم (Body End)',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getPositionNameAttribute(): string
    {
        return self::POSITIONS[$this->position] ?? $this->position;
    }
}
