@extends('template.app')
@section('title', 'Tambah SP2D | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input SPM
                </div>
                <div class="card-body">
                    @csrf
                    {{-- Beban dan Jenis --}}
                    <div class="mb-3 row">
                        <label for="beban" class="col-md-2 col-form-label">Beban</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('beban') is-invalid @enderror"
                                style="width: 100%" id="beban" name="beban">
                                <optgroup label="Daftar Beban">
                                    <option value="" disabled selected>Silahkan Pilih</option>
                                    <option value="1">UP</option>
                                    <option value="2">GU</option>
                                    <option value="3">TU</option>
                                    <option value="4">LS GAJI</option>
                                    <option value="5">LS Pihak Ketiga Lainnya</option>
                                    <option value="6">LS Barang Jasa</option>
                                </optgroup>
                            </select>
                            @error('beban')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="jenis" name="jenis" readonly hidden>
                            <input type="text" class="form-control" id="nama_jenis" name="nama_jenis" readonly>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- No SPM dan Tanggal SPM --}}
                    <div class="mb-3 row">
                        <label for="no_spm" class="col-md-2 col-form-label">No. SPM</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('no_spm') is-invalid @enderror"
                                style="width: 100%" id="no_spm" name="no_spm">
                                <optgroup label="Daftar Nomor SPM">
                            </select>
                            @error('no_spm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="tgl_spm" class="col-md-2 col-form-label">Tanggal SPM</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control @error('tgl_spm') is-invalid @enderror" id="tgl_spm"
                                name="tgl_spm" readonly>
                            @error('tgl_spm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- No SP2D dan Tanggal SP2D --}}
                    <div class="mb-3 row">
                        <label for="no_spd" class="col-md-2 col-form-label">No. SP2D</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('no_sp2d') is-invalid @enderror" id="no_sp2d"
                                name="no_sp2d" readonly>
                            <input type="text" class="form-control @error('nomor_urut') is-invalid @enderror"
                                id="nomor_urut" name="nomor_urut" hidden>
                            @error('no_spd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="tgl_sp2d" class="col-md-2 col-form-label">Tanggal SP2D</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control @error('tgl_sp2d') is-invalid @enderror"
                                id="tgl_sp2d" name="tgl_sp2d">
                            @error('tgl_sp2d')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- No SPP dan Tanggal SPP --}}
                    <div class="mb-3 row">
                        <label for="no_spp" class="col-md-2 col-form-label">No. SPP</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('no_spp') is-invalid @enderror" id="no_spp"
                                name="no_spp" readonly>
                            @error('no_spp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="tgl_spp" class="col-md-2 col-form-label">Tanggal SPP</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control @error('tgl_spp') is-invalid @enderror" id="tgl_spp"
                                name="tgl_spp" readonly>
                            @error('tgl_spp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- KD SKPD dan NM SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control @error('kd_skpd') is-invalid @enderror" type="text" id="kd_skpd"
                                name="kd_skpd" required readonly>
                            @error('kd_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control @error('nm_skpd') is-invalid @enderror" type="text"
                                id="nm_skpd" name="nm_skpd" required readonly>
                            @error('nm_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Bulan dan Rekanan --}}
                    <div class="mb-3 row">
                        <label for="bulan" class="col-md-2 col-form-label">Bulan</label>
                        <div class="col-md-4">
                            <input class="form-control @error('bulan') is-invalid @enderror" type="text"
                                id="bulan" name="bulan" required readonly hidden>
                            <input class="form-control @error('bunama_lan') is-invalid @enderror" type="text"
                                id="nama_bulan" name="nama_bulan" required readonly>
                            @error('bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="rekanan" class="col-md-2 col-form-label">Rekanan</label>
                        <div class="col-md-4">
                            <input class="form-control @error('rekanan') is-invalid @enderror" type="text"
                                id="rekanan" name="rekanan" required readonly>
                            @error('rekanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Anggaran dan Keperluan --}}
                    <div class="mb-3 row">
                        <label for="anggaran" class="col-md-2 col-form-label">Anggaran</label>
                        <div class="col-md-4">
                            <input class="form-control @error('anggaran') is-invalid @enderror" type="text"
                                id="anggaran" name="anggaran" required readonly>
                            @error('anggaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="keperluan" class="col-md-2 col-form-label">Keperluan</label>
                        <div class="col-md-4">
                            <textarea name="keperluan" class="form-control" id="keperluan" readonly></textarea>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Nomor SPD dan Jenis SPD --}}
                    <div class="mb-3 row">
                        <label for="no_spd" class="col-md-2 col-form-label">Nomor SPD</label>
                        <div class="col-md-4">
                            <input class="form-control @error('no_spd') is-invalid @enderror" type="text"
                                id="no_spd" name="no_spd" required readonly>
                            @error('no_spd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="jenis_spd" class="col-md-2 col-form-label">Jenis SPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('jenis_spd') is-invalid @enderror"
                                id="jenis_spd" name="jenis_spd" readonly>
                            @error('jenis_spd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Kode Bank dan Nama Bank --}}
                    <div class="mb-3 row">
                        <label for="kode_bank" class="col-md-2 col-form-label">Kode Bank</label>
                        <div class="col-md-4">
                            <input class="form-control @error('kode_bank') is-invalid @enderror" type="text"
                                id="kode_bank" name="kode_bank" required readonly>
                            @error('kode_bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="nama_bank" class="col-md-2 col-form-label">Nama Bank</label>
                        <div class="col-md-4">
                            <input class="form-control @error('nama_bank') is-invalid @enderror" type="text"
                                id="nama_bank" name="nama_bank" required readonly>
                            @error('nama_bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- NPWP dan Rekening --}}
                    <div class="mb-3 row">
                        <label for="npwp" class="col-md-2 col-form-label">NPWP</label>
                        <div class="col-md-4">
                            <input class="form-control @error('npwp') is-invalid @enderror" type="text"
                                id="npwp" name="npwp" required readonly>
                            @error('npwp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="rekening" class="col-md-2 col-form-label">Nomor Rekening</label>
                        <div class="col-md-4">
                            <input class="form-control @error('rekening') is-invalid @enderror" type="text"
                                id="rekening" name="rekening" required readonly>
                            @error('rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="simpan_spm" class="btn btn-primary btn-md">Simpan</button>
                        <a href="{{ route('sp2d.index') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Detail SPM --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detail SPM
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_spm" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Sisa</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total" class="col-md-8 col-form-label" style="text-align: right">Total</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right" readonly
                                class="form-control @error('total') is-invalid @enderror" id="total_spm" name="total">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- List Potongan --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Potongan
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_potongan" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total" class="col-md-8 col-form-label" style="text-align: right">Total</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right" readonly
                                class="form-control @error('total') is-invalid @enderror" id="total_potongan"
                                name="total">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-center" id="konfirmasi_potongan" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <p style="text-align: center">Apakah Anda ingin menambahkan potongan?</p>
                    <div class="mt-2" style="text-align: center">
                        <a href="{{ route('spm.tambah_potongan') }}" class="btn btn-primary btn-md">Ya</a>
                        <a href="{{ route('sp2d.index') }}" class="btn btn-danger btn-md">Tidak</a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection
@section('js')
    @include('penatausahaan.pengeluaran.sp2d.js.create');
@endsection
