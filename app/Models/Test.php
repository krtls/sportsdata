<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Test extends Model
{
    protected $fillable = [
        'term',
        'student_id',
        'first_service_speed',
        'second_service_speed',
        'third_service_speed',

    ];

    public function student():BelongsTo{
        return $this->belongsTo(Student::class);
    }
}
