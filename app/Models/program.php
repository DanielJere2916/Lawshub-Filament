<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class program extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'name',
        'Acronym',
        'program_status'

    ];
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
