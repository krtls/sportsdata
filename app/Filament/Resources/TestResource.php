<?php

namespace App\Filament\Resources;

use App\Filament\Exports\TestExporter;
use App\Filament\Resources\TestResource\Pages;
use App\Filament\Resources\TestResource\RelationManagers;
use App\Imports\StudentsImport;
use App\Imports\TestsImport;
use App\Models\Club;
use App\Models\Test;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-vertical';
    protected static ?string $navigationLabel = 'Testler'; // Change menu label
    protected static ?string $pluralLabel = 'Testler'; // Change table title
    protected static ?string $modelLabel = 'Test'; // Change singular label

    public static function form(Form $form): Form
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

                Select::make('student_id')
                    ->label('Öğrenci')
                    ->required()
                    ->options(Student::select('id', DB::raw('CONCAT(name, " ", surname) AS full_name'))
                        ->pluck('full_name', 'id'))
                    ->searchable(),

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

    public static function table(Table $table): Table
    {
        return $table
            ->query(Test::with('student'))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->date('d-m-Y')
                    ->toggleable(),
                TextColumn::make('term')
                    ->label('Dönem')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('student.name')
                    ->label('Öğrenci Adı')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('student.surname')
                    ->label('Öğrenci Soyadı'),
                TextColumn::make('first_service_speed')
                    ->label('1. Servis Hızı'),
                TextColumn::make('second_service_speed')
                    ->label('2. Servis Hızı'),
                TextColumn::make('third_service_speed')
                    ->label('3. Servis Hızı'),
            ])
            ->filters([
                SelectFilter::make('club')
                    ->relationship('student.club', 'name')
                    ->label('Kulüp'),
                    //->options(Club::all()->pluck('name', 'id')),

                SelectFilter::make('term')
                    ->label('Dönem')
                ->options(['1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                        ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Action::make('importExcel')
                    ->label('Excel Verilerini Al')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        FileUpload::make('attachment')
                            ->label('Excel Dosyası Seç')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel'
                            ])
                            ->required()
                            ->disk('local')
                            ->storeFiles(false),
                    ])
                    ->action(function (array $data) {
                        // Burada, FileUpload nesnesinin dosya verisini alıyoruz
                        $uploadedFile = $data['attachment']; // 'file' burada sadece dosya adı olabilir

                        // Excel dosyasını işle
                        try {
                            Excel::import(new TestsImport(), $uploadedFile);
                            Notification::make()
                                ->title('Başarıyla Aktarıldı!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Hata: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                ExportAction::make()
                    ->exporter(TestExporter::class)
                    ->label('Verileri Excel\'e Gönder')
                    ->icon('heroicon-o-arrow-up-tray')

            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()
                    ->exporter(TestExporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
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
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }
}
