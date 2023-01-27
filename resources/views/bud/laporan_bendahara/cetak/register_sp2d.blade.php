<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REGISTER SP2D</title>
    <style>
        table {
            border-collapse: collapse
        }

        .t1 {
            font-weight: normal
        }

        #header3>th {
            background-color: #CCCCCC;
        }

        .kanan {
            border-right: 1px solid black
        }

        .kiri {
            border-left: 1px solid black
        }

        .bawah {
            border-bottom: hidden
        }

        .atas {
            border-top: hidden
        }

        .angka {
            text-align: right
        }
    </style>
</head>

{{-- <body onload="window.print()"> --}}

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td align="left" style="font-size:16px" width="93%">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px">
                <strong>
                    {{ $skpd->nm_skpd }}
                </strong>
            </td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>&nbsp;</strong></td>
        </tr>
    </table>

    <table style="border-collapse:collapse;font-family: Open Sans; font-size:14px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align: center"><b>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</b></td>
        </tr>
        <tr>
            <td style="text-align: center">
                <b>
                    DAFTAR REKAP GAJI
                    @if ($pilihan == '12' || $pilihan == '22')
                        BULAN {{ Str::upper(bulan($data_awal['bulan'])) }}
                    @endif
                    TAHUN
                    {{ tahun_anggaran() }}
                </b>
            </td>
        </tr>
    </table>
    <table style="width: 100%" border="1" id="rincian">
        <thead>
            <tr id="header3">
                <th rowspan="2" style="width: 3%">No.<br>Urut</th>
                <th rowspan="2" style="width: 10%">KODE</th>
                <th rowspan="2" style="width: 5%">NAMA</th>
                <th rowspan="2" style="width: 5%">NOMOR</th>
                <th rowspan="2" style="width: 5%">JUMLAH KOTOR</th>
                <th colspan="8" style="width: 40%">POTONGAN</th>
                <th rowspan="2" style="width: 5%">BERSIH</th>
            </tr>
            <tr id="header3">
                <th style="width: 5%">IWP</th>
                <th style="width: 5%">JKK</th>
                <th style="width: 10%">JKM</th>
                <th style="width: 10%">BPJS</th>
                <th style="width: 5%">PPH21</th>
                <th style="width: 5%">TAPERUM</th>
                <th style="width: 5%">HKPG</th>
                <th style="width: 10%">Total</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
                <th>8</th>
                <th>9</th>
                <th>10</th>
                <th>11</th>
                <th>12</th>
                <th>13</th>
                <th>14</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totnilai_sp2d = 0;
                $totIWP = 0;
                $totJKK = 0;
                $totJKM = 0;
                $totBPJS = 0;
                $totPPH21 = 0;
                $totTAPERUM = 0;
                $totHKPG = 0;
                $totTotal = 0;
                $totBersih = 0;
            @endphp
            @foreach ($rekap_gaji as $rekap)
                @php
                    $totnilai_sp2d += $rekap->nilai_sp2d;
                    $totIWP += $rekap->IWP;
                    $totJKK += $rekap->JKK;
                    $totJKM += $rekap->JKM;
                    $totBPJS += $rekap->BPJS;
                    $totPPH21 += $rekap->PPH21;
                    $totTAPERUM += $rekap->TAPERUM;
                    $totHKPG += $rekap->HKPG;
                    $totTotal += $rekap->Total;
                    $totBersih = $totnilai_sp2d - $totTotal;
                @endphp
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td>{{ $rekap->kd_skpd }}</td>
                    <td>{{ $rekap->nm_skpd }}</td>
                    <td>{{ $rekap->nomor }}</td>
                    <td class="angka">{{ rupiah($rekap->nilai_sp2d) }}</td>
                    <td class="angka">{{ rupiah($rekap->IWP) }}</td>
                    <td class="angka">{{ rupiah($rekap->JKK) }}</td>
                    <td class="angka">{{ rupiah($rekap->JKM) }}</td>
                    <td class="angka">{{ rupiah($rekap->BPJS) }}</td>
                    <td class="angka">{{ rupiah($rekap->PPH21) }}</td>
                    <td class="angka">{{ rupiah($rekap->TAPERUM) }}</td>
                    <td class="angka">{{ rupiah($rekap->HKPG) }}</td>
                    <td class="angka">{{ rupiah($rekap->Total) }}</td>
                    <td class="angka">{{ rupiah($rekap->nilai_sp2d - $rekap->Total) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: center"><b>Jumlah</b></td>
                <td class="angka"><b>{{ rupiah($totnilai_sp2d) }}</b></td>
                <td class="angka"><b>{{ rupiah($totIWP) }}</b></td>
                <td class="angka"><b>{{ rupiah($totJKK) }}</b></td>
                <td class="angka"><b>{{ rupiah($totJKM) }}</b></td>
                <td class="angka"><b>{{ rupiah($totBPJS) }}</b></td>
                <td class="angka"><b>{{ rupiah($totPPH21) }}</b></td>
                <td class="angka"><b>{{ rupiah($totTAPERUM) }}</b></td>
                <td class="angka"><b>{{ rupiah($totHKPG) }}</b></td>
                <td class="angka"><b>{{ rupiah($totTotal) }}</b></td>
                <td class="angka"><b>{{ rupiah($totBersih) }}</b></td>
            </tr>
        </tbody>
    </table>

    @if (isset($tanda_tangan))
        <div style="padding-top:20px;padding-left:800px">
            <table class="table" style="width:100%">
                <tr>
                    <td style="margin: 2px 0px;text-align: center">
                        @if (isset($tanggal))
                            Pontianak, {{ tanggal($tanggal) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom: 50px;text-align: center">
                        {{ $tanda_tangan->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center"><b><u>{{ $tanda_tangan->nama }}</u></b></td>
                </tr>
                <tr>
                    <td style="text-align: center">{{ $tanda_tangan->pangkat }}</td>
                </tr>
                <tr>
                    <td style="text-align: center">NIP. {{ $tanda_tangan->nip }}</td>
                </tr>
            </table>
        </div>
    @endif
</body>

</html>
