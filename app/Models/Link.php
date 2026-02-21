<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'type',
        'label',
        'url',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /** أنواع الروابط الشائعة (للاستخدام في القوائم والتحقق) */
    public const TYPES = [
        'facebook'   => 'فيسبوك',
        'instagram'  => 'انستغرام',
        'twitter'    => 'تويتر (X)',
        'youtube'    => 'يوتيوب',
        'whatsapp'   => 'واتساب',
        'website'    => 'الموقع',
        'tiktok'     => 'تيك توك',
        'linkedin'   => 'لينكد إن',
        'telegram'   => 'تيليجرام',
        'snapchat'   => 'سناب شات',
    ];
}
