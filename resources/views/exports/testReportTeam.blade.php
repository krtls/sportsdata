<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Rapor</title>

    <link rel="stylesheet" href="{{ asset('pdf.css') }}" type="text/css">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        h4 {
            margin: 0;
        }
        .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
        }
        .margin-top {
            margin-top: 1.25rem;
        }
        .footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;

        }
        .message{
            font-size: 7pt;
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        table.students {
            font-size: 9px;
        }
        table.students tr {

        }
        table.students th {
            font-size: 10px;
            background-color: white;
            color: black;

        }

        table.scalas th {
            font-size: 8px;
            background-color: white;
            color: black;

        }
        table tr.items {
            font-size: 8px;
        }
        table tr.items2 {
            font-size: 7px;
        }
        table tr.items td {
            background-color: white;
            text-align: center;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .total {
            text-align: right;
            margin-top: 5px;
            font-size: 7px;
        }

    </style>
</head>
<body>

<div style="font-size: 0;">
    <img src="{{ asset('images\logo.png') }}" style="display: inline-block; vertical-align: middle; height: 50px; margin-left: 10px;">

    <h2 style="display: inline-block; vertical-align: middle; margin: 0; font-size: 18px;">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sportif Performans Takım Raporu
    </h2>
</div>

@foreach($students as $student)
    @php
        $test = $student->tests->first();
        $club=$student->club->name;
    @endphp
@endforeach
<div style="font-size: 9px;">
    Kulüp: {{$club}} <br>
    Test Tarihi: {{ $test->created_at->format('d/m/Y') }}
</div>
<div style="width: 100%; overflow: hidden;">

    <!-- First Table -->
    <table style="width: 40%; float: left; margin-right: 2%;"
            class="students">
         <thead>
            <tr>
                <th >Sıra</th>
                <th > Ad</th>
                <th > Soyadı</th>
                <th > Yaş</th>
                <th >1. Servis Hızı</th>
                <th >2. Servis Hızı</th>
                <th >3. Servis Hızı</th>
                <th >En İyi Servis Hızı</th>
                <th >Düşünceler</th>

            </tr>
            </thead>

            <tbody>
            @foreach($students as $student)
                @php
                    $test = $student->tests->first();
                @endphp
                <tr class="items">
                    <td >{{ $loop->iteration }}</td>
                    <td >{{ $student->name }}</td>
                    <td >{{ $student->surname }}</td>
                    <td >{{ $student->age }}</td>
                    <td >{{ $test->first_service_speed }}</td>
                    <td >{{ $test->second_service_speed }}</td>
                    <td >{{ $test->third_service_speed }}</td>
                    <td style="color: blue;">{{ max($test->first_service_speed, $test->second_service_speed, $test->third_service_speed) }}</td>

                    <td >
                        {!! App\Helpers\ArrowHelper::findArrow($student->age, max($test->first_service_speed, $test->second_service_speed, $test->third_service_speed)) !!}
                    </td>

                </tr>
            @endforeach
            </tbody>

        </table>

    <h5>SportsData Voleybol Servis Hızı Ölçüm Referans Değerleri</h5>

    <!-- Second Table -->
    <table style="width: 40%; float: left; "
        class="scalas">
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
        <tr class="items2">
            <td>14 Yaş Altı Kızlar</td>
            <td>34 - 42</td>
            <td>43 - 63</td>
            <td>63 - 72</td>
            <td>72</td>
        </tr>
        <tr class="items2">
            <td>15-16 Yaş Kızlar</td>
            <td>40	48</td>
            <td>49	67</td>
            <td>68	76</td>
            <td>76</td>
        </tr>
        <tr class="items2">
            <td>17-18 Yaş Kızlar</td>
            <td>49	55</td>
            <td>56	71</td>
            <td>72	79</td>
            <td>79</td>
        </tr >
        <tr class="items2">
            <td>19-25 Yaş Kızlar</td>
            <td>52	58</td>
            <td>59	72</td>
            <td>73	79</td>
            <td>79</td>
        </tr>
        <tr class="items2">
            <td>Genç Kadınlar</td>
            <td>58	63</td>
            <td>64	75</td>
            <td>76	80</td>
            <td>80</td>
        </tr>
        </tbody>
    </table>
<br><br><br><br><br><br><br><br>

<h5>Takım Kıyaslama Grafiği</h5>
    <img src="{{ $chartUrl['chartUrl'] }}" alt="Chart" width="40%" />

<h5>Sporcu Bazlı Başarı Grafiği</h5>
    <img src="{{ $chartUrl['chartUrl2'] }}" alt="Chart" width="40%" />

        <h5>Takım Performans Değerlendirmesi</h5>
        <p class="message" style="font-size: 7pt; ">{!! $msg !!}</p>

    <div style="clear: both;"></div> <!-- Clear float -->

</div>
<div class="footer">
    © {{ date('Y') }} Test Raporu
</div>
</body>
</html>
