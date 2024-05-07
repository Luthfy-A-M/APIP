<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbm_instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'tbm_id',
        'instructor_id',
        'signed_date' => 'datetime',
    ];
}
