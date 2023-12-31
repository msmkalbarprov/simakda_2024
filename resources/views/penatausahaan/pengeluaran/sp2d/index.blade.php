@extends('template.app')
@section('title', 'SP2D | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-md btn-success tipe" data-jenis="cair">Hijau : CAIR SP2D</button>
                    <button class="btn btn-md btn-danger tipe" data-jenis="batal">Merah : BATAL SP2D</button>
                    <button class="btn btn-md tipe" style="background-color: #bf00ff;color:white" data-jenis="nampung">Ungu
                        :
                        PENAMPUNG</button>
                    <input type="hidden" name="tipe" id="tipe">
                    <a href="{{ route('sp2d.create') }}" id="tambah_sp2d" class="btn btn-primary"
                        style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="sp2d" class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 100px;text-align:center">No.SP2D</th>
                                        <th style="width: 100px;text-align:center">Tanggal Transfer</th>
                                        <th style="width: 50px;text-align:center">No.Penguji</th>
                                        <th style="width: 50px;text-align:center">No.SPM</th>
                                        <th style="width: 150px;text-align:center">Tanggal</th>
                                        <th style="width: 150px;text-align:center">SKPD</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    {{-- modul batal sp2d --}}
    <div id="sp2d_batal" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">KETERANGAN PEMBATALAN SP2D</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No SP2D --}}
                    <div class="mb-3 row">
                        <label for="no_sp2d" class="col-md-12 col-form-label">No SP2D</label>
                        <div class="col-md-12">
                            <input type="text" readonly class="form-control" id="no_sp2d_batal" name="no_sp2d_batal">
                            <input type="text" readonly class="form-control" id="beban_batal" name="beban_batal" hidden>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="no_spm" class="col-md-2 col-form-label">No SPM</label>
                        <div class="col-md-12">
                            <input type="text" readonly class="form-control" id="no_spm_batal" name="no_spm_batal">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="no_spp" class="col-md-2 col-form-label">No SPP</label>
                        <div class="col-md-12">
                            <input type="text" readonly class="form-control" id="no_spp_batal" name="no_spp_batal">
                            <input type="text" readonly class="form-control" id="status_bud" name="status_bud" hidden>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="keterangan_batal" class="col-md-12 col-form-label">KETERANGAN PEMBATALAN SP2D</label>
                        <div class="col-md-12">
                            <textarea type="text" class="form-control" id="keterangan_batal" name="keterangan_batal"></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-md btn-danger" id="input_batal"><i
                                    class="uil-ban"></i>Batal SP2D</button>
                            <button type="button" class="btn btn-md btn-warning" data-bs-dismiss="modal"><i
                                    class="fa fa-undo"></i>Keluar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modul cetak --}}
    <div id="modal_cetak" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cetak SP2D</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No SP2D --}}
                    <div class="mb-3 row">
                        <label for="no_sp2d" class="col-md-4 col-form-label">No SP2D</label>
                        <div class="col-md-8">
                            <input type="text" readonly class="form-control" id="no_sp2d" name="no_sp2d">
                            <input type="text" hidden class="form-control" id="beban" name="beban">
                            <input type="text" hidden class="form-control" id="kd_skpd" name="kd_skpd">
                        </div>
                    </div>
                    {{-- Penandatangan BUD --}}
                    <div class="mb-3 row">
                        <label for="bendahara" class="col-md-4 col-form-label">Penandatangan BUD</label>
                        <div class="col-md-8">
                            <select name="ttd_bud" class="form-control" id="ttd_bud">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($ttd1 as $ttd)
                                    <option value="{{ $ttd->nip }}" data-nama="{{ $ttd->nama }}">
                                        {{ $ttd->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Penandatangan I --}}
                    <div class="mb-3 row">
                        <label for="ttd1" class="col-md-4 col-form-label">Penandatangan I</label>
                        <div class="col-md-8">
                            <select name="ttd1" class="form-control" id="ttd1">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($ttd1 as $ttd)
                                    <option value="{{ $ttd->nip }}" data-nama="{{ $ttd->nama }}">
                                        {{ $ttd->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Penandatangan II --}}
                    <div class="mb-3 row">
                        <label for="ttd2" class="col-md-4 col-form-label">Penandatangan II</label>
                        <div class="col-md-8">
                            <select name="ttd2" class="form-control" id="ttd2">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($ttd2 as $ttd)
                                    <option value="{{ $ttd->nip }}" data-nama="{{ $ttd->nama }}">
                                        {{ $ttd->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Jenis --}}
                    <div class="mb-3 row">
                        <label for="jenis" class="col-md-4 col-form-label">Jenis</label>
                        <div class="col-md-8">
                            <select name="jenis" class="form-control" id="jenis">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                <option value="1">Normal</option>
                                <option value="2">Keterangan Panjang</option>
                                <option value="3">Baris Manual</option>
                            </select>
                        </div>
                    </div>
                    {{-- KOP --}}
                    <div class="mb-3 row">
                        <label for="kop" class="col-md-4 col-form-label">KOP</label>
                        <div class="col-md-8">
                            <select name="kop" class="form-control" id="kop">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                <option value="0">Tanpa KOP</option>
                                <option value="1">Dengan KOP</option>
                            </select>
                        </div>
                    </div>
                    {{-- Baris --}}
                    <div class="mb-3 row">
                        <label for="baris" class="col-md-4 col-form-label">Baris</label>
                        <div class="col-md-8">
                            <input type="number" value="15" min="1" class="form-control" id="baris"
                                name="baris">
                        </div>
                    </div>
                    {{-- Margin Atas --}}
                    <div class="mb-3 row">
                        <label for="baris" class="col-md-4 col-form-label">Margin Atas</label>
                        <div class="col-md-8">
                            <input type="number" value="10" min="1" class="form-control" id="margin_atas"
                                name="margin_atas">
                        </div>
                    </div>
                    {{-- SP2D, lampiran --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-md sp2d orange" data-jenis="pdf"><b>SP2D</b></button>
                            <button type="button" class="btn btn-md lampiran orange"
                                data-jenis="pdf"><b>Lampiran</b></button>
                            <button type="button" class="btn btn-md lampiran_lama orange" id="lampiran_lama"
                                data-jenis="pdf"><b>Lampiran
                                    Lama</b></button>
                            <button type="button" class="btn btn-md kelengkapan orange"
                                data-jenis="pdf"><b>Kelengkapan</b></button>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12" style="text-align: right">
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CALLBACK SP2D --}}
    <div id="modal_callback" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaksi SP2D Bank Kalbar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            DETAIL SP2D
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label for="no_sp2d_callback" class="form-label">No. SP2D*</label>
                                    <input type="text" readonly class="form-control" id="no_sp2d_callback"
                                        name="no_sp2d_callback">
                                    <input type="text" readonly class="form-control" id="no_spm_callback"
                                        name="no_spm_callback" hidden>
                                </div>
                                <div class="col-md-4">
                                    <label for="tgl_transaksi" class="form-label">Tanggal Transaksi*</label>
                                    <input type="text" readonly class="form-control" id="tgl_transaksi"
                                        name="tgl_transaksi">
                                </div>
                                <div class="col-md-4">
                                    <label for="nilai_transaksi" class="form-label">Nilai Transaksi*</label>
                                    <input type="text" readonly class="form-control" id="nilai_transaksi"
                                        name="nilai_transaksi" style="text-align: right">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label for="npwp" class="form-label">NPWP*</label>
                                    <input type="text" readonly class="form-control" id="npwp" name="npwp">
                                </div>
                                <div class="col-md-4">
                                    <label for="skpd" class="form-label">SKPD*</label>
                                    <input type="text" readonly class="form-control" id="skpd" name="skpd">
                                </div>
                                <div class="col-md-4">
                                    <label for="penerima" class="form-label">Penerima*</label>
                                    <input type="text" readonly class="form-control" id="penerima" name="penerima">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label for="rekening" class="form-label">Rekening*</label>
                                    <input type="text" readonly class="form-control" id="rekening" name="rekening">
                                </div>
                                <div class="col-md-4">
                                    <label for="bank" class="form-label">Bank*</label>
                                    <input type="text" readonly class="form-control" id="bank" name="bank">
                                </div>
                                <div class="col-md-4">
                                    <label for="jumlah_bayar" class="form-label">Jumlah Dibayar*</label>
                                    <input type="text" readonly class="form-control" id="jumlah_bayar"
                                        name="jumlah_bayar" style="text-align: right">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="keperluan" class="form-label">Keperluan*</label>
                                    <input type="text" readonly class="form-control" id="keperluan" name="keperluan">
                                </div>
                                <div class="col-md-6">
                                    <label for="status_callback" class="form-label">Status*</label>
                                    <input type="text" readonly class="form-control" id="status_callback"
                                        name="status_callback">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            DETAIL POTONGAN MPN
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" id="detail_potongan_mpn">
                                <thead>
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th>Nilai Potongan</th>
                                        <th>ID Billing</th>
                                        <th>NTPN</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            DETAIL POTONGAN NON MPN
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" id="detail_potongan_nonmpn">
                                <thead>
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th>Kode Map</th>
                                        <th>Nilai Potongan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-12" style="text-align: right">
                            <button type="button" id="callback" class="btn btn-md btn-primary">Update</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <style>
        .orange {
            background-color: #FFA500
        }
    </style>
    @include('penatausahaan.pengeluaran.sp2d.js.cetak')
@endsection
