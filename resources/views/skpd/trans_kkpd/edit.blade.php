@extends('template.app')
@section('title', 'EDIT KKPD | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    TRANSAKSI KKPD
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NOMOR DAN TANGGAL VOUCHER --}}
                    <div class="mb-3 row">
                        <label for="no_voucher" class="col-md-2 col-form-label">No. Voucher</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_voucher" name="no_voucher" readonly
                                value="{{ $kkpd->no_voucher }}">
                        </div>
                        <label for="tgl_voucher" class="col-md-2 col-form-label">Tanggal Voucher</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_voucher" name="tgl_voucher"
                                value="{{ $kkpd->tgl_voucher }}">
                        </div>
                    </div>
                    {{-- SKPD DAN NAMA SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="kd_skpd" readonly value="{{ $kkpd->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_skpd" readonly
                                value="{{ nama_skpd($kkpd->kd_skpd) }}">
                        </div>
                    </div>
                    {{-- NOMOR DPT --}}
                    <div class="mb-3 row">
                        <label for="no_dpt" class="col-md-2 col-form-label">Nomor DPT</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="no_dpt" readonly value="{{ $kkpd->no_dpt }}">
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan">{{ $kkpd->ket }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="simpan" class="btn btn-primary btn-md"
                            {{ $kkpd->status_upload == '1' ? 'hidden' : '' }}>Simpan</button>
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
                                <th>Kegiatan</th>
                                <th>Nama Kegiatan</th>
                                <th>Kode Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                                <th>Kode Sumber</th>
                                <th>Sumber</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($rincian_kkpd as $item)
                                @php
                                    $total += $item->nilai;
                                @endphp
                                <tr>
                                    <td>{{ $item->kd_sub_kegiatan }}</td>
                                    <td>{{ $item->nm_sub_kegiatan }}</td>
                                    <td>{{ $item->kd_rek6 }}</td>
                                    <td>{{ $item->nm_rek6 }}</td>
                                    <td>{{ rupiah($item->nilai) }}</td>
                                    <td>{{ $item->sumber }}</td>
                                    <td>{{ nama_sumber_dana($item->sumber) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total_belanja" class="col-md-8 col-form-label" style="text-align: right">Total
                            Belanja</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total_belanja" name="total_belanja" value="{{ rupiah($total) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.trans_kkpd.js.edit')
@endsection
