@extends('template.app')
@section('title', 'EDIT DPT GABUNGAN | SIMAKDA')
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
                            <input type="text" class="form-control" id="no_dpt" name="no_dpt" readonly
                                value="{{ $dpt->no_dpt }}">
                        </div>
                        <label for="tgl_dpt" class="col-md-2 col-form-label">Tanggal DPT</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_dpt" name="tgl_dpt"
                                value="{{ $dpt->tgl_dpt }}">
                        </div>
                    </div>
                    {{-- SKPD DAN NAMA SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="kd_skpd" readonly value="{{ $dpt->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_skpd" readonly
                                value="{{ nama_skpd($dpt->kd_skpd) }}">
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan">{{ $dpt->keterangan }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="simpan" class="btn btn-primary btn-md"
                            {{ $dpt->status != '0' ? 'hidden' : '' }}>Simpan</button>
                        <a href="{{ route('dpt_gabungan.index') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekening --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    RINCIAN DAFTAR PEMBAYARAN TAGIHAN
                    <button type="button" style="float: right" id="tambah_rincian" class="btn btn-success btn-md"
                        hidden>Tambah
                        DPT Unit</button>
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_pengeluaran" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>DPT Global</th>
                                <th>Unit</th>
                                <th>Nama Unit</th>
                                <th>DPT Unit</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($rincian_dpt as $item)
                                @php
                                    $total += $item->nilai;
                                @endphp
                                <tr>
                                    <td>{{ $item->no_dpt }}</td>
                                    <td>{{ $item->kd_skpd }}</td>
                                    <td>{{ nama_skpd($item->kd_skpd) }}</td>
                                    <td>{{ $item->no_dpt_unit }}</td>
                                    <td>{{ rupiah($item->nilai) }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total" class="col-md-8 col-form-label" style="text-align: right">Total</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total" name="total" value="{{ rupiah($total) }}">
                        </div>
                        {{-- <label for="sisa_kas" class="col-md-8 col-form-label" style="text-align: right">Sisa Kas</label>
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

    {{-- modul tambah dpt unit --}}
    <div id="modal_tambah" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Data DPT Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No. DPT --}}
                    <div class="mb-3 row">
                        <label for="pilih_no_dpt" class="col-md-2 col-form-label">No. DPT</label>
                        <div class="col-md-10">
                            <select name="pilih_no_dpt" class="form-control select-modal" id="pilih_no_dpt">
                                <option value="" selected disabled>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    {{-- Unit --}}
                    <div class="mb-3 row">
                        <label for="unit" class="col-md-2 col-form-label">Unit</label>
                        <div class="col-md-6">
                            <input type="text" name="unit" id="unit" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="nm_unit" id="nm_unit" class="form-control" readonly>
                        </div>
                    </div>
                    {{-- Nilai --}}
                    <div class="mb-3 row">
                        <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-10">
                            <input type="text" name="nilai" id="nilai" style="text-align: right"
                                class="form-control" readonly>
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
    @include('skpd.dpt_gabungan.js.edit')
@endsection
