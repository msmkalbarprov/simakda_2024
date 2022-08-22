@extends('template.app')
@section('title', 'Ubah Peran | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('peran.update', $peran->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        {{-- Kode --}}
                        <div class="mb-3 row">
                            <label for="role" class="col-md-2 col-form-label">Kode</label>
                            <div class="col-md-10">
                                <input class="form-control @error('role') is-invalid @enderror" value="{{ $peran->role }}"
                                    type="text" placeholder="Silahkan isi dengan kode peran" id="role"
                                    name="role">
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Nama --}}
                        <div class="mb-3 row">
                            <label for="nama_role" class="col-md-2 col-form-label">Nama</label>
                            <div class="col-md-10">
                                <input class="form-control @error('nama_role') is-invalid @enderror"
                                    value="{{ $peran->nama_role }}" type="text"
                                    placeholder="Silahkan isi dengan nama peran" id="nama_role" name="nama_role">
                                @error('nama_role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Hak Akses --}}
                        <div class="mb-3 row">
                            <label for="hak_akses" class="col-md-2 col-form-label">Hak Akses</label>
                            <div class="col-md-10">
                                <select class="form-control select2-multiple @error('hak_akses') is-invalid @enderror"
                                    name="hak_akses[]" multiple="multiple" data-placeholder="Silahkan Pilih">
                                    <optgroup label="Daftar Hak Akses">
                                        @foreach ($available_daftar_hak_akses as $hak_akses)
                                            <option value="{{ $hak_akses['id'] }}"
                                                {{ in_array($hak_akses['id'], $daftar_hak_akses) ? 'selected' : '' }}>
                                                {{ $hak_akses['display_name'] }}</option>
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
            $('.select2-multiple').select2();
        });
    </script>
@endsection
