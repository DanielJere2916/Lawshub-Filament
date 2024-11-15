<?php

// app/Rules/UniqueAcademicIntake.php

namespace App\Rules;

use App\Models\AcademicIntake;
use Illuminate\Contracts\Validation\Rule;

class UniqueAcademicIntake implements Rule
{
    protected $intakeType;
    protected $academicYear;
    protected $intakeName;

    public function __construct($intakeType, $academicYear, $intakeName)
    {
        $this->intakeType = $intakeType;
        $this->academicYear = $academicYear;
        $this->intakeName = $intakeName;
    }

    public function passes($attribute, $value)
    {
        // Check if an intake with the same type, year, and name already exists
        $exists = AcademicIntake::where('intake_type', $this->intakeType)
            ->where('academic_year', $this->academicYear)
            ->where('intake_name', $this->intakeName)
            ->exists();

        if ($exists) {
            return false;
        }

        // Check if there is an active academic intake
        $activeExists = AcademicIntake::where('academic_status', true)->exists();

        return !$activeExists;
    }

    public function message()
    {
        return 'The academic intake is already available or another intake is running.';
    }
}