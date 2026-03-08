<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Doctor extends Model
{
    use HasFactory;
    protected $fillable = ['clinic_id', 'name', 'descreption', 'job'];

    public function links()
    {
        return $this->hasMany(LinkDoctor::class);
    }
}
