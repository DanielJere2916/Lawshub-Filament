<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSemester extends Model
{
    use HasFactory;
    protected $fillable = [
        'academic_year',
        'duration',
        'academic_status',
    ];
}
