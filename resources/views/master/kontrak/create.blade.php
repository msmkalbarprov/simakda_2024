@extends('template.app')
@section('title', 'Tambah Kontrak | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('kontrak.store') }}" method="post">
                        @csrf
                        <!-- Kode SKPD -->
                        <div class="mb-3 row">
                            <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD/Unit</label>
                            <div class="col-md-10">
                                <input type="text" readonly class="form-control @error('kd_skpd') is-invalid @enderror"
                                    name="kd_skpd" id="kd_skpd" value="{{ $kd_skpd }}">
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
                                    value="{{ $skpd->nm_skpd }}" id="nm_skpd" name="nm_skpd" readonly required>
                                @error('nm_skpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- No Kontrak -->
                        <div class="mb-3 row">
                            <label for="no_kontrak" class="col-md-2 col-form-label">No Kontrak</label>
                            <div class="col-md-10">
                                <input type="text" placeholder="Isi Nomor Kontrak Tanpa Spasi"
                                    class="form-control @error('no_kontrak') is-invalid @enderror"
                                    value="{{ old('no_kontrak') }}" id="no_kontrak" name="no_kontrak">
                                @error('no_kontrak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Tanggal Kontrak -->
                        <div class="mb-3 row">
                            <label for="tgl_kerja" class="col-md-2 col-form-label">Tanggal Kontrak</label>
                            <div class="col-md-10">
                                <input type="date" name="tgl_kerja" id="tgl_kerja" value="{{ old('tgl_kerja') }}"
                                    class="form-control @error('tgl_kerja') is-invalid @enderror">
                                @error('tgl_kerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Pelaksana Pekerjaan/Rekanan -->
                        <div class="mb-3 row">
                            <label for="nmpel" class="col-md-2 col-form-label">Pelaksana Pekerjaan/Rekanan</label>
                            <div class="col-md-10">
                                <input class="form-control @error('nmpel') is-invalid @enderror" value="{{ old('nmpel') }}"
                                    type="text" placeholder="Silahkan isi dengan nama pelaksana pekerjaan" id="nmpel"
                                    name="nmpel">
                                @error('nmpel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Pimpinan -->
                        <div class="mb-3 row">
                            <label for="pimpinan" class="col-md-2 col-form-label">Pimpinan</label>
                            <div class="col-md-10">
                                <input class="form-control @error('pimpinan') is-invalid @enderror" type="text"
                                    placeholder="Silahkan isi dengan nama pimpinan" value="{{ old('pimpinan') }}"
                                    id="pimpinan" name="pimpinan">
                                @error('pimpinan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Nama Pekerjaan -->
                        <div class="mb-3 row">
                            <label for="nm_kerja" class="col-md-2 col-form-label">Nama Pekerjaan</label>
                            <div class="col-md-10">
                                <input class="form-control @error('nm_kerja') is-invalid @enderror" type="text"
                                    placeholder="Silahkan isi dengan nama pekerjaan" value="{{ old('nm_kerja') }}"
                                    id="nm_kerja" name="nm_kerja">
                                @error('nm_kerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Rekanan -->
                        <div class="mb-3 row">
                            <label for="nm_rekening" class="col-md-2 col-form-label">(Rekanan) Nama Pemilik Rekening</label>
                            <div class="col-md-10">
                                <select class="form-control @error('nm_rekening') is-invalid @enderror select2-multiple"
                                    style="width: 100%;" id="nm_rekening" name="nm_rekening" required>
                                    <optgroup label="Nama Rekening | No Rekening | NPWP">
                                        <option value="" disabled selected>Silahkan Pilih Rekening</option>
                                        @foreach ($daftar_rekening as $data_rekening)
                                            <option value="{{ $data_rekening->nm_rekening }}"
                                                data-npwp="{{ $data_rekening->npwp }}"
                                                data-rekening="{{ $data_rekening->rekening }}"
                                                {{ old('nm_rekening') == $data_rekening->nm_rekening ? 'selected' : '' }}>
                                                {{ $data_rekening->nm_rekening }} | {{ $data_rekening->rekening }} |
                                                {{ $data_rekening->npwp }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('nm_rekening')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- No Rekening -->
                        <div class="mb-3 row">
                            <label for="no_rekening" class="col-md-2 col-form-label">No Rekening</label>
                            <div class="col-md-10">
                                <input type="text" readonly name="no_rekening" value="{{ old('no_rekening') }}"
                                    id="no_rekening" class="form-control @error('no_rekening') is-invalid @enderror">
                                @error('no_rekening')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- NPWP -->
                        <div class="mb-3 row">
                            <label for="npwp" class="col-md-2 col-form-label">NPWP</label>
                            <div class="col-md-10">
                                <input type="text" readonly name="npwp" value="{{ old('npwp') }}"
                                    id="npwp" class="form-control @error('npwp') is-invalid @enderror">
                                @error('npwp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Nilai Kontrak -->
                        <div class="mb-3 row">
                            <label for="nilai" class="col-md-2 col-form-label">Nilai Kontrak</label>
                            <div class="col-md-10">
                                <input type="number" min="0" value="{{ old('nilai') }}"
                                    placeholder="Silahkan isi dengan nilai kontrak" name="nilai" id="nilai"
                                    class="form-control @error('nilai') is-invalid @enderror">
                                @error('nilai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- SIMPAN -->
                        <div style="float: right;">
                            <button type="submit" id="save" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('kontrak.index') }}" class="btn btn-warning btn-md">Kembali</a>
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
        });
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#nm_rekening').on("change", function() {
                let rekening = $(this).find(':selected').data('rekening');
                let npwp = $(this).find(':selected').data('npwp');
                $("#no_rekening").val(rekening);
                $("#npwp").val(npwp);
            });
        });
    </script>
@endsection
