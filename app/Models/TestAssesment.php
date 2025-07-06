<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAssesment extends Model
{
    protected $fillable = [
        'for_whom',
        'age_group',
        'age_group_description',
        '34-38',
        '38-42',
        '43-48',
        '49-54',
        '55-59',
        '60-62',
        '63-65',
        '66-68',
        '69-72',
        '73+',
        'speed_description',
    ];
}
