<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Test;
use App\Models\TestAssesment;
use App\Helpers\ArrowHelper;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class TestReportController extends Controller
{
    public function showIndividual($studentId)
    {
        $student = Student::with('tests', 'club')->findOrFail($studentId);

        // Chart data for individual student
        $chartData = $this->generateIndividualChartData($student);

        // Get assessment message
        $msg = $this->getIndividualMessage($student);

        return view('reports.individual', [
            'student' => $student,
            'chartData' => $chartData,
            'msg' => $msg
        ]);
    }

    public function showTeam(Request $request)
    {
        // Get filters from request
        $year = $request->get('year');
        $term = $request->get('term', 1);
        $clubId = $request->get('club_id');

        $query = Student::query();

        // Apply filters
        if ($clubId) {
            $query->where('club_id', $clubId);
        }

        $testFilter = function ($q) use ($year, $term) {
            if ($year) {
                $q->whereYear('created_at', $year);
            }
            if ($term) {
                $q->where('term', $term);
            }
        };

        $query->whereHas('tests', $testFilter);
        $students = $query->with(['tests' => $testFilter, 'club'])->get();

        // Calculate average
        $avg = $this->getAverageOfHighestScores($students);

        // Generate charts
        $chartData = $this->generateTeamChartData($students);

        // Get team message
        $msg = $this->getTeamMessage($avg);

        return view('reports.team', [
            'students' => $students,
            'avg' => $avg,
            'chartData' => $chartData,
            'msg' => $msg,
            'filters' => [
                'year' => $year,
                'term' => $term,
                'club_id' => $clubId
            ]
        ]);
    }

    private function generateIndividualChartData($student)
    {
        $labels = [];
        $chartData = [];
        $chartData2 = ['max' => [], 'avg' => []];

        foreach ($student->tests as $test) {
            $labels[] = 'Test: ' . $test->term;
            $speeds = [
                $test->first_service_speed,
                $test->second_service_speed,
                $test->third_service_speed,
            ];

            $chartData[] = max($speeds);
            $chartData2['max'][] = max($speeds);
            $chartData2['avg'][] = round(array_sum($speeds) / count($speeds), 2);
        }

        return [
            'labels' => $labels,
            'data' => $chartData,
            'data2' => $chartData2
        ];
    }

    private function generateTeamChartData($students)
    {
        $labelNames = [];
        $bestSpeeds = [];

        foreach ($students as $student) {
            $fullName = "{$student->name} {$student->surname}";
            $labelNames[] = $fullName;
            $maxValue = $this->calculateBestServiceSpeed($student->tests);
            $bestSpeeds[] = $maxValue;
        }

        return [
            'labels' => $labelNames,
            'data' => $bestSpeeds
        ];
    }

    private function calculateBestServiceSpeed($tests)
    {
        $first = $tests->pluck('first_service_speed')->max();
        $second = $tests->pluck('second_service_speed')->max();
        $third = $tests->pluck('third_service_speed')->max();

        return max($first, $second, $third);
    }

    private function getAverageOfHighestScores($students)
    {
        $scores = $students->map(function ($student) {
            return $this->calculateBestServiceSpeed($student->tests);
        })->filter()->avg();

        return round($scores ?? 0, 2);
    }

    private function getIndividualMessage($student)
    {
        $avg = [];
        foreach ($student->tests as $test) {
            $avg[] = round(($test->first_service_speed + $test->second_service_speed + $test->third_service_speed)/3, 2);
        }
        $avg = max($avg);

        return $this->getAssessmentMessage($avg, 'bireysel');
    }

    private function getTeamMessage($avg)
    {
        return $this->getAssessmentMessage($avg, 'takım');
    }

    private function getAssessmentMessage($avg, $forWhom)
    {
        if ($avg >= 34 && $avg < 39)
            $col = '34-38';
        else if ($avg >= 38 && $avg < 43)
            $col = '38-42';
        else if ($avg >= 43 && $avg < 49)
            $col = '43-48';
        else if ($avg >= 49 && $avg < 55)
            $col = '49-54';
        else if ($avg >= 55 && $avg < 60)
            $col = '55-59';
        else if ($avg >= 60 && $avg < 63)
            $col = '60-62';
        else if ($avg >= 63 && $avg < 66)
            $col = '63-65';
        else if ($avg >= 66 && $avg < 69)
            $col = '66-68';
        else if ($avg >= 69 && $avg < 73)
            $col = '69-72';
        else if ($avg >= 73)
            $col = '73+';
        else
            $col = '34-38';

        return TestAssesment::query()
            ->where('for_whom', $forWhom)
            ->value($col) ?? 'Değerlendirme bulunamadı.';
    }
}
