<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CETAK DPR</title>

    <style>
        #rincian1>tbody>tr>td {
            vertical-align: top;
            font-size: 14px
        }

        .rincian>tbody>tr>td {
            font-size: 14px
        }

        #rincian {
            border-collapse: collapse
        }
    </style>
</head>

<body>
    <table style="border-collapse:collapse;font-family: Open Sans; font-size:12px" width="100%" align="center"
        border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td rowspan="5" align="left" width="7%">
                <img src="{{ asset('template/assets/images/' . $header->logo_pemda_hp) }}" width="75"
                    height="100" />
            </td>
            <td align="left" style="font-size:16px" width="93%">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px" width="93%"><strong>PEMERINTAH
                    {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>{{ nama_skpd($kd_skpd) }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>TAHUN ANGGARAN {{ tahun_anggaran() }}</strong></td>
        </tr>
        <tr>
            <td align="left" style="font-size:16px"><strong>&nbsp;</strong></td>
        </tr>
    </table>
    <hr>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif;text-align:center">
        <tr>
            <td>
                <b>
                    DAFTAR PENGELUARAN RIIL PEMBAYARAN
                    @if ($jenis == '1')
                        PERJALANAN DINAS
                    @elseif ($jenis == '2')
                        BELANJA MODAL
                    @elseif ($jenis == '3')
                        BELANJA BARANG DAN JASA
                    @endif
                    DENGAN MENGGUNAKAN KKPD
                </b>
            </td>
        </tr>
    </table>

    <br>

    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">
        <tr>
            <td colspan="3">
                Yang bertandatangan di bawah ini:
            </td>
        </tr>
        <tr>
            <td style="width: 20%">Nama</td>
            <td>:</td>
            <td>{{ $pptk->nama }}</td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $pptk->nip }}</td>
        </tr>
        <tr>
            <td>Pangkat/Gol. Ruang</td>
            <td>:</td>
            <td>{{ $pptk->pangkat }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $pptk->jabatan }}</td>
        </tr>
        <tr>
            <td>SKPD</td>
            <td>:</td>
            <td>{{ nama_skpd($dpr->kd_skpd) }}</td>
        </tr>
        <tr>
            <td>Nomor KKPD</td>
            <td>:</td>
            <td>{{ $dpr->no_kkpd }}</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">
        <tr>
            <td>Berdasarkan pembayaran dengan KKPD dalam rangka penggunaan UP, dengan ini kami menyatakan dengan
                sesungguhnya bahwa :</td>
        </tr>
        <tr>
            <td>1. Rincian pengeluaran riil pembayaran @if ($dpr->jenis_belanja == '1')
                    perjalanan dinas
                @elseif ($dpr->jenis_belanja == '2')
                    belanja modal
                @elseif ($dpr->jenis_belanja == '3')
                    belanja barang/jasa
                @endif dengan menggunakan
                KKPD sebagai berikut:</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif" border="1" id="rincian">
        <thead>
            <tr>
                <th>No</th>
                <th>Uraian Pengeluaran</th>
                <th>Jenis Belanja</th>
                <th colspan="7">Pembebanan Anggaran</th>
                <th colspan="2">Bukti Pembelian/Pembayaran</th>
                <th>Jumlah</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Kode sub kegiatan</th>
                <th>Kode akun</th>
                <th>Kode kelompok</th>
                <th>Kode jenis</th>
                <th>Kode objek</th>
                <th>Kode rincian objek</th>
                <th>Kode sub rincian objek</th>
                <th>Ya</th>
                <th>Tidak</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($detail_dpr as $detail)
                @php
                    $total += $detail->nilai;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->uraian }}</td>
                    <td>{{ (($detail->jenis_belanja == 1 ? 'Perjalanan Dinas' : $detail->jenis_belanja == 2) ? 'Belanja Modal' : $detail->jenis_belanja == 3) ? 'Belanja Barang/Jasa' : '' }}
                    </td>
                    <td>{{ $detail->kd_sub_kegiatan }}</td>
                    <td style="text-align: center">{{ Str::substr($detail->kd_rek6, 0, 1) }}</td>
                    <td style="text-align: center">{{ Str::substr($detail->kd_rek6, 1, 1) }}</td>
                    <td style="text-align: center">{{ Str::substr($detail->kd_rek6, 2, 2) }}</td>
                    <td style="text-align: center">{{ Str::substr($detail->kd_rek6, 4, 2) }}</td>
                    <td style="text-align: center">{{ Str::substr($detail->kd_rek6, 6, 2) }}</td>
                    <td style="text-align: center">{{ Str::substr($detail->kd_rek6, 8, 4) }}</td>
                    <td style="text-align: center">{!! $detail->bukti == '1' ? '&#10004' : '' !!}</td>
                    <td style="text-align: center">{!! $detail->bukti == '2' ? '&#10004' : '' !!}</td>
                    <td style="text-align: right">{{ rupiah($detail->nilai) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="12" style="text-align: center"><b>Jumlah</b></td>
                <td style="text-align: right"><b>{{ rupiah($total) }}</b></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">
        <tr>
            <td>2. Jumlah uang tersebut pada angka 1 di atas benar-benar dikeluarkan untuk pembayaran
                @if ($dpr->jenis_belanja == '1')
                    perjalanan dinas
                @elseif ($dpr->jenis_belanja == '2')
                    belanja modal
                @elseif ($dpr->jenis_belanja == '3')
                    belanja barang/jasa
                @endif SKPD/Unit SKPD dengan menggunakan KKPD dan apabila di kemudian hari terdapat
                kelebihan
                atas
                pembayaran, kami bersedia untuk menyetorkan kelebihan tersebut ke Rekening Kas Umum Daerah.
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">
        <tr>
            <td>Demikian pernyataan ini kami buat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.</td>
        </tr>
    </table>
    <br>
    <br>

    <div style="padding-top:20px">
        <table class="table rincian" style="width: 100%;font-family:'Open Sans', Helvetica,Arial,sans-serif">

            <tr>
                <td style="width: 50%"></td>
                <td style="margin: 2px 0px;text-align: center">
                    {{-- {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM Y') }}
                    @endif --}}
                    Pontianak, {{ tanggal($dpr->tgl_dpr) }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-bottom: 50px;text-align: center">
                    {{ $pptk->jabatan }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center">
                    <b><u>{{ $pptk->nama }}</u></b> <br>
                    {{ $pptk->pangkat }} <br>
                    NIP. {{ $pptk->nip }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
