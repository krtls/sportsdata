<!DOCTYPE html>
<html>
<head>
    <title>Test Report</title>
    <style>
        @page {

        }
            body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
        }
        .column {
            float: left;
            width: 50%;

        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th {
            font-size: 0.5rem;
            background-color: white;
            color: black;

        }
        p{ font-size: 0.5rem;}
        table tr {
            font-size: 0.5rem;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .message{
            font-size: 0.65rem;
        }

        caption{
            font-size: 0.65rem;
            font-weight: bold;
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
    </style>
</head>
<body>

<div style="font-size: 0;">
    <img src="{{ asset('storage/images/logo.jpeg') }}" style="display: inline-block; vertical-align: middle; height: 60px; width: 184px; margin-left: 10px;">

    <h2 style="display: inline-block; vertical-align: middle; margin: 0; font-size: 18px;">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sportif Performans Bireysel Gelişim Raporu
    </h2>
</div>

<div class="row">
    <div class="column">

        <p><strong>Öğrenci:</strong> {{ $student->getFullName() }}</p>
        <p><strong>Yaş:</strong> {{ $student->age }} &nbsp;&nbsp;
            <strong>Cinsiyet:</strong>{{ $student->gender }}</p>
        <p><strong>Kulüp:</strong> {{ $student->club->name ?? '-' }}</p>

        <table style="" class="">
            <caption >Bireysel Test Ölçüm Sonuçları</caption>
            <thead>
            <tr>
                <th >Test Sırası</th>
                <th >Test Tarihi</th>
                <th >Test Türü</th>
                <th > 1. Ölçüm</th>
                <th > 2. Ölçüm</th>
                <th > 3. Ölçüm</th>
                <th > Ölçüm Sonucu</th>
                <th > Servis Hızı Ortalaması</th>

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
                <td>{{ max($test->first_service_speed, $test->second_service_speed, $test->third_service_speed) }}</td>
                <td>{{ number_format(($test->first_service_speed+ $test->second_service_speed+ $test->third_service_speed)/3, 2) }}</td>

            </tr>
        @endforeach

            </tbody>
        </table>

        <h5>Voleybol Servis Hızı Gelişim Grafiği</h5>

        <img src="{{ $chartUrl[0] }}" alt="Chart" width="300" height="300" style="display: block; margin: auto;"/>

        <h5>Voleybol Servis Hızı Değerlendirme Grafiği</h5>

        <img src="{{ $chartUrl[1] }}" alt="Chart" width="350" height="300"/>

    </div>

    <div class="column" style="margin-left: 10px;">
        <table style="width: 100%; float: left; ">
            <caption>Sports Data Voleybol Servis Hızı Ölçüm Referans Değerleri</caption>
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
                <tr >
                    <td>14 Yaş Altı Kızlar</td>
                    <td>34 - 42</td>
                    <td>43 - 62</td>
                    <td>63 - 72</td>
                    <td>73 ve üzeri</td>
                </tr>
                <tr >
                    <td>15-16 Yaş Kızlar</td>
                    <td>40 - 48</td>
                    <td>49 - 66</td>
                    <td>67 - 76</td>
                    <td>77 ve üzeri</td>
                </tr>
                <tr >
                    <td>17-18 Yaş Kızlar</td>
                    <td>49 - 55</td>
                    <td>56 - 70</td>
                    <td>71 -  79</td>
                    <td>80 ve üzeri</td>
                </tr >
                <tr >
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
        <br><br><br><br><br><br><br>
        <h5>Profesyonel Sporcuların Servis Hızı</h5>
        <img src="{{ asset('storage\images\prosSpeed.png') }}" alt="Profesyonellerin servis hızı"
             style="margin-top: 50px; width: 100%;"/>

        <h5>Bireysel Performans Değerlendirmesi</h5>
        <p class="message">{!! $msg !!}</p>
    </div>
</div>

<div class="footer">
    © {{ date('Y') }} Test Raporu
</div>

{{-- Add more fields as needed --}}
</body>
</html>
