<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class LinkDoctor extends Model
{
    use HasFactory;
    protected $fillable = ['doctor_id', 'linkOne', 'linkTwo', 'linkThree'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
