<?php

namespace App\Filament\Resources\ClubResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Ad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('surname')
                    ->label('Soyad')
                    ->required()
                    ->maxLength(255),
                TextInput::make('birth_year')
                    ->required()
                    ->label('Doğum Yılı')
                    ->numeric()
                    ->mask('9999')
                    ->maxLength(4),
                Forms\Components\Select::make('gender')
                    ->required()
                    ->label('Cinsiyet')
                    ->options([
                        'kız' => 'Kız',
                        'erkek' => 'Erkek',
                    ])
                    ->default('kız'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad'),
                Tables\Columns\TextColumn::make('surname')
                    ->label('Soyad'),
                Tables\Columns\TextColumn::make('birth_year')
                    ->label('Doğum Yılı'),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Cinsiyet'),

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
