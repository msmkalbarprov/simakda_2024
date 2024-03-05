@extends('template.app')
@section('title', 'Tambah kkpd | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">{{ 'Tambah KKPD' }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ 'KKPD' }}</a></li>
                                <li class="breadcrumb-item active">{{ 'Tambah' }}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('kkpd.store') }}" method="post">
                        @csrf
                        <!-- SKPD -->
                        <div class="mb-3 row">
                            <label for="kd_skpd" class="col-md-2 col-form-label">SKPD/Unit</label>
                            <div class="col-md-10">
                                <select class="form-control select2-modal @error('kd_skpd') is-invalid @enderror"
                                    style=" width: 100%;" id="kd_skpd" name="kd_skpd">
                                    <option value="" disabled selected>Silahkan Pilih</option>
                                </select>
                                @error('kd_skpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- NIP -->
                        <div class="mb-3 row">
                            <label for="no_kkpd" class="col-md-2 col-form-label">Nomor KKPD</label>
                            <div class="col-md-10">
                                <input type="text" placeholder="Isi nomor kkpd"
                                    class="form-control @error('no_kkpd') is-invalid @enderror" value="{{ old('no_kkpd') }}"
                                    id="no_kkpd" name="no_kkpd">
                                @error('no_kkpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- nama -->
                        <div class="mb-3 row">
                            <label for="nm_kkpd" class="col-md-2 col-form-label">Nama Pemilik</label>
                            <div class="col-md-10">
                                <input type="text" placeholder="Isi nama pemilik KKPD"
                                    class="form-control @error('nm_kkpd') is-invalid @enderror" value="{{ old('nm_kkpd') }}"
                                    id="nm_kkpd" name="nm_kkpd">
                                @error('nm_kkpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nm_kkpd" class="col-md-2 col-form-label">Jenis Kartu</label>
                            <div class="col-md-10">
                                <select class="form-control select2-multiple @error('jenis') is-invalid @enderror"
                                    style="width: 100%;" id="jenis" name="jenis" data-placeholder="Silahkan Pilih">
                                        <option value="" disabled selected>Silahkan Pilih Jenis Kartu</option>
                                        <option value="BARJAS" {{ old('jenis') == 'BARJAS' ? 'selected' : '' }}>Barang & Jasa
                                        </option>
                                        <option value="PERJADIN" {{ old('jenis') == 'PERJADIN' ? 'selected' : '' }}>Perjalanan Dinas</option>

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
                theme: 'bootstrap-5'
            });

            $('#kd_skpd').select2({
                theme: 'bootstrap-5'
            });
            $('#kode').select2({
                theme: 'bootstrap-5'
            });




        });
    </script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('kkpd.skpd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#kd_skpd').empty();
                    $('#kd_skpd').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                }
            })

        });
    </script>
@endsection
