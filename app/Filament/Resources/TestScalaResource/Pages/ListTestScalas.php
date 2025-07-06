<?php

namespace App\Filament\Resources\TestScalaResource\Pages;

use App\Filament\Resources\TestScalaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestScalas extends ListRecords
{
    protected static string $resource = TestScalaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
