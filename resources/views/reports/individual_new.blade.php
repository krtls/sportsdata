<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bireysel Test Raporu - {{ $student->getFullName() }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 10;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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

        .student-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        .two-column-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 10px 0;
        }

        .left-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .right-column {
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 900px) {
            .two-column-layout {
                grid-template-columns: 1fr;
            }

            .left-column,
            .right-column {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 15px;
            }
        }

        @media (max-width: 1100px) {
            .gauge-grid {
                flex-wrap: wrap;
            }

            .chart-wrapper {
                flex-basis: 45%;
            }
        }

        @media (max-width: 700px) {
            .gauge-grid {
                flex-direction: column;
                align-items: center;
            }

            .chart-wrapper {
                flex-basis: 100%;
                max-width: 100%;
            }
        }
        .img-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('storage/images/logo.jpeg') }}" alt="Logo" class="logo">
            <h1>Sportif Performans Bireysel Gelişim Raporu</h1>
        </div>

        <div class="student-info">
            <p><strong>Öğrenci:</strong> {{ $student->getFullName() }}</p>
            <p><strong>Yaş:</strong> {{ $student->age }} &nbsp;&nbsp; <strong>Cinsiyet:</strong> {{ $student->gender }}
            </p>
            <p><strong>Kulüp:</strong> {{ $student->club->name ?? '-' }}</p>
        </div>

        @php
            $lastTest = $student->tests->last();
        @endphp

        {{-- <div style="position: relative; width: 350px; height: 350px; margin: 0 auto;">
            <img src="/images/voleybol_saha.png" alt="Voleybol Sahası" style="width: 100%; height: 100%;">

            <!-- 5. Bölge (1. Servis Hızı) -->
            <canvas id="gauge1" width="40" height="40"
                style="position: absolute; left: 165px; top: 225px;"></canvas>

            <!-- 6. Bölge (2. Servis Hızı) -->
            <canvas id="gauge2" width="40" height="40"
                style="position: absolute; left: 225px; top: 185px;"></canvas>

            <!-- 1. Bölge (3. Servis Hızı) -->
            <canvas id="gauge3" width="40" height="40"
                style="position: absolute; left: 285px; top: 145px;"></canvas>
        </div> --}}
        <!-- İKİ SÜTUN BAŞLANGIÇ -->
        <div class="two-column-layout">
            <div class="left-column">
                <div style="position: relative; width: 350px; height: 350px; margin: 0 auto;">
                    <img src="/images/voleybol_saha.png" alt="Voleybol Sahası" style="width: 100%; height: 100%;">
                    <canvas id="gauge1" width="40" height="40"
                        style="position: absolute; left: 165px; top: 225px;"></canvas>
                    <canvas id="gauge2" width="40" height="40"
                        style="position: absolute; left: 225px; top: 185px;"></canvas>
                    <canvas id="gauge3" width="40" height="40"
                        style="position: absolute; left: 285px; top: 145px;"></canvas>
                </div>

                <h1 class="text-center bg-amber-300 p-4">Servis Hızları Ortalaması</h1>
                <div class="gauge-grid"
                    style="display: flex; flex-wrap: wrap; gap: 24px; justify-content: center; margin-bottom: 32px;">

                    @foreach ($student->tests as $test)
                        <div class="chart-wrapper" style="max-width: 220px; flex: 1 1 200px; min-width: 180px;">
                            <div class="chart-title" style="text-align:center; margin-bottom:10px;">
                                {{ $test->term }}. Dönem Servis Hızı Ortalaması
                            </div>
                            <canvas id="gaugeChart_{{ $test->id }}" width="200" height="120"></canvas>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col items-center my-4">
                    <div style="display: flex; width: 400px; height: 30px; border-radius: 4px; overflow: hidden;">
                        <div style="flex:1; background: #006400;"></div>
                        <div style="flex:1; background: #32CD32;"></div>
                        <div style="flex:1; background: #FFFF00;"></div>
                        <div style="flex:1; background: #FFA500;"></div>
                        <div style="flex:1; background: #FF0000;"></div>
                    </div>
                    <div
                        style="display: flex; width: 400px; justify-content: space-between; font-size: 14px; margin-top: 2px;">
                        <span>0</span><span>20</span><span>40</span><span>60</span><span>80</span><span>100</span>
                    </div>
                    <div class="text-sm mt-1">Servis Hızı Barem Çubuğu (0-100 km/h)</div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Test Sırası</th>
                            <th>Test Tarihi</th>
                            {{-- <th>Test Türü</th>
                    <th>1. Ölçüm</th>
                    <th>2. Ölçüm</th>
                    <th>3. Ölçüm</th>
                    <th class="best-score">Ölçüm Sonucu</th> --}}
                            <th>Servis Hızı Ortalaması</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($student->tests as $test)
                            <tr>
                                <td>{{ $test->term }}</td>
                                <td>{{ $test->created_at->format('d/m/Y') }}</td>
                                {{-- <td>Voleybol Servis Hızı</td>
                    <td>{{ $test->first_service_speed }}</td>
                    <td>{{ $test->second_service_speed }}</td>
                    <td>{{ $test->third_service_speed }}</td>
                    <td class="best-score">{{ max($test->first_service_speed, $test->second_service_speed, $test->third_service_speed) }}</td> --}}
                                <td>{{ number_format(($test->first_service_speed + $test->second_service_speed + $test->third_service_speed) / 3, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="right-column">


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
                                <td>43 - 62</td>
                                <td>63 - 72</td>
                                <td>73 ve üzeri</td>
                            </tr>
                            <tr>
                                <td>15-16 Yaş Kızlar</td>
                                <td>40 - 48</td>
                                <td>49 - 66</td>
                                <td>67 - 76</td>
                                <td>77 ve üzeri</td>
                            </tr>
                            <tr>
                                <td>17-18 Yaş Kızlar</td>
                                <td>49 - 55</td>
                                <td>56 - 70</td>
                                <td>71 - 79</td>
                                <td>80 ve üzeri</td>
                            </tr>
                            <tr>
                                <td>19-25 Yaş Kızlar</td>
                                <td>52 -58</td>
                                <td>59 - 71</td>
                                <td>72 - 80</td>
                                <td>81 ve üzeri</td>
                            </tr>
                            <tr>
                                <td>Genç Kadınlar</td>
                                <td>58 - 63</td>
                                <td>64 - 74</td>
                                <td>75 - 85</td>
                                <td>86 ve üzeri</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

<div class="img-center">
    <img src="{{ asset('storage/images/prosSpeed.png') }}"
    style="" alt="ProsSpeed">
</div>

                <div class="assessment">
                    <h3>Bireysel Performans Değerlendirmesi</h3>
                    <p>{!! $msg !!}</p>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ route('reports.team') }}" class="btn btn-secondary">Takım Raporuna Git</a>
                    <a href="{{ url()->previous() }}" class="btn">Geri Dön</a>
                </div>
            </div> <!-- right-column -->
        </div> <!-- two-column-layout -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($student->tests as $test)
                (function() {
                    const avgSpeed =
                        {{ number_format(($test->first_service_speed + $test->second_service_speed + $test->third_service_speed) / 3, 0) }};
                    const maxSpeed = 100;
                    const startAngle = -135 * Math.PI / 180;
                    const endAngle = 135 * Math.PI / 180;

                    let displayValue = avgSpeed;
                    if (displayValue > maxSpeed) displayValue = maxSpeed;
                    const angle = startAngle + (endAngle - startAngle) * (displayValue / maxSpeed);

                    const gaugeData = [
                        maxSpeed * 0.2, // 0-10
                        maxSpeed * 0.2, // 10-20
                        maxSpeed * 0.2, // 20-30
                        maxSpeed * 0.2, // 30-40
                        maxSpeed * 0.2 // 40-50
                    ];
                    const gaugeColors = [
                        '#2ecc40', // yeşil
                        '#b6e651', // açık yeşil
                        '#ffe066', // sarı
                        '#ffae42', // turuncu
                        '#ff4136' // kırmızı
                    ];

                    const ctx = document.getElementById('gaugeChart_{{ $test->id }}').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: gaugeData,
                                backgroundColor: gaugeColors,
                                borderWidth: 0,
                                cutout: '70%'
                            }]
                        },
                        options: {
                            responsive: false,
                            rotation: -135,
                            circumference: 270,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: false
                                }
                            }
                        },
                        plugins: [{
                            id: 'needle',
                            afterDraw: chart => {
                                const {
                                    ctx
                                } = chart;
                                const width = chart.width;
                                const height = chart.height;
                                ctx.save();

                                const cx = width / 2;
                                const cy = height * 0.9;
                                const r = Math.min(width, height * 2) / 2.2 * 0.85;

                                const maxSpeed = 100;
                                const startAngle = -135 * Math.PI / 180;
                                const endAngle = 135 * Math.PI / 180;

                                let displayValue = avgSpeed;
                                if (displayValue > maxSpeed) displayValue = maxSpeed;
                                const angle = startAngle + (endAngle - startAngle) * (
                                    displayValue / maxSpeed);

                                ctx.translate(cx, cy);
                                ctx.rotate(angle);
                                ctx.beginPath();
                                ctx.moveTo(0, 0);
                                ctx.lineTo(0, -r);
                                ctx.lineWidth = 4;
                                ctx.strokeStyle = '#222';
                                ctx.stroke();
                                ctx.rotate(-angle);
                                ctx.translate(-cx, -cy);

                                ctx.beginPath();
                                ctx.arc(cx, cy, 8, 0, 2 * Math.PI);
                                ctx.fillStyle = '#222';
                                ctx.fill();

                                ctx.font = 'bold 32px Segoe UI';
                                ctx.fillStyle = '#222';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(avgSpeed, cx, cy - r * 0.7);

                                ctx.restore();
                            }
                        }]
                    });
                })();
            @endforeach

            // Son testin servis hızları
            const s1 = {{ $lastTest->first_service_speed ?? 0 }};
            const s2 = {{ $lastTest->second_service_speed ?? 0 }};
            const s3 = {{ $lastTest->third_service_speed ?? 0 }};
            const maxSpeed = 100;

            function drawGauge(canvasId, value) {
                const ctx = document.getElementById(canvasId).getContext('2d');
                const gaugeData = [
                    maxSpeed * 0.2, // 0-20
                    maxSpeed * 0.2, // 20-40
                    maxSpeed * 0.2, // 40-60
                    maxSpeed * 0.2, // 60-80
                    maxSpeed * 0.2 // 80-100
                ];
                const gaugeColors = [
                    '#006400', // koyu yeşil
                    '#32CD32', // açık yeşil
                    '#FFFF00', // sarı
                    '#FFA500', // turuncu
                    '#FF0000' // kırmızı
                ];
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: gaugeData,
                            backgroundColor: gaugeColors,
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: false,
                        rotation: -90,
                        circumference: 180,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    },
                    plugins: [{
                        id: 'needle',
                        afterDraw: chart => {
                            const {
                                ctx
                            } = chart;
                            const width = chart.width;
                            const height = chart.height;
                            ctx.save();

                            const cx = width / 2;
                            const cy = height * 0.9;
                            const r = Math.min(width, height * 2) / 2.2 * 0.85;

                            let displayValue = value;
                            if (displayValue > maxSpeed) displayValue = maxSpeed;
                            
                            const startAngle = Math.PI; // left (180°)
                            const endAngle = 0;         // right (0°)
                            const angle = startAngle + (endAngle - startAngle) * (displayValue / maxSpeed)+30;

                            ctx.translate(cx, cy);
                            ctx.rotate(-angle);
                            ctx.beginPath();
                            ctx.moveTo(0, 0);
                            ctx.lineTo(0, -r);
                            ctx.lineWidth = 1;
                            ctx.strokeStyle = '#222';
                            ctx.stroke();
                            ctx.rotate(angle);
                            ctx.translate(-cx, -cy);

                            ctx.beginPath();
                            ctx.arc(cx, cy, 2, 0, 2 * Math.PI);
                            ctx.fillStyle = '#222';
                            ctx.fill();

                            ctx.font = 'bold 8px Segoe UI';
                            ctx.fillStyle = '#222';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(value, cx, cy - r * 0.7);

                            ctx.restore();
                        }
                    }]
                });
            }

            drawGauge('gauge1', s1);
            drawGauge('gauge2', s2);
            drawGauge('gauge3', s3);
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
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
