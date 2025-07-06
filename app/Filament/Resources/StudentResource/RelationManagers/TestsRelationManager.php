<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class TestsRelationManager extends RelationManager
{
    protected static string $relationship = 'tests';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('term')
                    ->required()
                    ->label('Dönem')
                    ->numeric()
                    ->mask('9')
                    ->maxLength(1)
                    ->default(1),

                TextInput::make('first_service_speed')
                    ->label('1. Servis Hızı')
                    ->numeric()
                    ->mask('99')
                    ->maxLength(2),

                TextInput::make('second_service_speed')
                    ->label('2. Servis Hızı')
                    ->numeric()
                    ->mask('99')
                    ->maxLength(2),

                TextInput::make('third_service_speed')
                    ->label('3. Servis Hızı')
                    ->numeric()
                    ->mask('99')
                    ->maxLength(2),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_service_speed')
            ->columns([
                Tables\Columns\TextColumn::make('first_service_speed')
                    ->label('1. Servis Hızı'),
                Tables\Columns\TextColumn::make('second_service_speed')
                    ->label('2. Servis Hızı'),
                Tables\Columns\TextColumn::make('third_service_speed')
                    ->label('3. Servis Hızı'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
