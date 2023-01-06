@extends('template.app')
@section('title', 'Setting | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">{{ 'Setting' }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ 'Setting' }}</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            @if ($message = Session::get('errors'))
                <div class="alert alert-warning alert-block">
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <!-- end page title -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('setting.update') }}" method="post" enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <!-- Nama Pemda -->
                        <div class="mb-3 row">
                            <label for="nm_pemda" class="col-md-2 col-form-label">Nama Pemerintah</label>
                            <div class="col-md-10">
                                <input class="form-control" placeholder="Isi dengan nama pemerintah" type="text"
                                    id="nm_pemda" name="nm_pemda" required value="{{ $data_setting->nm_pemda }}">
                            </div>
                        </div>
                        <!-- Nama Dinas -->
                        <div class="mb-3 row">
                            <label for="nm_badan" class="col-md-2 col-form-label">Nama Badan / Dinas</label>
                            <div class="col-md-10">
                                <input type="text" placeholder="Isi dengan nama Badan atau Dinas" class="form-control"
                                    value="{{ $data_setting->nm_badan }}" id="nm_badan" name="nm_badan">
                            </div>
                        </div>
                        <!-- Tahun Anggaran -->
                        <div class="mb-3 row">
                            <label for="thn_ang" class="col-md-2 col-form-label">Tahun Anggaran</label>
                            <div class="col-md-10">
                                <input type="text" maxlength="4" placeholder="Isi dengan nama Badan atau Dinas"
                                    class="form-control" value="{{ $data_setting->thn_ang }}" id="thn_ang" name="thn_ang">
                            </div>
                        </div>
                        <!-- Logo Warna -->
                        <div class="mb-3 row">
                            <label for="logo_pemda_warna" class="col-md-2 col-form-label">Logo Pemda
                                <small>(Warna)</small></label>
                            <img src="{{ asset('template/assets/images/' . $data_setting->logo_pemda_warna) }}"
                                class="col-md-1" alt="">
                            <div class="col-md-9">
                                <input type="file" placeholder="Isi dengan nama Badan atau Dinas" class="form-control"
                                    id="logo_pemda_warna" name="logo_pemda_warna">
                                <input type="hidden" class="form-control" value="{{ $data_setting->logo_pemda_warna }}"
                                    id="logo_pemda_warna_old" name="logo_pemda_warna_old">
                            </div>
                        </div>
                        
                        <!-- Logo hitam Putih -->
                        <div class="mb-3 row">
                            <label for="logo_pemda_hp" class="col-md-2 col-form-label">Logo Pemda <small>(Hitam
                                    Putih)</small></label>
                            <img src="{{ asset('template/assets/images/' . $data_setting->logo_pemda_hp) }}"
                                class="col-md-1" alt="">
                            <div class="col-md-9">
                                <input type="file" placeholder="Isi dengan nama Badan atau Dinas" class="form-control"
                                    id="logo_pemda_hp" name="logo_pemda_hp">
                                <input type="hidden" class="form-control" value="{{ $data_setting->logo_pemda_hp }}"
                                    id="logo_pemda_hp_old" name="logo_pemda_hp_old">
                            </div>
                        </div>
                        
                        {{-- Persen Tunai dan Persen KKPD --}}
                        <div class="mb-3 row">
                            <label for="persen_tunai" class="col-md-2 col-form-label">Persen Tunai</label>
                            <div class="col-md-4">
                                <input type="number" min="1" max="100"
                                    value="{{ $data_setting->persen_tunai }}" class="form-control" name="persen_tunai"
                                    required>
                            </div>
                            <label for="persen_kkpd" class="col-md-2 col-form-label">Persen KKPD</label>
                            <div class="col-md-4">
                                <input type="number" min="1" max="100"
                                    value="{{ $data_setting->persen_kkpd }}" class="form-control" name="persen_kkpd"
                                    required>
                            </div>
                        </div>
                        <!-- backup -->
                        <div class="mb-3 row">
                            <label for="last_backup" class="col-md-2 col-form-label">Backup database terakhir</label>
                            <div class="col-md-10">
                                <input class="form-control" placeholder="Isi dengan nama pemerintah" type="text"
                                    id="last_backup" name="last_backup" required value="{{ $data_setting->last_db_backup }}" readonly>
                            </div>
                        </div>
                        <!-- SIMPAN -->
                        <div style="float: right;">
                            <button type="submit" id="save" class="btn btn-primary btn-md">Simpan</button>
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
