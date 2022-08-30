@extends('template.app')
@section('title', 'Edit Penagihan | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Data Penagihan
                </div>
                <div class="card-body">
                    @csrf
                    <!-- No Tersimpan -->
                    <div class="mb-3 row">
                        <label for="no_tersimpan" class="col-md-2 col-form-label">No Tersimpan</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control @error('no_tersimpan') is-invalid @enderror"
                                name="no_tersimpan" id="no_tersimpan" value="{{ $data_tagih->no_bukti }}">
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
                                id="no_bukti" name="no_bukti" required value="{{ $data_tagih->no_bukti }}">
                            @error('no_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="tgl_bukti" class="col-md-2 col-form-label">Tanggal Penagihan</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control @error('tgl_bukti') is-invalid @enderror"
                                value="{{ $data_tagih->tgl_bukti }}" id="tgl_bukti" name="tgl_bukti">
                            @error('tgl_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Kode SKPD Nama SKPD -->
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode OPD / Unit</label>
                        <div class="col-md-4">
                            <input type="text" readonly name="kd_skpd" id="kd_skpd" value="{{ $data_tagih->kd_skpd }}"
                                class="form-control @error('kd_skpd') is-invalid @enderror">
                            @error('kd_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama OPD / Unit</label>
                        <div class="col-md-4">
                            <input class="form-control @error('nm_skpd') is-invalid @enderror"
                                value="{{ $data_tagih->nm_skpd }}" readonly type="text"
                                placeholder="Silahkan isi dengan nama pelaksana pekerjaan" id="nm_skpd" name="nm_skpd">
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
                                placeholder="Silahkan isi dengan keterangan" id="ket" name="ket">{{ $data_tagih->ket }}</textarea>
                            @error('ket')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="ket_bast" class="col-md-2 col-form-label">Keterangan (BA)</label>
                        <div class="col-md-4">
                            <textarea type="text" name="ket_bast" placeholder="Silahkan isi dengan keterangan (BA)" id="ket_bast"
                                class="form-control @error('ket_bast') is-invalid @enderror">{{ $data_tagih->ket_bast }}</textarea>
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
                                    <option value="1" {{ $data_tagih->status == '1' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="2" {{ $data_tagih->status == '2' ? 'selected' : '' }}>Belum
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
                                    <option value="" {{ $data_tagih->jenis == '' ? 'selected' : '' }}>Tanpa Termin /
                                        Sekali Pembayaran</option>
                                    <option value="1" {{ $data_tagih->jenis == '1' ? 'selected' : '' }}>Konstruksi
                                        Dalam
                                        Pengerjaan</option>
                                    <option value="2" {{ $data_tagih->jenis == '2' ? 'selected' : '' }}>Uang Muka
                                    </option>
                                    <option value="3" {{ $data_tagih->jenis == '3' ? 'selected' : '' }}>Hutang Tahun
                                        Lalu</option>
                                    <option value="4" {{ $data_tagih->jenis == '4' ? 'selected' : '' }}>Perbulan
                                    </option>
                                    <option value="5" {{ $data_tagih->jenis == '5' ? 'selected' : '' }}>Bertahap
                                    </option>
                                    <option value="6" {{ $data_tagih->jenis == '6' ? 'selected' : '' }}>Berdasarkan
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
                                        <option value="{{ $kontrak->no_kontrak }}" data-nilai="{{ $kontrak->nilai }}"
                                            data-lalu="{{ $kontrak->lalu }}"
                                            {{ $data_tagih->kontrak == $kontrak->no_kontrak ? 'selected' : '' }}>
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
                                style=" width: 100%;" id="rekanan" name="rekanan" data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Rekanan">
                                    <option value="" disabled selected>Nama Rekanan | Rekening | NPWP</option>
                                    @foreach ($daftar_rekanan as $rekanan)
                                        <option value="{{ $rekanan->nm_rekening }}"
                                            {{ $data_tagih->nm_rekanan == $rekanan->nm_rekening ? 'selected' : '' }}>
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
                        <button id="simpan_penagihan" class="btn btn-primary btn-md">Simpan</button>
                        <a href="{{ route('penagihan.index') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rincian Penagihan --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Rincian Penagihan
                    <button type="button" style="float: right" id="tambah_rincian"
                        class="btn btn-primary btn-sm">Tambah Rincian</button>
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_penagihan" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No Bukti</th> {{-- hidden --}}
                                <th>No SP2D</th> {{-- hidden --}}
                                <th>Kode Sub Kegiatan</th>
                                <th>Nama Sub Kegiatan</th> {{-- hidden --}}
                                <th>Kode Rekening</th>
                                <th>REK 13</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                                <th>Lalu</th> {{-- hidden --}}
                                <th>SP2D</th> {{-- hidden --}}
                                <th>Anggaran</th>
                                <th>Sumber</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail_tagih as $data)
                                <tr>
                                    <td>{{ $data->no_bukti }}</td>
                                    <td>{{ $data->no_sp2d }}</td>
                                    <td>{{ $data->kd_sub_kegiatan }}</td>
                                    <td>{{ $data->nm_sub_kegiatan }}</td>
                                    <td>{{ $data->kd_rek6 }}</td>
                                    <td>{{ $data->kd_rek }}</td>
                                    <td>{{ $data->nm_rek6 }}</td>
                                    <td>{{ $data->nilai }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $data->sumber }}</td>
                                    <td><a href="javascript:void(0);"
                                            onclick="deleteData('{{ $data->no_bukti }}','{{ $data->kd_sub_kegiatan }}','{{ $data->kd_rek }}','{{ $data->sumber }}','{{ $data->nilai }}')"
                                            class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Totalan --}}
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="total_nilai" class="col-md-4 col-form-label">Total</label>
                        <div class="col-md-8">
                            <input type="text" readonly style="text-align: right" class="form-control"
                                name="total_nilai" id="total_nilai" value="{{ $data_tagih->total }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nilai_lalu" class="col-md-4 col-form-label">Nilai
                            Lalu</label>
                        <div class="col-md-8">
                            <input type="text" readonly style="text-align: right" class="form-control"
                                name="nilai_lalu" id="nilai_lalu" value="{{ $data_tagih->total }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nilai_kontrak" class="col-md-4 col-form-label">Nilai
                            Kontrak</label>
                        <div class="col-md-8">
                            <input type="text" readonly style="text-align: right" class="form-control"
                                name="nilai_kontrak" id="nilai_kontrak" value="{{ $kontrak->nilai }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="sisa_kontrak" class="col-md-4 col-form-label">Sisa
                            Kontrak</label>
                        <div class="col-md-8">
                            <input type="text" readonly style="text-align: right" class="form-control"
                                name="sisa_kontrak" id="sisa_kontrak"
                                value="{{ $kontrak->nilai - $data_tagih->total }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="tambah-penagihan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
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
                        <label for="realisasi_sumber_dana" class="col-md-1 col-form-label">Realisasi</label>
                        <div class="col-md-3">
                            <input type="text" readonly
                                class="form-control @error('realisasi_sumber_dana') is-invalid @enderror"
                                name="realisasi_sumber_dana" id="realisasi_sumber_dana">
                            @error('realisasi_sumber_dana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="sisa_sumber_dana" class="col-md-1 col-form-label">Sisa</label>
                        <div class="col-md-3">
                            <input type="text" readonly
                                class="form-control @error('sisa_sumber_dana') is-invalid @enderror"
                                name="sisa_sumber_dana" id="sisa_sumber_dana">
                            @error('sisa_sumber_dana')
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
                            <input type="text" class="form-control @error('nilai_penagihan') is-invalid @enderror"
                                name="nilai_penagihan" id="nilai_penagihan" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                                data-type="currency">
                            @error('nilai_penagihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button id="simpan-btn" class="btn btn-md btn-primary">Simpan</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="total_input_penagihan" style="text-align: right"
                        class="col-md-9 col-form-label">Total</label>
                    <div class="col-md-3" style="padding-right: 30px">
                        <input type="text" width="100%" class="form-control" value="{{ $data_tagih->total }}"
                            readonly name="total_input_penagihan" id="total_input_penagihan">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" style="width: 100%" id="input_penagihan">
                        <thead>
                            <tr>
                                <th>No Bukti</th> {{-- hidden --}}
                                <th>No SP2D</th> {{-- hidden --}}
                                <th>Kode Sub Kegiatan</th>
                                <th>Nama Sub Kegiatan</th> {{-- hidden --}}
                                <th>REK LO</th>
                                <th>REK 13</th>
                                <th>Nama Rekening</th>
                                <th>Rupiah</th>
                                <th>Lalu</th> {{-- hidden --}}
                                <th>SP2D</th> {{-- hidden --}}
                                <th>Anggaran</th>
                                <th>Sumber</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail_tagih as $data)
                                <tr>
                                    <td>{{ $data->no_bukti }}</td>
                                    <td>{{ $data->no_sp2d }}</td>
                                    <td>{{ $data->kd_sub_kegiatan }}</td>
                                    <td>{{ $data->nm_sub_kegiatan }}</td>
                                    <td>{{ $data->kd_rek6 }}</td>
                                    <td>{{ $data->kd_rek }}</td>
                                    <td>{{ $data->nm_rek6 }}</td>
                                    <td>{{ $data->nilai }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $data->sumber }}</td>
                                    <td><a href="javascript:void(0);"
                                            onclick="deleteData('{{ $data->no_bukti }}', '{{ $data->kd_sub_kegiatan }}', '{{ $data->kd_rek }}','{{ $data->sumber }}','{{ $data->nilai }}');"
                                            class="btn btn-danger btn-sm" id="delete"><i
                                                class="fas fa-trash-alt"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('penatausahaan.pengeluaran.penagihan.js.edit')
@endsection
