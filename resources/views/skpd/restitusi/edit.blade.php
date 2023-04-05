@extends('template.app')
@section('title', 'Edit Data Restitusi | SIMAKDA')
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
                    {{-- No Terima dan Tanggal Terima --}}
                    <div class="mb-3 row">
                        <label for="no_terima" class="col-md-2 col-form-label">No. Terima</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_terima" name="no_terima"
                                value="{{ $data->no_sts }}" placeholder="Silahkan Diisi" required>
                        </div>
                        <label for="tgl_terima" class="col-md-2 col-form-label">Tanggal Terima</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_terima" name="tgl_terima" required
                                value="{{ $data->tgl_sts }}">
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- Kode dan Nama SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $skpd->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ $skpd->nm_skpd }}">
                        </div>
                    </div>
                    {{-- Rekening --}}
                    <div class="mb-3 row">
                        <label for="rekening" class="col-md-2 col-form-label">Rekening</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="rekening"
                                name="rekening">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_akun as $akun)
                                    <option value="{{ $akun->kd_rek6 }}" data-kd_sub_kegiatan="{{ $akun->kd_sub_kegiatan }}"
                                        data-nm_rek="{{ $akun->nm_rek }}" data-kd_rek="{{ $akun->kd_rek }}"
                                        {{ $data->kd_rek6 == $akun->kd_rek6 ? 'selected' : '' }}>
                                        {{ $akun->kd_rek6 }} | {{ $akun->kd_rek }} | {{ $akun->nm_rek }} |
                                        {{ $akun->nm_rek5 }} |
                                        {{ $akun->kd_sub_kegiatan }}
                                    </option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- pengirim --}}
                    <div class="mb-3 row">
                        <label for="pengirim" class="col-md-2 col-form-label">Pengirim</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="pengirim"
                                name="pengirim">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_pengirim as $pengirim)
                                    <option value="{{ $pengirim->kd_pengirim }}"
                                        data-nm_pengirim="{{ $pengirim->nm_pengirim }}"
                                        {{ $data->sumber == $pengirim->kd_pengirim ? 'selected' : '' }}>
                                        {{ $pengirim->kd_pengirim }} | {{ $pengirim->nm_pengirim }}
                                    </option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Nilai --}}
                    <div class="mb-3 row">
                        <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="nilai" id="nilai"
                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" style="text-align: right"
                                value="{{ $data->total }}">
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan">{{ $data->ket }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-3 row" style="float: right;">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" hidden class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('restitusi.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.restitusi.js.edit');
@endsection
