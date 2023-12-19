@extends('template.app')
@section('title', 'TAMBAH TRANSAKSI KKPD | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Transaksi KKPD
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NOMOR DAN TANGGAL VOUCHER --}}
                    <div class="mb-3 row">
                        <label for="no_voucher" class="col-md-2 col-form-label">No. Voucher</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_voucher" name="no_voucher" readonly>
                        </div>
                        <label for="tgl_voucher" class="col-md-2 col-form-label">Tanggal Voucher</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_voucher" name="tgl_voucher">
                        </div>
                    </div>
                    {{-- SKPD DAN NAMA SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="kd_skpd" readonly
                                value="{{ Auth::user()->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_skpd" readonly
                                value="{{ nama_skpd(Auth::user()->kd_skpd) }}">
                        </div>
                    </div>
                    {{-- NOMOR DPT --}}
                    {{-- <div class="mb-3 row">
                        <label for="no_dpt" class="col-md-2 col-form-label">Nomor DPT</label>
                        <div class="col-md-10">
                            <select name="no_dpt" id="no_dpt" class="form-control select2-multiple">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($daftar_dpt as $daftar)
                                    <option value="{{ $daftar->no_dpt }}" data-nilai="{{ $daftar->nilai }}">
                                        {{ $daftar->no_dpt }} | {{ $daftar->kd_skpd }} | {{ rupiah($daftar->nilai) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    {{-- NO SP2D  --}}
                    <div class="mb-3 row">
                        <label for="no_sp2d" class="col-md-2 col-form-label">No. SP2D</label>
                        <div class="col-md-10">
                            <select name="no_sp2d" id="no_sp2d" class="form-control select2-multiple">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($daftar_sp2d as $daftar)
                                    <option value="{{ $daftar->no_sp2d }}" data-no_spp="{{ $daftar->no_spp }}">
                                        {{ $daftar->no_sp2d }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="text" class="form-control" id="no_spp" readonly hidden>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan"></textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="simpan" class="btn btn-primary btn-md">Simpan</button>
                        <a href="{{ route('trans_kkpd.index') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekening --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    RINCIAN TRANSAKSI KKPD
                    <button type="button" style="float: right" id="tambah_rincian" class="btn btn-success btn-md">Tambah
                        Rincian</button>
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_pengeluaran" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kegiatan</th>
                                <th>Nama Kegiatan</th>
                                <th>Kode Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                                <th>Kode Sumber</th>
                                <th>Sumber</th>
                                <th>Kode Bukti</th>
                                <th>Bukti</th>
                                <th>Uraian</th>
                                <th>Kode Pembayaran</th>
                                <th>Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total_belanja" class="col-md-8 col-form-label" style="text-align: right">Total
                            Belanja</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total_belanja" name="total_belanja">
                        </div>
                        <label for="sisa_kas" class="col-md-8 col-form-label" style="text-align: right">Sisa Kas</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="sisa_kas" name="sisa_kas"
                                value="{{ rupiah($sisa_kas->terima - $sisa_kas->keluar) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_tambah" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Rincian DPT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No. LPJ --}}
                    <div class="mb-3 row">
                        <label for="pilih_rincian_dpt" class="col-md-2 col-form-label">Rincian DPT</label>
                        <div class="col-md-10">
                            <select name="pilih_rincian_dpt" class="form-control select-modal" id="pilih_rincian_dpt">
                                <option value="" selected disabled>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" id="pilih" class="btn btn-md btn-success">Pilih</button>
                            <button type="button" class="btn btn-md btn-warning"
                                data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.trans_kkpd.js.create')
@endsection
