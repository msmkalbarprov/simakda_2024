@extends('template.app')
@section('title', 'Tampil Data Restitusi | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Data Restitusi
                </div>
                <div class="card-body">
                    @csrf
                    {{-- No kas dan Tanggal kas --}}
                    <div class="mb-3 row">
                        <label for="no_kas" class="col-md-2 col-form-label">No. kas</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_kas" name="no_kas"
                                value="{{ $restitusi->no_kas }}" placeholder="Silahkan Diisi" required readonly>
                        </div>
                        <label for="tgl_kas" class="col-md-2 col-form-label">Tanggal kas</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_kas" name="tgl_kas" required
                                value="{{ $restitusi->tgl_kas }}" readonly>
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- No Bukti dan Tanggal Bukti --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $restitusi->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ nama_skpd($restitusi->kd_skpd) }}">
                        </div>
                    </div>
                    {{-- Rekening --}}
                    <div class="mb-3 row">
                        <label for="no_bukti" class="col-md-2 col-form-label">No Bukti</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_bukti" name="no_bukti" required readonly
                                value="{{ $restitusi->no_sts }}">
                        </div>
                        <label for="tgl_bukti" class="col-md-2 col-form-label">Tanggal Bukti</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="tgl_bukti" name="tgl_bukti" required readonly
                                value="{{ $restitusi->tgl_sts }}">
                        </div>
                    </div>
                    {{-- pengirim --}}
                    <div class="mb-3 row">
                        <label for="kegiatan" class="col-md-2 col-form-label">Kegiatan</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kegiatan" name="kegiatan" required readonly
                                value="{{ $restitusi->kd_sub_kegiatan }}">
                        </div>
                        <label for="jenis" class="col-md-2 col-form-label">Jenis Transaksi</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="jenis" name="jenis" required readonly
                                value="{{ $restitusi->jns_trans }}">
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea readonly class="form-control" style="width: 100%" id="keterangan" name="keterangan">{{ $restitusi->keterangan }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-3 row" style="float: right;">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" hidden class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('list_restitusi.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Restitusi --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detail Restitusi
                </div>
                <div class="card-body table-responsive">
                    <table id="detail_restitusi" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 40%">Nomor Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Rupiah</th>
                                <th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($rincian as $item)
                                @php
                                    $total += $item->rupiah;
                                @endphp
                                <tr>
                                    <td>{{ $item->kd_rek6 }}</td>
                                    <td>{{ $item->nm_rek }}</td>
                                    <td>{{ rupiah($item->rupiah) }}</td>
                                    <td>{{ $item->nm_pengirim }}</td>
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
    @include('bud.list_restitusi.js.edit');
@endsection
