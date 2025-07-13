<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bireysel Test Raporu - {{ $student->getFullName() }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @page {
            margin: 20px;
            size: A4;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 100%;
            margin: 0;
            background: white;
            padding: 20px;
            border-radius: 0;
            box-shadow: none;
            page-break-inside: avoid;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 15px;
            page-break-inside: avoid;
        }
        .logo {
            height: 100px;
            margin-right: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .student-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .student-info p {
            margin: 5px 0;
            font-size: 16px;
        }
        .student-info strong {
            color: #495057;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            page-break-inside: avoid;
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
            gap: 20px;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .chart-wrapper {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            page-break-inside: avoid;
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
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .reference-table h3 {
            margin-top: 0;
            color: #333;
        }
        .assessment {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
            page-break-inside: avoid;
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
        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 15px;
            }
        }
        
        /* DOMPDF specific styles */
        .no-break {
            page-break-inside: avoid;
        }
        
        /* Ensure content fits on one page */
        .container {
            max-height: 100vh;
            overflow: hidden;
        }
        
        /* Reduce spacing for PDF */
        .header {
            margin-bottom: 15px !important;
            padding-bottom: 10px !important;
        }
        
        .student-info {
            margin-bottom: 15px !important;
            padding: 10px !important;
        }
    </style>
</head>
<body>
    <div class="container no-break">
        <div class="header">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="logo">
            <h1>Sportif Performans Bireysel Gelişim Raporu</h1>
        </div>

        <div class="student-info">
            <p><strong>Öğrenci:</strong> {{ $student->getFullName() }}</p>
            <p><strong>Yaş:</strong> {{ $student->age }} &nbsp;&nbsp; <strong>Cinsiyet:</strong> {{ $student->gender }}</p>
            <p><strong>Kulüp:</strong> {{ $student->club->name ?? '-' }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Test Sırası</th>
                    <th>Test Tarihi</th>
                    <th>Test Türü</th>
                    <th>1. Ölçüm</th>
                    <th>2. Ölçüm</th>
                    <th>3. Ölçüm</th>
                    <th class="best-score">Ölçüm Sonucu</th>
                    <th>Servis Hızı Ortalaması</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student->tests as $test)
                <tr>
                    <td>{{ $test->term }}</td>
                    <td>{{ $test->created_at->format('d/m/Y') }}</td>
                    <td>Voleybol Servis Hızı</td>
                    <td>{{ $test->first_service_speed }}</td>
                    <td>{{ $test->second_service_speed }}</td>
                    <td>{{ $test->third_service_speed }}</td>
                    <td class="best-score">{{ max($test->first_service_speed, $test->second_service_speed, $test->third_service_speed) }}</td>
                    <td>{{ number_format(($test->first_service_speed + $test->second_service_speed + $test->third_service_speed)/3, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="charts-container">
            <div class="chart-wrapper">
                <div class="chart-title">Voleybol Servis Hızı Gelişim Grafiği</div>
                <canvas id="pieChart" width="300" height="300"></canvas>
            </div>
            <div class="chart-wrapper">
                <div class="chart-title">Voleybol Servis Hızı Değerlendirme Grafiği</div>
                <canvas id="barChart" width="350" height="300"></canvas>
            </div>
        </div>

        <div class="reference-table">
            <h3>Sports Data Voleybol Servis Hızı Ölçüm Referans Değerleri</h3>
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
            <h3>Bireysel Performans Değerlendirmesi</h3>
            <p>{!! $msg !!}</p>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('reports.team') }}" class="btn btn-secondary">Takım Raporuna Git</a>
            <a href="{{ url()->previous() }}" class="btn">Geri Dön</a>
        </div>
    </div>

    <script>
        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    data: @json($chartData['data']),
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Ölçüm Sonucu',
                        data: @json($chartData['data2']['max']),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Servis Hızı Ortalaması',
                        data: @json($chartData['data2']['avg']),
                        backgroundColor: 'rgba(255, 206, 86, 0.6)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
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
                    title: {
                        display: true,
                        text: 'Servis Hızı Karşılaştırması'
                    }
                }
            }
        });
    </script>
</body>
</html>
