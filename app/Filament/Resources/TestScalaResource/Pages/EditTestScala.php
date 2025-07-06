<?php

namespace App\Filament\Resources\TestScalaResource\Pages;

use App\Filament\Resources\TestScalaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestScala extends EditRecord
{
    protected static string $resource = TestScalaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
