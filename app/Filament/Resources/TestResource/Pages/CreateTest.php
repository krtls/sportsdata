<?php

namespace App\Filament\Resources\TestResource\Pages;

use App\Filament\Resources\TestResource;
use App\Models\Student;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateTest extends CreateRecord
{
    protected static string $resource = TestResource::class;

    public function form(Form|\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $clubId = request()->get('club_id');
        $termId = request()->get('term_id');

        $options = Student::when($clubId, fn ($query) => $query->where('club_id', $clubId))
            ->get()
            ->map(fn ($student) => [
                'id' => $student->id,
                'name' => $student->getFullName()
            ])
            ->pluck('name', 'id')
            ->toArray();

        return $form
            ->schema([
                TextInput::make('term')
                    ->required()
                    ->label('Dönem')
                    ->numeric()
                    ->mask('9')
                    ->maxLength(1)
                    ->default($termId),

                Select::make('student_id')
                    ->label('Öğrenci')
                    ->options(
                        $options
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('first_service_speed')
                    ->label('5. Bölge')
                    ->numeric()
                    ->mask('99')
                    ->maxLength(2),

                TextInput::make('second_service_speed')
                    ->label('6. Bölge')
                    ->numeric()
                    ->mask('99')
                    ->maxLength(2),

                TextInput::make('third_service_speed')
                    ->label('1. Bölge')
                    ->numeric()
                    ->mask('99')
                    ->maxLength(2),

                // diğer alanlar...
            ]);
    }
}
