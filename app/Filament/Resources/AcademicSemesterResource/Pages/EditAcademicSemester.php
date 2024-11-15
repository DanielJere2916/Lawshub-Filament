<?php

namespace App\Filament\Resources\AcademicSemesterResource\Pages;

use App\Filament\Resources\AcademicSemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcademicSemester extends EditRecord
{
    protected static string $resource = AcademicSemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
