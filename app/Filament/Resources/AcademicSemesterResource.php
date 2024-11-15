<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicSemesterResource\Pages;
use App\Filament\Resources\AcademicSemesterResource\RelationManagers;
use App\Models\AcademicSemester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcademicSemesterResource extends Resource
{
    protected static ?string $model = AcademicSemester::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('academic_year')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Toggle::make('academic_status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->searchable(),
                Tables\Columns\IconColumn::make('academic_status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicSemesters::route('/'),
            'create' => Pages\CreateAcademicSemester::route('/create'),
            'edit' => Pages\EditAcademicSemester::route('/{record}/edit'),
        ];
    }    
}
