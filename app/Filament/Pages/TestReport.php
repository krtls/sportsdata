<?php

namespace App\Filament\Pages;

use App\Models\Student;
use App\Models\Test;
use App\Models\TestAssesment;
use Barryvdh\DomPDF\Facade\Pdf;
use ChartPdf\Charts\ChartPdf;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Club;
use App\Helpers\ArrowHelper;

use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class TestReport extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';  // Icon for the page in the sidebar
    protected static string $view = 'filament.pages.test-report'; // View for the page
    protected static ?string $title = 'Test Raporu';
    protected static ?string $navigationLabel = 'Test Raporu';  // Label for the sidebar
    protected static ?int $navigationSort = 2;  // The position of the page in the sidebar
    public float $avgOfHighestScores;

    public ?string $arrow="";

    public ?string $ageGroup="";

    // Define the table function
    public function mount(): void
    {

    }

    public function table(Tables\Table $table): Tables\Table
    {

        return $table
            //->query(Student::with('tests')) // Load students with tests relationship
            ->query(function () {
                $filters = $this->tableFilters ?? [];

                $year = $filters['year']['value'] ?? null;
                $term = $filters['term']['value'] ?? null;
                $clubId = $filters['club_id']['value'] ?? null;
                $ageFilter = $filters['age']['value'] ?? null;

                $query = Student::query();

                // Doğrudan students tablosuna uygulanır
                if ($clubId && $clubId != "") {
                    $query->where('club_id', $clubId);
                }

                // Yaş filtrelemesi
//                if ($ageFilter && $ageFilter != "") {
////                    match ($ageFilter) {
////                        'under_14' => $query->where('age', '<=', 14),
////                        '15_16' => $query->whereBetween('age', [15, 16]),
////                        '17_18' => $query->whereBetween('age', [17, 18]),
////                        '19_25' => $query->whereBetween('age', [19, 25]),
////                        'over_25' => $query->where('age', '>', 25),
////                        default => null,
////                    };
//                    switch ($ageFilter) {
//                        case 'under_14':
//                            $query->where('age', '<=', 14);
//                            $this->ageGroup=1;
//                            break;
//                        case '15_16':
//                            $query->whereBetween('age', [15, 16]);
//                            $this->ageGroup=2;
//                            break;
//                        case '17_18':
//                            $query->whereBetween('age', [17, 18]);
//                            $this->ageGroup=3;
//                            break;
//                        case '19_25':
//                            $query->whereBetween('age', [19, 25]);
//                            $this->ageGroup=4;
//                            break;
//                        case 'over_25':
//                            $query->where('age', '>', 25);
//                            $this->ageGroup=5;
//                            break;
//                    }
//                }

                $testFilter = function ($q) use ($year, $term) {
                    if ($year) {
                        $q->whereYear('created_at', $year);
                    }
                    if ($term) {
                        $q->where('term', $term);
                    }
                };

                $query->whereHas('tests', $testFilter);
                $query->with(['tests' => $testFilter]);

                return $query;
            })

            ->columns([
                TextColumn::make('name')->label('Ad'),
                TextColumn::make('surname')->label('Soyad'),
                TextColumn::make('age')->label('Yaş')->toggleable(),
                TextColumn::make('tests.term')->label('Test No')->toggleable(),
//                    ->getStateUsing(function ($record) {
//                        // Eğer term kolonundaki veriler virgüllerle ayrılıyorsa, alt alta gelmesini sağlamak için
//                        $terms = explode(',', $record->term);  // Virgülle ayır
//                        return implode('<br>', $terms);         // Alt alta yazmak için <br> etiketini kullan
//                    })
//                    ->html(), // HTML etiketlerinin işlenmesini sağlar,
                TextColumn::make('tests.first_service_speed')->label('1. Servis Hızı'),
                TextColumn::make('tests.second_service_speed')->label('2. Servis Hızı'),
                TextColumn::make('tests.third_service_speed')->label('3. Servis Hızı'),

                Tables\Columns\TextColumn::make('best')
                    ->label('En İyi Servis Hızı')
                    ->color('primary')
                    ->getStateUsing(function ($record) {
                        return $this->calculateBestServiceSpeed($record->tests);
                    }),

//                BadgeColumn::make('status')
//                    ->label('Durum')
//                    ->enum([
//                        'passed' => 'Geçti',
//                        'failed' => 'Kaldı',
//                    ])
//                    ->colors(['success', 'danger']),
                Tables\Columns\TextColumn::make('arrow')
                    ->label('Düşünceler')
                    ->getStateUsing(function ($record) {
                        $highest=$this->calculateBestServiceSpeed($record->tests);
                        $age = $record->age;
                        //$avg = $this->avgOfHighestScores;

                        $arrow = ArrowHelper::findArrow($age, $highest);//$this->findArrow($age, $highest);
                        $this->arrow=$arrow;
                        return $arrow;

                    })
                    ->html()

            ])
            ->filters([
//                SelectFilter::make('club_id')
//                    ->label('Kulüp')
//                    ->options(Club::all()->pluck('name', 'id')),

                SelectFilter::make('club_id')
                    ->label('Kulüp')
                    ->options(Club::all()->pluck('name', 'id')),
//                    ->query(function (Builder $query, $state) {
//
//                        //if($state['value'])
//                        when($state['value'],
//                            $query->whereHas('tests', fn ($q) => $q->where('club_id', $state['value']))
//                        );
//                    }),

                SelectFilter::make('age')
                    ->label('Yaş Grubu')
                    ->default('under_14')
                    ->options([
                        'under_14' => '14 yaş altı',
                        '15_16' => '15–16',
                        '17_18' => '17-18',
                        '19_25' => '19-25',
                        'over_25' => 'Genç kadınlar',
                    ])
                    ->query(function (Builder $query, $state) {

                        if ($state['value']) {
                            //logger("Filtering by age group:" . print_r($state, true));
                            switch ($state['value']) {
                                case 'under_14':
                                    $query->where('age', '<=', 14);
                                    $this->ageGroup="under_14";
                                    break;

                                case '15_16':
                                    $query->whereBetween('age', [15, 16]);
                                    $this->ageGroup="15_16";
                                    break;

                                case '17_18':
                                    $query->whereBetween('age', [17, 18]);
                                    $this->ageGroup="17_18";
                                    break;

                                case '19_25':
                                    $query->whereBetween('age', [19, 25]);
                                    $this->ageGroup="19_25";
                                    break;

                                case 'over_25':
                                    $query->where('age', '>', 25);
                                    $this->ageGroup="over_25";
                                    break;
                            }
                        }
                    }),

                SelectFilter::make('year')
                    //->relationship('tests', 'created_at')
                    ->label('Test Yılı')
                    ->default(date('Y'))
                    ->options(function () {
                        return Test::query()
                                ->selectRaw('YEAR(created_at) as year')
                                ->distinct()
                                ->get()
                                ->pluck('year', 'year')
                                ->toArray();

                    })

                    ->query(function (Builder $query, $state) {
                        if($state['value']!="")
                            $query->whereHas('tests', fn ($q) => $q->whereYear('created_at', $state['value']));
                    }),

                SelectFilter::make('term')
                    ->label('Dönem')
                    ->default(1)
                    ->options(Test::query()
                        ->select('term')
                        ->distinct()
                        ->pluck('term', 'term')
                        ->toArray()
                    )
                    ->query(function (Builder $query, $state) {
                        if($state['value']!="")
                         $query->whereHas('tests', fn ($q) => $q->where('term', $state['value']));

                    }),

            ])

            ->headerActions([
                Action::make('viewTeam')
                    ->label('Takım Raporunu Görüntüle')
                    ->icon('heroicon-o-eye')
                    ->url(fn () => route('reports.team'))
                    ->openUrlInNewTab()
                    ->color('primary'),
                Action::make('exportPdf')
                    ->label('Takım Raporu İndir')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => $this->exportFilteredPdf())
                    ->color('success'),

            ])
            ->bulkActions([
                BulkAction::make('download_reports')
                    ->label('Toplu Bireysel Rapor')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Collection $records) {
                        set_time_limit(300); // 5 dakika izin
                        $zipFileName = 'kulüp-test-raporlari.zip';
                        $zipPath = storage_path("app/tmp-reports/$zipFileName");

                        // Ensure temp directory exists
                        Storage::makeDirectory('tmp-reports');

                        $zip = new ZipArchive;
                        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                            foreach ($records as $record) {

                                $pdf = Pdf::loadView('exports.testReportIndividual',
                                                [   'student' => $record,
                                                    'chartUrl' => $this->indGeneratePieChart($record),
                                                    'msg' => $this->getMsg($team=0, $record),
                                                ]);
                                $pdfContent = $pdf->output();

                                $filename = str($record->getFullName())->slug() . '.pdf';
                                $zip->addFromString($filename, $pdfContent);
                            }

                            $zip->close();
                        }

                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
            ])

            ->actions([
                // Tables\Actions\Action::make('view')
                //     ->label('Eski')
                //     ->color('primary')
                //     ->icon('heroicon-o-eye')
                //     ->url(fn (Student $record) => route('reports.individual', $record))
                //     ->openUrlInNewTab(),
                Tables\Actions\Action::make('new_view')
                    ->label('Görüntüle')
                    ->color('warning')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Student $record) => route('reports.individual.new', $record))
                    ->openUrlInNewTab(),
                // Tables\Actions\Action::make('pdf')
                //     ->label('PDF')
                //     ->color('success')
                //     ->icon('heroicon-s-arrow-down-tray')
                //     ->action(function (Student $record) {
                //         $pdf = Pdf::loadView('exports.testReportIndividual',
                //                                 [   'student' => $record,
                //                                     'chartUrl' =>$this->indGeneratePieChart($record),
                //                                     'msg' => $this->getMsg($team=0, $record),
                //                                 ]);
                //         return response()->streamDownload(
                //             fn () => print($pdf->output()),
                //             $record->getFullName().'.pdf'
                //         );
                //     }),

                    Tables\Actions\Action::make('pdf-new')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-s-arrow-down-tray')
                    ->action(function (Student $record) {
                        $chartUrl = $this->indGenerateNewCharts($record);
                        $pdf = Pdf::loadView('exports.testReportIndividualNew', [
                            'student' => $record,
                            'chartUrl' => $chartUrl,
                            'msg' => $this->getMsg($team=0, $record),
                        ]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            $record->getFullName().'-yeni.pdf'
                        );
                    }),
            ])
            ->recordAction(null); // Eğer default view/edit gibi aksiyonları kapatmak istersen

    }

    public function getIdealValue()
    {
        return match (true) {
            $this->ageGroup=="under_14" => 63,
            $this->ageGroup=="15_16" => 68,
            $this->ageGroup=="17_18" => 72,
            $this->ageGroup=="19_25" => 73,
            $this->ageGroup=="over_25" => 76,
        };

    }

    public function getOrtValue()
    {
        return match (true) {
            $this->ageGroup=="under_14" => 43,
            $this->ageGroup=="15_16" => 49,
            $this->ageGroup=="17_18" => 56,
            $this->ageGroup=="19_25" => 59,
            $this->ageGroup=="over_25" => 64,
        };

    }

    public function indGeneratePieChart($student): array
    {
        $labels = [];
        $chartData = [];
        $chartData2 = ['max' => [], 'avg' => []];

        if(count($student->tests)==1) {
            $labels = ['1. Ölçüm', '2. Ölçüm', '3. Ölçüm'];

        // Initialize arrays to hold the service speed values

            $firstTest = $student->tests->first();
            $datas = [
                $firstTest?->first_service_speed,
                $firstTest?->second_service_speed,
                $firstTest?->third_service_speed,
            ];

            $chartData = $datas;

//            $datas=[
//                $student->tests->first()?->first_service_speed,
//                $student->tests->first()?->second_service_speed,
//                $student->tests->first()?->third_service_speed,
//            ];
//            $chartData=[
//                $datas[0],
//                $datas[1],
//                $datas[2]
//            ];

            $chartData2['max'][] = max($datas);
            $chartData2['avg'][] = round(array_sum($datas) / count($datas), 2);

        }
        else{
//            $datas=[
//                'highest' => [],
//            ];
//            $labels=[
//                'term' => [],
//            ];
            foreach ($student->tests as $test) {
                $labels[]='Test: '. $test->term; //$labels['term'][]
                $speeds = [
                    $test->first_service_speed,
                    $test->second_service_speed,
                    $test->third_service_speed,
                ];

                $chartData[] = max($speeds);
                $chartData2['max'][] = max($speeds);
                $chartData2['avg'][] = round(array_sum($speeds) / count($speeds), 2);
            }
            //dd(count($student->tests),$labels,$chartData);
        }

        $chartConfig = [
            'type' => 'pie',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' =>  $chartData,

                    ]
                ]
            ],
            'options' => [
                'responsive' => false,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => ['position' => 'bottom'],
                    'datalabels' => [
                        'color' => 'white',
                        'font' => [
                            'family' => 'Arial',
                            'size' => 16,
                            'weight' => 'bold'
                        ],
                        'formatter' => 'value',
                    ],
                ]
            ]
        ];

        $chartConfig2 = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Ölçüm Sonucu',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                        'data' => $chartData2['max'],
                    ],
                    [
                        'label' => 'Servis Hızı Ortalaması',
                        'backgroundColor' => 'rgba(255, 206, 86, 0.6)',
                        'data' => $chartData2['avg'],
                    ]
                ]
            ],
            'options' => [
                'responsive' => false,
                'plugins' => [
                    'datalabels' => [
                        'anchor' => 'end',
                        'align' => 'top',
                        'color' => '#000',
                        'font' => [
                            'weight' => 'bold'
                        ]
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Servis Hızı Karşılaştırması'
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true
                    ]
                ]
            ]
        ];

        $chartUrl1 = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig) ). '&width=300&height=300';
        $chartUrl2 = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig2));

        return [$chartUrl1, $chartUrl2];
    }

    protected function calculateBestServiceSpeed($tests)
    {
        $first = $tests->pluck('first_service_speed')->max(); //->max() ?? 0
        $second = $tests->pluck('second_service_speed')->max();
        $third = $tests->pluck('third_service_speed')->max();
//        $first = $tests->first()?->first_service_speed;
//        $second = $tests->first()?->second_service_speed;
//        $third = $tests->first()?->third_service_speed;

        //dd($first,$second,$third);
        return max($first, $second, $third);
    }
    public function generateChart(): array
    {
        // Example product data
        $labels = ['İdeal Değer', 'Ortalama Değer', 'Takım Ortalaması'];
        $datas = [
            $this->getIdealValue(),
            $this->getOrtValue(),
            $this->getAverageOfHighestScores()
        ];

        // Generate QuickChart chart config
        $chartConfig = [
            'type' => 'horizontalBar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Servis Hızı',
                    'data' => $datas,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                    ],
                    'barThickness' => 25,         // Sabit bar kalınlığı
                    'maxBarThickness' => 35,      // Responsive kullanımda üst sınır
                ]],
            ],
            'options' => [
                'responsive' => false,
                'plugins' => [
                    'datalabels' => [
                        'anchor' => 'end',
                        'align' => 'top',
                        'color' => '#000',
                        'font' => [
                            'weight' => 'bold',
                        ],
                    ],
                    'legend' => [
                        'position' => 'bottom',
                    ],
                ],
                'scales' => [
                    'x' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'precision' => 0,
                        ],
                    'y' => [
                        'categoryPercentage' => 0.2,
                        'barPercentage' => 0.6,
                    ],
                ],
                ],
            ],
            'plugins' => ['chartjs-plugin-datalabels'],
        ];

        $labelNames = [];
        $bestSpeeds = [];

        $queryCollection = $this->getFilteredTableQuery()->get();

//        $labelNames = $this->getFilteredTableQuery()
//            ->get()
//            ->map(fn($item) => "{$item->name} {$item->surname}")
//            ->toArray();
//
//        $labelBestServices=map(function ($student) {
//            return $this->calculateBestServiceSpeed($student->tests);
//        }
        //dd($labelBestServices);

        foreach ($queryCollection as $qc) {
            $fullName = "{$qc->name} {$qc->surname}";
            $labelNames[] = $fullName;

            $maxValue = $this->calculateBestServiceSpeed($qc->tests);

            $bestSpeeds[] = $maxValue;
        }

//        $avgBestSpeeds=count($bestSpeeds) > 0
//            ? array_sum($bestSpeeds) / count($bestSpeeds) : 0;
//
//        $avgOfBestSpeeds=round($avgBestSpeeds, 2);
        $avgOfBestSpeeds=$this->getAverageOfHighestScores();
        //dd($labelNames, $bestSpeeds, $avgOfBestSpeeds);
        $chartConfig2=[
            'type' => 'line',
            'data' => [
                'labels' => $labelNames,
                'datasets' => [
                    [
                        'label' => 'En İyi Servis Hızı',
                        'data' => $bestSpeeds,
                        'borderColor' => 'blue',
                        'fill' => false,
                    ],
                    [
                        'label' => 'Takım Ortalama Servis Hızı',
                        'data' => array_fill(0, count($bestSpeeds), $avgOfBestSpeeds),
                        'borderColor' => 'orange',
                        'fill' => false,
                    ]
                ],
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['labels' => ['color' => 'white']],
                ],
                'scales' => [
                    'x' => ['ticks' => ['color' => 'white']],
                    'y' => ['ticks' => ['color' => 'white']],
                ],
            ],

        ];


        $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));
        $chartUrl2 = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig2));

        $chart=compact('chartUrl', 'chartUrl2');

        return $chart;
    }

    public function getAverageOfHighestScores(): float
    {
        return round(
            $this->getFilteredTableQuery()
                    ->with(['tests' => function ($query) {
                        $query->when($this->tableFilters['term']['value'], function (Builder $q, $term) {
                                $q->where('term', $term);
                           });
                        $query->when($this->tableFilters['year']['value'], function (Builder $q, $year) {
                            $q->whereYear('created_at', $year);
                        });
                    }])
                    //->with('tests') ->whereYear('created_at', $this->tableFilters['year']['value'])
                    ->get()
                    ->map(function ($student) {
                        return $this->calculateBestServiceSpeed($student->tests);
                    })
                    ->filter()
                    ->avg() ?? 0.0 , 2
        );

    }

    protected function exportFilteredPdf()
    {
        // 1. Tabloya uygulanan filtreleri içeren query'yi al
        $filteredTests = $this->getFilteredTableQuery()->with('tests')->get();
//dd($filteredTests->toArray());
        // 2. PDF oluştur
        $pdf = Pdf::loadView('exports.testReportTeam', [
            'students' => $filteredTests,
            //'arrow' => $this->arrow,
            'avg' => $this->getAverageOfHighestScores(),
            'chartUrl' => $this->generateChart(),
            'msg' => $this->getMsg($team=1),
        ]);

        // 3. İndir
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'takım-raporu.pdf'
        );
    }

    private function getMsg($team, $students=null): string
    {
        //dd($students->tests->count());
        if ($team==1) {
            $forWhom = "takım";
            $avg = $this->getAverageOfHighestScores();
        }
        else {
            $forWhom = "bireysel";
            foreach ($students->tests as $test) {
                $avg[]=round(($test->first_service_speed + $test->second_service_speed + $test->third_service_speed)/3,2);
            }

            $avg=max($avg);
        }

        if ($avg >= 34 && $avg < 39)
            $col='34-38';
        else if ($avg >= 38 && $avg < 43)
            $col='38-42';
        else if ($avg >= 43 && $avg < 49)
            $col='43-48';
        else if ($avg >= 49 && $avg < 55)
            $col='49-54';
        else if ($avg >= 55 && $avg < 60)
            $col='55-59';
        else if ($avg >= 60 && $avg < 63)
            $col='60-62';
        else if ($avg >= 63 && $avg < 66)
            $col='63-65';
        else if ($avg >= 66 && $avg < 69)
            $col='66-68';
        else if ($avg >= 69 && $avg < 73)
            $col='69-72';
        else if ($avg >= 73)
            $col='73+';
        else
            $col='34-38';

        // return TestAssesment::query()
        //     ->where('for_whom', $forWhom)
        //     ->where('age_group', $this->ageGroup)
        //     ->value($col);

        return "Lorem ipsum nasıl yapılır?
HTML Seçenekleri (isteğe bağlı): Eğer metnin içeriğine HTML etiketleri eklemek istiyorsanız, bu seçenekleri işaretleyin. Örneğin, <p> (paragraf), <i> (italik) veya <strong> (kalın) gibi etiketler kullanabilirsiniz. Metni Oluştur: “Oluştur” veya benzeri bir düğmeye tıklayarak Lorem Ipsum metnini üretin.
Lorem ipsum nasıl yapılır?
HTML Seçenekleri (isteğe bağlı): Eğer metnin içeriğine HTML etiketleri eklemek istiyorsanız, bu seçenekleri işaretleyin. Örneğin, <p> (paragraf), <i> (italik) veya <strong> (kalın) gibi etiketler kullanabilirsiniz. Metni Oluştur: “Oluştur” veya benzeri bir düğmeye tıklayarak Lorem Ipsum metnini üretin. ";
    }

    private function indGenerateNewCharts($student): array
    {
        $labels = [];
        $chartData = [];
        $chartData2 = ['max' => [], 'avg' => []];
        $chartConfig=[];
        $chartConfig2=[];
        if(count($student->tests)===1) {
            $labels = ['1. Ölçüm', '2. Ölçüm', '3. Ölçüm'];

        // Initialize arrays to hold the service speed values

            $firstTest = $student->tests->first();
            $datas = [
                $firstTest?->first_service_speed,
                $firstTest?->second_service_speed,
                $firstTest?->third_service_speed,
            ];

            $chartData = $datas;

//            $datas=[
//                $student->tests->first()?->first_service_speed,
//                $student->tests->first()?->second_service_speed,
//                $student->tests->first()?->third_service_speed,
//            ];
//            $chartData=[
//                $datas[0],
//                $datas[1],
//                $datas[2]
//            ];

            $chartData2['max'][] = max($datas);
            $chartData2['avg'][] = round(array_sum($datas) / count($datas), 2);

        }
        else{
//            $datas=[
//                'highest' => [],
//            ];
//            $labels=[
//                'term' => [],
//            ];
            foreach ($student->tests as $i=>$test) {
                $labels[]='Test: '. $test->term; //$labels['term'][]
                $speeds = [
                    $test->first_service_speed,
                    $test->second_service_speed,
                    $test->third_service_speed,
                ];

                $chartData[] = max($speeds);
                $chartData2['max'][] = max($speeds);
                $chartData2['avg'][] = round(array_sum($speeds) / count($speeds), 2);

                $chartConfig2[] = [
                    'type' => 'gauge',
                    'data' => [
                        'labels' => ['20', '40', '60', '80', '100'],
                        'datasets' => [
                            [
                                'data' => [20, 40, 60, 80, 100],
                                'label' => 'Servis Hızı',
                                'value' => $chartData2['avg'][$i],
                                'min' => 0,
                                'max' => 100,
                                'backgroundColor' => ['#2ecc40', '#b6e651', '#ffe066', '#ffae42', '#ff4136' ],
                                'borderColor' => 'rgba(255, 99, 132, 1)',
                                'borderWidth' => 1,
                                'pointStyle' => 'circle',
                                'radius' => '100%',
                                'datalabels' => [
                                    'color' => 'white',
                                    'font' => [
                                        'family' => 'Arial',
                                        'size' => 20,
                                        'weight' => 'bold'
                                    ],
                                    'formatter' => function($value) {
                                        return $value . ' km/h';
                                    }
                                ]
                            ]
                        ]
                    ],
                    'options' => [
                        'responsive' => false,
                        'needle' => [
                            'radiusPercentage'=> 1,
                            'widthPercentage'=> 1,
                            'lengthPercentage'=> 60,
                            'color'=> '#000',
                        ],
                        'valueLabel'=> [
                            'fontSize'=> 20,
                            'backgroundColor'=> 'transparent',
                            'color'=> '#000',
                        ],


                        'plugins' => [
                            'datalabels' => [
                                'anchor' => 'end',
                                'align' => 'top',
                                'color' => '#000',
                                'font' => [
                                    'weight' => 'bold'
                                ]
                            ],
                            'title' => [
                                'display' => true,
                                'text' => 'Servis Hızı'
                            ]
                        ],
                        'scales' => [
                            'y' => [
                                'beginAtZero' => true,
                                'min' => 0,
                                'max' => 100,
                                'ticks' => [
                                    'precision' => 0,
                                    'color' => 'black'
                                ],
                                'grid' => [
                                    'color' => 'rgba(0, 0, 0, 0.2)'
                                ]
                            ]
                        ]
                    ]
                ];

            }
            //dd(count($student->tests),$labels,$chartData);
        }

        $lastTests=$student->tests()->latest()->first();
        $lastTestDatas = [
            $lastTests?->first_service_speed,
            $lastTests?->second_service_speed,
            $lastTests?->third_service_speed,
        ];

        foreach ($lastTestDatas as $i=>$lastTestData) {
            
            $chartConfig[] = [
                'type' => 'gauge',
                'data' => [
                    'labels' => ['20', '40', '60', '80', '100'],
                    'datasets' => [
                        [
                            'data' => [20, 40, 60, 80, 100],
                            'label' => 'Servis Hızı',
                            'value' => $lastTestData,
                            'min' => 0,
                            'max' => 100,
                            'backgroundColor' => ['#2ecc40', '#b6e651', '#ffe066', '#ffae42', '#ff4136' ],
                            'borderColor' => 'rgba(255, 99, 132, 1)',
                            'borderWidth' => 1,
                            'pointStyle' => 'circle',
                            'radius' => '100%',
                            'datalabels' => [
                                'color' => 'white',
                                'font' => [
                                    'family' => 'Arial',
                                    'size' => 20,
                                    'weight' => 'bold'
                                ],
                                'formatter' => function($value) {
                                    return $value . ' km/h';
                                }
                            ]
                        ]
                    ]
                ],
                'options' => [
                    'responsive' => false,
                    'needle' => [
                        'radiusPercentage'=> 1,
                        'widthPercentage'=> 1,
                        'lengthPercentage'=> 60,
                        'color'=> '#000',
                    ],
                    'valueLabel'=> [
                        'fontSize'=> 30,
                        'backgroundColor'=> 'transparent',
                        'color'=> '#fff',
                    ],


                    'plugins' => [
                        'datalabels' => [
                            'anchor' => 'end',
                            'align' => 'top',
                            'color' => '#fff',
                            'font' => [
                                'weight' => 'bold'
                            ]
                        ],
                        'title' => [
                            'display' => true,
                            'text' => 'Servis Hızı'
                        ]
                    ],
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                            'min' => 0,
                            'max' => 100,
                            'ticks' => [
                                'precision' => 0,
                                'color' => 'white'
                            ],
                            'grid' => [
                                'color' => 'rgba(0, 0, 0, 0.2)'
                            ]
                        ]
                    ]
                ]
            ];

        }
        // $chartConfig = [
        //     'type' => 'bar',
        //     'data' => [
        //         'labels' => $labels,
        //         'datasets' => [
        //             [
        //                 'label' => 'Ölçüm Sonucu',
        //                 'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
        //                 'data' => $chartData2['max'],
        //             ],
        //             [
        //                 'label' => 'Servis Hızı Ortalaması',
        //                 'backgroundColor' => 'rgba(255, 206, 86, 0.6)',
        //                 'data' => $chartData2['avg'],
        //             ]
        //         ]
        //     ],
        //     'options' => [
        //         'responsive' => false,
        //         'plugins' => [
        //             'datalabels' => [
        //                 'anchor' => 'end',
        //                 'align' => 'top',
        //                 'color' => '#000',
        //                 'font' => [
        //                     'weight' => 'bold'
        //                 ]
        //             ],
        //             'title' => [
        //                 'display' => true,
        //                 'text' => 'Servis Hızı Karşılaştırması'
        //             ]
        //         ],
        //         'scales' => [
        //             'y' => [
        //                 'beginAtZero' => true
        //             ]
        //         ]
        //     ]
        // ];


        // $chartConfig2 = [
        //     'type' => 'gauge',
        //     'data' => [
        //         'labels' => ['20', '40', '60', '80', '100'],
        //         'datasets' => [
        //             [
        //                 'data' => [20, 40, 60, 80, 100],
        //                 'label' => 'Servis Hızı',
        //                 'value' => $chartData2['avg'][0],
        //                 'min' => 0,
        //                 'max' => 100,
        //                 'backgroundColor' => ['#2ecc40', '#b6e651', '#ffe066', '#ffae42', '#ff4136' ],
        //                 'borderColor' => 'rgba(255, 99, 132, 1)',
        //                 'borderWidth' => 1,
        //                 'pointStyle' => 'circle',
        //                 'radius' => '100%',
        //                 'datalabels' => [
        //                     'color' => 'white',
        //                     'font' => [
        //                         'family' => 'Arial',
        //                         'size' => 20,
        //                         'weight' => 'bold'
        //                     ],
        //                     'formatter' => function($value) {
        //                         return $value . ' km/h';
        //                     }
        //                 ]
        //             ]
        //         ]
        //     ],
        //     'options' => [
        //         'responsive' => false,
        //         'needle' => [
        //             'radiusPercentage'=> 1,
        //             'widthPercentage'=> 1,
        //             'lengthPercentage'=> 60,
        //             'color'=> '#000',
        //         ],
        //         'valueLabel'=> [
        //             'fontSize'=> 20,
        //             'backgroundColor'=> 'transparent',
        //             'color'=> '#000',
        //         ],


        //         'plugins' => [
        //             'datalabels' => [
        //                 'anchor' => 'end',
        //                 'align' => 'top',
        //                 'color' => '#000',
        //                 'font' => [
        //                     'weight' => 'bold'
        //                 ]
        //             ],
        //             'title' => [
        //                 'display' => true,
        //                 'text' => 'Servis Hızı'
        //             ]
        //         ],
        //         'scales' => [
        //             'y' => [
        //                 'beginAtZero' => true,
        //                 'min' => 0,
        //                 'max' => 100,
        //                 'ticks' => [
        //                     'precision' => 0,
        //                     'color' => 'black'
        //                 ],
        //                 'grid' => [
        //                     'color' => 'rgba(0, 0, 0, 0.2)'
        //                 ]
        //             ]
        //         ]
        //     ]
        // ];

        // $chartUrl1 = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig) );
        $chartUrl = [];
        foreach ($chartConfig as $i=>$config) {
            $chartUrl[] = 'https://quickchart.io/chart?c=' . urlencode(json_encode($config));

        }
       
        $chartUrl2 = [];
        foreach ($chartConfig2 as $i=>$config2) {
            $chartUrl2[] = 'https://quickchart.io/chart?c=' . urlencode(json_encode($config2));

        }
        return [
            'gauge' => $chartUrl2,
            'lastGauge' => $chartUrl,
        ];
    }

}
