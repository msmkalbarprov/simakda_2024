<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. CALK LPE Lain-lain</title>
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
            <TD colspan="29" align="center" ><b>PEMERINTAH PROVINSI KALIMANTAN BARAT</TD>
        </TR>
        <TR>
            <TD colspan="29" align="center" ><b>LAPORAN CALK LPE LAIN LAIN</TD>
        </TR>
        <TR>
            <TD colspan="29" align="center" ><b>TAHUN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td bgcolor="#CCCCCC" width="5%" align="center">KODE SKPD</td>
            <td bgcolor="#CCCCCC" width="35%" align="center">NAMA SKPD</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">Penyisihan Piutang</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Penyusutan</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Hibah Keluar</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Mutasi Masuk Aset OPD</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Mutasi Keluar Aset OPD</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Penghapusan TPTGR</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Perubahan Kode Rekening</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Tanah</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Utang Belanja</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Reklass Antar Akun</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Tagihan Penjualan Angsuran</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Penyertaan Modal</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Persediaan APBN yang belum</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Aset peralatan dan mesin reklas ke persediaan lain-lain</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Dana Transfer Pemerintah Pusat</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Gedung dan Bangunan</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Persediaan</td>  
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Kas</td>  
            <td bgcolor="#CCCCCC" width="15%" align="center">Extracompatable</td>  
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Peralatan Dan Mesin</td> 
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Jaringan Irigasi Jembatan</td>  
            
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Aset Tetap Lainya</td>  
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Piutang</td>  
            <td bgcolor="#CCCCCC" width="15%" align="center">Koreksi Aset Lain Lain</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">Pelimpahan Masuk</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">Pelimpahan Keluar</td>  
            <td bgcolor="#CCCCCC" width="15%" align="center">Total</td>                    
        </tr>
        @foreach($query as $row)
            @php
                $kd_skpd=$row->kd_skpd;
                $nm_skpd=$row->nm_skpd;
                $peny_piutang=$row->peny_piutang;
                $koreksi_peny=$row->koreksi_peny;
                $hibah_kel=$row->hibah_kel;
                $mutasi_masuk=$row->mutasi_masuk;
                $mutasi_kel=$row->mutasi_kel;
                $hapus_pers=$row->hapus_pers;
                $ubah_kode=$row->ubah_kode;
                $koreksi_tanah=$row->koreksi_tanah;
                $koreksi_utang=$row->koreksi_utang;
                $reklas_akun=$row->reklas_akun;
                $tagihan=$row->tagihan;
                $peny_modal=$row->peny_modal;
                $persediaan_apbn=$row->persediaan_apbn;
                $aset_peralatan=$row->aset_peralatan;
                $koreksi_dana=$row->koreksi_dana;
                $koreksi_gedung=$row->koreksi_gedung;
                $koreksi_persediaan=$row->koreksi_persediaan;
                $koreksi_kas=$row->koreksi_kas;
                $extracompatable=$row->extracompatable;
                $koreksi_peralatan_mesin=$row->koreksi_peralatan_mesin;
                $koreksi_jij=$row->koreksi_jij;
                $koreksi_atl=$row->koreksi_atl;
                $koreksi_piutang=$row->koreksi_piutang;
                $koreksi_all=$row->koreksi_all;
                $pelimpahan_masuk=$row->pelimpahan_masuk;
                $pelimpahan_keluar=$row->pelimpahan_keluar;
                            
                $tot_lain = $peny_piutang+$koreksi_peny+$hibah_kel+$mutasi_masuk+$mutasi_kel+$hapus_pers+$ubah_kode+$koreksi_tanah+$koreksi_utang+$reklas_akun+$tagihan+$peny_modal+$persediaan_apbn+$aset_peralatan+$koreksi_dana+$koreksi_gedung+$koreksi_persediaan+$koreksi_kas+$extracompatable+$koreksi_peralatan_mesin+$koreksi_jij+$koreksi_atl+$koreksi_piutang+$koreksi_all+$pelimpahan_masuk+$pelimpahan_keluar;
            @endphp
            <tr>
                <td align="center" valign="top">{{$kd_skpd}}</td>
                <td align="left" valign="top">{{$nm_skpd}}</td>
                <td align="right" valign="top">{{$peny_piutang < 0 ? '(' . rupiah($peny_piutang * -1) . ')' : rupiah($peny_piutang) }}</td>
                <td align="right" valign="top">{{$koreksi_peny < 0 ? '(' . rupiah($koreksi_peny * -1) . ')' : rupiah($koreksi_peny) }}</td>
                <td align="right" valign="top">{{$hibah_kel < 0 ? '(' . rupiah($hibah_kel * -1) . ')' : rupiah($hibah_kel) }}</td>
                <td align="right" valign="top">{{$mutasi_masuk < 0 ? '(' . rupiah($mutasi_masuk * -1) . ')' : rupiah($mutasi_masuk) }}</td>
                <td align="right" valign="top">{{$mutasi_kel < 0 ? '(' . rupiah($mutasi_kel * -1) . ')' : rupiah($mutasi_kel) }}</td>
                <td align="right" valign="top">{{$hapus_pers < 0 ? '(' . rupiah($hapus_pers * -1) . ')' : rupiah($hapus_pers) }}</td>
                <td align="right" valign="top">{{$ubah_kode < 0 ? '(' . rupiah($ubah_kode * -1) . ')' : rupiah($ubah_kode) }}</td>
                <td align="right" valign="top">{{$koreksi_tanah < 0 ? '(' . rupiah($koreksi_tanah * -1) . ')' : rupiah($koreksi_tanah) }}</td>
                <td align="right" valign="top">{{$koreksi_utang < 0 ? '(' . rupiah($koreksi_utang * -1) . ')' : rupiah($koreksi_utang) }}</td>
                <td align="right" valign="top">{{$reklas_akun < 0 ? '(' . rupiah($reklas_akun * -1) . ')' : rupiah($reklas_akun) }}</td>
                <td align="right" valign="top">{{$tagihan < 0 ? '(' . rupiah($tagihan * -1) . ')' : rupiah($tagihan) }}</td>
                <td align="right" valign="top">{{$peny_modal < 0 ? '(' . rupiah($peny_modal * -1) . ')' : rupiah($peny_modal) }}</td>
                <td align="right" valign="top">{{$persediaan_apbn < 0 ? '(' . rupiah($persediaan_apbn * -1) . ')' : rupiah($persediaan_apbn) }}</td>
                <td align="right" valign="top">{{$aset_peralatan < 0 ? '(' . rupiah($aset_peralatan * -1) . ')' : rupiah($aset_peralatan) }}</td>
                <td align="right" valign="top">{{$koreksi_dana < 0 ? '(' . rupiah($koreksi_dana * -1) . ')' : rupiah($koreksi_dana) }}</td>
                <td align="right" valign="top">{{$koreksi_gedung < 0 ? '(' . rupiah($koreksi_gedung * -1) . ')' : rupiah($koreksi_gedung) }}</td>
                <td align="right" valign="top">{{$koreksi_persediaan < 0 ? '(' . rupiah($koreksi_persediaan * -1) . ')' : rupiah($koreksi_persediaan) }}</td>
                <td align="right" valign="top">{{$koreksi_kas < 0 ? '(' . rupiah($koreksi_kas * -1) . ')' : rupiah($koreksi_kas) }}</td>
                <td align="right" valign="top">{{$extracompatable < 0 ? '(' . rupiah($extracompatable * -1) . ')' : rupiah($extracompatable) }}</td>
                <td align="right" valign="top">{{$koreksi_peralatan_mesin < 0 ? '(' . rupiah($koreksi_peralatan_mesin * -1) . ')' : rupiah($koreksi_peralatan_mesin) }}</td>
                <td align="right" valign="top">{{$koreksi_jij < 0 ? '(' . rupiah($koreksi_jij * -1) . ')' : rupiah($koreksi_jij) }}</td>
                <td align="right" valign="top">{{$koreksi_atl < 0 ? '(' . rupiah($koreksi_atl * -1) . ')' : rupiah($koreksi_atl) }}</td>
                <td align="right" valign="top">{{$koreksi_piutang < 0 ? '(' . rupiah($koreksi_piutang * -1) . ')' : rupiah($koreksi_piutang) }}</td>
                <td align="right" valign="top">{{$koreksi_all < 0 ? '(' . rupiah($koreksi_all * -1) . ')' : rupiah($koreksi_all) }}</td>
                <td align="right" valign="top">{{$pelimpahan_masuk < 0 ? '(' . rupiah($pelimpahan_masuk * -1) . ')' : rupiah($pelimpahan_masuk) }}</td>
                <td align="right" valign="top">{{$pelimpahan_keluar < 0 ? '(' . rupiah($pelimpahan_keluar * -1) . ')' : rupiah($pelimpahan_keluar) }}</td>
                <td align="right" valign="top">{{$tot_lain < 0 ? '(' . rupiah($tot_lain * -1) . ')' : rupiah($tot_lain) }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>