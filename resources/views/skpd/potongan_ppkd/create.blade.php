@extends('template.app')
@section('title', 'Input Data Potongan PPKD | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Data Potongan PPKD
                </div>
                <div class="card-body">
                    @csrf
                    {{-- No dan Tanggal Kas --}}
                    <div class="mb-3 row">
                        <label for="no_kas" class="col-md-2 col-form-label">No. Kas</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_kas" name="no_kas" required>
                        </div>
                        <label for="tgl_kas" class="col-md-2 col-form-label">Tanggal</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_kas" name="tgl_kas" required>
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- NO STS --}}
                    <div class="mb-3 row">
                        <label for="no_sts" class="col-md-2 col-form-label">No. STS</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple csts" style="width: 100%" id="no_sts" name="no_sts">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                {{-- @foreach ($data_sts as $sts)
                                    <option value="{{ $sts->no_sts }}" data-tgl="{{ $sts->tgl_sts }}"
                                        data-kd_rek6="{{ Str::of($sts->kd_rek6)->trim() }}"
                                        data-sumber="{{ $sts->sumber }}" data-total="{{ rupiah($sts->total) }}">
                                        {{ $sts->no_sts }} | {{ $sts->tgl_sts }} | {{ rupiah($sts->total) }}
                                    </option>
                                @endforeach --}}
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Jenis dan Nama Jenis --}}
                    <div class="mb-3 row">
                        <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="jenis" name="jenis">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_jenis as $jenis)
                                    <option value="{{ $jenis->kd_rek6 }}" data-nama="{{ $jenis->nm_rek6 }}">
                                        {{ $jenis->kd_rek6 }} | {{ $jenis->nm_rek6 }}
                                    </option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Pengirim dan Nama Pengirim --}}
                    <div class="mb-3 row">
                        <label for="pengirim" class="col-md-2 col-form-label">Pengirim</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="pengirim"
                                name="pengirim">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_pengirim as $pengirim)
                                    <option value="{{ $pengirim->kd_pengirim }}" data-nama="{{ $pengirim->nm_pengirim }}">
                                        {{ $pengirim->kd_pengirim }} | {{ $pengirim->nm_pengirim }}
                                    </option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- RKUD --}}
                    <div class="mb-3 row">
                        <label for="rkud" class="col-md-2 col-form-label">RKUD</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="rkud" name="rkud">
                                @foreach ($daftar_rkud as $rkud)
                                    <option value="{{ $rkud->rek_bank }}" data-nama="{{ $rkud->nm_rek_bank }}">
                                        {{ $rkud->rek_bank }} | {{ $rkud->nm_rek_bank }}
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
                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" style="text-align: right">
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan"></textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-3 row" style="float: right;">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('potongan_ppkd.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.potongan_ppkd.js.create');
@endsection
