<?php

namespace App\Filament\Resources;

use App\Filament\Exports\StudentExporter;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Club;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Öğrenciler'; // Change menu label
    protected static ?string $pluralLabel = 'Öğrenciler'; // Change table title
    protected static ?string $modelLabel = 'Öğrenci'; // Change singular label


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Ad')
                    ->required()
                    ->maxLength(255),
                TextInput::make('surname')
                    ->label('Soyad')
                    ->required()
                    ->maxLength(255),
                TextInput::make('age')
                    ->required()
                    ->label('Yaş')
                    ->numeric()
                    ->mask('99')
                    ->maxLength(2),
                Forms\Components\Select::make('gender')
                    ->required()
                    ->label('Cinsiyet')
                    ->options([
                        'kız' => 'Kız',
                        'erkek' => 'Erkek',
                    ])
                    ->default('kız'),

                Forms\Components\Select::make('club_id')
                    ->label('Kulüp')
                    ->required()
                    ->options(Club::all()->pluck('name', 'id'))//->relationship('club', 'name')//
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Ad')->searchable(),
                TextColumn::make('surname')->label('Soyad')->searchable(),
                TextColumn::make('age')->label('Yaş'),
                TextColumn::make('club.name')->label('Kulüp'),
            ])
            ->filters([
                SelectFilter::make('club_id')
                        ->label('Kulüp')
                        ->options(Club::all()->pluck('name', 'id')),
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
                            Excel::import(new StudentsImport(), $uploadedFile);
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
                    ->exporter(StudentExporter::class)
                    ->label('Verileri Excel\'e Gönder')
                    ->icon('heroicon-o-arrow-up-tray')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()
                    ->exporter(StudentExporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TestsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getTableActions(): array
    {
        return [

        ];
    }

}
