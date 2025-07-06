<!DOCTYPE html>
<html>
<head>
    <title>Test Report</title>
    <style>
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
        }
    </style>
</head>
<body>

<h2>Sportif Performans Bireysel Gelişim Raporu</h2>

<div class="row">
    <div class="column">
        <p><strong>Öğrenci:</strong> {{ $student->getFullName() }}</p>
        <p><strong>Yaş:</strong> {{ $student->age }} &nbsp;&nbsp;
            <strong>Cinsiyet:</strong>{{ $student->gender }}</p>

        <table style="" class="">
            <caption>Bireysel Test Ölçüm Sonuçları</caption>
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
                    <td>{{ $test->created_at->format('d-m-Y') }}</td>
                    <td>Voleybol Servis Hızı</td>
                    <td>{{ $test->first_service_speed }}</td>
                    <td>{{ $test->second_service_speed }}</td>
                    <td>{{ $test->third_service_speed }}</td>
                    <td>{{ max($test->first_service_speed, $test->second_service_speed, $test->third_service_speed) }}</td>
                    <td>{{  number_format(($test->first_service_speed+ $test->second_service_speed+ $test->third_service_speed)/3, 2) }}</td>

                </tr>
            @endforeach

            </tbody>
        </table>

        <h4>Voleybol Servis Hızı Gelişim Grafiği</h4>

        <img src="{{ $chartUrl }}" alt="Chart" width="300" height="300"/>

    </div>

    <div class="column">
        <table style="width: 48%; float: left; "
               class="scalas">
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
    </div>
</div>



{{-- Add more fields as needed --}}
</body>
</html>
