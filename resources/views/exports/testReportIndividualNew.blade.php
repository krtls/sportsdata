<!DOCTYPE html>
<html>
<head>
    <title>Bireysel Test Raporu</title>
    <style>
        @page {}
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
        }
        .column {
            float: left;
            width: 50%;
        }
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
            padding: 6px 4px;
            line-height: 1.3;
        }
        .message{
            font-size: 0.65rem;
        }
        caption{
            font-size: 0.65rem;
            font-weight: bold;
        }
        .bar-scale {
            display: flex;
            width: 90%;
            height: 30px;
            border-radius: 4px;
            overflow: hidden;
            margin: 16px 0 4px 0;
        }
        .bar-scale > div { flex: 1; }
        .bar-labels {
            display: flex;
            width: 90%;
            justify-content: space-between;
            font-size: 10px;
            margin-top: 2px;
        }
        .bar-desc {
            font-size: 10px;
            margin-top: 4px;
        }
        p, .message {
            line-height: 1.4;
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
<br>
<table width="100%">
  <tr>
    <td style="width:50%; vertical-align:top; padding-right:10px;">
      <!-- Sol sütun içeriği (öğrenci info, grafikler, tablo vs) -->
        <div class="student-info">
            <p><strong>Öğrenci:</strong> {{ $student->getFullName() }}</p>
            <p><strong>Yaş:</strong> {{ $student->age }} &nbsp;&nbsp; <strong>Cinsiyet:</strong> {{ $student->gender }}</p>
            <p><strong>Kulüp:</strong> {{ $student->club->name ?? '-' }}</p>
        </div>

        <div style="position: relative;">
            <img src="{{ asset('storage/images/voleybol_saha.png') }}" alt="Voleybol Sahası" style="width: 90%; margin-bottom: 10px;"/>
            
            {{-- Speedometer chart'ları voleybol sahasındaki 5, 6 ve 1 nolu bölgeler üzerinde --}}
            @php
                // Voleybol sahasındaki bölge pozisyonları (tahmini)
                $x = [160, 210, 260]; // 5, 6, 1 nolu bölgelerin x koordinatları
                $y = [140, 105, 70]; // 5, 6, 1 nolu bölgelerin y koordinatları
            @endphp
            @foreach($chartUrl['lastGauge'] as $i => $lastGaugeUrl)
            <div style="position: absolute; left: {{ $x[$i] }}px; top: {{ $y[$i] }}px; text-align: center;">
                <img src="{{ $lastGaugeUrl }}" alt="Servis Hızı" style="width: 30px; height: 20px; margin-bottom: 1px;" />
            </div>
            @endforeach
        </div>


        {{-- <img src="{{ $chartUrl['gauge'] }}" alt="Servis Hızı" style="width: 90%; margin-bottom: 10px;"/> --}}

        {{-- @foreach($chartUrl['gauge'] as $gaugeUrl)
        <div style="margin-bottom: 10px; text-align: center;">
            <div style="font-weight: bold; margin-bottom: 2px;">
                {{ $loop->iteration }}. Test Dönemi
            </div>
            <img src="{{ $gaugeUrl }}" alt="Servis Hızı" style="width: 45%; margin-bottom: 5px;" />
        </div>
        @endforeach --}}

        <table width="100%" style="border-collapse: collapse;">
            <tr>
            @foreach($chartUrl['gauge'] as $i => $gaugeUrl)
                <td style="width:50%; text-align: center; vertical-align: top; padding-bottom: 12px;">
                    <div style="font-weight: bold; margin-bottom: 2px;">
                        {{ $i + 1 }}. Test Dönemi
                    </div>
                    <img src="{{ $gaugeUrl }}" alt="Servis Hızı" style="width: 90%; margin-bottom: 5px;" />
                </td>
                @if(($i+1) % 2 == 0 && $i+1 < count($chartUrl['gauge']))
            </tr><tr>
                @endif
            @endforeach
            @if(count($chartUrl['gauge']) % 2 == 1)
                <td style="width:50%"></td>
            @endif
            </tr>
        </table>

        <img src="{{ asset('storage/images/service_bar.png') }}" alt="Servis Hızı Barem Çubuğu" style="display: block; margin: 0 auto 10px auto; max-width: 320px; width: 100%;" />

        <table style="margin-bottom: 16px;">
            <caption>Voleybol Servis Hızı Gelişim Tablosu</caption>
            <thead>
            <tr>
                <th>Test Sırası</th>
                <th>Test Tarihi</th>
                <th>Servis Hızı Ortalaması</th>
            </tr>
            </thead>
            <tbody>
            @foreach($student->tests as $test)
                <tr>
                    <td>{{ $test->term }}</td>
                    <td>{{ $test->created_at->format('d/m/Y') }}</td>
                    <td>{{ number_format(($test->first_service_speed + $test->second_service_speed + $test->third_service_speed)/3, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </td>
    <td style="width:50%; vertical-align:top; padding-left:10px;">
      <!-- Sağ sütun içeriği (referans tablo, resim, $msg vs) -->
        <table style="width: 80%; float: left; ">
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
        <div style="clear: both; height: 18px;"></div>
        <h5 style="margin-top: 20px;">Profesyonel Sporcuların Servis Hızı</h5>
        <img src="{{ asset('storage/images/prosSpeed.png') }}" alt="Profesyonellerin servis hızı" style="margin-top: 10px; width: 80%; display:block;"/>

        <br>
        <h5 style="margin-top: 20px;">Bireysel Performans Değerlendirmesi</h5>
        <p class="message">{!! $msg !!}</p>
       </td>
  </tr>
</table>
</body>
</html>
