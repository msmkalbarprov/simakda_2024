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
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kode Rekening </td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Uraian</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Tahun<br>Penetapan</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Bulan<br>Penetapan</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Keterangan</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Saldo<br>Piutang</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Koreksi<br>BPK</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Audited</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Umur<br>Piutang</td>
                <td width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kualitas<br>Piutang</td>
                <td width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Taksiran<br>Piutang Tak<br>Tertagih</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Penyisihan<br>Piutang</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Nilai Buku<br>Piutang Tahun<br>{{$lntahunang}}</td>

            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">7</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">8</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">9</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">10</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">11</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">12</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">13</td> 
            </tr>
        </thead>
    
        
    @foreach($query as $row)
        @php
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
                       if ($skpd=="1.02.0.00.0.00.02.0000") {
                           $audited=$akhir;
                       }
                       $keterangan = $row->keterangan;
                       $umur_t = $row->umur_t;
                       $umur_b = $row->umur_b;
                       $kualitas = $row->kualitas;
                       $penyi_piu = $row->penyi_piu;
                       $nilai_piu = $audited-$penyi_piu;
                       $jumlah==0 ? $jumlah = '' : $jumlah=rupiah($jumlah);
                       $bln==0 ? $bln = '' : $bln=$bln;
                       $sal_awal==0 ? $sal_awal = '' : $sal_awal=rupiah($sal_awal);
                       $kurang==0 ? $kurang = '' : $kurang=rupiah($kurang);
                       $tambah==0 ? $tambah = '' : $tambah=rupiah($tambah);
                       $tahun_n==0 ? $tahun_n = '' : $tahun_n=rupiah($tahun_n);
                       $umur_t==0 ? $umur_t = '' : $umur_t=$umur_t;
                       $umur_b==0 ? $umur_b = '' : $umur_b=$umur_b;
                       
                       if ($umur_b==0 && strlen($kode1)==12){$umur_piu=$umur_t." Tahun";}
                       elseif ($umur_t==0 && strlen($kode1)==12){$umur_piu=$umur_b." Bulan";}
                       elseif (strlen($kode1)!=12){$umur_piu = '';}
                       else {$umur_piu=$umur_t." Tahun " .$umur_b. " Bulan";}
                       
                       if($kualitas=='Lancar'){
                       $taksiran="0,5%";}
                       elseif($kualitas=='Kurang Lancar'){
                       $taksiran="10%";}
                       elseif($kualitas=='Diragukan'){
                       $taksiran="50%";}
                       elseif($kualitas==''){
                       $taksiran="";}
                       else{
                       $taksiran="100%";}
                        
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
            <td align="center" style="font-size:12px">{{$tahun}}</td> 
            <td align="center" style="font-size:12px">{{$bln}}</td> 
            <td align="center" style="font-size:12px">{{$keterangan}}</td> 
            <td align="center" style="font-size:12px">{{$akhir}}</td> 
            <td align="center" style="font-size:12px">{{$min001}}{{$koreksi}}{{$min002}}</td> 
            <td align="center" style="font-size:12px">{{$audited}}</td> 
            <td align="center" style="font-size:12px">{{$umur_piu}}</td> 
            <td align="center" style="font-size:12px">{{$kualitas}}</td> 
            <td align="center" style="font-size:12px">{{$taksiran}}</td> 
            <td align="center" style="font-size:12px">{{$penyi_piu}}</td>
            <td align="center" style="font-size:12px">{{$nilai_piu}}</td> 
        </tr>
    @endforeach
    
    </TABLE>

    
    
</body>

</html>
