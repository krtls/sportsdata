<?php

namespace App\Filament\Resources\TestAssesmentResource\Pages;

use App\Filament\Resources\TestAssesmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestAssesment extends EditRecord
{
    protected static string $resource = TestAssesmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
