<?php

namespace App\Filament\Resources\AcademicIntakeResource\Pages;

use App\Filament\Resources\AcademicIntakeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcademicIntake extends EditRecord
{
    protected static string $resource = AcademicIntakeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
