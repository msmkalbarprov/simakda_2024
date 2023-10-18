@extends('template.app')
@section('title', 'EDIT DPR | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    DAFTAR PENGELUARAN RILL
                </div>
                @if ($dpr->status == 1)
                    <div class="alert alert-warning alert-block">
                        <b style="font-size:16px">Sudah di Buat DPT!!</b>
                    </div>
                @endif
                <div class="card-body">
                    @csrf
                    {{-- NOMOR DAN TANGGAL DPR --}}
                    <div class="mb-3 row">
                        <label for="no_dpr" class="col-md-2 col-form-label">No. DPR</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_dpr" name="no_dpr" readonly
                                value="{{ $dpr->no_dpr }}">
                        </div>
                        <label for="tgl_dpr" class="col-md-2 col-form-label">Tanggal DPR</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_dpr" name="tgl_dpr"
                                value="{{ $dpr->tgl_dpr }}">
                        </div>
                    </div>
                    {{-- NOMOR URUT DAN JENIS BELANJA --}}
                    <div class="mb-3 row">
                        <label for="no_urut" class="col-md-2 col-form-label">No. Urut</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_urut" name="no_urut" readonly
                                value="{{ $dpr->no_urut }}">
                        </div>
                        <label for="jenis_belanja" class="col-md-2 col-form-label">Jenis Belanja</label>
                        <div class="col-md-4">
                            <select name="jenis_belanja" id="jenis_belanja" class="form-control select2-multiple">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                <option value="1" {{ $dpr->jenis_belanja == '1' ? 'selected' : '' }}>Perjalanan Dinas
                                </option>
                                <option value="2" {{ $dpr->jenis_belanja == '2' ? 'selected' : '' }}>Belanja Modal
                                </option>
                                <option value="3" {{ $dpr->jenis_belanja == '3' ? 'selected' : '' }}>Belanja
                                    Barang/Jasa
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- SKPD DAN NAMA SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="kd_skpd" readonly
                                value="{{ Auth::user()->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_skpd" readonly
                                value="{{ nama_skpd(Auth::user()->kd_skpd) }}">
                        </div>
                    </div>
                    {{-- NOMOR KKPD --}}
                    <div class="mb-3 row">
                        <label for="no_kkpd" class="col-md-2 col-form-label">Nomor KKPD</label>
                        <div class="col-md-10">
                            <select name="no_kkpd" id="no_kkpd" class="form-control select2-multiple">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($rek_kkpd as $kkpd)
                                    <option value="{{ $kkpd->no_kkpd }}" data-nama="{{ $kkpd->nm_kkpd }}"
                                        {{ $kkpd->no_kkpd == $dpr->no_kkpd ? 'selected' : '' }}>
                                        {{ $kkpd->no_kkpd }} | {{ $kkpd->nm_kkpd }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- PEMEGANG KKPD --}}
                    <div class="mb-3 row">
                        <label for="nm_kkpd" class="col-md-2 col-form-label">Nama KKPD</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="nm_kkpd" readonly value="{{ $dpr->nm_kkpd }}">
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="simpan" class="btn btn-primary btn-md"
                            {{ $dpr->status == '1' ? 'hidden' : '' }}>Simpan</button>
                        <a href="{{ route('dpr.index') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekening --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Rekening
                    <button type="button" style="float: right" id="tambah_rek" class="btn btn-primary btn-md" hidden>Tambah
                        Sub Kegiatan</button>
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_rekening" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No DPR</th>
                                <th>Kegiatan</th>
                                <th>Nama Kegiatan</th>
                                <th>Kode Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                                <th>Kode Sumber</th>
                                <th>Sumber</th>
                                <th>Kode Bukti</th>
                                <th>Bukti</th>
                                <th>Uraian</th>
                                <th>Kode Pembayaran</th>
                                <th>Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_belanja = 0;
                            @endphp
                            @foreach ($rincian_dpr as $rincian)
                                @php
                                    $total_belanja += $rincian->nilai;
                                @endphp
                                <tr>
                                    <td>{{ $rincian->no_dpr }}</td>
                                    <td>{{ $rincian->kd_sub_kegiatan }}</td>
                                    <td>{{ $rincian->nm_sub_kegiatan }}</td>
                                    <td>{{ $rincian->kd_rek6 }}</td>
                                    <td>{{ $rincian->nm_rek6 }}</td>
                                    <td>{{ rupiah($rincian->nilai) }}</td>
                                    <td>{{ $rincian->sumber }}</td>
                                    <td>{{ nama_sumber_dana($rincian->sumber) }}</td>
                                    <td>{{ $rincian->bukti }}</td>
                                    <td>{{ $rincian->bukti == '1' ? 'YA' : 'TIDAK' }}</td>
                                    <td>{{ $rincian->uraian }}</td>
                                    <td>{{ $rincian->pembayaran }}</td>
                                    <td>{{ nama_pembayaran($rincian->pembayaran) }}</td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total_belanja" class="col-md-8 col-form-label" style="text-align: right">Total
                            Belanja</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total_belanja" name="total_belanja"
                                value="{{ rupiah($total_belanja) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_kegiatan" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Rincian Tagihan KKPD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- SUB KEGIATAN -->
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Sub Kegiatan</label>
                        <div class="col-md-6">
                            <select class="form-control select2-modal" style=" width: 100%;" id="kd_sub_kegiatan"
                                name="kd_sub_kegiatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_sub_kegiatan" readonly
                                name="nm_sub_kegiatan">
                        </div>
                    </div>
                    <!-- REKENING -->
                    <div class="mb-3 row">
                        <label for="kd_rekening" class="col-md-2 col-form-label">Rekening</label>
                        <div class="col-md-6">
                            <select class="form-control select2-modal" style=" width: 100%;" id="kd_rekening"
                                name="kd_rekening">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_rekening" readonly name="nm_rekening">
                        </div>
                    </div>
                    <!-- SUMBER DANA -->
                    <div class="mb-3 row">
                        <label for="sumber" class="col-md-2 col-form-label">Sumber</label>
                        <div class="col-md-6">
                            <select class="form-control select2-modal" style=" width: 100%;" id="sumber"
                                name="sumber">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_sumber" readonly name="nm_sumber">
                        </div>
                    </div>
                    <!-- TOTAL SPD -->
                    <div class="mb-3 row">
                        <label for="total_spd" class="col-md-2 col-form-label">Total SPD</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="total_spd" id="total_spd"
                                style="text-align: right">
                        </div>
                        <label for="realisasi_spd" class="col-md-2 col-form-label">Realisasi</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="realisasi_spd" id="realisasi_spd"
                                style="text-align: right">
                        </div>
                        <label for="sisa_spd" class="col-md-2 col-form-label">Sisa</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="sisa_spd" id="sisa_spd"
                                style="text-align: right">
                        </div>
                    </div>
                    <!-- ANGKAS -->
                    <div class="mb-3 row">
                        <label for="total_angkas" class="col-md-2 col-form-label">Total Anggaran Kas</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="total_angkas" id="total_angkas"
                                style="text-align: right">
                        </div>
                        <label for="realisasi_angkas" class="col-md-2 col-form-label">Realisasi</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="realisasi_angkas"
                                id="realisasi_angkas" style="text-align: right">
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
                        <label for="realisasi_anggaran" class="col-md-2 col-form-label">Realisasi</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="realisasi_anggaran"
                                id="realisasi_anggaran" style="text-align: right">
                        </div>
                        <label for="sisa_anggaran" class="col-md-2 col-form-label">Sisa</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="sisa_anggaran" id="sisa_anggaran"
                                style="text-align: right">
                        </div>
                    </div>
                    <!-- NILAI SUMBER DANA -->
                    <div class="mb-3 row">
                        <label for="total_sumber" class="col-md-2 col-form-label">Nilai Sumber Dana</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="total_sumber" id="total_sumber"
                                style="text-align: right">
                        </div>
                        <label for="realisasi_sumber" class="col-md-2 col-form-label">Realisasi</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="realisasi_sumber"
                                id="realisasi_sumber" style="text-align: right">
                        </div>
                        <label for="sisa_sumber" class="col-md-2 col-form-label">Sisa</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="sisa_sumber" id="sisa_sumber"
                                style="text-align: right">
                        </div>
                    </div>
                    <!-- Status Anggaran, Status Anggaran Kas, Sisa Kas Bank -->
                    <div class="mb-3 row">
                        <label for="status_anggaran" class="col-md-2 col-form-label">Status Anggaran</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="status_anggaran"
                                id="status_anggaran">
                        </div>
                        <label for="status_angkas" class="col-md-2 col-form-label">Status Anggaran Kas</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="status_angkas" id="status_angkas">
                        </div>
                        <label for="sisa_kas" class="col-md-2 col-form-label">Sisa Kas KKPD</label>
                        <div class="col-md-2">
                            <input type="text" readonly class="form-control" name="sisa_kas" id="sisa_kas"
                                style="text-align: right">
                        </div>
                    </div>
                    {{-- URAIAN --}}
                    <div class="mb-3 row">
                        <label for="uraian" class="col-md-2 col-form-label">Uraian</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control" id="uraian" name="uraian"></textarea>
                        </div>
                    </div>
                    {{-- PEMBAYARAN --}}
                    <div class="mb-3 row">
                        <label for="jarak" class="col-md-8 col-form-label"></label>
                        <label for="pembayaran" class="col-md-2 col-form-label">Pembayaran</label>
                        <div class="col-md-2">
                            <select class="form-control select2-modal" style=" width: 100%;" id="pembayaran"
                                name="pembayaran">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1" data-nama="KATALOG">KATALOG</option>
                                <option value="2" data-nama="TOKO DARING">TOKO DARING</option>
                                <option value="3" data-nama="LPSE">LPSE</option>
                                <option value="4" data-nama="LAIN-LAIN">LAIN-LAIN</option>
                            </select>
                        </div>
                    </div>
                    {{-- BUKTI --}}
                    <div class="mb-3 row">
                        <label for="jarak" class="col-md-8 col-form-label"></label>
                        <label for="bukti" class="col-md-2 col-form-label">Bukti</label>
                        <div class="col-md-2">
                            <select class="form-control select2-modal" style=" width: 100%;" id="bukti"
                                name="bukti">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1" data-nama="Ya">Ya</option>
                                <option value="2" data-nama="Tidak">Tidak</option>
                            </select>
                        </div>
                    </div>
                    {{-- Nilai --}}
                    <div class="mb-3 row">
                        <label for="jarak" class="col-md-8 col-form-label"></label>
                        <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="nilai" id="nilai"
                                style="text-align: right" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                        </div>
                    </div>
                    {{-- Simpan --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button id="simpan_rekening" class="btn btn-md btn-primary"
                                {{ $dpr->status == '1' ? 'hidden' : '' }}>Simpan</button>
                            <button type="button" class="btn btn-md btn-warning" data-bs-dismiss="modal">Keluar</button>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="total_input_rekening" style="text-align: right"
                        class="col-md-9 col-form-label">Total</label>
                    <div class="col-md-3" style="padding-right: 30px">
                        <input type="text" width="100%" class="form-control"
                            style="text-align: right;background-color:white;border:none;" readonly
                            name="total_input_rekening" id="total_input_rekening" value="{{ rupiah($total_belanja) }}">
                    </div>
                </div>
                <div class="card" style="margin: 4px">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered border-primary mb-0" style="width: 100%"
                                id="input_rekening">
                                <thead>
                                    <tr>
                                        <th>No DPR</th>
                                        <th>Kegiatan</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Kode Rekening</th>
                                        <th>Nama Rekening</th>
                                        <th>Rupiah</th>
                                        <th>Kode Sumber</th>
                                        <th>Sumber</th>
                                        <th>Kode Bukti</th>
                                        <th>Bukti</th>
                                        <th>Uraian</th>
                                        <th>Kode Pembayaran</th>
                                        <th>Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rincian_dpr as $rincian)
                                        <tr>
                                            <td>{{ $rincian->no_dpr }}</td>
                                            <td>{{ $rincian->kd_sub_kegiatan }}</td>
                                            <td>{{ $rincian->nm_sub_kegiatan }}</td>
                                            <td>{{ $rincian->kd_rek6 }}</td>
                                            <td>{{ $rincian->nm_rek6 }}</td>
                                            <td>{{ rupiah($rincian->nilai) }}</td>
                                            <td>{{ $rincian->sumber }}</td>
                                            <td>{{ nama_sumber_dana($rincian->sumber) }}</td>
                                            <td>{{ $rincian->bukti }}</td>
                                            <td>{{ $rincian->bukti == '1' ? 'YA' : 'TIDAK' }}</td>
                                            <td>{{ $rincian->uraian }}</td>
                                            <td>{{ $rincian->pembayaran }}</td>
                                            <td>{{ nama_pembayaran($rincian->pembayaran) }}</td>
                                            <td>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.dpr.js.edit')
@endsection
