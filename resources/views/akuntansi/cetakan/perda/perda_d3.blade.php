<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA D3</title>
    <style>
        body {
          font-family: Arial;
        }

        .bordered {
          width: 100%;
          border-collapse: collapse;
        }

        .bordered th,
        .bordered td {
          border: 1px solid black;
          padding: 4px;
        }

        .bordered td:nth-child(n+5) {
          text-align: right;
        }

        .bordered th {
          /* background-color: #cccccc; */
        }

        .bordered {
          font-size: 11px;
        }

        .bold {
          font-weight: bold;
        }

        table {
          width: 100%;
        }

        
    </style>
</head>

<body >
{{-- <body> --}}
    <table style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN D3 &nbsp;{{ strtoupper($nogub->ket_perda) }}</TD>
        </TR>
        <TR>
            <TD  colspan="3" width="100%" valign="top" align="left" >NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($nogub->ket_perda_no) }}</TD>
        </TR>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >TENTANG &nbsp; {{ strtoupper($nogub->ket_perda_tentang) }}</TD>
        </TR>
    </table>
    <table  style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
            <tr>
                <td rowspan="4" align="center" style="border-right:hidden">
                    <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
                </td>
                
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-bottom:hidden"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>REKAPITULASI BELANJA UNTUK PEMENUHAN STANDAR PELAYANAN MINIMUM (SPM)</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>
 
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family: arial; font-size:12px" width="100%" align="center" border="1" cellspacing="0" cellpadding="2">
        <thead >                       
            <tr>
                <td  width="2%" align="center"><b>No</b></td>                            
                <td width="20%" align="center"><b>Jenis Pelayanan Dasar</b></td>
                <td width="15%" align="center"><b>Kegiatan</b></td>
                <td width="30%" align="center"><b>Anggaran (Rp)</b></td>
                <td width="33%" align="center"><b>Realisasi</b></td>
            </tr>
            <tr>
                <td style="font-weight:bold;" width="5%" align="center">1</td>                            
                <td style="font-weight:bold;" width="20%"  align="center">2</td>
                <td style="font-weight:bold;" width="15%"  align="center">3</td>
                <td style="font-weight:bold;" width="33%"  align="center">4</td>
                <td style="font-weight:bold;" width="30%"  align="center">5</td>
            </tr>
        </thead>
        {{-- A pendidikan --}}
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>              
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>A SPM Bidang Pendidikan</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Pendidikan Menengah</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_sma)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="left">{{rupiah($ang_sma)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="left">{{rupiah($real_sma)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pengelolaan Pendidikan Sekolah Menengah Atas</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_sma)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_sma)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_smk)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="left">{{rupiah($ang_smk)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="left">{{rupiah($real_smk)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pengelolaan Pendidikan Sekolah Menengah Kejuruan</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_smk)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_smk)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="5" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Pendidikan Khusus</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_khusus)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_khusus)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_khusus)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pengelolaan Pendidikan Khusus</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_khusus)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_khusus)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td  colspan="2"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="20%" align="right">Jumlah SPM Bidang Pendidikan</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_sma+$ang_smk+$ang_khusus)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_sma+$real_smk+$real_khusus)}}</b></td>
            </tr>
        {{-- B kesehatan --}}
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>B SPM Bidang Kesehatan</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>{{nama_sub_kegiatan($kd_sub_kegiatan_bencana)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_bencana)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_bencana)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_bencana)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Penyediaan Layanan Kesehatan untuk UKP Rujukan, UKM dan UKM Rujukan Tingkat Daerah Provinsi</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_bencana)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_bencana)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>{{nama_sub_kegiatan($kd_sub_kegiatan_lb)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_lb)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_lb)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_lb)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Penyediaan Layanan Kesehatan untuk UKP Rujukan, UKM dan UKM Rujukan Tingkat Daerah Provinsi</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_lb)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_lb)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td  colspan="2"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="20%" align="right">Jumlah SPM Bidang Kesehatan</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_bencana+$ang_lb)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_bencana+$real_lb)}}</td>
            </tr>
        {{-- C Bidang Pekerjaan Umum Dan Penataan Ruang (pupr) --}}
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>C SPM Bidang Pekerjaan Umum Dan Penataan Ruang</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Pemenuhan Kebutuhan Air Minum Curah Lintas Kabupaten/Kota</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_spam)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_spam)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_spam)}}</td>
                </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                    
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pengelolaan dan Pengembangan Sistem Penyediaan Air Minum (SPAM) Lintas Kabupaten/Kota      </td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_spam)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_spam)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_saldr)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_saldr)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_saldr)}}</td>
                </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                    
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pengelolaan dan Pengembangan Sistem Air Limbah Domestik Regional      </td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_saldr)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_saldr)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td  colspan="2"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="20%" align="right">Jumlah SPM Bidang Pekerjaan Umum Dan Penataan Ruang</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_spam+$ang_saldr)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_spam+$real_saldr)}}</b></td>
            </tr>
        {{-- D Bidang Perumahan Rakyat dan Kawasan Pemukiman (prkp) --}}
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>D SPM Bidang Perumahan Rakyat dan Kawasan Pemukiman</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Penyediaan dan Rehabilitasi Rumah yang Layak Huni Bagi Korban Bencana Provinsi</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_penyediaan)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_penyediaan)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_penyediaan)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pendataan Penyediaan dan Rehabilitasi Rumah Korban Bencana atau Relokasi Program Provinsi </td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_penyediaan)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_penyediaan)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Fasilitasi Penyediaan Rumah yang Layak Huni Bagi Masyarakat yang Terkena Relokasi Program Pemerintah Daerah Provinsi</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_pembangunan)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_pembangunan)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_pembangunan)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pembangunan dan Rehabilitasi Rumah Korban Bencana atau Relokasi Program Provinsi </td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_pembangunan)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_pembangunan)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                                        
                <td  colspan="2"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="20%" align="right">Jumlah SPM Bidang Perumahan Rakyat dan Kawasan Pemukiman</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_penyediaan+$ang_pembangunan)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_penyediaan+$real_pembangunan)}}</td>
            </tr>
        {{-- E SPM Bidang Ketentraman dan Ketertiban Umum (kku) --}}
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>E SPM Bidang Ketentraman dan Ketertiban Umum</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Pelayanan Ketentraman dan Ketertiban Umum Provinsi</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_gangguan)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_gangguan)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_gangguan)}}</td>
                </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pendataan Penyediaan dan Rehabilitasi Rumah Korban Bencana atau Relokasi Program Provinsi </td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_gangguan)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_gangguan)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_ppns)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_ppns)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_ppns)}}</td>
                                     </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Pembinaan Penyidik Pegawai Negeri Sipil (PPNS) Provinsi   </td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_ppns)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_ppns)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td  colspan="2"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="20%" align="right">Jumlah SPM Bidang Ketentraman dan Ketertiban Umum</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_gangguan+$ang_ppns)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_gangguan+$real_ppns)}}</td>
            </tr>
        {{-- F SPM Bidang Sosial --}}
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>F SPM Bidang Sosial</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                 <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Rehabilitasi Sosial Dasar Penyandang Disabilitas Telantar di Dalam Panti</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_10604101)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_10604101)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_10604101)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Rehabilitasi Sosial Dasar Penyandang Disabilitas Terlantar di dalam Panti </td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_10604101)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_10604101)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Rehabilitasi Sosial Dasar Anak Telantar di Dalam Panti</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_10604102)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_10604102)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_10604102)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Rehabilitasi Sosial Dasar Anak Terlantar di Dalam Panti</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_10604102)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_10604102)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Rehabilitasi Sosial Dasar Lanjut Usia Telantar di Dalam Panti</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_10604103)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_10604103)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_10604103)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Rehabilitasi Sosial Dasar Lanjut Usia Terlantar di dalam Panti</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_10604103)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_10604103)}}</b></td></tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Rehabilitasi Sosial Dasar Tuna Sosial Khususnya Gelandangan dan Pengemis di Dalam Panti</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_10604104)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_10604104)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_10604104)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Rehabilitasi Sosial Dasar Gelandangan dan Pengemis di dalam Panti</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_10604104)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_10604104)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td colspan="4" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"><b>Perlindungan dan Jaminan Sosial Pada Saat dan Setelah Tanggap Darurat Bencana Bagi Korban Bencana Provinsi</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="90%"><b>{{nama_keg($kd_kegiatan_10606101)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($ang_10606101)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="25%" align="right">{{rupiah($real_10606101)}}</td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah Perlindungan Sosial Korban Bencana Alam dan Sosial Provinsi</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_10606101)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_10606101)}}</b></td>
            </tr>
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td>  
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="10%" align="left"></td> 
                <td colspan="1" style="vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;" width="70%" align="right">Jumlah SPM Bidang Sosial</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($ang_10604101+$ang_10604102+$ang_10604103+$ang_10604104+$ang_10606101)}}</b></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%" align="right"><b>{{rupiah($real_10604101+$real_10604102+$real_10604103+$real_10604104+$real_10606101)}}</b></td>
            </tr>

    </table>
    {{-- isi --}}
    {{-- tanda tangan --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" align="center" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td width="50%" align="center">&nbsp;</td>
            <td width="50%" align="center"></td>
        </tr>
        <tr>
            <td width="50%" align="center">&nbsp;</td>
            <td width="50%" align="center">Pontianak, {{tgl_format_oyoy($tanggal_ttd)}}<br>Pj. GUBERNUR KALIMANTAN BARAT<br><br><br><br><br><b><u>HARISSON</u></b>
            </td>
        </tr>
    </table>
    
</body>

</html>
