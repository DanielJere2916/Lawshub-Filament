<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicIntake extends Model
{
    protected $casts = [
        'academic_status' => 'boolean',
        'admission_start' => 'date',
        'admission_deadline' => 'date',
        'intake_type' => 'string',
    ];

    protected $fillable = [
        'intake_type',
        'intake_name',
        'academic_year',
        'admission_start',
        'admission_deadline',
        'post_fees',
        'other_fees',
        'academic_status',
    ];
}