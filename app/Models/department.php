<?php

namespace App\Models;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class department extends Model
{
    use HasFactory;
    protected $fillable = [
        'faculty_id',
        'name',
        'Acronym',
        'department_status'

    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
