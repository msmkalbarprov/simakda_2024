<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TRANSFER DANA</title>
    <style>
        table {
            border-collapse: collapse
        }

        .t1 {
            font-weight: normal
        }

        #pilihan1>thead>tr>th {
            background-color: #CCCCCC;
            font-weight: normal
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

        .angka {
            text-align: right
        }
        .tanggal {
            text-align: center
        }
    </style>
</head>

{{-- <body onload="window.print()"> --}}

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:16px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="font-size:16px" width="93%"><h2> KONFIRMASI TRANSFER KE DAERAH</h2></td>
        </tr>
        <tr>
            <td align="center" style="font-size:16px"><strong>&nbsp;</strong></td>
        </tr>
    </table>

    <table style="border-collapse:collapse;font-family: Open Sans; font-size:14px" width="100%">
        <tr>
            <td>Telah Terima dari</td>
            <td>:</td>
            <td colspan="3">Direktur Jenderal Perbendaharaan Selaku Kuasa Bendahara Umum Negara</td>
        </tr>
        <tr>
            <td>Melalui KPPN sejumlah Rp.</td>
            <td>:</td>
            <td colspan="3">Rp{{rupiah($total_kppn-$total_pot_kppn)}}</td>
        </tr>
        <tr>
            <td>Terbilang</td>
            <td>:</td>
            <td colspan="3">{{terbilang($total_kppn-$total_pot_kppn)}}</td>
        </tr>
        <tr>
            <td>Untuk Keperluan</td>
            <td>:</td>
            <td colspan="3">Penyaluran Anggaran Transfer Ke Daerah TA {{ tahun_anggaran() }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="width: 10%">Periode</td>
            <td>:</td>
            <td>{{ tanggal($tgl1) }} s/d {{ tanggal($tgl2) }} </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="width: 10%">Daerah</td>
            <td>:</td>
            <td>{{ ucwords(strtolower($header->nm_pemda)) }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td>Dengan Rincian</td>
            <td>:</td>
            <td colspan="3"></td>
        </tr>
    </table>

    <table style="width: 100%;border-collapse:collapse;font-family: Open Sans; font-size:14px" border="1" cellpading="2px">
        <thead>
            <tr>
                <th bgcolor="#CCCCCC"><b>JENIS ANGGARAN TRANSFER KE DAERAH</b></th>
                <th bgcolor="#CCCCCC"><b>JUMLAH BERSIH</b></th>
                <th bgcolor="#CCCCCC"><b>POTONGAN</b></th>
                <th bgcolor="#CCCCCC"><b>JUMLAH KOTOR</b></th>
                <th bgcolor="#CCCCCC"><b>TANGGAL TERIMA</b></th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $total_potongan = 0;
            @endphp
            @foreach ($map as $data)
                @if ($data->kode == 0)
                    <tr>
                        <td ><b>{{ $data->nama }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif ($data->kode == 1)
                    <tr>
                        <td style="padding-left: 20px"><b>{{ $data->nama }}</b>
                        </td>
                        <td class="angka"></td>
                        <td></td>
                        <td class="angka"></td>
                        <td class="angka"></td>
                    </tr>
                @elseif ($data->kode == 2)
                    <tr>
                        <td style="padding-left: 40px"><b>{{ $data->nama }}</b>
                        </td>
                        <td class="angka"></td>
                        <td></td>
                        <td class="angka"></td>
                        <td class="angka"></td>
                    </tr>
                @elseif ($data->kode == 3)
                @php
                    ini_set('max_execution_time', -1);
                    $kd_rek         = isset($data->kd_rek) ?  $data->kd_rek: "'-'"  ;
                    $panjang        = isset($data->panjang) ?$data->panjang:   0  ;
                    $kd_rek_notin   = isset($data->kd_rek_not_in) ?  $data->kd_rek_not_in : "'-'" ;
                    $panjang_notin  = isset($data->panjang_not_in) ?  $data->panjang_not_in : 0 ;
                    
                    
                    $pendapatan = DB::table('trdkasin_ppkd as a')
                                        ->join('trhkasin_ppkd as b', function ($join) {
                                                $join->on('a.no_kas', '=', 'b.no_kas');
                                                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                                            })
                                        ->selectRaw("sum(rupiah) as nilai")
                                        ->whereRaw("left(kd_rek6,$panjang) in ($kd_rek) and left(kd_rek6,$panjang_notin) not in ($kd_rek_notin) and (tgl_kas BETWEEN '$tgl1' and '$tgl2')")
                                        ->first();

                    $potongan = DB::table('trhkasin_ppkd_pot')
                    ->selectRaw("sum(total) as nilai")
                    ->whereRaw("left(kd_rek6,$panjang) in ($kd_rek) and left(kd_rek6,$panjang_notin) not in ($kd_rek_notin) and (tgl_kas BETWEEN '$tgl1' and '$tgl2')")
                    ->first();
                @endphp
                    <tr>
                        <td style="padding-left: 60px"><b>- {{ $data->nama }}
                        </b></td>
                        <td class="angka"><b>{{rupiah($pendapatan->nilai)}}</b></td>
                        <td class="angka"><b>{{rupiah($potongan->nilai)}}</b></td>
                        <td class="angka"><b>{{rupiah($pendapatan->nilai+$potongan->nilai)}}</b></td>
                        <td class="angka"><b></b></td>
                    </tr>
                @php
                ini_set('max_execution_time', -1);
                    $rincian = DB::table('trdkasin_ppkd as a')
                                        ->join('trhkasin_ppkd as b', function ($join) {
                                                $join->on('a.no_kas', '=', 'b.no_kas');
                                                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                                            })
                                        ->selectRaw("a.no_kas,a.no_sts,a.kd_skpd,tgl_kas,keterangan,rupiah,(select sum(total) from trhkasin_ppkd_pot c where c.no_sts=a.no_sts and c.kd_skpd=a.kd_skpd)as pot")
                                        ->whereRaw("left(kd_rek6,$panjang) in ($kd_rek) and left(kd_rek6,$panjang_notin) not in ($kd_rek_notin) and (tgl_kas BETWEEN '$tgl1' and '$tgl2')")
                                        ->get();
                @endphp
                @foreach ($rincian as $item)
                        @php
                            $total          += $item->rupiah;
                            $total_potongan += $item->pot;
                        @endphp
                    <tr>
                        <td style="padding-left: 80px">{{ $item->no_kas }} - {{ $item->keterangan }}
                        </td>
                        <td class="angka">{{rupiah($item->rupiah)}}</td>
                        <td class="angka">{{rupiah($item->pot)}}</td>
                        <td class="angka">{{rupiah($item->rupiah+$item->pot)}}</td>
                        <td class="tanggal">{{ $item->tgl_kas }}</td>
                    </tr>
                @endforeach
                
                @endif

            @endforeach
            <tr>
                <td bgcolor="#CCCCCC"><b>JUMLAH TOTAL PENERIMAAN TRANSFER</b></td>
                <td bgcolor="#CCCCCC" class="angka"><b>{{ rupiah($total) }}</b></td>
                <td bgcolor="#CCCCCC" class="angka"><b>{{ rupiah($total_potongan) }}</b></td>
                <td bgcolor="#CCCCCC" class="angka"><b>{{ rupiah($total+$total_potongan) }}</b></td>
                <td bgcolor="#CCCCCC"></td>
            </tr>
        </tbody>
    </table>

    <table style="border-collapse:collapse;font-family: Open Sans; font-size:14px;width: 100%">
        <tr>
            <td colspan="6">Dana tersebut telah diterima pada Rekening Kas Daerah sebagai berikut:</td>
        </tr>
        <tr>
            <td></td>
            <td>- Rekening Kas Daerah</td>
            <td><b>&#8594;</b></td>
            <td>Nomor Rekening</td>
            <td>:</td>
            <td>1001002201</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Nama Rekening</td>
            <td>:</td>
            <td>RKUD PROV. KALIMANTAN BARAT</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Nama Bank</td>
            <td>:</td>
            <td>Bank Kalbar Cabang Utama Pontianak</td>
        </tr>
    </table>
    <br />
    <br />
    @if (isset($tanda_tangan))
            <table class="table" style="border-collapse:collapse;font-family: Open Sans; font-size:14px;width:100%">
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center">
                        @if (isset($tanggal))
                            Pontianak, {{ tanggal($tanggal) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center">
                        {{ $tanda_tangan->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center">&nbsp;
                    </td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center">&nbsp;
                    </td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center">&nbsp;
                    </td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center"><b><u>{{ $tanda_tangan->nama }}</u></b></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center">{{ $tanda_tangan->pangkat }}</td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center">NIP. {{ $tanda_tangan->nip }}</td>
                </tr>
            </table>
    @endif
</body>

</html>
