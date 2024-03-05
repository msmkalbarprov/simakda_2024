@extends('template.app')
@section('title', 'Kontrak | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">{{ 'Edit KKPD' }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ 'KKPD' }}</a></li>
                                <li class="breadcrumb-item active">{{ 'Edit KKPD' }}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('kkpd.update', Crypt::encryptString($data_kkpd->id)) }}"
                        method="post">
                        @method('PUT')
                        @csrf
                        <!-- Kode SKPD -->
                        <div class="mb-3 row">
                            <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD/Unit</label>
                            <div class="col-md-10">
                                <input type="text" readonly class="form-control @error('kd_skpd') is-invalid @enderror"
                                    name="kd_skpd" id="kd_skpd" value="{{ $data_kkpd->kd_skpd }}">
                                @error('kd_skpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Nama SKPD -->
                        <div class="mb-3 row">
                            <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD/Unit</label>
                            <div class="col-md-10">
                                <input class="form-control @error('nm_skpd') is-invalid @enderror" type="text"
                                    id="nm_skpd" name="nm_skpd" readonly required value="{{ cari_nama($data_kkpd->kd_skpd,'ms_skpd','kd_skpd','nm_skpd') }}">
                                @error('nm_skpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- NIP -->
                        <div class="mb-3 row">
                            <label for="no_kkpd" class="col-md-2 col-form-label">Nomor KKPD</label>
                            <div class="col-md-10">
                                <input type="text" placeholder="Isi Nomor Kontrak Tanpa Spasi"
                                    class="form-control @error('no_kkpd') is-invalid @enderror"
                                    value="{{ $data_kkpd->no_kkpd }}" id="no_kkpd" name="no_kkpd">
                                @error('no_kkpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- NAMA -->
                        <div class="mb-3 row">
                            <label for="nm_kkpd" class="col-md-2 col-form-label">Nama Pemilik KKPD</label>
                            <div class="col-md-10">
                                <input type="text" name="nm_kkpd" id="nm_kkpd"
                                    value="{{ $data_kkpd->nm_kkpd }}"
                                    class="form-control @error('nm_kkpd') is-invalid @enderror">
                                @error('nm_kkpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="nm_kkpd" class="col-md-2 col-form-label">Nama Pemilik KKPD</label>
                            <div class="col-md-10">
                                    <select class="form-control select2-multiple @error('jenis') is-invalid @enderror"
                                    style="width: 100%;" id="jenis" name="jenis" data-placeholder="Silahkan Pilih">
                                        <option value="" disabled selected>Silahkan Pilih Jenis Kartu</option>
                                        <option value="BARJAS" {{ $data_kkpd->jenis == 'BARJAS' ? 'selected' : '' }}>Barang & Jasa
                                        </option>
                                        <option value="PERJADIN" {{ $data_kkpd->jenis == 'PERJADIN' ? 'selected' : '' }}>Perjalanan Dinas</option>

                                </select>


                                @error('nm_kkpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                       
                        <!-- SIMPAN -->
                        <div style="float: right;">
                            <button type="submit" id="save" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('kkpd.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2({
                theme: 'bootstrap-5',
            });


        });
       
    </script>
@endsection
