<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .unbold {
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div style="text-align: left;margin-top:20px">
        <h5 style="margin: 2px 0px">PEMERINTAH PROVINSI KALIMANTAN BARAT</h5>
        @if ($beban == '4')
            <h5 style="margin: 2px 0px">{{ $data->nm_skpd }}</h5>
        @elseif ($beban == '6')
            <h5 style="margin: 2px 0px">SKPD {{ $data->nm_skpd }}</h5>
        @endif
        <h5 style="margin: 2px 0px">TAHUN ANGGARAN {{ $tahun_anggaran }}</h5>
        <div style="clear: both"></div>
    </div>
    <hr>
    <div style="text-align: center">
        @if ($beban == '4')
            <h5 style="margin: 2px 0px;text-decoration:underline">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK</h5>
        @elseif ($beban == '5')
            <h5 style="margin: 2px 0px;text-decoration:underline">SURAT PERNYATAAN TANGGUNG JAWABAN BELANJA</h5>
        @else
            <h5 style="margin: 2px 0px;text-decoration:underline">SURAT PERNYATAAN PENGAJUAN SPP -
                {{ strtoupper($lcbeban) }}</h5>
            <h5 style="margin: 2px 0px;text-decoration:underline">Nomor : {{ $no_spp }}</h5>
        @endif
    </div>
    <br>
    <div style="text-align: justify">
        @if ($beban == '4')
            <h5 style="margin: 2px 0px" class="unbold">Yang Bertanda tangan di bawah ini:</h5>
            <table class="table" style="width: 100%">
                <tr>
                    <td>Nama</td>
                    <td style="padding-left: 50px">{{ $cari_bendahara->nama }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td style="padding-left: 50px">{{ $cari_bendahara->nip }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td style="padding-left: 50px">{{ $cari_bendahara->jabatan }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">1.</td>
                    <td style="padding-left:50px;text-align:justify">
                        <h5 style="margin: 2px 0px" class="unbold">Perhitungan yang terdapat pada Daftar Perhitungan
                            Tambahan Penghasilan bagi PNS di Lingkungan Pemerintah PROVINSI KALIMANTAN BARAT
                            {{ strtoupper($lcbeban) }} bulan {{ bulan($data->bulan) }} {{ $tahun_anggaran }} bagi
                            {{ $data->nm_skpd }} telah dhitung dengan benar dan berdasarkan daftar hadir kerja Pegawai
                            Negeri Sipil Daerah pada {{ $data->nm_skpd }}</h5>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">2.</td>
                    <td style="padding-left:50px;text-align:justify">
                        <h5 class="unbold" style="margin: 2px 0px">Apabila dikemudian hari terdapat kelebihan atas
                            pembayaran {{ strtoupper($lcbeban) }} tersebut kami bersedia untuk menyetorkan kelebihan
                            tersebut ke Kas Daerah</h5>
                    </td>
                </tr>
            </table>
        @elseif ($beban == '5')
            <table class="table" style="width: 100%">
                <tr>
                    <td>1. OPD</td>
                    <td>:</td>
                    <td>{{ $kd_skpd }} - {{ $data->nm_skpd }}</td>
                </tr>
                <tr>
                    <td>2. Satuan Kerja</td>
                    <td>:</td>
                    <td>{{ $kd_skpd }} - {{ $data->nm_skpd }}</td>
                </tr>
                <tr>
                    <td>3. Tanggal/NO.DPA</td>
                    <td>:</td>
                    <td>
                        {{ $data_dpa->tgl_dpa == ''? 'Belum ada Tanggal DPA': \Carbon\Carbon::parse($data_dpa->tgl_dpa)->locale('id')->isoFormat('DD MMMM Y') }}
                        - {{ $data_dpa->no_dpa }}
                    </td>
                </tr>
                <tr>
                    <td>4. Tahun Anggaran</td>
                    <td>:</td>
                    <td>{{ $tahun_anggaran }}</td>
                </tr>
                <tr>
                    <td>5. Jumlah Belanja</td>
                    <td>:</td>
                    <td>{{ rupiah($data->nilai) }}</td>
                </tr>
            </table>
        @elseif($beban == '6')
            <h5 style="margin: 2px 0px" class="unbold">Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - LS
                {{ strtoupper($lcbeban) }} Nomor
                {{ $no_spp }} Tanggal {{ $data->tgl_spp }} yang kami ajukan sebesar {{ rupiah($data->nilai) }}
                ({{ terbilang($data->nilai) }})
            </h5>
            <h5 style="margin: 2px 0px" class="unbold">Untuk Keperluan OPD : {{ $data->nm_skpd }} Tahun Anggaran
                {{ $tahun_anggaran }}</h5>
            <h5 style="margin: 2px 0px" class="unbold">Dengan ini menyatakan sebenarnya bahwa :</h5>
        @endif
    </div>
    <div>
        @if ($beban == '4')
            <h5 class="unbold" style="margin: 8px 0px">Demikian pernyataan ini kami buat dengan sebenar-benarnya.</h5>
        @elseif ($beban == '5')
            <h5 style="margin: 2px 0px;text-align:justify" class="unbold">Yang bertanda tangan di bawah ini adalah
                {{ $cari_bendahara->jabatan }} Satuan Kerja
                {{ $data->nm_skpd }} Menyatakan bahwa saya bertanggung jawab penuh atas segala pengeluaran-pengeluaran
                yang telah dibayar lunas oleh Bendahara Pengeluaran kepada yang berhak menerima, sebagaimana tertera
                dalam Laporan Pertanggung Jawaban Tambah Uang di sampaikan oleh Bendahara Pengeluaran</h5>
            <h5 style="margin: 2px 0px;text-align:justify" class="unbold">Bukti-bukti belanja tertera dalam Laporan
                Pertanggung Jawaban
                Tambah Uang disimpan sesuai
                ketentuan yang
                berlaku pada sistem Satuan Kerja {{ $data->nm_skpd }} untuk kelengkapan administrasi dan keperluan
                pemeriksaan aparat pengawasan Fungsional</h5>
            <h5 style="margin: 2px 0px;text-align:justify" class="unbold" style="margin: 8px 0px">Demikian pernyataan
                ini kami buat dengan
                sebenar-benarnya.</h5>
        @elseif ($beban == '6')
            <table class="table" style="width: 100%">
                <tr>
                    <td style="padding-left: 20px">1.</td>
                    <td style="padding-left: 50px;text-align:justify">Jumlah Pembayaran Langsung (LS)
                        {{ $lcbeban }} tersebut di atas
                        akan dipergunakan untuk
                        keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">2.</td>
                    <td style="padding-left: 50px;text-align:justify">Jumlah Pembayaran Langsung (LS)
                        {{ $lcbeban }} tersebut tidak
                        akan dipergunakan untuk
                        membiayai
                        pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                        harus dilakasanakan dengan Pembayaran Langsung (UP/GU/TU/LS-Gaji)</td>
                </tr>
            </table>
            <h5 class="unbold" style="margin: 2px 0px">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan
                pengajuan
                SPP-LS {{ $lcbeban }} OPD kami</h5>
        @endif
    </div>
    {{-- tanda tangan --}}
    <div style="padding-top:20px">
        <table class="table" style="width: 100%">
            <tr>
                <td style="margin: 2px 0px;text-align: center;padding-left:500px">
                    {{ $daerah->daerah }},
                    @if ($tanpa == 1)
                        ______________{{ $tahun_anggaran }}
                    @else
                        {{ \Carbon\Carbon::parse($data->tgl_spp)->locale('id')->isoFormat('DD MMMM Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 50px;text-align: center;padding-left:500px">
                    {{ $cari_bendahara->jabatan }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px">{{ $cari_bendahara->nama }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px">{{ $cari_bendahara->pangkat }}</td>
            </tr>
            <tr>
                <td style="text-align: center;padding-left:500px">NIP. {{ $cari_bendahara->nip }}</td>
            </tr>

        </table>
    </div>
</body>

</html>
