<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;

    protected $fillable = [
        'dept_code',
        'dept_name',
         'GL1',
          'GL2',
           'GL3',
            'SPV1' ,
             'SPV2' ,
             'SPV3',
             'MGR1',
             'MGR2',
             'Dept_Head',
    ];

}
