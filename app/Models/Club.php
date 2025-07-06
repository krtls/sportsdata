<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    protected $fillable = [
        'name',
        'detail',

    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

}
