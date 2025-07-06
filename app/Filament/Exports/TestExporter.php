<?php

namespace App\Filament\Exports;

use App\Models\Test;
use Filament\Actions\Action;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;


class TestExporter extends Exporter
{
    protected static ?string $model = Test::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('term')
                ->label('Dönem'),
            ExportColumn::make('student.name')
                ->label('Öğrenci Ad'),
            ExportColumn::make('student.surname')
                ->label('Öğrenci Soyad'),
            ExportColumn::make('first_service_speed')
                ->label('1. Servis Hızı'),
            ExportColumn::make('second_service_speed')
                ->label('2. Servis Hızı'),
            ExportColumn::make('third_service_speed')
                ->label('3. Servis Hızı'),
            //ExportColumn::make('created_at'),
            ExportColumn::make('updated_at')
                ->label('Düzenlenme Tarihi'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Dışa aktarma hazırlandı ve  ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' aktarıldı.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' aktarılamadı.';
        }

        return $body;
    }


}
