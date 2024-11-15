<?php

namespace App\Filament\Resources\AcademicIntakeResource\Pages;

use App\Filament\Resources\AcademicIntakeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class CreateAcademicIntake extends CreateRecord
{
    protected static string $resource = AcademicIntakeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $intakeTypes = $data['intake_type'];
        unset($data['intake_type']);
        
        $firstRecord = null;
        $createdTypes = [];
        $skippedTypes = [];
        
        // Check for active intakes first
        $activeIntakes = static::getModel()::whereIn('intake_type', $intakeTypes)
            ->where('academic_status', true)
            ->get();
            
        if ($activeIntakes->isNotEmpty()) {
            $activeTypes = $activeIntakes->pluck('intake_type')->toArray();
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Cannot create new intake. Active intakes exist for: ' . implode(', ', $activeTypes))
                ->persistent()
                ->send();
                
            return redirect()->back();
        }
        
        // Check if all selected types already exist
        $allExist = true;
        foreach ($intakeTypes as $intakeType) {
            $exists = static::getModel()::where([
                'intake_type' => $intakeType,
                'academic_year' => $data['academic_year'],
                'intake_name' => $data['intake_name'],
            ])->exists();
            
            if (!$exists) {
                $allExist = false;
                break;
            }
        }
        
        if ($allExist) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('All selected intake types already exist for this academic year and intake name.')
                ->persistent()
                ->send();
                
            return redirect()->back();
        }
        
        // Create records for non-existing combinations
        foreach ($intakeTypes as $intakeType) {
            $exists = static::getModel()::where([
                'intake_type' => $intakeType,
                'academic_year' => $data['academic_year'],
                'intake_name' => $data['intake_name'],
            ])->exists();
            
            if (!$exists) {
                $recordData = array_merge($data, ['intake_type' => $intakeType]);
                $record = static::getModel()::create($recordData);
                
                if (!$firstRecord) {
                    $firstRecord = $record;
                }
                $createdTypes[] = $intakeType;
            } else {
                $skippedTypes[] = $intakeType;
            }
        }
        
        // Show combined notification for created and skipped types
        $notificationBody = [];
        if (!empty($createdTypes)) {
            $notificationBody[] = 'Created intakes for: ' . implode(', ', $createdTypes);
        }
        if (!empty($skippedTypes)) {
            $notificationBody[] = 'Skipped existing intakes for: ' . implode(', ', $skippedTypes);
        }
        
        Notification::make()
            ->success()
            ->title('Operation Complete')
            ->body(implode("\n", $notificationBody))
            ->persistent()
            ->send();
        
        return $firstRecord;
    }

    protected function afterCreate(): void
    {
        // Prevent the default "Created" notification from being sent
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}