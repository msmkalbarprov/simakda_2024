<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DTH SKPD</title>
    <style>
        table {
            border-collapse: collapse
        }

        .t1 {
            font-weight: normal
        }

        #rincian>tbody>tr>td {
            vertical-align: top
        }

        .kanan {
            border-right: 1px solid black
        }

        .kiri {
            border-left: 1px solid black
        }

        .bawah {
            border-bottom: 1px solid black
        }
    </style>
</head>

{{-- <body onload="window.print()"> --}}

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%">
        <tr>
            <td rowspan="5" align="left" width="7%">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td align="left" style="font-size:14px" width="93%">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>SKPD {{ $skpd->nm_skpd }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px"><strong>&nbsp;</strong></td>
        </tr>
    </table>
    <hr>

    <table style="border-collapse:collapse;font-family: Open Sans; font-size:14px" width="100%">
        <tr>
            <td style="text-align: center"><b>DAFTAR TRANSAKSI HARIAN (DTH)</b></td>
        </tr>
        <tr>
            <td style="text-align: center;padding-bottom:30px"><b>PERIODE {{ strtoupper(bulan($bulan)) }}</b></td>
        </tr>
    </table>

    <table style="font-size:14px;width:100%" border="1">
        <thead>
            <tr>
                <td rowspan="2" bgcolor="#CCCCCC" align="center">No.</td>
                <td colspan="2" bgcolor="#CCCCCC" align="center">SPM/SPD</td>
                <td colspan="2" bgcolor="#CCCCCC" align="center">SP2D </td>
                <td rowspan="2" bgcolor="#CCCCCC" align="center">Akun Belanja</td>
                <td colspan="3" bgcolor="#CCCCCC" align="center">Potongan Pajak</td>
                <td rowspan="2" bgcolor="#CCCCCC" align="center">NPWP</td>
                <td rowspan="2" bgcolor="#CCCCCC" align="center">Nama Rekanan</td>
                <td rowspan="2" bgcolor="#CCCCCC" align="center">NTPN</td>
                <td rowspan="2" bgcolor="#CCCCCC" align="center">No Billing</td>
                <td rowspan="2" bgcolor="#CCCCCC" align="center">Ket</td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC" align="center">No. SPM</td>
                <td bgcolor="#CCCCCC" align="center">Nilai Belanja(Rp)</td>
                <td bgcolor="#CCCCCC" align="center">No. SP2D </td>
                <td bgcolor="#CCCCCC" align="center">Nilai Belanja (Rp)</td>
                <td bgcolor="#CCCCCC" align="center">Akun Potongan</td>
                <td bgcolor="#CCCCCC" align="center">Jenis</td>
                <td bgcolor="#CCCCCC" align="center">jumlah (Rp)</td>
            </tr>
        </thead>
        <tbody>
            @php
                $nomor = 0;
                $tot_nilai = 0;
                $tot_nilai_belanja = 0;
                $tot_nilai_pot = 0;
            @endphp
            @foreach ($rincian as $rincian)
                @if ($rincian->jns_spp == '2')
                    @php
                        $nilai_belanja = $rincian->nilai;
                    @endphp
                @else
                    @php
                        $nilai_belanja = $rincian->nilai_belanja;
                    @endphp
                @endif

                @if ($rincian->kd_rek6 == '210106010001')
                    @php
                        $kd_rek6 = '210106010001';
                        $jenis_pajak = 'PPn';
                    @endphp
                @elseif($rincian->kd_rek6 == '210105010001')
                    @php
                        $kd_rek6 = '210105010001';
                        $jenis_pajak = 'PPh 21';
                    @endphp
                @elseif($rincian->kd_rek6 == '210105020001')
                    @php
                        $kd_rek6 = '210105020001';
                        $jenis_pajak = 'PPh 22';
                    @endphp
                @elseif($rincian->kd_rek6 == '210105030001')
                    @php
                        $kd_rek6 = '210105030001';
                        $jenis_pajak = 'PPh 23';
                    @endphp
                @elseif($rincian->kd_rek6 == '210109010001')
                    @php
                        $kd_rek6 = '210109010001';
                        $jenis_pajak = 'PPh Pasal 4 Ayat 2';
                    @endphp
                @endif
                @if ($rincian->urut == 1)
                    <tr>
                        <td valign="top" align="center">{{ ++$nomor }}</td>
                        <td valign="top">{{ $rincian->no_spm }}</td>
                        <td valign="top" align="right">{{ rupiah($rincian->nilai) }}</td>
                        <td valign="top">{{ $rincian->no_sp2d }}</td>
                        <td valign="top" align="right">{{ rupiah($nilai_belanja) }}</td>
                        <td align="right"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td valign="top" align="left"> </td>
                        <td></td>
                    </tr>
                @else
                    <tr>
                        <td align="right" style="border-top:hidden;"></td>
                        <td align="right" style="border-top:hidden;"></td>
                        <td align="right" style="border-top:hidden;"></td>
                        <td align="right" style="border-top:hidden;"></td>
                        <td align="right" style="border-top:hidden;"></td>
                        <td width="150" valign="top" align="left" style="border-top:hidden;">
                            {{ $rincian->kode_belanja }}</td>
                        <td width="150" valign="top" align="center" style="border-top:hidden;">
                            {{ $rincian->kd_rek6 }}</td>
                        <td width="150" valign="top" align="left" style="border-top:hidden;">
                            {{ $jenis_pajak }}
                        </td>
                        <td width="150" valign="top" align="right" style="border-top:hidden;">
                            {{ rupiah($rincian->nilai_pot) }}</td>
                        <td width="150" valign="top" align="left" style="border-top:hidden;">
                            {{ $rincian->npwp }}</td>
                        <td width="150" valign="top" align="left" style="border-top:hidden;">
                            {{ $rincian->nmrekan }}</td>
                        <td align="right" style="border-top:hidden;">{{ $rincian->ntpn }}</td>
                        <td align="right" style="border-top:hidden;">{{ $rincian->ebilling }}</td>
                        <td style="border-top:hidden;" width="150" valign="top" align="left">
                            {{ $rincian->ket }}
                        </td>
                    </tr>
                @endif
                @php
                    $tot_nilai = $tot_nilai + $rincian->nilai;
                    $tot_nilai_belanja = $tot_nilai_belanja + $nilai_belanja;
                    $tot_nilai_pot = $tot_nilai_pot + $rincian->nilai_pot;
                @endphp
            @endforeach
            <tr>
                <td width="90" bgcolor="#CCCCCC" align="center">Total</td>
                <td width="90" bgcolor="#CCCCCC" align="center"></td>
                <td width="90" bgcolor="#CCCCCC" align="right">{{ rupiah($tot_nilai) }}</td>
                <td width="90" bgcolor="#CCCCCC" align="center"></td>
                <td width="90" bgcolor="#CCCCCC" align="right">{{ rupiah($tot_nilai_belanja) }}</td>
                <td width="90" bgcolor="#CCCCCC" align="center"></td>
                <td width="150" bgcolor="#CCCCCC" align="center"></td>
                <td width="150" bgcolor="#CCCCCC" align="center"></td>
                <td width="150" bgcolor="#CCCCCC" align="right">{{ rupiah($tot_nilai_pot) }}</td>
                <td width="150" bgcolor="#CCCCCC" align="center"></td>
                <td width="150" bgcolor="#CCCCCC" align="center"></td>
                <td width="150" bgcolor="#CCCCCC" align="center"></td>
                <td width="150" bgcolor="#CCCCCC" align="center"></td>
                <td width="150" bgcolor="#CCCCCC" align="center"></td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%;font-size:12px;font-family:Open Sans">
            <tr>
                <td style="margin: 2px 0px;text-align: center;">
                    Disetujui oleh
                </td>
                <td style="margin: 2px 0px;text-align: center;">
                    {{ $daerah->daerah }},
                    {{ \Carbon\Carbon::parse($tanggal_ttd)->locale('id')->isoFormat('DD MMMM Y') }}
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;">
                    {{ ucwords(strtolower($cari_pa_kpa->jabatan)) }}
                </td>
                <td style="padding-bottom: 50px;text-align: center;">
                    {{ ucwords(strtolower($cari_bendahara->jabatan)) }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;"><b><u>{{ $cari_pa_kpa->nama }}</u></b></td>
                <td style="text-align: center;"><b><u>{{ $cari_bendahara->nama }}</u></b></td>
            </tr>
            <tr>
                <td style="text-align: center;">{{ $cari_pa_kpa->pangkat }}</td>
                <td style="text-align: center;">{{ $cari_bendahara->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">NIP. {{ $cari_pa_kpa->nip }}</td>
                <td style="text-align: center;">NIP. {{ $cari_bendahara->nip }}</td>
            </tr>

        </table>
    </div>
</body>

</html>
