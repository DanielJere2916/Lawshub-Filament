<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicIntakeResource\Pages;
use App\Models\AcademicIntake;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;

class AcademicIntakeResource extends Resource
{
    protected static ?string $model = AcademicIntake::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('intake_type')
                ->multiple()
                ->required()
                ->options([
                    'Bridging' => 'Bridging',
                    'Postgraduate' => 'Postgraduate',
                    'Undergraduate' => 'Undergraduate',
                    'Upgrading' => 'Upgrading',
                ])
                ->live()
                ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                    if (empty($state)) return;
                    
                    // Check for active intakes
                    $activeIntakes = AcademicIntake::whereIn('intake_type', $state)
                        ->where('academic_status', true)
                        ->get();
                        
                    if ($activeIntakes->isNotEmpty()) {
                        $activeTypes = $activeIntakes->pluck('intake_type')->toArray();
                        throw ValidationException::withMessages([
                            'intake_type' => 'Cannot create new intake. Active intakes exist for: ' . implode(', ', $activeTypes),
                        ]);
                    }
                    
                    $academicYear = $get('academic_year');
                    $intakeName = $get('intake_name');
                    
                    if ($academicYear && $intakeName) {
                        self::validateIntakeExists($state, $academicYear, $intakeName);
                    }
                }),

                Forms\Components\TextInput::make('intake_name')
                    ->required()
                    ->maxLength(191)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                        if (empty($state)) return;
                        
                        $intakeTypes = $get('intake_type');
                        $academicYear = $get('academic_year');
                        
                        if ($intakeTypes && $academicYear) {
                            self::validateIntakeExists($intakeTypes, $academicYear, $state);
                        }
                    }),

                Forms\Components\TextInput::make('academic_year')
                    ->required()
                    ->maxLength(191)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                        if (empty($state)) return;
                        
                        $intakeTypes = $get('intake_type');
                        $intakeName = $get('intake_name');
                        
                        if ($intakeTypes && $intakeName) {
                            self::validateIntakeExists($intakeTypes, $state, $intakeName);
                        }
                    }),

                Forms\Components\DatePicker::make('admission_start')
                    ->required(),

                Forms\Components\DatePicker::make('admission_deadline')
                    ->required(),

                Forms\Components\TextInput::make('post_fees')
                    ->numeric(),

                Forms\Components\TextInput::make('other_fees')
                    ->numeric(),

                Forms\Components\Toggle::make('academic_status')
                    ->required(),
            ]);
    }

    private static function validateIntakeExists($intakeTypes, $academicYear, $intakeName)
    {
        if (!is_array($intakeTypes)) {
            $intakeTypes = [$intakeTypes];
        }
    
        $existingIntakes = AcademicIntake::query()
            ->whereIn('intake_type', $intakeTypes)
            ->where('academic_year', $academicYear)
            ->where('intake_name', $intakeName)
            ->get();
    
        if ($existingIntakes->count() === count($intakeTypes)) {
            throw ValidationException::withMessages([
                'intake_type' => 'All selected intake types already exist for this academic year and intake name.',
            ]);
        }
    
        if ($existingIntakes->isNotEmpty()) {
            $duplicateIntakes = $existingIntakes->pluck('intake_type')->toArray();
            $message = 'The following intake types already exist for this academic year and intake name: ' . 
                      implode(', ', $duplicateIntakes);
            
            throw ValidationException::withMessages([
                'intake_type' => $message,
            ]);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('intake_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('intake_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admission_start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('admission_deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('post_fees')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('other_fees')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListAcademicIntakes::route('/'),
            'create' => Pages\CreateAcademicIntake::route('/create'),
            'edit' => Pages\EditAcademicIntake::route('/{record}/edit'),
        ];
    }
}