<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Penjelasan Calk</title>
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

<body>
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="7" align="center" ><b>DAFTAR MUTASI ASET TETAP {{strtoupper(nama_rek3($rek))}}</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>PEMERINTAH PROVINSI KALIMANTAN BARAT</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>TAHUN ANGGARAN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        @if($rek=="1301" || $rek=="1304" || $rek=="1305" || $rek=="1306")
            {!! $head !!}
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal    =$row->realisasibelanjamodal;
                    $hibah                    =$row->hibah;
                    $ket_hibah                =$row->ket_hibah;
                    $Beban                    =$row->beban;
                    $ket_beban                =$row->ket_beban;
                    $mutasiantaropd           =$row->mutasiantaropd;
                    $ket_mutasiantaropd       =$row->ket_mutasiantaropd;
                    $reklas                   =$row->reklas;
                    $ket_reklas               =$row->ket_reklas;
                    $revaluasi                =$row->revaluasi;
                    $ket_revaluasi            =$row->ket_revaluasi;
                    $koreksi                  =$row->koreksi;
                    $ket_koreksi              =$row->ket_koreksi;
                    $mutasi_nomenklatur                  =$row->mutasi_nomenklatur;
                    $ket_mutasi_nomenklatur               =$row->ket_mutasi_nomenklatur;
                    $Jumlah                   =$RealisasiBelanjaModal+$hibah+$Beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                    =$row->hibah1;
                    $ket_hibah1                =$row->ket_hibah1;
                    $Penghapusan1              =$row->penghapusan1;
                    $ket_penghapusan           =$row->ket_penghapusan;
                    $mutasiantaropd1           =$row->mutasiantaropd1;
                    $ket_mutasiantaropd1       =$row->ket_mutasiantaropd1;
                    $Reklas1                   =$row->reklas1;
                    $ket_reklas1               =$row->ket_reklas1;
                    $revaluasi1                =$row->revaluasi1;
                    $ket_revaluasi1            =$row->ket_revaluasi1;
                    $koreksi1                  =$row->koreksi1;
                    $ket_koreksi1              =$row->ket_koreksi1;
                    $rusakberat                =$row->rusakberat;
                    $ket_rusakberat            =$row->ket_rusakberat;
                    $Beban1                    =$row->beban1;
                    $ket_beban1                =$row->ket_beban1;
                    $mutasi_nomenklatur1                       =$row->mutasi_nomenklatur1;
                    $ket_mutasi_nomenklatur1               =$row->ket_mutasi_nomenklatur1;
                    $Jumlah1                   =$hibah1+$Penghapusan1+$mutasiantaropd1+$Reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$Beban1+$mutasi_nomenklatur1;

                    $total                    =$sal_lalu+$Jumlah-$Jumlah1;
                @endphp
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{$ket_hibah}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{$ket_beban}}</td>
                    <td>{{rupiah($Beban)}}</td>
                    <td>{{$ket_mutasiantaropd}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{$ket_reklas}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{$ket_revaluasi}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{$ket_koreksi}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{$ket_mutasi_nomenklatur}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{$ket_hibah1}}</td>
                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{$ket_penghapusan}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{$ket_mutasiantaropd1}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{$ket_reklas1}}</td>
                    <td>{{rupiah($Reklas1)}}</td>
                    <td>{{$ket_revaluasi1}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{$ket_koreksi1}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{$ket_rusakberat}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{$ket_beban1}}</td>
                    <td>{{rupiah($Beban1)}}</td>
                    <td>{{$ket_mutasi_nomenklatur1}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach
        @elseif($rek=="1302")
            {!! $head !!}
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal    =$row->realisasibelanjamodal;
                    $hibah                    =$row->hibah;
                    $ket_hibah                =$row->ket_hibah;
                    $Beban                    =$row->beban;
                    $ket_beban                =$row->ket_beban;
                    $mutasiantaropd           =$row->mutasiantaropd;
                    $ket_mutasiantaropd       =$row->ket_mutasiantaropd;
                    $reklas                   =$row->reklas;
                    $ket_reklas               =$row->ket_reklas;
                    $revaluasi                =$row->revaluasi;
                    $ket_revaluasi            =$row->ket_revaluasi;
                    $koreksi                  =$row->koreksi;
                    $ket_koreksi              =$row->ket_koreksi;
                    $pengadaan_btt            =$row->pengadaan_btt;
                    $ket_pengadaan_btt        =$row->ket_pengadaan_btt;
                    $mutasi_nomenklatur            =$row->mutasi_nomenklatur;
                    $ket_mutasi_nomenklatur           =$row->ket_mutasi_nomenklatur;
                    $Jumlah                   =$RealisasiBelanjaModal+$hibah+$Beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$pengadaan_btt+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                    =$row->hibah1;
                    $ket_hibah1                =$row->ket_hibah1;
                    $Penghapusan1              =$row->penghapusan1;
                    $ket_penghapusan           =$row->ket_penghapusan;
                    $mutasiantaropd1           =$row->mutasiantaropd1;
                    $ket_mutasiantaropd1       =$row->ket_mutasiantaropd1;
                    $Reklas1                   =$row->reklas1;
                    $ket_reklas1               =$row->ket_reklas1;
                    $revaluasi1                =$row->revaluasi1;
                    $ket_revaluasi1            =$row->ket_revaluasi1;
                    $koreksi1                  =$row->koreksi1;
                    $ket_koreksi1              =$row->ket_koreksi1;
                    $rusakberat                =$row->rusakberat;
                    $ket_rusakberat            =$row->ket_rusakberat;
                    $Beban1                    =$row->beban1;
                    $ket_beban1                =$row->ket_beban1;
                    $Ekstracomptable           =$row->Ekstracomptable;
                    $ket_Ekstracomptable       =$row->ket_Ekstracomptable;
                    $mutasi_nomenklatur1           =$row->mutasi_nomenklatur1;
                    $ket_mutasi_nomenklatur1       =$row->ket_mutasi_nomenklatur1;
                    $Jumlah1                   =$hibah1+$Penghapusan1+$mutasiantaropd1+$Reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$Beban1+$Ekstracomptable+$mutasi_nomenklatur1;

                    $total                    =$sal_lalu+$Jumlah-$Jumlah1;
                @endphp
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{$ket_hibah}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{$ket_beban}}</td>
                    <td>{{rupiah($Beban)}}</td>
                    <td>{{$ket_mutasiantaropd}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{$ket_reklas}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{$ket_revaluasi}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{$ket_koreksi}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{$ket_pengadaan_btt}}</td>
                    <td>{{rupiah($pengadaan_btt)}}</td>
                    <td>{{$ket_mutasi_nomenklatur}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{$ket_hibah1}}</td>
                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{$ket_penghapusan}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{$ket_mutasiantaropd1}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{$ket_reklas1}}</td>
                    <td>{{rupiah($Reklas1)}}</td>
                    <td>{{$ket_revaluasi1}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{$ket_koreksi1}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{$ket_rusakberat}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{$ket_beban1}}</td>
                    <td>{{rupiah($Beban1)}}</td>
                    <td>{{$ket_Ekstracomptable}}</td>
                    <td>{{rupiah($Ekstracomptable)}}</td>
                    <td>{{$ket_mutasi_nomenklatur1}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach
        @elseif($rek=="1303")
            {!! $head !!}
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal    =$row->realisasibelanjamodal;
                    $hibah                    =$row->hibah;
                    $ket_hibah                =$row->ket_hibah;
                    $Beban                    =$row->beban;
                    $ket_beban                =$row->ket_beban;
                    $mutasiantaropd           =$row->mutasiantaropd;
                    $ket_mutasiantaropd       =$row->ket_mutasiantaropd;
                    $reklas                   =$row->reklas;
                    $ket_reklas               =$row->ket_reklas;
                    $revaluasi                =$row->revaluasi;
                    $ket_revaluasi            =$row->ket_revaluasi;
                    $koreksi                  =$row->koreksi;
                    $ket_koreksi              =$row->ket_koreksi;
                    $pengadaan_btt                  =$row->pengadaan_btt;
                    $ket_pengadaan_btt            =$row->ket_pengadaan_btt;
                    $mutasi_nomenklatur                  =$row->mutasi_nomenklatur;
                    $ket_mutasi_nomenklatur               =$row->ket_mutasi_nomenklatur;
                    $Jumlah                   =$RealisasiBelanjaModal+$hibah+$Beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$pengadaan_btt+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                    =$row->hibah1;
                    $ket_hibah1                =$row->ket_hibah1;
                    $Penghapusan1              =$row->penghapusan1;
                    $ket_penghapusan           =$row->ket_penghapusan;
                    $mutasiantaropd1           =$row->mutasiantaropd1;
                    $ket_mutasiantaropd1       =$row->ket_mutasiantaropd1;
                    $Reklas1                   =$row->reklas1;
                    $ket_reklas1               =$row->ket_reklas1;
                    $revaluasi1                =$row->revaluasi1;
                    $ket_revaluasi1            =$row->ket_revaluasi1;
                    $koreksi1                  =$row->koreksi1;
                    $ket_koreksi1              =$row->ket_koreksi1;
                    $rusakberat                =$row->rusakberat;
                    $ket_rusakberat            =$row->ket_rusakberat;
                    $Beban1                    =$row->beban1;
                    $ket_beban1                =$row->ket_beban1;
                    $mutasi_nomenklatur1                       =$row->mutasi_nomenklatur1;
                    $ket_mutasi_nomenklatur1               =$row->ket_mutasi_nomenklatur1;
                    $Jumlah1                   =$hibah1+$Penghapusan1+$mutasiantaropd1+$Reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$Beban1+$mutasi_nomenklatur1;

                    $total                    =$sal_lalu+$Jumlah-$Jumlah1;
                @endphp
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{$ket_hibah}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{$ket_beban}}</td>
                    <td>{{rupiah($Beban)}}</td>
                    <td>{{$ket_mutasiantaropd}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{$ket_reklas}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{$ket_revaluasi}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{$ket_koreksi}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{$ket_pengadaan_btt}}</td>
                    <td>{{rupiah($pengadaan_btt)}}</td>
                    <td>{{$ket_mutasi_nomenklatur}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{$ket_hibah1}}</td>
                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{$ket_penghapusan}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{$ket_mutasiantaropd1}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{$ket_reklas1}}</td>
                    <td>{{rupiah($Reklas1)}}</td>
                    <td>{{$ket_revaluasi1}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{$ket_koreksi1}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{$ket_rusakberat}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{$ket_beban1}}</td>
                    <td>{{rupiah($Beban1)}}</td>
                    <td>{{$ket_mutasi_nomenklatur1}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach
        @elseif($rek=="1503")
            {!! $head !!}
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal    =$row->realisasibelanjamodal;
                    $hibah                    =$row->hibah;
                    $ket_hibah                =$row->ket_hibah;
                    $Beban                    =$row->beban;
                    $ket_beban                =$row->ket_beban;
                    $mutasiantaropd           =$row->mutasiantaropd;
                    $ket_mutasiantaropd       =$row->ket_mutasiantaropd;
                    $reklas                   =$row->reklas;
                    $ket_reklas               =$row->ket_reklas;
                    $revaluasi                =$row->revaluasi;
                    $ket_revaluasi            =$row->ket_revaluasi;
                    $koreksi                  =$row->koreksi;
                    $ket_koreksi              =$row->ket_koreksi;
                    $mutasi_nomenklatur                  =$row->mutasi_nomenklatur;
                    $ket_mutasi_nomenklatur               =$row->ket_mutasi_nomenklatur;
                    $Jumlah                   =$RealisasiBelanjaModal+$hibah+$Beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                    =$row->hibah1;
                    $ket_hibah1                =$row->ket_hibah1;
                    $Penghapusan1              =$row->penghapusan1;
                    $ket_penghapusan           =$row->ket_penghapusan;
                    $mutasiantaropd1           =$row->mutasiantaropd1;
                    $ket_mutasiantaropd1       =$row->ket_mutasiantaropd1;
                    $Reklas1                   =$row->reklas1;
                    $ket_reklas1               =$row->ket_reklas1;
                    $revaluasi1                =$row->revaluasi1;
                    $ket_revaluasi1            =$row->ket_revaluasi1;
                    $koreksi1                  =$row->koreksi1;
                    $ket_koreksi1              =$row->ket_koreksi1;
                    $rusakberat                =$row->rusakberat;
                    $ket_rusakberat            =$row->ket_rusakberat;
                    $Beban1                    =$row->beban1;
                    $ket_beban1                =$row->ket_beban1;
                    $mutasi_nomenklatur1                       =$row->mutasi_nomenklatur1;
                    $ket_mutasi_nomenklatur1               =$row->ket_mutasi_nomenklatur1;
                    $Jumlah1                   =$hibah1+$Penghapusan1+$mutasiantaropd1+$Reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$Beban1+$mutasi_nomenklatur1;

                    $total                    =$sal_lalu+$Jumlah-$Jumlah1;
                @endphp 
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{$ket_hibah}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{$ket_beban}}</td>
                    <td>{{rupiah($Beban)}}</td>
                    <td>{{$ket_mutasiantaropd}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{$ket_reklas}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{$ket_revaluasi}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{$ket_koreksi}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{$ket_mutasi_nomenklatur}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{$ket_hibah1}}</td>
                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{$ket_penghapusan}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{$ket_mutasiantaropd1}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{$ket_reklas1}}</td>
                    <td>{{rupiah($Reklas1)}}</td>
                    <td>{{$ket_revaluasi1}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{$ket_koreksi1}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{$ket_rusakberat}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{$ket_beban1}}</td>
                    <td>{{rupiah($Beban1)}}</td>
                    <td>{{$ket_mutasi_nomenklatur1}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach  
        @elseif($rek=="1504")
            {!! $head !!}
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal    =$row->realisasibelanjamodal;
                    $hibah                    =$row->hibah;
                    $ket_hibah                =$row->ket_hibah;
                    $Beban                    =$row->beban;
                    $ket_beban                =$row->ket_beban;
                    $mutasiantaropd           =$row->mutasiantaropd;
                    $ket_mutasiantaropd       =$row->ket_mutasiantaropd;
                    $reklas                   =$row->reklas;
                    $ket_reklas               =$row->ket_reklas;
                    $revaluasi                =$row->revaluasi;
                    $ket_revaluasi            =$row->ket_revaluasi;
                    $koreksi                  =$row->koreksi;
                    $ket_koreksi              =$row->ket_koreksi;
                    $mutasi_nomenklatur                  =$row->mutasi_nomenklatur;
                    $ket_mutasi_nomenklatur               =$row->ket_mutasi_nomenklatur;
                    $Jumlah                   =$RealisasiBelanjaModal+$hibah+$Beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                    =$row->hibah1;
                    $ket_hibah1                =$row->ket_hibah1;
                    $Penghapusan1              =$row->penghapusan1;
                    $ket_penghapusan           =$row->ket_penghapusan;
                    $mutasiantaropd1           =$row->mutasiantaropd1;
                    $ket_mutasiantaropd1       =$row->ket_mutasiantaropd1;
                    $Reklas1                   =$row->reklas1;
                    $ket_reklas1               =$row->ket_reklas1;
                    $revaluasi1                =$row->revaluasi1;
                    $ket_revaluasi1            =$row->ket_revaluasi1;
                    $koreksi1                  =$row->koreksi1;
                    $ket_koreksi1              =$row->ket_koreksi1;
                    $rusakberat                =$row->rusakberat;
                    $ket_rusakberat            =$row->ket_rusakberat;
                    $Beban1                    =$row->beban1;
                    $ket_beban1                =$row->ket_beban1;
                    $mutasi_nomenklatur1                       =$row->mutasi_nomenklatur1;
                    $ket_mutasi_nomenklatur1               =$row->ket_mutasi_nomenklatur1;
                    $Jumlah1                   =$hibah1+$Penghapusan1+$mutasiantaropd1+$Reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$Beban1+$mutasi_nomenklatur1;

                    $total                    =$sal_lalu+$Jumlah-$Jumlah1; 
                @endphp
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{$ket_hibah}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{$ket_beban}}</td>
                    <td>{{rupiah($Beban)}}</td>
                    <td>{{$ket_mutasiantaropd}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{$ket_reklas}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{$ket_revaluasi}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{$ket_koreksi}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{$ket_mutasi_nomenklatur}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{$ket_hibah1}}</td>
                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{$ket_penghapusan}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{$ket_mutasiantaropd1}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{$ket_reklas1}}</td>
                    <td>{{rupiah($Reklas1)}}</td>
                    <td>{{$ket_revaluasi1}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{$ket_koreksi1}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{$ket_rusakberat}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{$ket_beban1}}</td>
                    <td>{{rupiah($Beban1)}}</td>
                    <td>{{$ket_mutasi_nomenklatur1}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach
        @endif
        
    </table>
</body>
</html>