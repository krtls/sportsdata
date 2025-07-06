<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'age',
        'gender',
        'club_id',

    ];
    public function club():BelongsTo{
        return $this->belongsTo(Club::class);
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }

}
