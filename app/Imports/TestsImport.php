<?php

namespace App\Imports;

use App\Models\Test;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class TestsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Başlık satırını atla (varsa)

            Test::create([
                'term' => $row[0],// Excel'deki sütun sırasına göre değiştir
                'student_id' => $row[1],
                'first_service_speed' => $row[2],
                'second_service_speed' => $row[3],
                'third_service_speed' => $row[4],
            ]);
        }
    }
}
