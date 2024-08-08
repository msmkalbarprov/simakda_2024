@extends('template.app')
@section('title', 'TAMBAH DPT | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    DAFTAR PEMBAYARAN TAGIHAN
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NOMOR DAN TANGGAL DPT --}}
                    <div class="mb-3 row">
                        <label for="no_dpt" class="col-md-2 col-form-label">No. DPT</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_dpt" name="no_dpt" readonly>
                            <input type="text" class="form-control" id="no_urut" name="no_urut" readonly hidden>
                            <input type="date" class="form-control" id="tgl_dpr" name="tgl_dpr" readonly hidden>
                        </div>
                        <label for="tgl_dpt" class="col-md-2 col-form-label">Tanggal DPT</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_dpt" name="tgl_dpt">
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
                    {{-- NOMOR DPR --}}
                    <div class="mb-3 row">
                        <label for="no_dpr" class="col-md-2 col-form-label">Nomor DPR</label>
                        <div class="col-md-10">
                            <select name="no_dpr" id="no_dpr" class="form-control select2-multiple">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($daftar_dpr as $daftar)
                                    <option value="{{ $daftar->no_dpr }}" data-nilai="{{ $daftar->nilai }}"
                                        data-tgl="{{ $daftar->tgl_dpr }}">
                                        {{ $daftar->no_dpr }} | {{ $daftar->kd_skpd }} | {{ rupiah($daftar->nilai) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- NILAI --}}
                    <div class="mb-3 row">
                        <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="nilai" readonly>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="simpan" class="btn btn-primary btn-md">Simpan</button>
                        <a href="{{ route('dpt.index') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekening --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    RINCIAN DAFTAR PENGELUARAN RILL
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_pengeluaran" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No.</th>
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
                        {{-- <label for="sisa_kas" class="col-md-8 col-form-label" style="text-align: right">Sisa Kas
                            Bank</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="sisa_kas" name="sisa_kas"
                                value="{{ rupiah($sisa_kas->terima - $sisa_kas->keluar) }}">
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.dpt.js.create')
@endsection
