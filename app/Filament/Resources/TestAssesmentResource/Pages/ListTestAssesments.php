<?php

namespace App\Filament\Resources\TestAssesmentResource\Pages;

use App\Filament\Resources\TestAssesmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestAssesments extends ListRecords
{
    protected static string $resource = TestAssesmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
