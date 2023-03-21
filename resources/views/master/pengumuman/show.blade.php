@extends('template.app')
@section('title', 'Kontrak | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">{{'View Kontrak'}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{'Kontrak'}}</a></li>
                                <li class="breadcrumb-item active">{{'View'}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('kontrak.index') }}" class="btn btn-warning btn-md" style="float: right;">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="judul" class="col-md-3 col-form-label">Judul</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Judul Pengumuman"
                                class="form-control @error('judul') is-invalid @enderror"
                                value="{{ $data_pengumuman->judul }}" id="judul" name="judul" readonly>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Tanggal pengumuman -->
                    <div class="mb-3 row">
                        <label for="tanggal" class="col-md-3 col-form-label">Tanggal pengumuman</label>
                        <div class="col-md-9">
                            <input type="date" name="tanggal" id="tanggal" value="{{ $data_pengumuman->tanggal }}"
                                class="form-control @error('tanggal') is-invalid @enderror" readonly>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Isi pengumuman -->
                    <div class="mb-3 row">
                        <label for="isi" class="col-md-3 col-form-label">Isi pengumuman</label>
                        <div class="col-md-9">
                            <textarea class="form-control @error('isi') is-invalid @enderror" value="{{ old('isi') }}"
                                type="text" rows="5" placeholder="Silahkan isi" id="isi"
                                name="isi" readonly>{{ $data_pengumuman->isi }}</textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- File -->
                    <div class="mb-3 row">
                        <label for="dokumen" class="col-md-3 col-form-label">File</label>
                       
                        <div class="col-md-9">
                            <input type="text" name="dokumenasli" id="dokumenasli" value="{{ $data_pengumuman->file }}"
                                class="form-control @error('dokumenasli') is-invalid @enderror" readonly>
                            @error('dokumenasli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="aktif" class="col-md-3 col-form-label">Aktif/Tidak Aktif</label>
                        <div class="col-md-9">
                            <select class="form-control select2-multiple @error('aktif') is-invalid @enderror"
                                name="aktif" readonly>
                                    <option value="" selected disabled>Silahkan Pilih</option>
                                    <option value="1" {{ $data_pengumuman->aktif == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $data_pengumuman->aktif == '0' ? 'selected' : '' }}>Tidak Aktif
                                    </option>
                            </select>
                            @error('aktif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="status" class="col-md-3 col-form-label">Muncul di beranda</label>
                        <div class="col-md-9">
                            <select class="form-control select2-multiple @error('status') is-invalid @enderror"
                                name="status" @readonly('true')>
                                    <option value="" selected disabled>Silahkan Pilih</option>
                                    <option value="1" {{ $data_pengumuman->status == '1' ? 'selected' : '' }}>Muncul</option>
                                    <option value="0" {{ $data_pengumuman->status == '0' ? 'selected' : '' }}>Tidak Muncul
                                    </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
