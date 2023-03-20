<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA I.1</title>
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
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN I.1 &nbsp;{{ strtoupper($nogub->ket_perda) }}</TD>
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
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>

    <hr>
 
    {{-- isi --}}
    <table class="bordered">
    <thead>
      <tr>
        <th colspan="3" rowspan="2">Kode</th>
        <th rowspan="2">Urusan Pemerintahan Daerah</th>
        <th colspan="2">Jumlah (Rp)</th>
        <th colspan="2">Bertambah/(Berkurang)</th>
      </tr>
      <tr>
        <th>Anggaran</th>
        <th>Realisasi</th>
        <th>(RP)</th>
        <th>%</th>
      </tr>
    </thead>
    <tbody>
        @php
            $show_belanja = true;
            $show_pendapatan = true;
            $last_urusan = null; 
        @endphp
        @foreach ($daftar_lra as $item)
            <tr class=" {{$item->is_bold ? 'bold' : ''}} ?> ">
          <td>
            <?php if ($item->jenis == 'urusan' || ($item->jenis == 'bidang_urusan' && $last_urusan != substr($item->kode, 0, 1))) : ?>
              <?php echo substr($item->kode, 0, 1) ?>
            <?php endif ?>
          </td>
          <td><?php echo $item->jenis == 'bidang_urusan' ? substr($item->kode, -2) : '' ?></td>
          <td><?php echo $item->jenis == 'skpd' ? $item->kode : '' ?></td>
          <td><?php echo $item->nama ?> </td>
          <?php if ($item->jenis == 'urusan') : ?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

          <?php else : ?>
            <td><?php echo number_format($item->nilai_ag, 2, ',', '.') ?></td>
            <td><?php echo number_format($item->nilai_real, 2, ',', '.') ?></td>
            <?php $bertambah_berkurang = $item->nilai_real-$item->nilai_ag ?>
            <td><?php echo $bertambah_berkurang >= 0 ? number_format($bertambah_berkurang, 2, ',', '.') : '(' . number_format(-$bertambah_berkurang, 2, ',', '.') . ')' ?></td>
            <td><?php echo $item->nilai_ag == 0 ? number_format($item->nilai_real == 0 ? 0 : 100, 2, ',', '.') : number_format($item->nilai_real * 100 / $item->nilai_ag, 2, ',', '.') ?></td>
          <?php endif ?>
        </tr>
        <?php if ($show_pendapatan && substr($item->ikey, 0, 1) == '4') : ?>
          <?php $show_pendapatan = false; ?>
          <tr class="bold">
            <td></td>
            <td></td>
            <td></td>
            <td>PENDAPATAN</td>
            <td>{{rupiah($pendapatan->nilai_ag)}}</td>
            <td><?php echo number_format($pendapatan->nilai_real, 2, ',', '.') ?></td>
            <?php $bertambah_berkurang =  $pendapatan->nilai_real -$pendapatan->nilai_ag ?>
            <td><?php echo $bertambah_berkurang >= 0 ? number_format($bertambah_berkurang, 2, ',', '.') : '(' . number_format(-$bertambah_berkurang, 2, ',', '.') . ')' ?></td>
            <td><?php echo $pendapatan->nilai_ag == 0 ? number_format($pendapatan->nilai_real == 0 ? 0 : 100, 2, ',', '.') : number_format($pendapatan->nilai_real * 100 / $pendapatan->nilai_ag, 2, ',', '.') ?></td>
          </tr>
        <?php endif ?>
        <?php if ($show_belanja && substr($item->ikey, 0, 1) == '5') : ?>
          <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <?php $show_belanja = false; ?>
          <tr class="bold">
            <td></td>
            <td></td>
            <td></td>
            <td>BELANJA</td>
            <td><?php echo number_format($belanja->nilai_ag, 2, ',', '.') ?></td>
            <td><?php echo number_format($belanja->nilai_real, 2, ',', '.') ?></td>
            <?php $bertambah_berkurang = $belanja->nilai_real - $belanja->nilai_ag ?>
            <td><?php echo $bertambah_berkurang >= 0 ? number_format($bertambah_berkurang, 2, ',', '.') : '(' . number_format(-$bertambah_berkurang, 2, ',', '.') . ')' ?></td>
            <td><?php echo $belanja->nilai_ag == 0 ? number_format($belanja->nilai_real == 0 ? 0 : 100, 2, ',', '.') : number_format($belanja->nilai_real * 100 / $belanja->nilai_ag, 2, ',', '.') ?></td>
          </tr>
        <?php endif ?>
        <?php if ($item->jenis == 'bidang_urusan') : ?>
          <?php $last_urusan = substr($item->kode, 0, 1) ?>
        <?php endif ?>



        @endforeach
        <tr class="bold">
        <td colspan="4" style="text-align: right;">(SURPLUS/DEFISIT)</td>
        <?php 
        $surplus_anggaran = $pendapatan->nilai_ag - $belanja->nilai_ag;
        $surplus_realisasi = $pendapatan->nilai_real - $belanja->nilai_real;
        ?>
        <td style="text-align: right;">
          <?php if ($surplus_anggaran >= 0) : ?>
            <?php echo number_format($surplus_anggaran, 2, ',', '.') ?>
          <?php else : ?>
            <?php echo '(' . number_format(abs(-$surplus_anggaran), 2, ',', '.') . ')' ?>
          <?php endif ?>
        </td>
        <td style="text-align: right;">
          <?php if ($surplus_realisasi >= 0) : ?>
            <?php echo number_format($surplus_realisasi, 2, ',', '.') ?>
          <?php else : ?>
            <?php echo '(' . number_format(abs(-$surplus_realisasi), 2, ',', '.') . ')' ?>
          <?php endif ?>
        </td>
        <?php $sisa_anggaran = $surplus_realisasi - $surplus_anggaran ?>
        <td style="text-align: right;">
          <?php if ($sisa_anggaran >= 0) : ?>
            <?php echo number_format($sisa_anggaran, 2, ',', '.') ?>
          <?php else : ?>
            (<?php echo number_format(abs($sisa_anggaran), 2, ',', '.') ?>)
          <?php endif ?>
        </td>
        <td style="text-align: right;">
          <?php if ($surplus_anggaran == 0) : ?>
            <?php echo number_format($surplus_realisasi == 0 ? 0 : 100, 2, ',', '.') ?>
          <?php else : ?>
            <?php echo number_format($surplus_realisasi * 100 / $surplus_anggaran, 2, ',', '.') ?>
          <?php endif ?>
        </td>
      </tr>
    </tbody>
   

    </table>
    {{-- isi --}}
    
    <div style="padding-top:20px">
        <table class="table" style="width: 100%;font-size:12px;font-family:Open Sans">
            <tr>
                <td style="font-size:14px;font-family:Open Sans;margin: 2px 0px;text-align: center;" width='50%'>
                    &nbsp;
                </td>
                <td style="font-size:14px;font-family:Open Sans;margin: 2px 0px;text-align: center;" width='50%'>
                    {{ $daerah->daerah }},
                        {{ \Carbon\Carbon::parse($tanggal_ttd)->locale('id')->isoFormat('DD MMMM Y') }}
                </td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;padding-bottom: 50px;text-align: center;">
                </td>
                <td style="font-size:14px;font-family:Open Sans;padding-bottom: 50px;text-align: center;">
                    GUBERNUR KALIMANTAN BARAT
                </td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b></b></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b><u>SUTARMIDJI</u></b></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>

        </table>
    </div>

    {{-- tanda tangan --}}
    
</body>

</html>
