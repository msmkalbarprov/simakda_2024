@extends('template.app')
@section('title', 'Cetak Angkas Murni RO | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    CETAK ANGGARAN KAS SUB RINCIAN OBJEK
                </div>
                <div class="card-body">
                    @csrf
                    {{-- SKPD dan Nama SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD/UNIT</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple" style="width: 100%" id="kd_skpd">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($skpd as $kode)
                                    <option value="{{ $kode->kd_skpd }}" data-nama="{{ $kode->nm_skpd }}">
                                        {{ $kode->kd_skpd }} | {{ $kode->nm_skpd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_skpd" required readonly>
                        </div>
                    </div>
                    {{-- Jenis Anggaran --}}
                    <div class="mb-3 row">
                        <label for="jenis_anggaran" class="col-md-2 col-form-label">Jenis Anggaran</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple" style="width: 100%" id="jenis_anggaran">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    {{-- Jenis Anggaran Kas --}}
                    <div class="mb-3 row">
                        <label for="jenis_rak" class="col-md-2 col-form-label">Jenis Anggaran Kas</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple" style="width: 100%" id="jenis_rak">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    {{-- Sub Kegiatan --}}
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Sub Kegiatan</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple" style="width: 100%" id="kd_sub_kegiatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_sub_kegiatan" required readonly>
                        </div>
                    </div>
                    {{-- Penandatangan --}}
                    <div class="mb-3 row">
                        <label for="ttd1" class="col-md-2 col-form-label">Penandatangan</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple" style="width: 100%" id="ttd1">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_ttd1 as $ttd1)
                                    <option value="{{ $ttd1->id_ttd }}" data-nama="{{ $ttd1->nama }}">{{ $ttd1->nip }}
                                        | {{ $ttd1->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_ttd1" required readonly>
                        </div>
                    </div>
                    {{-- Penandatangan 2 --}}
                    <div class="mb-3 row">
                        <label for="ttd2" class="col-md-2 col-form-label">Penandatangan 2</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple" style="width: 100%" id="ttd2">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_ttd2 as $ttd2)
                                    <option value="{{ $ttd2->id_ttd }}" data-nama="{{ $ttd2->nama }}">{{ $ttd2->nip }}
                                        | {{ $ttd2->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_ttd2" required readonly>
                        </div>
                    </div>
                    {{-- Penandatangan 2 --}}
                    <div class="mb-3 row">
                        <label for="tanggal_ttd" class="col-md-2 col-form-label">Tanggal TTD</label>
                        <div class="col-md-6">
                            <input class="form-control" type="date" id="tanggal_ttd" required>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-3 row" style="float: center;">
                        <div class="col-md-12" style="text-align: center">
                            <button class="btn btn-dark btn-md cetak_rak" data-jenis="layar">Layar</button>
                            <button class="btn btn-warning btn-md cetak_rak" data-jenis="pdf">PDF</button>
                            <button class="btn btn-success btn-md cetak_rak" data-jenis="excel">Excel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <label class="col-md-12" id="demo"></label>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.cetak_rak.per_sub_rincian_objek.js.cetak');
@endsection
