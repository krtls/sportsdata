<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestAssesmentResource\Pages;
use App\Filament\Resources\TestAssesmentResource\RelationManagers;
use App\Models\TestAssesment;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestAssesmentResource extends Resource
{
    protected static ?string $model = TestAssesment::class;

    protected static ?string $navigationLabel = 'Test Referans Değerleri'; // Change menu label
    protected static ?string $pluralLabel = 'Test Referans Değerleri'; // Change table title
    protected static ?string $modelLabel = 'Test Referans Değer'; // Change singular label
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('for_whom')
                    ->options([
                        'takım' => 'Takım',
                        'bireysel' => 'Bireysel',
                    ])
                    ->label('Takım/Bireysel')
                    ->required(),
                Select::make('age_group')
                    ->options([
                        'under_14' => '14 yaş altı',
                        '15_16' => '15–16',
                        '17_18' => '17-18',
                        '19_25' => '19-25',
                        'over_25' => 'Genç kadınlar',
                    ]),
                RichEditor::make('34-38')
                    ->required(),
                RichEditor::make('38-42')
                    ->required(),
                RichEditor::make('43-48')
                    ->required(),
                RichEditor::make('49-54')
                    ->required(),
                RichEditor::make('55-59')
                    ->required(),
                RichEditor::make('60-62')
                    ->required(),
                RichEditor::make('63-65')
                    ->required(),
                RichEditor::make('66-68')
                    ->required(),
                RichEditor::make('69-72')
                    ->required(),
                RichEditor::make('73+')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('for_whom')->label('Takım/Bireysel'),
                TextColumn::make('age_group')->label('Yaş Grubu'),
                TextColumn::make('34-38')->label('34-38'),
                TextColumn::make('38-42')->label('38-42'),
                TextColumn::make('43-48')->label('43-48'),
                TextColumn::make('49-54')->label('49-54'),
                TextColumn::make('55-59')->label('55-59'),
                TextColumn::make('60-62')->label('60-62'),
                TextColumn::make('63-65')->label('63-65'),
                TextColumn::make('66-68')->label('66-68'),
                TextColumn::make('69-72')->label('69-72'),
                TextColumn::make('73+')->label('73+'),

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
            'index' => Pages\ListTestAssesments::route('/'),
            'create' => Pages\CreateTestAssesment::route('/create'),
            'edit' => Pages\EditTestAssesment::route('/{record}/edit'),
        ];
    }
}
