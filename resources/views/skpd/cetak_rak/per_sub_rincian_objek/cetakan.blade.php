<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ANGKAS SUB RO</title>
    <style>
        #header tr>td {
            font-weight: bold;
            text-align: center
        }

        #sub_header tr>td {
            font-weight: normal;
            text-align: left
        }

        #tabel_angkas,
        th,
        td {
            border-collapse: collapse;
        }

        .angka {
            text-align: right
        }
    </style>
</head>

<body>
    <table style="width: 100%;font-size:20px" id="header">
        <tr>
            <td>RENCANA ANGGARAN KAS</td>
        </tr>
        <tr>
            <td>{{ Str::upper($nama_angkas->nama) }}</td>
        </tr>
        <tr>
            <td>SATUAN KERJA PERANGKAT DAERAH</td>
        </tr>
        <tr>
            <td style="font-weight: normal">Pemerintah Provinsi Kalimantan Barat Tahun Anggaran {{ tahun_anggaran() }}
            </td>
        </tr>
    </table>

    <table style="width: 100%;padding-top:20px" id="sub_header">
        <tr>
            <td>Urusan</td>
            <td>: {{ $sub_header->urusan }}</td>
            <td>{{ $sub_header->nmurusan }}</td>
        </tr>
        <tr>
            <td>Bidang</td>
            <td>: {{ $sub_header->bidang }}</td>
            <td>{{ $sub_header->nmbidang }}</td>
        </tr>
        <tr>
            <td>Unit Organisasi</td>
            <td>: {{ $sub_header->org }}.0000</td>
            <td>{{ $sub_header->nmorg }}</td>
        </tr>
        <tr>
            <td>Sub Unit Organisasi</td>
            <td>: {{ $sub_header->unit }}</td>
            <td>{{ $sub_header->nmunit }}</td>
        </tr>
        <tr>
            <td>Program</td>
            <td>: {{ $sub_header1->program }}</td>
            <td>{{ $sub_header1->nmprogram }}</td>
        </tr>
        <tr>
            <td>Kegiatan</td>
            <td>: {{ $sub_header1->kegiatan }}</td>
            <td>{{ Str::upper($sub_header1->nmkegiatan) }}</td>
        </tr>
        <tr>
            <td>Sub Kegiatan</td>
            <td>: {{ $sub_header1->subkegiatan }}</td>
            <td>{{ Str::upper($sub_header1->nmsubkegiatan) }}</td>
        </tr>
        <tr>
            <td style="vertical-align:top">Nilai Anggaran</td>
            <td style="vertical-align:top">: {{ rupiah($sub_header1->ang) }}</td>
            <td style="vertical-align:top">{{ Str::upper(terbilang($sub_header1->ang)) }}</td>
        </tr>
    </table>

    <table style="width: 100%" id="tabel_angkas" border="1">
        <thead>
            <tr>
                <th>Rekening</th>
                <th>Jumlah Anggaran</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Triwulan I</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Triwulan II</th>
                <th>Jul</th>
                <th>Ags</th>
                <th>Sep</th>
                <th>Triwulan III</th>
                <th>Okt</th>
                <th>Nov</th>
                <th>Des</th>
                <th>Triwulan IV</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_ang = 0;
                $total_jan = 0;
                $total_feb = 0;
                $total_mar = 0;
                $total_apr = 0;
                $total_mei = 0;
                $total_jun = 0;
                $total_jul = 0;
                $total_ags = 0;
                $total_sep = 0;
                $total_okt = 0;
                $total_nov = 0;
                $total_des = 0;
                $total_jumlah = 0;
            @endphp
            @foreach ($data_giat as $giat)
                @php
                    $total_ang += anggaran_rekening_objek($kd_skpd, $kd_sub_kegiatan, $giat->kd_rek6, $jenis_anggaran);
                    $total_jan += $giat->jan;
                    $total_feb += $giat->feb;
                    $total_mar += $giat->mar;
                    $total_apr += $giat->apr;
                    $total_mei += $giat->mei;
                    $total_jun += $giat->jun;
                    $total_jul += $giat->jul;
                    $total_ags += $giat->ags;
                    $total_sep += $giat->sep;
                    $total_okt += $giat->okt;
                    $total_nov += $giat->nov;
                    $total_des += $giat->des;
                @endphp
                <tr>
                    <td>{{ $giat->kd_rek6 }} <br>{{ $giat->nm_rek }}</td>
                    <td class="angka">
                        {{ rupiah(anggaran_rekening_objek($kd_skpd, $kd_sub_kegiatan, $giat->kd_rek6, $jenis_anggaran)) }}
                    </td>
                    <td class="angka">{{ rupiah($giat->jan) }}</td>
                    <td class="angka">{{ rupiah($giat->feb) }}</td>
                    <td class="angka">{{ rupiah($giat->mar) }}</td>
                    <td class="angka">{{ rupiah($giat->jan + $giat->feb + $giat->mar) }}</td>
                    <td class="angka">{{ rupiah($giat->apr) }}</td>
                    <td class="angka">{{ rupiah($giat->mei) }}</td>
                    <td class="angka">{{ rupiah($giat->jun) }}</td>
                    <td class="angka">{{ rupiah($giat->apr + $giat->mei + $giat->jun) }}</td>
                    <td class="angka">{{ rupiah($giat->jul) }}</td>
                    <td class="angka">{{ rupiah($giat->ags) }}</td>
                    <td class="angka">{{ rupiah($giat->sep) }}</td>
                    <td class="angka">{{ rupiah($giat->jul + $giat->ags + $giat->sep) }}</td>
                    <td class="angka">{{ rupiah($giat->okt) }}</td>
                    <td class="angka">{{ rupiah($giat->nov) }}</td>
                    <td class="angka">{{ rupiah($giat->des) }}</td>
                    <td class="angka">{{ rupiah($giat->okt + $giat->nov + $giat->des) }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align: center"><b>Total</b></td>
                <td class="angka"><b>{{ rupiah($total_ang) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_jan) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_feb) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_mar) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_jan + $total_feb + $total_mar) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_apr) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_mei) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_jun) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_apr + $total_mei + $total_jun) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_jul) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_ags) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_sep) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_jul + $total_ags + $total_sep) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_okt) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_nov) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_des) }}</b></td>
                <td class="angka"><b>{{ rupiah($total_okt + $total_nov + $total_des) }}</b></td>
            </tr>
            {{-- <tr>
                <td colspan="14" style="border-left:hidden;border-bottom:hidden;border-right:hidden"></td>
                <td colspan="4" style="border-left:hidden;border-bottom:hidden;border-right:hidden">
                    @if ($hidden != 'hidden')
                        @if ($hidden != 'hidden')
                            <div style="padding-top:20px">
                                <table class="table" style="width:100%">
                                    <tr>
                                        <td style="margin: 2px 0px;text-align: center">
                                            Pontianak, {{ tanggal($tanggal) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 50px;text-align: center">
                                            {{ $ttd1->jabatan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center"><b><u>{{ $ttd1->nama }}</u></b></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center">NIP. {{ $ttd1->nip }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    @endif
                </td>
            </tr> --}}
        </tbody>
    </table>

    @if ($hidden != 'hidden')
        <div style="padding-top:20px">
            <table class="table" style="width:100%">
                <tr>
                    <td style="width: 60%"></td>
                    <td style="margin: 2px 0px;text-align: center">
                        Pontianak, {{ tanggal($tanggal) }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 60%"></td>
                    <td style="padding-bottom: 50px;text-align: center">
                        {{ $ttd1->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 60%"></td>
                    <td style="text-align: center"><b><u>{{ $ttd1->nama }}</u></b></td>
                </tr>
                <tr>
                    <td style="width: 60%"></td>
                    <td style="text-align: center">NIP. {{ $ttd1->nip }}</td>
                </tr>
            </table>
        </div>
    @endif
</body>

</html>
