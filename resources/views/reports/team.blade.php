<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takım Test Raporu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
        }
        .logo {
            height: 120px;
            margin-right: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .team-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .team-info p {
            margin: 5px 0;
            font-size: 16px;
        }
        .team-info strong {
            color: #495057;
        }
        .filters {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .filters form {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        .filters label {
            font-weight: 600;
            color: #1976d2;
        }
        .filters select, .filters input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .filters button {
            padding: 8px 16px;
            background: #1976d2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .filters button:hover {
            background: #1565c0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .best-score {
            color: #007bff;
            font-weight: bold;
        }
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 30px 0;
        }
        .chart-wrapper {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .chart-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }
        .reference-table {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        .reference-table h3 {
            margin-top: 0;
            color: #333;
        }
        .assessment {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            border-left: 4px solid #2196f3;
        }
        .assessment h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 15px;
            }
            .filters form {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('storage/images/logo.jpeg') }}" alt="Logo" class="logo">
            <h1>Sportif Performans Takım Raporu</h1>
        </div>

        <div class="filters">
            <form method="GET" action="{{ route('reports.team') }}">
                <div>
                    <label for="year">Yıl:</label>
                    <select name="year" id="year">
                        <option value="">Tüm Yıllar</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $filters['year'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="term">Dönem:</label>
                    <select name="term" id="term">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ $filters['term'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="club_id">Kulüp:</label>
                    <select name="club_id" id="club_id">
                        <option value="">Tüm Kulüpler</option>
                        @foreach(\App\Models\Club::all() as $club)
                            <option value="{{ $club->id }}" {{ $filters['club_id'] == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit">Filtrele</button>
            </form>
        </div>

        @if($students->count() > 0)
            @php
                $firstStudent = $students->first();
                $club = $firstStudent->club->name ?? 'Bilinmeyen Kulüp';
                $test = $firstStudent->tests->first();
            @endphp

            <div class="team-info">
                <p><strong>Kulüp:</strong> {{ $club }}</p>
                <p><strong>Test Tarihi:</strong> {{ $test ? $test->created_at->format('d/m/Y') : 'Bilinmiyor' }}</p>
                <p><strong>Öğrenci Sayısı:</strong> {{ $students->count() }}</p>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <h3>Takım Ortalaması</h3>
                    <div class="value">{{ number_format($avg, 2) }}</div>
                </div>
                <div class="stat-card">
                    <h3>En Yüksek Skor</h3>
                    <div class="value">{{ $students->map(function($s) { return max($s->tests->pluck('first_service_speed')->max(), $s->tests->pluck('second_service_speed')->max(), $s->tests->pluck('third_service_speed')->max()); })->max() }}</div>
                </div>
                <div class="stat-card">
                    <h3>En Düşük Skor</h3>
                    <div class="value">{{ $students->map(function($s) { return max($s->tests->pluck('first_service_speed')->max(), $s->tests->pluck('second_service_speed')->max(), $s->tests->pluck('third_service_speed')->max()); })->min() }}</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Sıra</th>
                        <th>Ad</th>
                        <th>Soyadı</th>
                        <th>Yaş</th>
                        <th>1. Servis Hızı</th>
                        <th>2. Servis Hızı</th>
                        <th>3. Servis Hızı</th>
                        <th class="best-score">En İyi Servis Hızı</th>
                        <th>Düşünceler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @php
                            $test = $student->tests->first();
                            $bestSpeed = max($test->first_service_speed, $test->second_service_speed, $test->third_service_speed);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->surname }}</td>
                            <td>{{ $student->age }}</td>
                            <td>{{ $test->first_service_speed }}</td>
                            <td>{{ $test->second_service_speed }}</td>
                            <td>{{ $test->third_service_speed }}</td>
                            <td class="best-score">{{ $bestSpeed }}</td>
                            <td>{!! \App\Helpers\ArrowHelper::findArrow($student->age, $bestSpeed) !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="charts-container">
                <div class="chart-wrapper">
                    <div class="chart-title">Takım Kıyaslama Grafiği</div>
                    <canvas id="horizontalBarChart" width="400" height="300"></canvas>
                </div>
                <div class="chart-wrapper">
                    <div class="chart-title">Sporcu Bazlı Başarı Grafiği</div>
                    <canvas id="lineChart" width="400" height="300"></canvas>
                </div>
            </div>

            <div class="reference-table">
                <h3>SportsData Voleybol Servis Hızı Ölçüm Referans Değerleri</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Yaş Grupları</th>
                            <th>Önerilenin Altında</th>
                            <th>Ortalama Seviye</th>
                            <th>İdeal Performans</th>
                            <th>Yüksek Performans</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>14 Yaş Altı Kızlar</td>
                            <td>34 - 42</td>
                            <td>43 - 63</td>
                            <td>63 - 72</td>
                            <td>72+</td>
                        </tr>
                        <tr>
                            <td>15-16 Yaş Kızlar</td>
                            <td>40 - 48</td>
                            <td>49 - 67</td>
                            <td>68 - 76</td>
                            <td>76+</td>
                        </tr>
                        <tr>
                            <td>17-18 Yaş Kızlar</td>
                            <td>49 - 55</td>
                            <td>56 - 71</td>
                            <td>72 - 79</td>
                            <td>79+</td>
                        </tr>
                        <tr>
                            <td>19-25 Yaş Kızlar</td>
                            <td>52 - 58</td>
                            <td>59 - 72</td>
                            <td>73 - 79</td>
                            <td>79+</td>
                        </tr>
                        <tr>
                            <td>Genç Kadınlar</td>
                            <td>58 - 63</td>
                            <td>64 - 75</td>
                            <td>76 - 80</td>
                            <td>80+</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="assessment">
                <h3>Takım Performans Değerlendirmesi</h3>
                <p>{!! $msg !!}</p>
            </div>
        @else
            <div style="text-align: center; padding: 50px;">
                <h3>Seçilen kriterlere uygun öğrenci bulunamadı.</h3>
                <p>Farklı filtreler deneyebilirsiniz.</p>
            </div>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url()->previous() }}" class="btn">Geri Dön</a>
        </div>
    </div>

    @if($students->count() > 0)
    <script>
        // Horizontal Bar Chart
        const horizontalBarCtx = document.getElementById('horizontalBarChart').getContext('2d');
        new Chart(horizontalBarCtx, {
            type: 'bar',
            data: {
                labels: ['İdeal Değer', 'Ortalama Değer', 'Takım Ortalaması'],
                datasets: [{
                    label: 'Servis Hızı',
                    data: [73, 65, {{ $avg }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'En İyi Servis Hızı',
                        data: @json($chartData['data']),
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Takım Ortalama Servis Hızı',
                        data: Array(@json(count($chartData['data']))).fill({{ $avg }}),
                        borderColor: 'orange',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @endif
</body>
</html>
