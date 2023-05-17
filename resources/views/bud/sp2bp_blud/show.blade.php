@extends('template.app')
@section('title', 'Input SP2BP | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input SP2BP
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NO SP2BP dan Tanggal SP2BP --}}
                    <div class="mb-3 row">
                        <label for="no_sp2bp" class="col-md-2 col-form-label">No. SP2BP</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_sp2bp" name="no_sp2bp" required
                                value="{{ $sp2bp->status_sp2bp == 1 ? $sp2bp->no_sp2b : '' }}">

                        </div>
                        <label for="tgl_sp2bp" class="col-md-2 col-form-label">Tanggal SP2BP</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_sp2bp" name="tgl_sp2bp" required
                                value="{{ $sp2bp->status_sp2bp == 1 ? $sp2bp->tgl_sp2bp : '' }}">
                        </div>
                    </div>
                    {{-- SKPD dan Nama SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $sp2bp->kd_skpd }}">

                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ nama_skpd($sp2bp->kd_skpd) }}">
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- NO SP3B dan Tanggal SP3B --}}
                    <div class="mb-3 row">
                        <label for="no_sp3b" class="col-md-2 col-form-label">No. SP3B</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_sp3b" name="no_sp3b" required readonly
                                value="{{ $sp2bp->no_sp3b }}">
                        </div>
                        <label for="tgl_sp3b" class="col-md-2 col-form-label">Tanggal SP3B</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_sp3b" name="tgl_sp3b" required readonly
                                value="{{ $sp2bp->tgl_sp3b }}">
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan" readonly>{{ $sp2bp->keterangan }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-6 row" style="text-align;center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" class="btn btn-primary btn-md"
                                {{ $sp2bp->status_sp2bp == 1 ? 'hidden' : '' }}>Simpan</button>
                            <a href="{{ route('sp2bp_blud.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Detail SP2BP --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Detail SP2BP
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="tgl_transaksi" class="col-md-12 col-form-label">Tanggal Transaksi</label>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="tgl_awal" readonly
                                value="{{ $sp2bp->tgl_awal }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="tgl_akhir" readonly
                                value="{{ $sp2bp->tgl_akhir }}">
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="detail_sp2b" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>No Bukti</th>
                                <th>Sub Kegiatan</th>
                                <th>Nama Sub Kegiatan</th>
                                <th>Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($detail_sp2bp as $detail)
                                @php
                                    $total += $detail->nilai;
                                @endphp
                                <tr>
                                    <td>{{ $detail->kd_skpd }}</td>
                                    <td>{{ $detail->no_bukti }}</td>
                                    <td>{{ $detail->kd_sub_kegiatan }}</td>
                                    <td>{{ $detail->nm_sub_kegiatan }}</td>
                                    <td>{{ $detail->kd_rek6 }}</td>
                                    <td>{{ $detail->nm_rek6 }}</td>
                                    <td>{{ rupiah($detail->nilai) }}</td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('bud.sp2bp_blud.js.show');
@endsection
