@extends('template.app')
@section('title', 'Input SPB | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input SPB
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NO SPB HIBAH dan Tanggal SPB HIBAH --}}
                    <div class="mb-3 row">
                        <label for="no_spb" class="col-md-2 col-form-label">No. SPB HIBAH</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_spb" name="no_spb" required readonly
                                value="{{ $no_spb }}">
                            <input class="form-control" type="text" id="no_urut" name="no_urut" required readonly
                                hidden value="{{ $no_urut }}">
                        </div>
                        <label for="tgl_spb" class="col-md-2 col-form-label">Tanggal SPB HIBAH</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_spb" name="tgl_spb" required>
                        </div>
                    </div>
                    {{-- SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="kd_skpd" name="kd_skpd">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_skpd as $skpd)
                                    <option value="{{ $skpd->kd_skpd }}" data-nama="{{ $skpd->nm_skpd }}">
                                        {{ $skpd->kd_skpd }} |
                                        {{ $skpd->nm_skpd }}</option>
                                @endforeach
                                </option>
                            </select>
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- Kode Sub Kegiatan --}}
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Kode Sub Kegiatan</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="kd_sub_kegiatan"
                                name="kd_sub_kegiatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    {{-- Kode Rekening --}}
                    <div class="mb-3 row">
                        <label for="rekening" class="col-md-2 col-form-label">Kode Rekening</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="rekening"
                                name="rekening">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_rekening as $rekening)
                                    <option value="{{ $rekening->kd_rek6 }}" data-nama="{{ $rekening->nm_rek6 }}">
                                        {{ $rekening->kd_rek6 }} |
                                        {{ $rekening->nm_rek6 }}</option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Kategori dan Gelombang --}}
                    <div class="mb-3 row">
                        <label for="kategori" class="col-md-2 col-form-label">Kategori</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%" id="kategori"
                                name="kategori">
                                @foreach ($daftar_kategori as $kategori)
                                    <option value="{{ $kategori->kategori }}">
                                        {{ $kategori->kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="gelombang" class="col-md-2 col-form-label">Gelombang</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%" id="gelombang"
                                name="gelombang">
                                <option value="I"> I </option>
                                <option value="II"> II </option>
                                <option value="III"> III</option>
                                <option value="IV"> IV </option>
                            </select>
                        </div>
                    </div>
                    {{-- Tahapan --}}
                    <div class="mb-3 row">
                        <label for="tahapan" class="col-md-2 col-form-label">Tahapan</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%" id="tahapan" name="tahapan">
                                <option value="I"> I </option>
                                <option value="II"> II </option>
                                <option value="III"> III</option>
                                <option value="IV"> IV </option>
                            </select>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-6 row" style="text-align;center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('spb_hibah.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Detail SPB --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Detail SPB
                    <button type="button" style="float: right" id="tambah_rincian"
                        class="btn btn-success btn-md">Tambah Rincian</button>
                </div>
                <div class="card-body table-responsive">
                    <table id="detail_spb" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Tanggal SP2H</th>
                                <th>No SP2H</th>
                                <th>Kode Satdik</th>
                                <th>Nama Satdik</th>
                                <th>Rupiah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total" class="col-md-8 col-form-label" style="text-align: right">Total</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total" name="total">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modul tambah rincian --}}
    <div id="modal_rincian" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Rekening</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No. SP2H --}}
                    <div class="mb-3 row">
                        <label for="no_sp2h" class="col-md-2 col-form-label">No. SP2H</label>
                        <div class="col-md-10">
                            <select name="no_sp2h" class="form-control select-modal" id="no_sp2h">
                                <option value="" selected disabled>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    {{-- Nilai --}}
                    <div class="mb-3 row">
                        <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-10">
                            <input type="text" name="nilai" id="nilai" style="text-align: right"
                                class="form-control" data-type="currency">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" id="pilih" class="btn btn-md btn-success">Pilih</button>
                            <button type="button" class="btn btn-md btn-warning"
                                data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('bud.spb_hibah.js.create');
@endsection
