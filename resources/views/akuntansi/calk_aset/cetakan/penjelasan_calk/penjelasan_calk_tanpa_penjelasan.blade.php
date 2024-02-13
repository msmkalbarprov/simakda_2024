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
            @php
                $tot_sal_lalu                 =0;
                $tot_RealisasiBelanjaModal      =0;
                $tot_hibah                      =0;
                $tot_beban                      =0;
                $tot_mutasiantaropd             =0;
                $tot_reklas                     =0;
                $tot_revaluasi                  =0;
                $tot_koreksi                    =0;
                $tot_mutasi_nomenklatur         =0;
                $tot_Jumlah                     =0;
                $tot_hibah1                     =0;
                $tot_Penghapusan1               =0;
                $tot_mutasiantaropd1            =0;
                $tot_reklas1                    =0;
                $tot_revaluasi1                 =0;
                $tot_koreksi1                   =0;
                $tot_rusakberat                 =0;
                $tot_beban1                     =0;
                $tot_mutasi_nomenklatur1        =0;
                $tot_Jumlah1                    =0;
                $tot_total                      =0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal      =$row->realisasibelanjamodal;
                    $hibah                      =$row->hibah;
                    $beban                      =$row->beban;
                    $mutasiantaropd             =$row->mutasiantaropd;
                    $reklas                     =$row->reklas;
                    $revaluasi                  =$row->revaluasi;
                    $koreksi                    =$row->koreksi;
                    $mutasi_nomenklatur         =$row->mutasi_nomenklatur;
                    $Jumlah                     =$RealisasiBelanjaModal+$hibah+$beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                     =$row->hibah1;
                    $Penghapusan1               =$row->penghapusan1;
                    $mutasiantaropd1            =$row->mutasiantaropd1;
                    $reklas1                    =$row->reklas1;
                    $revaluasi1                 =$row->revaluasi1;
                    $koreksi1                   =$row->koreksi1;
                    $rusakberat                 =$row->rusakberat;
                    $beban1                     =$row->beban1;
                    $mutasi_nomenklatur1        =$row->mutasi_nomenklatur1;
                    $Jumlah1                    =$hibah1+$Penghapusan1+$mutasiantaropd1+$reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$beban1+$mutasi_nomenklatur1;

                    $total                      =$sal_lalu+$Jumlah-$Jumlah1;

                    //total //
                    $tot_sal_lalu                   =$tot_sal_lalu+$sal_lalu;
                    $tot_RealisasiBelanjaModal      =$tot_RealisasiBelanjaModal+$RealisasiBelanjaModal;
                    $tot_hibah                      =$tot_hibah+$hibah;
                    $tot_beban                      =$tot_beban+$beban;
                    $tot_mutasiantaropd             =$tot_mutasiantaropd+$mutasiantaropd;
                    $tot_reklas                     =$tot_reklas+$reklas;
                    $tot_revaluasi                  =$tot_revaluasi+$revaluasi;
                    $tot_koreksi                    =$tot_koreksi+$koreksi;
                    $tot_mutasi_nomenklatur         =$tot_mutasi_nomenklatur+$mutasi_nomenklatur;
                    $tot_Jumlah                     =$tot_Jumlah+$Jumlah;
                    $tot_hibah1                     =$tot_hibah1+$hibah1;
                    $tot_Penghapusan1               =$tot_Penghapusan1+$Penghapusan1;
                    $tot_mutasiantaropd1            =$tot_mutasiantaropd1+$mutasiantaropd1;
                    $tot_reklas1                    =$tot_reklas1+$reklas1;
                    $tot_revaluasi1                 =$tot_revaluasi1+$revaluasi1;
                    $tot_koreksi1                   =$tot_koreksi1+$koreksi1;
                    $tot_rusakberat                 =$tot_rusakberat+$rusakberat;
                    $tot_beban1                     =$tot_beban1+$beban1;
                    $tot_mutasi_nomenklatur1        =$tot_mutasi_nomenklatur1+$mutasi_nomenklatur1;
                    $tot_Jumlah1                    =$tot_Jumlah1+$Jumlah1;
                    $tot_total                      =$tot_total+$total;
                @endphp
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{rupiah($beban)}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{rupiah($reklas1)}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{rupiah($beban1)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center">TOTAL</td>
                <td>{{rupiah($tot_sal_lalu)}}</td>
                <td>{{rupiah($tot_RealisasiBelanjaModal)}}</td>
                <td>{{rupiah($tot_hibah)}}</td>
                <td>{{rupiah($tot_beban)}}</td>
                <td>{{rupiah($tot_mutasiantaropd)}}</td>
                <td>{{rupiah($tot_reklas)}}</td>
                <td>{{rupiah($tot_revaluasi)}}</td>
                <td>{{rupiah($tot_koreksi)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur)}}</td>
                <td>{{rupiah($tot_Jumlah)}}</td>

                <td>{{rupiah($tot_hibah1)}}</td>
                <td>{{rupiah($tot_Penghapusan1)}}</td>
                <td>{{rupiah($tot_mutasiantaropd1)}}</td>
                <td>{{rupiah($tot_reklas1)}}</td>
                <td>{{rupiah($tot_revaluasi1)}}</td>
                <td>{{rupiah($tot_koreksi1)}}</td>
                <td>{{rupiah($tot_rusakberat)}}</td>
                <td>{{rupiah($tot_beban1)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur1)}}</td>
                <td>{{rupiah($tot_Jumlah1)}}</td>
                <td>{{rupiah($tot_total)}}</td>
            </tr>
        @elseif($rek=="1302")
            {!! $head !!}
            @php
                $tot_sal_lalu                 =0;
                $tot_RealisasiBelanjaModal      =0;
                $tot_hibah                      =0;
                $tot_beban                      =0;
                $tot_mutasiantaropd             =0;
                $tot_reklas                     =0;
                $tot_revaluasi                  =0;
                $tot_koreksi                    =0;
                $tot_pengadaan_btt              =0;
                $tot_mutasi_nomenklatur         =0;
                $tot_Jumlah                     =0;
                $tot_hibah1                     =0;
                $tot_Penghapusan1               =0;
                $tot_mutasiantaropd1            =0;
                $tot_reklas1                    =0;
                $tot_revaluasi1                 =0;
                $tot_koreksi1                   =0;
                $tot_rusakberat                 =0;
                $tot_beban1                     =0;
                $tot_Ekstracomptable            =0;
                $tot_mutasi_nomenklatur1        =0;
                $tot_Jumlah1                    =0;
                $tot_total                      =0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal      =$row->realisasibelanjamodal;
                    $hibah                      =$row->hibah;
                    $beban                      =$row->beban;
                    $mutasiantaropd             =$row->mutasiantaropd;
                    $reklas                     =$row->reklas;
                    $revaluasi                  =$row->revaluasi;
                    $koreksi                    =$row->koreksi;
                    $pengadaan_btt              =$row->pengadaan_btt;
                    $mutasi_nomenklatur         =$row->mutasi_nomenklatur;
                    $Jumlah                     =$RealisasiBelanjaModal+$hibah+$beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$pengadaan_btt+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                     =$row->hibah1;
                    $Penghapusan1               =$row->penghapusan1;
                    $mutasiantaropd1            =$row->mutasiantaropd1;
                    $reklas1                    =$row->reklas1;
                    $revaluasi1                 =$row->revaluasi1;
                    $koreksi1                   =$row->koreksi1;
                    $rusakberat                 =$row->rusakberat;
                    $beban1                     =$row->beban1;
                    $Ekstracomptable            =$row->Ekstracomptable;
                    $mutasi_nomenklatur1        =$row->mutasi_nomenklatur1;
                    $Jumlah1                    =$hibah1+$Penghapusan1+$mutasiantaropd1+$reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$beban1+$Ekstracomptable+$mutasi_nomenklatur1;

                    $total                      =$sal_lalu+$Jumlah-$Jumlah1;

                    $tot_sal_lalu                   =$tot_sal_lalu+$sal_lalu;
                    $tot_RealisasiBelanjaModal      =$tot_RealisasiBelanjaModal+$RealisasiBelanjaModal;
                    $tot_hibah                      =$tot_hibah+$hibah;
                    $tot_beban                      =$tot_beban+$beban;
                    $tot_mutasiantaropd             =$tot_mutasiantaropd+$mutasiantaropd;
                    $tot_reklas                     =$tot_reklas+$reklas;
                    $tot_revaluasi                  =$tot_revaluasi+$revaluasi;
                    $tot_koreksi                    =$tot_koreksi+$koreksi;
                    $tot_pengadaan_btt              =$tot_pengadaan_btt+$pengadaan_btt;
                    $tot_mutasi_nomenklatur         =$tot_mutasi_nomenklatur+$mutasi_nomenklatur;
                    $tot_Jumlah                     =$tot_Jumlah+$Jumlah;
                    $tot_hibah1                     =$tot_hibah1+$hibah1;
                    $tot_Penghapusan1               =$tot_Penghapusan1+$Penghapusan1;
                    $tot_mutasiantaropd1            =$tot_mutasiantaropd1+$mutasiantaropd1;
                    $tot_reklas1                    =$tot_reklas1+$reklas1;
                    $tot_revaluasi1                 =$tot_revaluasi1+$revaluasi1;
                    $tot_koreksi1                   =$tot_koreksi1+$koreksi1;
                    $tot_rusakberat                 =$tot_rusakberat+$rusakberat;
                    $tot_beban1                     =$tot_beban1+$beban1;
                    $tot_Ekstracomptable            =$tot_Ekstracomptable+$Ekstracomptable;
                    $tot_mutasi_nomenklatur1        =$tot_mutasi_nomenklatur1+$mutasi_nomenklatur1;
                    $tot_Jumlah1                    =$tot_Jumlah1+$Jumlah1;
                    $tot_total                      =$tot_total+$total;
                @endphp
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{rupiah($beban)}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{rupiah($pengadaan_btt)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{rupiah($reklas1)}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{rupiah($beban1)}}</td>
                    <td>{{rupiah($Ekstracomptable)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center">TOTAL</td>
                <td>{{rupiah($tot_sal_lalu)}}</td>
                <td>{{rupiah($tot_RealisasiBelanjaModal)}}</td>
                <td>{{rupiah($tot_hibah)}}</td>
                <td>{{rupiah($tot_beban)}}</td>
                <td>{{rupiah($tot_mutasiantaropd)}}</td>
                <td>{{rupiah($tot_reklas)}}</td>
                <td>{{rupiah($tot_revaluasi)}}</td>
                <td>{{rupiah($tot_koreksi)}}</td>
                <td>{{rupiah($tot_pengadaan_btt)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur)}}</td>
                <td>{{rupiah($tot_Jumlah)}}</td>

                <td>{{rupiah($tot_hibah1)}}</td>
                <td>{{rupiah($tot_Penghapusan1)}}</td>
                <td>{{rupiah($tot_mutasiantaropd1)}}</td>
                <td>{{rupiah($tot_reklas1)}}</td>
                <td>{{rupiah($tot_revaluasi1)}}</td>
                <td>{{rupiah($tot_koreksi1)}}</td>
                <td>{{rupiah($tot_rusakberat)}}</td>
                <td>{{rupiah($tot_beban1)}}</td>
                <td>{{rupiah($tot_Ekstracomptable)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur1)}}</td>
                <td>{{rupiah($tot_Jumlah1)}}</td>
                <td>{{rupiah($tot_total)}}</td>
            </tr>
        @elseif($rek=="1303")
            {!! $head !!}
            @php
                $tot_sal_lalu                 =0;
                $tot_RealisasiBelanjaModal      =0;
                $tot_hibah                      =0;
                $tot_beban                      =0;
                $tot_mutasiantaropd             =0;
                $tot_reklas                     =0;
                $tot_revaluasi                  =0;
                $tot_koreksi                    =0;
                $tot_pengadaan_btt              =0;
                $tot_mutasi_nomenklatur         =0;
                $tot_Jumlah                     =0;
                $tot_hibah1                     =0;
                $tot_Penghapusan1               =0;
                $tot_mutasiantaropd1            =0;
                $tot_reklas1                    =0;
                $tot_revaluasi1                 =0;
                $tot_koreksi1                   =0;
                $tot_rusakberat                 =0;
                $tot_beban1                     =0;
                $tot_mutasi_nomenklatur1        =0;
                $tot_Jumlah1                    =0;
                $tot_total                      =0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal      =$row->realisasibelanjamodal;
                    $hibah                      =$row->hibah;
                    $beban                      =$row->beban;
                    $mutasiantaropd             =$row->mutasiantaropd;
                    $reklas                     =$row->reklas;
                    $revaluasi                  =$row->revaluasi;
                    $koreksi                    =$row->koreksi;
                    $pengadaan_btt              =$row->pengadaan_btt;
                    $mutasi_nomenklatur              =$row->mutasi_nomenklatur;
                    $Jumlah                     =$RealisasiBelanjaModal+$hibah+$beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$pengadaan_btt+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                     =$row->hibah1;
                    $Penghapusan1               =$row->penghapusan1;
                    $mutasiantaropd1            =$row->mutasiantaropd1;
                    $reklas1                    =$row->reklas1;
                    $revaluasi1                 =$row->revaluasi1;
                    $koreksi1                   =$row->koreksi1;
                    $rusakberat                 =$row->rusakberat;
                    $beban1                     =$row->beban1;
                    $mutasi_nomenklatur1        =$row->mutasi_nomenklatur1;
                    $Jumlah1                    =$hibah1+$Penghapusan1+$mutasiantaropd1+$reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$beban1+$mutasi_nomenklatur1;

                    $total                      =$sal_lalu+$Jumlah-$Jumlah1;

                    $tot_sal_lalu                   =$tot_sal_lalu+$sal_lalu;
                    $tot_RealisasiBelanjaModal      =$tot_RealisasiBelanjaModal+$RealisasiBelanjaModal;
                    $tot_hibah                      =$tot_hibah+$hibah;
                    $tot_beban                      =$tot_beban+$beban;
                    $tot_mutasiantaropd             =$tot_mutasiantaropd+$mutasiantaropd;
                    $tot_reklas                     =$tot_reklas+$reklas;
                    $tot_revaluasi                  =$tot_revaluasi+$revaluasi;
                    $tot_koreksi                    =$tot_koreksi+$koreksi;
                    $tot_pengadaan_btt              =$tot_pengadaan_btt+$pengadaan_btt;
                    $tot_mutasi_nomenklatur         =$tot_mutasi_nomenklatur+$mutasi_nomenklatur;
                    $tot_Jumlah                     =$tot_Jumlah+$Jumlah;
                    $tot_hibah1                     =$tot_hibah1+$hibah1;
                    $tot_Penghapusan1               =$tot_Penghapusan1+$Penghapusan1;
                    $tot_mutasiantaropd1            =$tot_mutasiantaropd1+$mutasiantaropd1;
                    $tot_reklas1                    =$tot_reklas1+$reklas1;
                    $tot_revaluasi1                 =$tot_revaluasi1+$revaluasi1;
                    $tot_koreksi1                   =$tot_koreksi1+$koreksi1;
                    $tot_rusakberat                 =$tot_rusakberat+$rusakberat;
                    $tot_beban1                     =$tot_beban1+$beban1;
                    $tot_mutasi_nomenklatur1        =$tot_mutasi_nomenklatur1+$mutasi_nomenklatur1;
                    $tot_Jumlah1                    =$tot_Jumlah1+$Jumlah1;
                    $tot_total                      =$tot_total+$total;
                @endphp
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{rupiah($beban)}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{rupiah($pengadaan_btt)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{rupiah($reklas1)}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{rupiah($beban1)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center">TOTAL</td>
                <td>{{rupiah($tot_sal_lalu)}}</td>
                <td>{{rupiah($tot_RealisasiBelanjaModal)}}</td>
                <td>{{rupiah($tot_hibah)}}</td>
                <td>{{rupiah($tot_beban)}}</td>
                <td>{{rupiah($tot_mutasiantaropd)}}</td>
                <td>{{rupiah($tot_reklas)}}</td>
                <td>{{rupiah($tot_revaluasi)}}</td>
                <td>{{rupiah($tot_koreksi)}}</td>
                <td>{{rupiah($tot_pengadaan_btt)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur)}}</td>
                <td>{{rupiah($tot_Jumlah)}}</td>

                <td>{{rupiah($tot_hibah1)}}</td>
                <td>{{rupiah($tot_Penghapusan1)}}</td>
                <td>{{rupiah($tot_mutasiantaropd1)}}</td>
                <td>{{rupiah($tot_reklas1)}}</td>
                <td>{{rupiah($tot_revaluasi1)}}</td>
                <td>{{rupiah($tot_koreksi1)}}</td>
                <td>{{rupiah($tot_rusakberat)}}</td>
                <td>{{rupiah($tot_beban1)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur1)}}</td>
                <td>{{rupiah($tot_Jumlah1)}}</td>
                <td>{{rupiah($tot_total)}}</td>
            </tr>
        @elseif($rek=="1503")
            {!! $head !!}
            @php
                $tot_sal_lalu                 =0;
                $tot_RealisasiBelanjaModal      =0;
                $tot_hibah                      =0;
                $tot_beban                      =0;
                $tot_mutasiantaropd             =0;
                $tot_reklas                     =0;
                $tot_revaluasi                  =0;
                $tot_koreksi                    =0;
                $tot_mutasi_nomenklatur         =0;
                $tot_Jumlah                     =0;
                $tot_hibah1                     =0;
                $tot_Penghapusan1               =0;
                $tot_mutasiantaropd1            =0;
                $tot_reklas1                    =0;
                $tot_revaluasi1                 =0;
                $tot_koreksi1                   =0;
                $tot_rusakberat                 =0;
                $tot_beban1                     =0;
                $tot_mutasi_nomenklatur1        =0;
                $tot_Jumlah1                    =0;
                $tot_total                      =0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal      =$row->realisasibelanjamodal;
                    $hibah                      =$row->hibah;
                    $beban                      =$row->beban;
                    $mutasiantaropd             =$row->mutasiantaropd;
                    $reklas                     =$row->reklas;
                    $revaluasi                  =$row->revaluasi;
                    $koreksi                    =$row->koreksi;
                    $mutasi_nomenklatur         =$row->mutasi_nomenklatur;
                    $Jumlah                     =$RealisasiBelanjaModal+$hibah+$beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                     =$row->hibah1;
                    $Penghapusan1               =$row->penghapusan1;
                    $mutasiantaropd1            =$row->mutasiantaropd1;
                    $reklas1                    =$row->reklas1;
                    $revaluasi1                 =$row->revaluasi1;
                    $koreksi1                   =$row->koreksi1;
                    $rusakberat                 =$row->rusakberat;
                    $beban1                     =$row->beban1;
                    $mutasi_nomenklatur1        =$row->mutasi_nomenklatur1;
                    $Jumlah1                    =$hibah1+$Penghapusan1+$mutasiantaropd1+$reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$beban1+$mutasi_nomenklatur1;

                    $total                      =$sal_lalu+$Jumlah-$Jumlah1;

                    $tot_sal_lalu                   =$tot_sal_lalu+$sal_lalu;
                    $tot_RealisasiBelanjaModal      =$tot_RealisasiBelanjaModal+$RealisasiBelanjaModal;
                    $tot_hibah                      =$tot_hibah+$hibah;
                    $tot_beban                      =$tot_beban+$beban;
                    $tot_mutasiantaropd             =$tot_mutasiantaropd+$mutasiantaropd;
                    $tot_reklas                     =$tot_reklas+$reklas;
                    $tot_revaluasi                  =$tot_revaluasi+$revaluasi;
                    $tot_koreksi                    =$tot_koreksi+$koreksi;
                    $tot_mutasi_nomenklatur         =$tot_mutasi_nomenklatur+$mutasi_nomenklatur;
                    $tot_Jumlah                     =$tot_Jumlah+$Jumlah;
                    $tot_hibah1                     =$tot_hibah1+$hibah1;
                    $tot_Penghapusan1               =$tot_Penghapusan1+$Penghapusan1;
                    $tot_mutasiantaropd1            =$tot_mutasiantaropd1+$mutasiantaropd1;
                    $tot_reklas1                    =$tot_reklas1+$reklas1;
                    $tot_revaluasi1                 =$tot_revaluasi1+$revaluasi1;
                    $tot_koreksi1                   =$tot_koreksi1+$koreksi1;
                    $tot_rusakberat                 =$tot_rusakberat+$rusakberat;
                    $tot_beban1                     =$tot_beban1+$beban1;
                    $tot_mutasi_nomenklatur1        =$tot_mutasi_nomenklatur1+$mutasi_nomenklatur1;
                    $tot_Jumlah1                    =$tot_Jumlah1+$Jumlah1;
                    $tot_total                      =$tot_total+$total;
                @endphp 
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{rupiah($beban)}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{rupiah($reklas1)}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{rupiah($beban1)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach  
            <tr>
                <td colspan="2" align="center">TOTAL</td>
                <td>{{rupiah($tot_sal_lalu)}}</td>
                <td>{{rupiah($tot_RealisasiBelanjaModal)}}</td>
                <td>{{rupiah($tot_hibah)}}</td>
                <td>{{rupiah($tot_beban)}}</td>
                <td>{{rupiah($tot_mutasiantaropd)}}</td>
                <td>{{rupiah($tot_reklas)}}</td>
                <td>{{rupiah($tot_revaluasi)}}</td>
                <td>{{rupiah($tot_koreksi)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur)}}</td>
                <td>{{rupiah($tot_Jumlah)}}</td>

                <td>{{rupiah($tot_hibah1)}}</td>
                <td>{{rupiah($tot_Penghapusan1)}}</td>
                <td>{{rupiah($tot_mutasiantaropd1)}}</td>
                <td>{{rupiah($tot_reklas1)}}</td>
                <td>{{rupiah($tot_revaluasi1)}}</td>
                <td>{{rupiah($tot_koreksi1)}}</td>
                <td>{{rupiah($tot_rusakberat)}}</td>
                <td>{{rupiah($tot_beban1)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur1)}}</td>
                <td>{{rupiah($tot_Jumlah1)}}</td>
                <td>{{rupiah($tot_total)}}</td>
            </tr>
        @elseif($rek=="1504")
            {!! $head !!}
            @php
                $tot_sal_lalu                 =0;
                $tot_RealisasiBelanjaModal      =0;
                $tot_hibah                      =0;
                $tot_beban                      =0;
                $tot_mutasiantaropd             =0;
                $tot_reklas                     =0;
                $tot_revaluasi                  =0;
                $tot_koreksi                    =0;
                $tot_mutasi_nomenklatur         =0;
                $tot_Jumlah                     =0;
                $tot_hibah1                     =0;
                $tot_Penghapusan1               =0;
                $tot_mutasiantaropd1            =0;
                $tot_reklas1                    =0;
                $tot_revaluasi1                 =0;
                $tot_koreksi1                   =0;
                $tot_rusakberat                 =0;
                $tot_beban1                     =0;
                $tot_mutasi_nomenklatur1        =0;
                $tot_Jumlah1                    =0;
                $tot_total                      =0;
            @endphp
            @foreach($query as $row)
                @php
                    $kd_skpd                  =$row->kd_skpd;
                    $nm_skpd                  =$row->nm_skpd;
                    $sal_lalu                 =$row->sal_lalu;
                    //------------------------------------mutasi bertambah--------------------//
                    $RealisasiBelanjaModal      =$row->realisasibelanjamodal;
                    $hibah                      =$row->hibah;
                    $beban                      =$row->beban;
                    $mutasiantaropd             =$row->mutasiantaropd;
                    $reklas                     =$row->reklas;
                    $revaluasi                  =$row->revaluasi;
                    $koreksi                    =$row->koreksi;
                    $mutasi_nomenklatur         =$row->mutasi_nomenklatur;
                    $Jumlah                     =$RealisasiBelanjaModal+$hibah+$beban+$mutasiantaropd+$reklas
                                                +$revaluasi+$koreksi+$mutasi_nomenklatur;
                    //---------------------------------------mutasi berkurang--------------------//
                    $hibah1                     =$row->hibah1;
                    $Penghapusan1               =$row->penghapusan1;
                    $mutasiantaropd1            =$row->mutasiantaropd1;
                    $reklas1                    =$row->reklas1;
                    $revaluasi1                 =$row->revaluasi1;
                    $koreksi1                   =$row->koreksi1;
                    $rusakberat                 =$row->rusakberat;
                    $beban1                     =$row->beban1;
                    $mutasi_nomenklatur1        =$row->mutasi_nomenklatur1;
                    $Jumlah1                    =$hibah1+$Penghapusan1+$mutasiantaropd1+$reklas1+$revaluasi1
                                                 +$koreksi1+$rusakberat+$beban1+$mutasi_nomenklatur1;

                    $total                      =$sal_lalu+$Jumlah-$Jumlah1;

                    $tot_sal_lalu                   =$tot_sal_lalu+$sal_lalu;
                    $tot_RealisasiBelanjaModal      =$tot_RealisasiBelanjaModal+$RealisasiBelanjaModal;
                    $tot_hibah                      =$tot_hibah+$hibah;
                    $tot_beban                      =$tot_beban+$beban;
                    $tot_mutasiantaropd             =$tot_mutasiantaropd+$mutasiantaropd;
                    $tot_reklas                     =$tot_reklas+$reklas;
                    $tot_revaluasi                  =$tot_revaluasi+$revaluasi;
                    $tot_koreksi                    =$tot_koreksi+$koreksi;
                    $tot_mutasi_nomenklatur         =$tot_mutasi_nomenklatur+$mutasi_nomenklatur;
                    $tot_Jumlah                     =$tot_Jumlah+$Jumlah;
                    $tot_hibah1                     =$tot_hibah1+$hibah1;
                    $tot_Penghapusan1               =$tot_Penghapusan1+$Penghapusan1;
                    $tot_mutasiantaropd1            =$tot_mutasiantaropd1+$mutasiantaropd1;
                    $tot_reklas1                    =$tot_reklas1+$reklas1;
                    $tot_revaluasi1                 =$tot_revaluasi1+$revaluasi1;
                    $tot_koreksi1                   =$tot_koreksi1+$koreksi1;
                    $tot_rusakberat                 =$tot_rusakberat+$rusakberat;
                    $tot_beban1                     =$tot_beban1+$beban1;
                    $tot_mutasi_nomenklatur1        =$tot_mutasi_nomenklatur1+$mutasi_nomenklatur1;
                    $tot_Jumlah1                    =$tot_Jumlah1+$Jumlah1;
                    $tot_total                      =$tot_total+$total;
                @endphp
                <tr>
                    <td>{{$kd_skpd}}</td>
                    <td>{{$nm_skpd}}</td>
                    <td>{{rupiah($sal_lalu)}}</td>
                    <td>{{rupiah($RealisasiBelanjaModal)}}</td>
                    <td>{{rupiah($hibah)}}</td>
                    <td>{{rupiah($beban)}}</td>
                    <td>{{rupiah($mutasiantaropd)}}</td>
                    <td>{{rupiah($reklas)}}</td>
                    <td>{{rupiah($revaluasi)}}</td>
                    <td>{{rupiah($koreksi)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur)}}</td>
                    <td>{{rupiah($Jumlah)}}</td>

                    <td>{{rupiah($hibah1)}}</td>
                    <td>{{rupiah($Penghapusan1)}}</td>
                    <td>{{rupiah($mutasiantaropd1)}}</td>
                    <td>{{rupiah($reklas1)}}</td>
                    <td>{{rupiah($revaluasi1)}}</td>
                    <td>{{rupiah($koreksi1)}}</td>
                    <td>{{rupiah($rusakberat)}}</td>
                    <td>{{rupiah($beban1)}}</td>
                    <td>{{rupiah($mutasi_nomenklatur1)}}</td>
                    <td>{{rupiah($Jumlah1)}}</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center">TOTAL</td>
                <td>{{rupiah($tot_sal_lalu)}}</td>
                <td>{{rupiah($tot_RealisasiBelanjaModal)}}</td>
                <td>{{rupiah($tot_hibah)}}</td>
                <td>{{rupiah($tot_beban)}}</td>
                <td>{{rupiah($tot_mutasiantaropd)}}</td>
                <td>{{rupiah($tot_reklas)}}</td>
                <td>{{rupiah($tot_revaluasi)}}</td>
                <td>{{rupiah($tot_koreksi)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur)}}</td>
                <td>{{rupiah($tot_Jumlah)}}</td>

                <td>{{rupiah($tot_hibah1)}}</td>
                <td>{{rupiah($tot_Penghapusan1)}}</td>
                <td>{{rupiah($tot_mutasiantaropd1)}}</td>
                <td>{{rupiah($tot_reklas1)}}</td>
                <td>{{rupiah($tot_revaluasi1)}}</td>
                <td>{{rupiah($tot_koreksi1)}}</td>
                <td>{{rupiah($tot_rusakberat)}}</td>
                <td>{{rupiah($tot_beban1)}}</td>
                <td>{{rupiah($tot_mutasi_nomenklatur1)}}</td>
                <td>{{rupiah($tot_Jumlah1)}}</td>
                <td>{{rupiah($tot_total)}}</td>
            </tr>

        @endif
        
    </table>
</body>
</html>