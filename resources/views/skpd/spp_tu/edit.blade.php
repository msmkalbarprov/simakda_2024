@extends('template.app')
@section('title', 'Edit SPP TU | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input SPP
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NO SPP dan Tanggal --}}
                    <div class="mb-3 row">
                        <label for="no_spp" class="col-md-2 col-form-label">No. SPP</label>
                        <div class="col-md-4">
                            <div class="md-form input-group mt-md-0 mb-0">
                                <input type="text" class="form-control" id="no_spp" name="no_spp" readonly
                                    value="{{ $spp->no_spp }}">
                                <span class="input-group-btn">
                                    <button type="button" id="cari" class="btn btn-primary" disabled><i
                                            class="uil-refresh"></i></button>
                                </span>
                            </div>
                        </div>
                        <label for="tgl_spp" class="col-md-2 col-form-label">Tanggal SPP</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_spp" name="tgl_spp" required
                                value="{{ $spp->tgl_spp }}">
                            <input class="form-control" type="date" id="tgl_lalu" name="tgl_lalu" required readonly
                                hidden value="{{ tanggal_spp_lalu() }}">
                            <input class="form-control" type="text" id="no_urut" name="no_urut" required readonly
                                hidden>
                        </div>
                    </div>
                    {{-- Beban dan Bulan --}}
                    <div class="mb-3 row">
                        <label for="beban" class="col-md-2 col-form-label">Beban</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%;" id="beban"
                                name="beban">
                                <option value="3" selected>TU</option>
                            </select>
                        </div>
                        <label for="bulan" class="col-md-2 col-form-label">Bulan</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%" id="bulan" name="bulan"
                                data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Bulan">
                                    <option value="" disabled selected>...Pilih Kebutuhan Bulan... </option>
                                    <option value="1" {{ $spp->bulan == 1 ? 'selected' : '' }}>Januari</option>
                                    <option value="2" {{ $spp->bulan == 2 ? 'selected' : '' }}>Februari</option>
                                    <option value="3" {{ $spp->bulan == 3 ? 'selected' : '' }}>Maret</option>
                                    <option value="4" {{ $spp->bulan == 4 ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ $spp->bulan == 5 ? 'selected' : '' }}>Mei</option>
                                    <option value="6" {{ $spp->bulan == 6 ? 'selected' : '' }}>Juni</option>
                                    <option value="7" {{ $spp->bulan == 7 ? 'selected' : '' }}>Juli</option>
                                    <option value="8" {{ $spp->bulan == 8 ? 'selected' : '' }}>Agustus</option>
                                    <option value="9" {{ $spp->bulan == 9 ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ $spp->bulan == 10 ? 'selected' : '' }}>Oktober</option>
                                    <option value="11" {{ $spp->bulan == 11 ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ $spp->bulan == 12 ? 'selected' : '' }}>Desember</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    {{-- SKPD dan Nama SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $skpd->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ $skpd->nm_skpd }}">
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- No SPD --}}
                    <div class="mb-3 row">
                        <label for="no_spd" class="col-md-2 col-form-label">No. SPD</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" id="no_spd" name="no_spd" required readonly
                                value="{{ $spp->no_spd }}">
                        </div>
                    </div>
                    {{-- Kode Sub Kegiatan --}}
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Kode Sub Kegiatan</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" id="kd_sub_kegiatan" name="kd_sub_kegiatan"
                                required readonly value="{{ $spp->kd_sub_kegiatan }}">
                            <small>(Sub Kegiatan dari SPD yang dipilih)</small>
                        </div>
                    </div>
                    {{-- Rekening Bank dan Nama Rekening --}}
                    <div class="mb-3 row">
                        <label for="rekening" class="col-md-2 col-form-label">Rekening Bank</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%;" id="rekening"
                                name="rekening">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_rekening as $rekening)
                                    <option value="{{ $rekening->rekening }}" data-nm_bank="{{ $rekening->nm_bank }}"
                                        data-bank="{{ $rekening->bank }}" data-nama="{{ $rekening->nm_rekening }}"
                                        data-npwp="{{ $rekening->npwp }}"
                                        {{ $rekening->rekening == $spp->no_rek ? 'selected' : '' }}>
                                        {{ $rekening->rekening }} | {{ $rekening->nm_rekening }} | {{ $rekening->npwp }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label for="nm_rekening" class="col-md-2 col-form-label">Nama Rekening</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_rekening" name="nm_rekening" required
                                readonly value="{{ $spp->nmrekan }}">
                        </div>
                    </div>
                    {{-- Kode dan Nama Bank --}}
                    <div class="mb-3 row">
                        <label for="bank" class="col-md-2 col-form-label">Kode Bank</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="bank" name="bank" required readonly
                                value="{{ $spp->bank }}">
                        </div>
                        <label for="nm_bank" class="col-md-2 col-form-label">Nama Bank</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_bank" name="nm_bank" required readonly
                                value="{{ nama_bank($spp->bank) }}">
                        </div>
                    </div>

                    {{-- NPWP --}}
                    <div class="mb-3 row">
                        <label for="npwp" class="col-md-2 col-form-label">NPWP</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="npwp" name="npwp" required readonly
                                value="{{ $spp->npwp }}">
                        </div>
                    </div>
                    {{-- Keperluan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keperluan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan">{{ $spp->keperluan }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-6 row" style="text-align;center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" hidden class="btn btn-primary btn-md">Simpan</button>
                            <input type="hidden" name="kode_spp" id="kode_spp">
                            <a href="{{ route('spp_tu.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rincian SPP GU --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Rincian SPP TU
                    <button type="button" style="float: right" id="tambah_rincian"
                        class="btn btn-success btn-md">Tambah Rincian</button>
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_spp" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Nama Kegiatan</th>
                                <th>Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Sumber</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($detail_spp as $detail)
                                @php
                                    $total += $detail->nilai;
                                @endphp
                                <tr>
                                    <td>{{ $detail->kd_sub_kegiatan }}</td>
                                    <td>{{ $detail->nm_sub_kegiatan }}</td>
                                    <td>{{ $detail->kd_rek6 }}</td>
                                    <td>{{ $detail->nm_rek6 }}</td>
                                    <td>{{ $detail->sumber }}</td>
                                    <td>{{ rupiah($detail->nilai) }}</td>
                                    <td>
                                        <a href="javascript:void(0);"
                                            onclick="hapus('{{ $detail->kd_sub_kegiatan }}','{{ $detail->kd_rek6 }}','{{ $detail->nilai }}')"
                                            class="btn btn-danger btn-sm"><i class="uil-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total" class="col-md-8 col-form-label" style="text-align: right">Total</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total" name="total" value="{{ rupiah($total) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_rincian" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control" name="skpd" id="skpd"
                                value="{{ $skpd->kd_skpd }}">
                        </div>
                    </div>
                    {{-- Kode Kegiatan --}}
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Kode Kegiatan</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control" name="kegiatan" id="kegiatan">
                        </div>
                    </div>
                    <!-- Kode Rekening -->
                    <div class="mb-3 row">
                        <label for="kode_rekening" class="col-md-2 col-form-label">Kode Rekening</label>
                        <div class="col-md-10">
                            <select class="form-control select2-modal" style=" width: 100%;" id="kode_rekening"
                                name="kode_rekening">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    <!-- Sumber Dana -->
                    <div class="mb-3 row">
                        <label for="sumber" class="col-md-2 col-form-label">Sumber Dana</label>
                        <div class="col-md-10">
                            <select class="form-control select2-modal" style=" width: 100%;" id="sumber"
                                name="sumber">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    <!-- Total SPD -->
                    <div class="mb-3 row">
                        <label for="total_spd" class="col-md-2 col-form-label">Total SPD</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="total_spd" id="total_spd"
                                style="text-align: right">
                        </div>
                        <label for="lalu_spd" class="col-md-2 col-form-label">Lalu</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="lalu_spd" id="lalu_spd"
                                style="text-align: right">
                        </div>
                        <label for="sisa_spd" class="col-md-2 col-form-label">Sisa</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="sisa_spd" id="sisa_spd"
                                style="text-align: right">
                        </div>
                    </div>
                    <!-- Anggaran Kas -->
                    <div class="mb-3 row">
                        <label for="total_angkas" class="col-md-2 col-form-label">Anggaran Kas</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="total_angkas" id="total_angkas"
                                style="text-align: right">
                        </div>
                        <label for="lalu_angkas" class="col-md-2 col-form-label">Lalu</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="lalu_angkas" id="lalu_angkas"
                                style="text-align: right">
                        </div>
                        <label for="sisa_angkas" class="col-md-2 col-form-label">Sisa</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="sisa_angkas" id="sisa_angkas"
                                style="text-align: right">
                        </div>
                    </div>
                    <!-- Anggaran -->
                    <div class="mb-3 row">
                        <label for="total_anggaran" class="col-md-2 col-form-label">Anggaran</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="total_anggaran"
                                id="total_anggaran" style="text-align: right">
                        </div>
                        <label for="lalu_anggaran" class="col-md-2 col-form-label">Lalu</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="lalu_anggaran" id="lalu_anggaran"
                                style="text-align: right">
                        </div>
                        <label for="sisa_anggaran" class="col-md-2 col-form-label">Sisa</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="sisa_anggaran" id="sisa_anggaran"
                                style="text-align: right">
                        </div>
                    </div>
                    <!-- Sumber Dana -->
                    <div class="mb-3 row">
                        <label for="total_sumber" class="col-md-2 col-form-label">Sumber Dana</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="total_sumber" id="total_sumber"
                                style="text-align: right">
                        </div>
                        <label for="lalu_sumber" class="col-md-2 col-form-label">Lalu</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="lalu_sumber" id="lalu_sumber"
                                style="text-align: right">
                        </div>
                        <label for="sisa_sumber" class="col-md-2 col-form-label">Sisa</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="sisa_sumber" id="sisa_sumber"
                                style="text-align: right">
                        </div>
                    </div>
                    <!-- Status Anggaran, Status Anggaran Kas, Nilai -->
                    <div class="mb-3 row">
                        <label for="status_anggaran" class="col-md-2 col-form-label">Status Anggaran</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="status_anggaran"
                                id="status_anggaran" value="{{ status_anggaran_new()->nama }}">
                        </div>
                        <label for="status_angkas" class="col-md-2 col-form-label">Status Anggaran Kas</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="status_angkas" id="status_angkas"
                                value="{{ status_angkas_penagihan() }}">
                        </div>
                        <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="nilai" id="nilai"
                                style="text-align: right" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                        </div>
                    </div>
                    {{-- Simpan --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button id="simpan_rincian" class="btn btn-md btn-primary">Simpan</button>
                            <button type="button" class="btn btn-md btn-warning" data-bs-dismiss="modal">Keluar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.spp_tu.js.edit');
@endsection
