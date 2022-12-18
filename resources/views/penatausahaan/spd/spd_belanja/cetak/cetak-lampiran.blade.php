<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lampiran SPD</title>
  <style>
    /* Avoid repetitive header */
    /* thead { display: table-row-group; } */
    #header {
      text-align: center;
      font-size: 12px;
    }

    #rincian-spd,
    #rincian-spd th,
    #rincian-spd td {
      border-collapse: collapse;
      border: 1px solid black;
      padding: 4px;
    }

    #rincian-spd tr td:first-child {
      text-align: center;
    }

    #rincian-spd {
      font-size: 14px;
    }

    .text-bold {
      font-weight: bold;
    }

    .spd {
      font-size: 14px;
    }

    #info-spd {
      border-collapse: collapse;
    }

    #info-spd tr td:nth-child(2) {
      padding-left: 8px;
      padding-right: 8px;
    }

    .number {
      text-align: right;
    }

    .content-text {
      font-size: 14px;
    }

    #ttd {
      width: 100%;
    }

    #ttd td {
      text-align: center;
    }

    #ttd tr>td:first-child {
      width: 60%;
    }
  </style>
</head>

<body>
  <div id="header">
  PEMERINTAH PROVINSI KALIMANTAN BARAT<br />
    PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH<br />
    NOMOR {{ $nospd }}<br />
    TENTANG<br />
    SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH<br />
    TAHUN ANGGARAN {{ tahun_anggaran() }}<br />
  </div>
  <br />
  <br />
  <div class="spd">LAMPIRAN SURAT PENYEDIAAN DANA</div>
  <br />
  <table class="spd" id="info-spd">
    <tbody>
      <tr>
        <td>NOMOR SPD </td>
        <td>:</td>
        <td>{{ $nospd }}</td>
      </tr>
      <tr>
        <td>TANGGAL</td>
        <td>:</td>
        <td>{{ tanggal($data->tgl_spd) }}</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>SKPD</td>
        <td>:</td>
        <td>{{ $data->nm_skpd }}</td>
      </tr>
      <tr>
        <td>PERIODE BULAN</td>
        <td>:</td>
        <td>{{ getMonths()[$data->bulan_awal] }} s/d {{ getMonths()[$data->bulan_akhir] }}</td>
      </tr>
      <tr>
        <td>TAHUN ANGGARAN</td>
        <td>:</td>
        <td>2022</td>
      </tr>
      <tr>
        <td>NOMOR DPA-SKPD</td>
        <td>:</td>
        <td>{{ $no_dpa->no_dpa }}</td>
      </tr>
    </tbody>
  </table>
  <br />
  <table id="rincian-spd">
    <thead>
      <tr>
        <th>No.</th>
        <th colspan="2">Kode, dan Nama Program, Kegiatan dan Sub Kegiatan</th>
        <th>ANGGARAN</th>
        <th>AKUMULASI SPD SEBELUMNYA</th>
        <th>JUMLAH SPD PERIODE INI</th>
        <th>SISA ANGGARAN</th>
      </tr>
      <tr>
        <th>1</th>
        <th colspan="2">2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6 = 3 - 4 - 5</th>
      </tr>
    </thead>
    <tbody>
   
    </tbody>
  </table>
  <br /><br />
  <div class="content-text">Jumlah Penyediaan Dana Rp</div>
  <div class="content-text"><i>()</i></div>
  <br /><br /><br />
  <table id="ttd">
    <tbody>
      <tr>
        <td></td>
        <td>
          <div>Ditetapkan di Sungai Raya</div>
        
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>