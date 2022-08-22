@extends('template.app')
@section('title', 'Tambah Peran | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('peran.store') }}" method="POST">
                        @csrf
                        <div class="mb-3 row">
                            <label for="role" class="col-md-2 col-form-label">Kode</label>
                            <div class="col-md-10">
                                <input class="form-control @error('role') is-invalid @enderror" type="text"
                                    placeholder="Silahkan isi dengan kode peran" id="role" name="role"
                                    value="{{ old('role') }}">
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama_role" class="col-md-2 col-form-label">Nama</label>
                            <div class="col-md-10">
                                <input class="form-control @error('nama_role') is-invalid @enderror" type="text"
                                    placeholder="Silahkan isi dengan nama peran" id="nama_role" name="nama_role"
                                    value="{{ old('nama_role') }}">
                                @error('nama_role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="hak_akses" class="col-md-2 col-form-label">Hak Akses</label>
                            <div class="col-md-10">
                                <select class="form-control select2-multiple @error('hak_akses') is-invalid @enderror"
                                    name="hak_akses[]" id="hak_akses" multiple="multiple">
                                    <optgroup label="Daftar Hak Akses">
                                        @foreach ($daftar_hak_akses as $hak_akses)
                                            <option value="{{ $hak_akses->id }}"
                                                {{ collect(old('hak_akses'))->contains($hak_akses->id) ? 'selected' : '' }}>
                                                {{ $hak_akses->display_name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('hak_akses')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div style="float: right;">
                            <button type="submit" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('peran.index') }}" class="btn btn-warning btn-md">Kembali</a>
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
                placeholder: 'Silahkan pilih hak akses',
            });
        });
    </script>
@endsection
