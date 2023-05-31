<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UMUR PIUTANG</title>
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

<body onload="window.print()">
{{-- <body> --}}

    <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="5" style="border-top:none;border-right:none;border-left:none;font-size:14px" align="center">
                <b>PEMERINTAH PROVINSI KALIMANTAN BARAT</b> <br>
                <b>{{nama_skpd($skpd)}}</b> <br>
                <b>ANALISIS UMUR PIUTANG</b><BR>
                <b>PER 31 DESEMBER {{$lntahunang}} </b>
                <BR>&nbsp;                                        
            </td>           
        </tr>
    </table>
    
    
    <table style="border-collapse:collapse;font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="5%"  align="left">SKPD </td> 
            <td> :</td>
            <td> {{$skpd}} - {{nama_skpd($skpd)}} <br></td>
        </tr>
        <tr>
            <td width="5%"  align="left">&nbsp;</td> 
        </tr>
    </table>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="2" width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kode Rekening </td>
                <td colspan="2" rowspan="2" width="20%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Uraian</td>
                <td rowspan="2"  width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Tahun<br>Pengakuan</td>
                <td rowspan="2"  width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Bulan<br>Penetapan</td>
                <td rowspan="2"  width="8%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Saldo<br>Awal</td>
                <td colspan="2"  width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Mutasi Tahun {{$lntahunang}}</td>
                <td rowspan="2"  width="8%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Saldo Akhir Piutang<br>{{$lntahunang}}</td>
                <td rowspan="2"  width="8%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Koreksi BPK<br>{{$lntahunang}}}}</td>
                <td rowspan="2"  width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kualitas<br>Piutang</td>
                <td rowspan="2" width="15%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Umur Piutang<br>s/d<br> Tahun {{$lntahunang}}</td>
                <td colspan="2" rowspan="2"  width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Penyisihan Piutang Tak<br>Tertagih {{$lntahunang}}</td>
                <td rowspan="2"  width="8%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Penyisihan Piutang<br>Tahun Sebelumnya</td>
                <td rowspan="2"  width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Beban Penyisihan <br>Piutang (LO) Tahun {{$lntahunang}}</td>
                <td rowspan="2"  width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Nilai Buku<br>Piutang Tahun<br>{{$lntahunang}}</td>
            </tr>
            <tr>
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Tambah</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Kurang</td> 
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px"></td>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px"></td>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">7=(4+5-6)</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">8</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">9</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">10</td>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">11</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">12=(7*11)</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">13=</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">14=(12-13)</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">15=(7-12)</td> 
            </tr>
        </thead>
    
    @php
        $tot_sal_awal=0;
        $tot_tambah=0;
        $tot_kurang=0;
        $tot_akhir=0;
        $tot_koreksi=0;
        $tot_koreksi=0;
        $tot_penyi_piu=0;
        $tot_piu_lalu=0;
        $tot_lo=0;
        $tot_bku=0;
    @endphp
    @foreach($query as $row)
        @php
            $no_lamp = $row->no_lamp;
                       $kode1 = $row->kode1;
                       $kode = $row->kode;
                       $nama = $row->nama;
                       $bln = $row->bulan;
                       $tahun = $row->tahun;
                       $lokasi = $row->lokasi;
                       $jumlah = $row->jumlah;
                       $sal_awal = $row->sal_awal;
                       $kurang = $row->kurang;
                       $tambah = $row->tambah;
                       $tahun_n = $row->tahun_n;
                       $akhir = $sal_awal-$kurang+$tambah+$tahun_n;
                       $koreksi = $row->koreksi;
                       $audited = $akhir+$koreksi;
                       $penyi_piu = $row->penyi_piu;
                       $piu_tahun_lalu = $row->piu_tahun_lalu;
                       $piu_lalu = $row->piu_lalu;
                       $keterangan = $row->keterangan;
                       $umur_t = $row->umur_t;
                       $umur_b = $row->umur_b;
                       $kualitas = $row->kualitas;
                       $kualitas_lalu = $row->kualitas_lalu;
                       if($kualitas=='Lancar'){
                            $taksiran="0,5%";
                            $persenan = 0.0005;
                        }
                       elseif($kualitas=='Kurang Lancar'){
                            $taksiran="10%";
                            $persenan = 0.01;}
                       elseif($kualitas=='Diragukan'){
                               $taksiran="50%";
                               $persenan = 0.5;}
                       elseif($kualitas==''){
                           $taksiran="";
                           $persenan = 0;}
                       else{
                           $taksiran="100%";
                           $persenan = 1;}

                       

                       if ($skpd=="1.02.0.00.0.00.02.0000" && $lntahunang<>2022) {
                                $koreksi=$koreksi;
                                $penyi_piu=$akhir*$persenan;
                                $piu_lalu=$piu_lalu-$koreksi;
                       }else if ($skpd=="1.02.0.00.0.00.02.0000" && $lntahunang==2022) {
                                $koreksi=0;
                                $penyi_piu=$akhir*$persenan;
                                if ($kode=='110616010001' && $tahun<=2020 && $bln<12 ) {
                                    if($kode=='110616010001' && $tahun==2020 && $no_lamp=='4432-1020201'){
                                            $piu_lalu=$piu_lalu+($piu_lalu*0.1);
                                    }else{

                                        $piu_lalu=$piu_lalu*2;
                                    }
                                }else{
                                    $piu_lalu=$piu_lalu;
                                }
                        } 
                       $nilai_piu = $audited-$penyi_piu;
                       $sal_sebelum = $row->sal_akhir;
                       // $lo=$penyi_piu-$piu_tahun_lalu;
                       $lo=$penyi_piu-$piu_lalu;
                       // $bku=$akhir-$penyi_piu;
                       $bku=$akhir+$koreksi-$penyi_piu;

                        $tot_sal_awal=$tot_sal_awal+$sal_awal;
                        $tot_tambah=$tot_tambah+$tambah;
                        $tot_kurang=$tot_kurang+$kurang;
                        $tot_akhir=$tot_akhir+$akhir;
                        $tot_koreksi=$tot_koreksi;
                        $tot_penyi_piu=$tot_penyi_piu+$penyi_piu;
                        $tot_piu_lalu=$tot_piu_lalu+$piu_lalu;
                        $tot_lo=$tot_lo+$lo;
                        $tot_bku=$tot_bku+$bku;


                       $jumlah==0 ? $jumlah = '' : $jumlah=$jumlah;
                       $bln==0 ? $bln = '' : $bln=$bln;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $umur_t==0 ? $umur_t = '' : $umur_t=$umur_t;
                       $umur_b==0 ? $umur_b = '' : $umur_b=$umur_b;
                       $sal_sebelum==0 ? $sal_sebelum = '' : $sal_sebelum=rupiah($sal_sebelum);
                       $piu_tahun_lalu==0 ? $piu_tahun_lalu = '' : $piu_tahun_lalu=rupiah($piu_tahun_lalu);
                       $piu_lalu==0 ? $piu_lalu = '' : $piu_lalu=rupiah($piu_lalu);
                       $lo==0 ? $lo = '' : $lo=rupiah($lo);
                       $bku==0 ? $bku = '' : $bku=rupiah($bku);

                       if ($umur_b==0 && strlen($kode1)==12){$umur_piu=$umur_t." Tahun";}
                       elseif ($umur_t==0 && strlen($kode1)==12){$umur_piu=$umur_b." Bulan";}
                       elseif (strlen($kode1)!=12){$umur_piu = '';}
                       else {$umur_piu=$umur_t." Tahun " .$umur_b. " Bulan";}
                       
                        
                       if (strlen($kode1)==12){$tahun=$tahun;}
                       else {$tahun = '';}
                       
                       if (strlen($kode1)==12 || strlen($kode1)==8){$akhir=rupiah($akhir);}
                       else {$akhir = '';}
                       
                       if (strlen($kode1)==12){$taksiran=$taksiran;}
                       else {$taksiran = '';}
                       
                       if (strlen($kode1)==12 || strlen($kode1)==8){$penyi_piu=rupiah($penyi_piu);}
                       else {$penyi_piu = '';}
                       
                       if (strlen($kode1)==12 || strlen($kode1)==8){$nilai_piu=rupiah($nilai_piu);}
                       else {$nilai_piu = '';}
                       
                       if ($koreksi < 0){
                       $min001="("; $koreksi=$koreksi*-1; $min002=")";
                       }else {
                       $min001=""; $koreksi; $min002="";
                       }            
        
                       if (strlen($kode1)==12 || strlen($kode1)==8){$koreksi=rupiah($koreksi);}
                       else {$koreksi = '';}
                       
                       if (strlen($kode1)==12 || strlen($kode1)==8){$audited=rupiah($audited);}
                       else {$audited = '';}
        @endphp
        <tr>
            <td align="left" style="font-size:12px">{{$kode}}</td> 
            <td align="left" style="font-size:12px">{{$nama}}</td> 
            <td align="center" style="font-size:12px">{{$keterangan}}</td> 
            <td align="center" style="font-size:12px">{{$tahun}}</td>
            <td align="center" style="font-size:12px">{{$bln}}</td> 
            <td align="center" style="font-size:12px">{{$sal_awal}}</td> 
            <td align="center" style="font-size:12px">{{$tambah}}</td> 
            <td align="center" style="font-size:12px">{{$kurang}}</td> 
            <td align="center" style="font-size:12px">{{$akhir}}</td> 
            <td align="center" style="font-size:12px">{{$koreksi}}</td> 
            <td align="center" style="font-size:12px">{{$kualitas}}</td> 
            <td align="center" style="font-size:12px">{{$umur_piu}}</td> 
            <td align="center" style="font-size:12px">{{$taksiran}}</td> 
            <td align="center" style="font-size:12px">{{$penyi_piu}}</td>
            <td align="center" style="font-size:12px">{{$piu_lalu}}</td>
            <td align="center" style="font-size:12px">{{$lo}}</td> 
            <td align="center" style="font-size:12px">{{$bku}}</td>
        </tr>
    @endforeach
    @php    
        $tot_sal_awal==0 ? $tot_sal_awal = '' : $tot_sal_awal=rupiah($tot_sal_awal);
        $tot_tambah==0 ? $tot_tambah = '' : $tot_tambah=rupiah($tot_tambah);
        $tot_kurang==0 ? $tot_kurang = '' : $tot_kurang=rupiah($tot_kurang);
        $tot_akhir==0 ? $tot_akhir = '' : $tot_akhir=rupiah($tot_akhir);
        $tot_koreksi==0 ? $tot_koreksi = '' : $tot_koreksi=rupiah($tot_koreksi);
        $tot_penyi_piu==0 ? $tot_penyi_piu = '' : $tot_penyi_piu=rupiah($tot_penyi_piu);
        $tot_piu_lalu==0 ? $tot_piu_lalu = '' : $tot_piu_lalu=rupiah($tot_piu_lalu);
        $tot_lo==0 ? $tot_lo = '' : $tot_lo=rupiah($tot_lo);
        $tot_bku==0 ? $tot_bku = '' : $tot_bku=rupiah($tot_bku);
    @endphp
    <tr>
        <td colspan="3"align="center" style="font-size:12px">TOTAL PIUTANG PENDAPATAN</td> 
        <td align="center" style="font-size:12px"></td>
        <td align="center" style="font-size:12px"></td> 
        <td align="center" style="font-size:12px">{{$tot_sal_awal}}</td> 
        <td align="center" style="font-size:12px">{{$tot_tambah}}</td> 
        <td align="center" style="font-size:12px">{{$tot_kurang}}</td> 
        <td align="center" style="font-size:12px">{{$tot_akhir}}</td> 
        <td align="center" style="font-size:12px">{{$tot_koreksi}}</td> 
        <td align="center" style="font-size:12px"></td> 
        <td align="center" style="font-size:12px"></td> 
        <td align="center" style="font-size:12px"></td> 
        <td align="center" style="font-size:12px">{{$tot_penyi_piu}}</td>
        <td align="center" style="font-size:12px">{{$tot_piu_lalu}}</td>
        <td align="center" style="font-size:12px">{{$tot_lo}}</td> 
        <td align="center" style="font-size:12px">{{$tot_bku}}</td>
    </tr>
    </TABLE>

    
    
</body>

</html>
