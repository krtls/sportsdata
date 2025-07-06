<?php

namespace App\Filament\Widgets;

use App\Models\Club;
use App\Models\Student;
use App\Models\Test;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Kulüpler', Club::count())
                ->description('Kulüp sayısı')
                ->descriptionIcon('heroicon-s-users')
                ->chart([1,3,5,10,20,40])
                ->color('info'),

            Stat::make('Öğrenciler', Student::count())
                ->description('Öğrenci sayısı')
                ->descriptionIcon('heroicon-s-users')
                ->chart([1,3,5,10,20,40])
                ->color('success'),

            Stat::make('Testler', Test::count())
                ->description('Test sayısı')
                ->descriptionIcon('heroicon-s-users')
                ->chart([1,3,5,10,20,40])
                ->color('danger'),
        ];
    }
}
