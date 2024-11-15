<?php

namespace App\Filament\Resources\AcademicIntakeResource\Pages;

use App\Filament\Resources\AcademicIntakeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademicIntakes extends ListRecords
{
    protected static string $resource = AcademicIntakeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
