<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbm_attendant extends Model
{
    use HasFactory;

    protected $fillable = [
        'tbm_id',
        'attendant_id',
        'signed_date' => 'datetime',
    ];
}
