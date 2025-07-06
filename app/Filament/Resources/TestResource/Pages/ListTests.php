<?php

namespace App\Filament\Resources\TestResource\Pages;

use App\Filament\Resources\TestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTests extends ListRecords
{
    protected static string $resource = TestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                 ->url(fn () => route('filament.admin.resources.tests.create', [
                                'club_id' => request()->input('tableFilters.club.value'),
                                'term_id' => request()->input('tableFilters.term.value')
                 ])),
         ];
    }
}
