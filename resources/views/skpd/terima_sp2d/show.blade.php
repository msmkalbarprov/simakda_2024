@extends('template.app')
@section('title', 'Tampil SP2D | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Data SP2D
                </div>
                <div class="card-body">
                    @csrf
                    {{-- No Cair --}}
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <a href="{{ route('terima_sp2d.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                    {{-- Nomor terima dan tanggal terima --}}
                    <div class="mb-3 row">
                        <label for="no_terima" class="col-md-2 col-form-label">Nomor Terima</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_terima" value="{{ $sp2d->no_terima }}"
                                name="no_terima" readonly>
                        </div>
                        <label for="tgl_terima" class="col-md-2 col-form-label">Tanggal Terima</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="tgl_terima" value="{{ tanggal($sp2d->tgl_kas) }}"
                                name="tgl_terima" readonly>
                        </div>
                    </div>
                    {{-- No SP2D --}}
                    <div class="mb-3 row">
                        <label for="no_sp2d" class="col-md-2 col-form-label">No SP2D</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="no_sp2d" name="no_sp2d"
                                value="{{ $sp2d->no_sp2d }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table style="width: 100%" style="border: 1px solid black">
                        <tbody>
                            <tr>
                                <td class="border1" colspan="3" style="width: 50%">{{ $daerah->provinsi }}</td>
                                <td class="border1" colspan="3" style="width: 50%">SURAT PERINTAH PENCAIRAN DANA
                                    (SP2D)<br>Nomor :
                                    {{ $no_sp2d }}</td>
                            </tr>
                            <tr>
                                <td class="kiri">Nomor SPM</td>
                                <td>:</td>
                                <td class="kanan">{{ $data_sp2d->no_spm }}</td>
                                <td>Dari</td>
                                <td>:</td>
                                <td class="kanan">KUASA BUD</td>
                            </tr>
                            <tr>
                                <td class="kiri">Tanggal</td>
                                <td>:</td>
                                <td class="kanan">{{ tanggal($data_sp2d->tgl_spm) }}</td>
                                <td>NPWP</td>
                                <td>:</td>
                                <td class="kanan"></td>
                            </tr>
                            <tr>
                                <td class="kiri bawah">Nama SKPD</td>
                                <td class="bawah">:</td>
                                <td class="kanan bawah">{{ $data_sp2d->kd_skpd }} {{ $data_sp2d->nm_skpd }}</td>
                                <td class="bawah">Tahun Anggaran</td>
                                <td class="bawah">:</td>
                                <td class="kanan bawah">{{ tahun_anggaran() }}</td>
                            </tr>
                            <tr>
                                <td class="kiri">Bank Pengirim</td>
                                <td>:</td>
                                <td colspan="4" class="kanan">PT. Bank Kalbar Cabang Utama Pontianak</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="kiri kanan">Hendaklah mencairkan / memindahbukukan dari baki
                                    Rekening
                                    Nomor
                                    1001002201</td>
                            </tr>
                            <tr>
                                <td class="kiri bawah">Uang sebesar</td>
                                <td class="bawah">:</td>
                                <td class="kanan bawah" colspan="4">Rp {{ rupiah($nilai) }} ({{ terbilang($nilai) }})
                                </td>
                            </tr>
                            <tr>
                                <td class="kiri">Kepada</td>
                                <td>:</td>
                                <td colspan="4" class="kanan">
                                    @if (($data_sp2d->jns_spp == '6' && $data_sp2d->jenis_beban == '6') || $data_sp2d->jns_spp == '5')
                                        {{ $data_sp2d->pimpinan }},
                                        {{ $data_sp2d->nmrekan }}, {{ $data_sp2d->alamat }}
                                    @else
                                        {{ $bk->nama ? $bk->nama : 'Belum Ada data Bendahara' }} -
                                        {{ $bk->jabatan ? $bk->jabatan : ' ' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="kiri">NPWP</td>
                                <td>:</td>
                                <td colspan="4" class="kanan">
                                    @if (($data_sp2d->jns_spp == '6' && $data_sp2d->jenis_beban == '6') || $data_sp2d->jns_spp == '5')
                                        {{ $data_sp2d->npwp }}
                                    @else
                                        {{ $bank->npwp ? $bank->npwp : ' ' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="kiri">No.Rekening Bank</td>
                                <td>:</td>
                                <td colspan="4" class="kanan">
                                    @if (($data_sp2d->jns_spp == '6' && $data_sp2d->jenis_beban == '6') || $data_sp2d->jns_spp == '5')
                                        {{ $data_sp2d->no_rek }}
                                    @else
                                        {{ $bank->rekening ? $bank->rekening : ' ' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="kiri">Bank Penerima</td>
                                <td>:</td>
                                <td colspan="4" class="kanan">
                                    @if (($data_sp2d->jns_spp == '6' && $data_sp2d->jenis_beban == '6') || $data_sp2d->jns_spp == '5')
                                        {{ $bank->bank ? bank($bank->bank) : 'Belum Pilih Bank' }}
                                    @else
                                        {{ $bank->bank ? bank($bank->bank) : 'Belum Pilih Bank' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="kiri">Keperluan Untuk</td>
                                <td>:</td>
                                <td colspan="4" class="kanan">{{ $data_sp2d->keperluan }}
                                    <br>
                                    @if ($data_sp2d->jns_spp == '6')
                                        {{ right($kd_prog, 2) }} - {{ $nm_prog }}<br>
                                        {{ right($kd_kegi, 2) }} - {{ $nm_kegi }}
                                    @else
                                        {{ right($kd_prog, 2) }} {{ $nm_prog }}<br>
                                        {{ right($kd_kegi, 2) }} {{ $nm_kegi }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="kiri">Pagu Anggaran</td>
                                <td>:</td>
                                <td colspan="4" class="kanan">
                                    @if ($data_sp2d->jns_spp == '1')
                                    @else
                                        Rp {{ rupiah($pagu) }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width: 100%" class="table table-bordered border-dark mb-0">
                        <tbody>
                            <tr>
                                <td class="center"><b>NO</b></td>
                                <td class="center"><b>KODE KEGIATAN/SUB KEGIATAN</b></td>
                                <td class="center"><b>URAIAN</b></td>
                                <td class="center"><b>JUMLAH<br>(Rp)</b></td>
                            </tr>
                            <tr>
                                <td class="center"><b>1</b></td>
                                <td class="center"><b>2</b></td>
                                <td class="center"><b>3</b></td>
                                <td class="center"><b>4</b></td>
                            </tr>
                            @if (in_array($data_sp2d->jns_spp, ['1', '2']))
                                <tr>
                                    <td style="text-align: center">1</td>
                                    <td>{{ $data_sp2d->kd_skpd }}</td>
                                    <td>{{ $data_sp2d->nm_skpd }}</td>
                                    <td style="text-align: right">{{ rupiah($total_kegiatan->nilai) }}</td>
                                </tr>
                            @else
                                @foreach ($sub_kegiatan as $sp2d)
                                    <tr>
                                        <td style="text-align: center">{{ $loop->iteration }}</td>
                                        <td>{{ $sp2d->kd_rek }}</td>
                                        <td>{{ $sp2d->nm_rek }}</td>
                                        <td style="text-align: right">{{ rupiah($sp2d->nilai) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="3" style="text-align: right"><b>JUMLAH</b></td>
                                <td style="text-align: right"><b>{{ rupiah($total_kegiatan->nilai) }}</b></td>
                            </tr>
                            <tr>
                                <td colspan="4">Potongan-potongan</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width: 100%" class="table table-bordered border-dark mb-0">
                        <tbody>
                            <tr>
                                <td class="center"><b>NO</b></td>
                                <td class="center"><b>Uraian (No.Rekening)</b></td>
                                <td class="center"><b>Jumlah (Rp)</b></td>
                                <td class="center"><b>Keterangan</b></td>
                            </tr>
                            @php
                                $jumlah_pot1 = 0;
                            @endphp
                            @foreach ($potongan1 as $potongan)
                                @php
                                    $jumlah_pot1 += $potongan->nilai;
                                @endphp
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ dotrek($potongan->kd_rek6) }} {{ $potongan->nm_rek6 }}</td>
                                    <td style="text-align: right">{{ rupiah($potongan->nilai) }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                            @for ($i = count($potongan1); $i < 4; $i++)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endfor
                            <tr>
                                <td colspan="2" style="text-align: right"><b>JUMLAH</b></td>
                                <td style="text-align: right"><b>{{ rupiah($jumlah_pot1) }}</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4">Informasi <i>(tidak mengurangi jumlah pembayaran SP2D)</i></td>
                            </tr>
                            <tr>
                                <td class="center"><b>NO</b></td>
                                <td class="center"><b>Uraian (No.Rekening)</b></td>
                                <td class="center"><b>Jumlah (Rp)</b></td>
                                <td class="center"><b>Keterangan</b></td>
                            </tr>
                            @php
                                $jumlah_pot2 = 0;
                            @endphp
                            @foreach ($potongan2 as $potongan)
                                @php
                                    $jumlah_pot2 += $potongan->nilai;
                                @endphp
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ dotrek($potongan->kd_rek6) }} {{ $potongan->nm_rek6 }}</td>
                                    <td style="text-align: right">{{ rupiah($potongan->nilai) }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                            @for ($i = count($potongan2); $i < 4; $i++)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endfor
                            <tr>
                                <td colspan="2" style="text-align: right"><b>JUMLAH</b></td>
                                <td style="text-align: right"><b>{{ rupiah($jumlah_pot2) }}</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4"><b>SP2D yang Dibayarkan</b></td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width: 100%">
                        <tbody>
                            <tr>
                                <td colspan="2" class="kiri bawah">Jumlah yang Diminta</td>
                                <td class="bawah">Rp</td>
                                <td style="text-align: right" class="kanan bawah">{{ rupiah($total_kegiatan->nilai) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="kiri bawah">Jumlah Potongan</td>
                                <td class="bawah">Rp</td>
                                <td style="text-align: right" class="kanan bawah">
                                    {{ rupiah($jumlah_pot1 + $jumlah_pot2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="kiri bawah"><b>Jumlah yang Dibayarkan</b></td>
                                <td class="bawah"><b>Rp</b></td>
                                <td style="text-align: right" class="kanan bawah">
                                    <b>{{ rupiah($total_kegiatan->nilai - ($jumlah_pot1 + $jumlah_pot2)) }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="kanan kiri bawah"><b>Uang Sejumlah :
                                        ({{ terbilang($total_kegiatan->nilai - ($jumlah_pot1 + $jumlah_pot2)) }})</b></td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width: 100%" id="ttd" style="border: 1px solid black">
                        <tr>
                            <td colspan="6" class="kanan kiri" style="height: 20px"></td>
                        </tr>
                        <tr>
                            <td class="kiri">Lembar 1</td>
                            <td>:</td>
                            <td>Bank Yang Ditunjuk</td>
                            <td></td>
                            <td style="text-align: center" class="kanan"><b>Pontianak,
                                    {{ tanggal($data_sp2d->tgl_sp2d) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td class="kiri">Lembar 2</td>
                            <td>:</td>
                            <td>Pengguna Anggaran/Kuasa Pengguna Anggaran</td>
                            <td></td>
                            <td style="text-align: center" class="kanan"><b>Kuasa Bendahara Umum Daerah</b></td>
                        </tr>
                        <tr>
                            <td class="kiri">Lembar 3</td>
                            <td>:</td>
                            <td>Arsip Kuasa BUD</td>
                            <td></td>
                            <td style="text-align: center" class="kanan"><b>{{ $bud->jabatan }}</b></td>
                        </tr>
                        <tr>
                            <td class="kiri">Lembar 4</td>
                            <td>:</td>
                            <td>Pihak Penerima</td>
                            <td></td>
                            <td style="text-align: center" class="kanan"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="kanan kiri" style="height: 50px"></td>
                        </tr>
                        <tr>
                            <td class="kiri"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: center" class="kanan"><b><u>{{ $bud->nama }}</u></b></td>
                        </tr>
                        <tr>
                            <td class="kiri bawah"></td>
                            <td class="bawah"></td>
                            <td class="bawah"></td>
                            <td class="bawah"></td>
                            <td style="text-align: center" class="kanan bawah"><b>NIP. {{ $bud->nip }}</b></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <style>
        .center {
            text-align: center
        }

        .border1 {
            border: 1px solid black;
            text-align: center
        }

        .kanan {
            border-right: 1px solid black;
        }

        .kiri {
            border-left: 1px solid black;
        }

        .bawah {
            border-bottom: 1px solid black;
        }
    </style>
    {{-- @include('penatausahaan.pengeluaran.pencairan_sp2d.js.show'); --}}
@endsection
