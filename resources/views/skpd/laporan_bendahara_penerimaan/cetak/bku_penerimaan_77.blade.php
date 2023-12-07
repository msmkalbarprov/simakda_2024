<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BKU PENERIMAAN</title>
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

<!-- <body onload="window.print()"> -->
<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
            <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
            </td>
            <td align="left" style="font-size:14px" width="93%">&nbsp;</td></tr>
            <tr>
            <td align="left" style="font-size:14px" width="93%"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>SKPD {{ $skpd->nm_skpd }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td></tr>
            <tr>
            <td align="left" style="font-size:14px" ><strong>&nbsp;</strong></td>
        </tr>
    </table>
    <hr>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="text-align: center"><b>BUKU KAS UMUM</td>
        </tr>
        <tr>
            <td style="text-align: center;padding-bottom:30px"><b>PERIODE {{ strtoupper(tanggal_indonesia($tanggal1)) }} s/d {{ strtoupper(tanggal_indonesia($tanggal2)) }}</b></td>
        </tr>
    </table>
    {{-- isi --}}
    <table style='border-collapse:collapse;font-family: Open Sans;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
		<thead>		   
            <tr>
                <td bgcolor='#CCCCCC' align='center' wfth="5%"><b>No</b></td>
                <td bgcolor='#CCCCCC' align='center' wfth="15%"><b>Tanggal</b></td>
                <td bgcolor='#CCCCCC' align='center' wfth="20%"><b>No. Bukti</b></td>
                <td bgcolor='#CCCCCC' align='center' wfth="5%"><b>Kode Rekening</b></td>
                <td bgcolor='#CCCCCC' align='center' wfth="10%"><b>Nama Rekening</b></td>
                <td bgcolor='#CCCCCC' align='center' wfth="15%"><b>Uraian</b></td>
                <td bgcolor='#CCCCCC' align='center' wfth="10%"><b>Penerimaan</b></td>
                <td bgcolor='#CCCCCC' align='center' wfth="10%"><b>Pengeluaran</b></td>
                <td bgcolor='#CCCCCC' align='center' wfth="10%"><b>Saldo</b></td>
            </tr>
            
		</thead>
        <tbody>
            <tr><td style="font-size:14px" align='right' colspan="8">Saldo Lalu</td>
                <td style="font-size:14px" align='right' >{{rupiah($saldo_lalu->lalu)}}</td>
             </tr> 
        
                @php
                    $lnnilai    = 0;                                 
                    $lntotal    = 0;       
					$nomor      = 0;
                    $hasil      = $saldo_lalu->lalu;
                @endphp
                    @foreach ($rincian as $row)
                        @php
                            $nomor          = ++$nomor;
                            $tgl            = $row->tglbukti;
                            $nobukti        = $row->nobukti;
                            $rek            = $row->kd_rek6;                    
                            $nmrek          = $row->nm_rek6;
                            $ket            = $row->keterangan;
                            $terima         = rupiah($row->terima);
                            $keluar         = rupiah($row->keluar);
                            $hasil          = $hasil + ($row->terima-$row->keluar);
                            
                        @endphp
                                <tr><td style="font-size:14px" align='center' >{{$nomor}}</td>
                                    <td style="font-size:14px" align='center' >{{$tgl}}</td>
                                    <td style="font-size:14px" align='left' >{{$nobukti}}</td>
                                    <td style="font-size:14px" align='center' >{{$rek}}</td>
                                    <td style="font-size:14px" align='left' >{{$nmrek}}</td>
                                    <td style="font-size:14px" align='left' >{{$ket}}</td>
                                    <td style="font-size:14px" align='right' >{{$terima}}</td>
                                    <td style="font-size:14px" align='right' >{{$keluar}}</td>
                                    <td style="font-size:14px" align='right' >{{rupiah($hasil)}}</td>
                                 </tr>    
                    @endforeach
        </tbody>
    </table>
<br/>
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="left" style="font-size:14px" colspan="2">Saldo kas di Bendahara Penerimaan / Bendahara Penerimaan Pembantu</td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rp{{rupiah($hasil)}}</td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({{terbilang($hasil)}})</td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Terdiri dari :</td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px" width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Tunai</td>
            <td align="left" style="font-size:14px" width="90%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp0,00</td>
        </tr>
        <tr>
            <td align="left" style="font-size:14px" width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Bank</td>
            <td align="left" style="font-size:14px" width="90%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp{{rupiah($hasil)}}</td>
        </tr>
    </table>
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
            @if($role=='1022')

                <tr>
                    <td style="padding-bottom: 50px;text-align: center;">
                        
                    </td>
                    <td style="padding-bottom: 50px;text-align: center;">
                        
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;"><b><u></u></b></td>
                    <td style="text-align: center;"><b><u></u></b></td>
                </tr>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                </tr>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                </tr>
            @else
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
            @endif

        </table>
    </div>
</body>

</html>
