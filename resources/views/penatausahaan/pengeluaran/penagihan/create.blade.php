@extends('template.app')
@section('title', 'Tambah Penagihan | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('penagihan.store') }}" method="post">
                        @csrf
                        <!-- No Tersimpan -->
                        <div class="mb-3 row">
                            <label for="no_tersimpan" class="col-md-2 col-form-label">No Tersimpan</label>
                            <div class="col-md-10">
                                <input type="text" readonly
                                    class="form-control @error('no_tersimpan') is-invalid @enderror" name="no_tersimpan"
                                    id="no_tersimpan">
                                @error('no_tersimpan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- No. Bast / Penagihan Tanggal Penagihan -->
                        <div class="mb-3 row">
                            <label for="no_bukti" class="col-md-2 col-form-label">No.BAST / Penagihan</label>
                            <div class="col-md-4">
                                <input class="form-control @error('no_bukti') is-invalid @enderror" type="text"
                                    id="no_bukti" name="no_bukti" required>
                                @error('no_bukti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="tgl_bukti" class="col-md-2 col-form-label">Tanggal Penagihan</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control @error('tgl_bukti') is-invalid @enderror"
                                    value="{{ old('tgl_bukti') }}" id="tgl_bukti" name="tgl_bukti">
                                @error('tgl_bukti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Kode SKPD Nama SKPD -->
                        <div class="mb-3 row">
                            <label for="kd_skpd" class="col-md-2 col-form-label">Kode OPD / Unit</label>
                            <div class="col-md-4">
                                <input type="text" readonly name="kd_skpd" id="kd_skpd" value="{{ $kd_skpd }}"
                                    class="form-control @error('kd_skpd') is-invalid @enderror">
                                @error('kd_skpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="nm_skpd" class="col-md-2 col-form-label">Nama OPD / Unit</label>
                            <div class="col-md-4">
                                <input class="form-control @error('nm_skpd') is-invalid @enderror"
                                    value="{{ $skpd->nm_skpd }}" readonly type="text"
                                    placeholder="Silahkan isi dengan nama pelaksana pekerjaan" id="nm_skpd"
                                    name="nm_skpd">
                                @error('nm_skpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Keterangan Keterangan BAST -->
                        <div class="mb-3 row">
                            <label for="ket" class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-4">
                                <textarea class="form-control @error('ket') is-invalid @enderror" type="text"
                                    placeholder="Silahkan isi dengan keterangan" value="{{ old('ket') }}" id="ket" name="ket"></textarea>
                                @error('ket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="ket_bast" class="col-md-2 col-form-label">Keterangan (BA)</label>
                            <div class="col-md-4">
                                <textarea type="text" name="ket_bast" placeholder="Silahkan isi dengan keterangan (BA)"
                                    value="{{ old('ket_bast') }}" id="ket_bast" class="form-control @error('ket_bast') is-invalid @enderror"></textarea>
                                @error('ket_bast')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Status Jenis -->
                        <div class="mb-3 row">
                            <label for="status_bayar" class="col-md-2 col-form-label">Status</label>
                            <div class="col-md-4">
                                <select class="form-control select2-multiple @error('status_bayar') is-invalid @enderror"
                                    style="width: 100%;" id="status_bayar" name="status_bayar"
                                    data-placeholder="Silahkan Pilih">
                                    <optgroup label="Daftar Status">
                                        <option value="" disabled selected>Silahkan Pilih Status</option>
                                        <option value="1" {{ old('status_bayar') == '1' ? 'selected' : '' }}>Selesai
                                        </option>
                                        <option value="2" {{ old('status_bayar') == '2' ? 'selected' : '' }}>Belum
                                            Selesai</option>
                                    </optgroup>
                                </select>
                                @error('status_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
                            <div class="col-md-4">
                                <select class="form-control select2-multiple @error('jenis') is-invalid @enderror"
                                    style="width: 100%;" id="jenis" name="jenis" data-placeholder="Silahkan Pilih">
                                    <optgroup label="Daftar Jenis">
                                        <option value="" disabled selected>Silahkan Pilih Jenis</option>
                                        <option value="" {{ old('jenis') == '' ? 'selected' : '' }}>Tanpa Termin /
                                            Sekali Pembayaran</option>
                                        <option value="1" {{ old('jenis') == '1' ? 'selected' : '' }}>Konstruksi Dalam
                                            Pengerjaan</option>
                                        <option value="2" {{ old('jenis') == '2' ? 'selected' : '' }}>Uang Muka
                                        </option>
                                        <option value="3" {{ old('jenis') == '3' ? 'selected' : '' }}>Hutang Tahun
                                            Lalu</option>
                                        <option value="4" {{ old('jenis') == '4' ? 'selected' : '' }}>Perbulan
                                        </option>
                                        <option value="5" {{ old('jenis') == '5' ? 'selected' : '' }}>Bertahap
                                        </option>
                                        <option value="6" {{ old('jenis') == '6' ? 'selected' : '' }}>Berdasarkan
                                            Progres / Pengajuan Pekerjaan</option>
                                    </optgroup>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- No Kontrak Rekanan -->
                        <div class="mb-3 row">
                            <label for="no_kontrak" class="col-md-2 col-form-label">Nomor Kontrak</label>
                            <div class="col-md-4">
                                <select class="form-control select2-multiple @error('no_kontrak') is-invalid @enderror"
                                    style=" width: 100%;" id="no_kontrak" name="no_kontrak"
                                    data-placeholder="Silahkan Pilih">
                                    <optgroup label="Daftar Kontrak">
                                        <option value="" disabled selected>Kontrak | Nilai Kontrak | Lalu</option>
                                        @foreach ($daftar_kontrak as $kontrak)
                                            <option value="{{ $kontrak->no_kontrak }}"
                                                {{ old('no_kontrak') == $kontrak->no_kontrak ? 'selected' : '' }}>
                                                {{ $kontrak->no_kontrak }} | {{ $kontrak->nilai }} | {{ $kontrak->lalu }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('no_kontrak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="rekanan" class="col-md-2 col-form-label">Rekanan</label>
                            <div class="col-md-4">
                                <select class="form-control select2-multiple @error('rekanan') is-invalid @enderror"
                                    style=" width: 100%;" id="rekanan" name="rekanan"
                                    data-placeholder="Silahkan Pilih">
                                    <optgroup label="Daftar Rekanan">
                                        <option value="" disabled selected>Nama Rekanan | Rekening | NPWP</option>
                                        @foreach ($daftar_rekanan as $rekanan)
                                            <option value="{{ $rekanan->nm_rekening }}"
                                                {{ old('rekanan') == $rekanan->nm_rekening ? 'selected' : '' }}>
                                                {{ $rekanan->nm_rekening }} | {{ $rekanan->rekening }} |
                                                {{ $rekanan->npwp }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('rekanan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- SIMPAN -->
                        <div style="float: right;">
                            <button type="submit" id="save" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('penagihan.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" style="float: right;margin-left:3px" id="hapus_rincian"
                        class="btn btn-danger btn-sm">Hapus Rincian</button>
                    <button type="button" style="float: right" id="tambah_rincian"
                        class="btn btn-primary btn-sm">Tambah Rincian</button>
                </div>
                <div class="card-body">
                    <table id="tech-companies-1" class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Sub Kegiatan</th>
                                <th>Kode Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                                <th>Sumber</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="tambah-penagihan" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Rincian Penagihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- SUB KEGIATAN -->
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Sub Kegiatan</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple @error('kd_sub_kegiatan') is-invalid @enderror"
                                style=" width: 100%;" id="kd_sub_kegiatan" name="kd_sub_kegiatan"
                                data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Sub Kegiatan">
                                    <option value="" disabled selected>Kode Sub Kegiatan | Nama Sub Kegiatan</option>
                                    @foreach ($daftar_sub_kegiatan as $sub_kegiatan)
                                        <option value="{{ $sub_kegiatan->kd_sub_kegiatan }}"
                                            data-nama="{{ $sub_kegiatan->nm_sub_kegiatan }}">
                                            {{ $sub_kegiatan->kd_sub_kegiatan }} | {{ $sub_kegiatan->nm_sub_kegiatan }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('kd_sub_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('nm_sub_kegiatan') is-invalid @enderror"
                                value="{{ old('nm_sub_kegiatan') }}" id="nm_sub_kegiatan" readonly
                                name="nm_sub_kegiatan">
                            @error('nm_sub_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- REKENING -->
                    <div class="mb-3 row">
                        <label for="kode_rekening" class="col-md-2 col-form-label">Rekening</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple @error('kode_rekening') is-invalid @enderror"
                                style=" width: 100%;" id="kode_rekening" name="kode_rekening"
                                data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Rekening">
                                    <option value="" disabled selected>Kode Rekening Ang. | Kode Rekening | Nama
                                        Rekening | Lalu | SP2D | Anggaran</option>
                                </optgroup>
                            </select>
                            @error('kode_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('nm_rekening') is-invalid @enderror"
                                value="{{ old('nm_rekening') }}" id="nm_rekening" readonly name="nm_rekening">
                            @error('nm_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- SUMBER DANA -->
                    <div class="mb-3 row">
                        <label for="sumber_dana" class="col-md-2 col-form-label">Sumber</label>
                        <div class="col-md-6">
                            <select class="form-control select2-multiple @error('sumber_dana') is-invalid @enderror"
                                style=" width: 100%;" id="sumber_dana" name="sumber_dana"
                                data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Sumber Dana">
                                    <option value="" disabled selected>Sumber Dana</option>
                                </optgroup>
                            </select>
                            @error('sumber_dana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('nm_sumber') is-invalid @enderror"
                                value="{{ old('nm_sumber') }}" id="nm_sumber" readonly name="nm_sumber">
                            @error('nm_sumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- TOTAL SPD -->
                    <div class="mb-3 row">
                        <label for="total_spd" class="col-md-2 col-form-label">Total SPD</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control @error('total_spd') is-invalid @enderror"
                                name="total_spd" id="total_spd">
                            @error('total_spd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="realisasi_spd" class="col-md-1 col-form-label">Realisasi</label>
                        <div class="col-md-3">
                            <input type="text" readonly
                                class="form-control @error('realisasi_spd') is-invalid @enderror" name="realisasi_spd"
                                id="realisasi_spd">
                            @error('realisasi_spd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="sisa_spd" class="col-md-1 col-form-label">Sisa</label>
                        <div class="col-md-3">
                            <input type="text" readonly class="form-control @error('sisa_spd') is-invalid @enderror"
                                name="sisa_spd" id="sisa_spd">
                            @error('sisa_spd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- ANGKAS -->
                    <div class="mb-3 row">
                        <label for="total_angkas" class="col-md-2 col-form-label">Angkas</label>
                        <div class="col-md-2">
                            <input type="text" readonly
                                class="form-control @error('total_angkas') is-invalid @enderror" name="total_angkas"
                                id="total_angkas">
                            @error('total_angkas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="realisasi_angkas" class="col-md-1 col-form-label">Realisasi</label>
                        <div class="col-md-3">
                            <input type="text" readonly
                                class="form-control @error('realisasi_angkas') is-invalid @enderror"
                                name="realisasi_angkas" id="realisasi_angkas">
                            @error('realisasi_angkas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="sisa_angkas" class="col-md-1 col-form-label">Sisa</label>
                        <div class="col-md-3">
                            <input type="text" readonly
                                class="form-control @error('sisa_angkas') is-invalid @enderror" name="sisa_angkas"
                                id="sisa_angkas">
                            @error('sisa_angkas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- PAGU -->
                    <div class="mb-3 row">
                        <label for="total_pagu" class="col-md-2 col-form-label">Pagu</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control @error('total_pagu') is-invalid @enderror"
                                name="total_pagu" id="total_pagu">
                            @error('total_pagu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="realisasi_pagu" class="col-md-1 col-form-label">Realisasi</label>
                        <div class="col-md-3">
                            <input type="text" readonly
                                class="form-control @error('realisasi_pagu') is-invalid @enderror" name="realisasi_pagu"
                                id="realisasi_pagu">
                            @error('realisasi_pagu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="sisa_pagu" class="col-md-1 col-form-label">Sisa</label>
                        <div class="col-md-3">
                            <input type="text" readonly class="form-control @error('sisa_pagu') is-invalid @enderror"
                                name="sisa_pagu" id="sisa_pagu">
                            @error('sisa_pagu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- NILAI SUMBER DANA -->
                    <div class="mb-3 row">
                        <label for="nilai_sumber_dana" class="col-md-2 col-form-label">Nilai Sumber Dana</label>
                        <div class="col-md-2">
                            <input type="text" readonly
                                class="form-control @error('nilai_sumber_dana') is-invalid @enderror"
                                name="nilai_sumber_dana" id="nilai_sumber_dana">
                            @error('nilai_sumber_dana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="realisasi_sumber" class="col-md-1 col-form-label">Realisasi</label>
                        <div class="col-md-3">
                            <input type="text" readonly
                                class="form-control @error('realisasi_sumber') is-invalid @enderror"
                                name="realisasi_sumber" id="realisasi_sumber">
                            @error('realisasi_sumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="sisa_sumber" class="col-md-1 col-form-label">Sisa</label>
                        <div class="col-md-3">
                            <input type="text" readonly
                                class="form-control @error('sisa_sumber') is-invalid @enderror" name="sisa_sumber"
                                id="sisa_sumber">
                            @error('sisa_sumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Status Anggaran -->
                    <div class="mb-3 row">
                        <label for="status_anggaran" class="col-md-2 col-form-label">Status Anggaran</label>
                        <div class="col-md-10">
                            <input type="text" readonly
                                class="form-control @error('status_anggaran') is-invalid @enderror"
                                name="status_anggaran" id="status_anggaran">
                            @error('status_anggaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Status Angkas -->
                    <div class="mb-3 row">
                        <label for="status_angkas" class="col-md-2 col-form-label">Status Angkas</label>
                        <div class="col-md-10">
                            <input type="text" readonly
                                class="form-control @error('status_angkas') is-invalid @enderror" name="status_angkas"
                                id="status_angkas">
                            @error('status_angkas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Nilai -->
                    <div class="mb-3 row">
                        <label for="nilai_penagihan" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-10">
                            <input type="text" readonly
                                class="form-control @error('nilai_penagihan') is-invalid @enderror"
                                name="nilai_penagihan" id="nilai_penagihan">
                            @error('nilai_penagihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="simpan-btn" class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2();
            $('#kd_sub_kegiatan').select2({
                dropdownParent: $('#tambah-penagihan')
            });
            $('#kode_rekening').select2({
                dropdownParent: $('#tambah-penagihan')
            });
            $('#sumber_dana').select2({
                dropdownParent: $('#tambah-penagihan')
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
            $('#tambah_rincian').on("click", function() {
                let no_bukti = document.getElementById('no_bukti').value;
                let tgl_bukti = document.getElementById('tgl_bukti').value;
                let skpd = document.getElementById('kd_skpd').value;
                let kontrak = document.getElementById('no_kontrak').value;
                if (no_bukti != '' && tgl_bukti != '' && skpd != '' && kontrak != '') {
                    $('#tambah-penagihan').modal('show')
                } else {
                    Swal.fire({
                        title: 'Harap isi kode, tanggal, nomor penagihan dan nomor kontrak',
                        confirmButtonColor: '#5b73e8',
                    })
                }
            });
            $('#tgl_bukti').on("change", function() {
                let tgl_bukti = this.value;
                $.ajax({
                    url: "{{ route('penagihan.cek_status_ang_new') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        tgl_bukti: tgl_bukti,
                    },
                    success: function(data) {
                        $('#status_anggaran').val(data.nama);
                    }
                })
            });
            $('#kd_sub_kegiatan').on("change", function() {
                let nm_sub_kegiatan = $(this).find(':selected').data('nama');
                let kd_sub_kegiatan = this.value;
                $("#nm_sub_kegiatan").val(nm_sub_kegiatan);
                let tgl_bukti = document.getElementById('tgl_bukti').value;
                $.ajax({
                    url: "{{ route('penagihan.cek_status_ang') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        tgl_bukti: tgl_bukti,
                    },
                    success: function(data) {
                        $('#status_angkas').val(data.status);
                    }
                })
                $.ajax({
                    url: "{{ route('penagihan.cari_rekening') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        kd_sub_kegiatan: kd_sub_kegiatan,
                    },
                    success: function(data) {
                        $('#kode_rekening').empty();
                        $('#kode_rekening').append(`<option value="0">Pilih Rekening</option>`);
                        $.each(data, function(index, data) {
                            $('#kode_rekening').append(
                                `<option value="${data.kd_rek6}" data-lalu="${data.lalu}" data-anggaran="${data.anggaran}" data-nama="${data.nm_rek6}">${data.kd_rek6} | ${data.kd_rek6} | ${data.nm_rek6} | ${data.lalu} | ${data.sp2d} | ${data.anggaran}</option>`
                            );
                        })
                    }
                })
            });
            $('#kode_rekening').on('change', function() {
                let selected = $(this).find('option:selected');
                let nm_rekening = selected.data('nama');
                let anggaran = selected.data('anggaran');
                let lalu = selected.data('lalu');
                let sisa = (anggaran - lalu);
                $("#nm_rekening").val(nm_rekening);
                $("#total_pagu").val(anggaran.toLocaleString('id-ID'));
                $("#realisasi_pagu").val(lalu.toLocaleString('id-ID'));
                $("#sisa_pagu").val(sisa.toLocaleString('id-ID'));
                // coba
            });
        });
    </script>
@endsection
