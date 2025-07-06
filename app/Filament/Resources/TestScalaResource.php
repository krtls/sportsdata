<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestScalaResource\Pages;
use App\Filament\Resources\TestScalaResource\RelationManagers;
use App\Models\TestScala;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestScalaResource extends Resource
{
    protected static ?string $model = TestScala::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Test Ölçekleri'; // Change menu label
    protected static ?string $pluralLabel = 'Test Ölçekleri'; // Change table title
    protected static ?string $modelLabel = 'Test Ölçeği'; // Change singular label

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('age_group')
                    ->label('Yaş Grubu')
                    ->required()
                    ->maxLength(255),
                TextInput::make('under1')
                    ->label('Önerilenin Altında')
                    ->required()
                    ->numeric(),
                TextInput::make('under2')
                    ->label('Önerilenin Altında')
                    ->required()
                    ->numeric(),
                TextInput::make('mid1')
                    ->label('Orta Seviye')
                    ->required()
                    ->numeric(),
                TextInput::make('mid2')
                    ->label('Orta Seviye')
                    ->required()
                    ->numeric(),
                TextInput::make('ideal1')
                    ->label('İdeal Performans')
                    ->required()
                    ->numeric(),
                TextInput::make('ideal2')
                    ->label('İdeal Performans')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('age_group')->label('Yaş Grubu'),
                TextColumn::make('under1')->label('Önerilenin Altında'),
                TextColumn::make('under2')->label('Önerilenin Altında'),
                TextColumn::make('mid1')->label('Orta Seviye'),
                TextColumn::make('mid2')->label('Orta Seviye'),
                TextColumn::make('ideal1')->label('İdeal Performans'),
                TextColumn::make('ideal2')->label('İdeal Performans'),

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
            'index' => Pages\ListTestScalas::route('/'),
            'create' => Pages\CreateTestScala::route('/create'),
            'edit' => Pages\EditTestScala::route('/{record}/edit'),
        ];
    }
}
