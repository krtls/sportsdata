<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class StudentsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Başlık satırını atla (varsa)

            Student::create([
                'club_id' => $row[0],
                'name' => $row[1],  // Excel'deki sütun sırasına göre değiştir
                'surname' => $row[2],
                'age' => $row[3],
                'gender' => $row[4],

            ]);
        }
    }
}
