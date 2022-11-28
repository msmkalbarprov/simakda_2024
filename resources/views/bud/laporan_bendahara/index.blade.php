@extends('template.app')
@section('title', 'Laporan bendahara | SIMAKDA')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'Laporan Bendahara Umum Daerah' }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ 'App' }}</a></li>
                        <li class="breadcrumb-item">{{ 'Laporan Bendahara Umum Daerah' }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="realisasi_pendapatan">
                <div class="card-body">
                    {{ 'Realisasi Pendapatan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="pembantu_penerimaan">
                <div class="card-body">
                    {{ 'Buku Kas Pembantu Penerimaan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="bku">
                <div class="card-body">
                    {{ 'BKU (B IX)' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="pajak_daerah">
                <div class="card-body">
                    {{ 'Penerimaan Pajak Daerah' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="rekap_gaji">
                <div class="card-body">
                    {{ 'Rekap Gaji' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="buku_besar_kasda">
                <div class="card-body">
                    {{ 'Buku Besar Kasda' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="buku_kas_pembantu_pengeluaran">
                <div class="card-body">
                    {{ 'Buku Kas Pembantu Pengeluaran' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="retribusi">
                <div class="card-body">
                    {{ 'Retribusi' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="register_cp">
                <div class="card-body">
                    {{ 'Register CP' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="daftar_potongan_pajak">
                <div class="card-body">
                    {{ 'Daftar Potongan Pajak' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="daftar_pengeluaran">
                <div class="card-body">
                    {{ 'Daftar Pengeluaran' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="daftar_penerimaan">
                <div class="card-body">
                    {{ 'Daftar Penerimaan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="penerimaan_non_pendapatan">
                <div class="card-body">
                    {{ 'Penerimaan Non Pendapatan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="transfer_dana">
                <div class="card-body">
                    {{ 'Transfer Dana' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="restitusi">
                <div class="card-body">
                    {{ 'Restitusi' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="rth">
                <div class="card-body">
                    {{ 'RTH' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="dth">
                <div class="card-body">
                    {{ 'DTH' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="buku_pembantu_pengeluaran_non_pendapatan">
                <div class="card-body">
                    {{ 'Buku Pembantu Pengeluaran Non Pendapatan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="register_koreksi">
                <div class="card-body">
                    {{ 'Register Koreksi' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="laporan_kas_harian_kasda">
                <div class="card-body">
                    {{ 'Laporan Kas Harian Kasda' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="uyhd">
                <div class="card-body">
                    {{ 'UYHD' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="laporan_koreksi_pengeluaran">
                <div class="card-body">
                    {{ 'Laporan Koreksi Pengeluaran' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="realisasi_kasda">
                <div class="card-body">
                    {{ 'Realisasi Kasda' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="laporan_koreksi_penerimaan">
                <div class="card-body">
                    {{ 'Laporan Koreksi Penerimaan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    {{-- modal cetak realisasi pendapatan --}}
    <div id="modal_realisasi_pendapatan" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><label for="labelcetak" id="labelcetak"></label></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Pilihan --}}
                    <div class="mb-3 row" id="row-hidden">
                        <div class="col-md-6">
                            <label for="kd_skpd" class="form-label">Pilih</label><br>
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_keseluruhan_realisasi_pendapatan" value="1">
                                <label class="form-check-label" for="pilihan">Keseluruhan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_skpd_realisasi_pendapatan" value="2">
                                <label class="form-check-label" for="pilihan">SKPD</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_unit_realisasi_pendapatan" value="3">
                                <label class="form-check-label" for="pilihan">Unit</label>
                            </div>
                        </div>
                    </div>
                    {{-- SKPD --}}
                    <div class="mb-3 row" id="pilih_skpd_realisasi_pendapatan">
                        <div class="col-md-6">
                            <label for="kd_skpd" class="form-label">SKPD</label>
                            <select class="form-control select2-realisasi_pendapatan" style=" width: 100%;"
                                id="kd_skpd_realisasi_pendapatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_skpd as $skpd)
                                    <option value="{{ $skpd->kd_skpd }}" data-nama="{{ $skpd->nm_skpd }}">
                                        {{ $skpd->kd_skpd }} | {{ $skpd->nm_skpd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nm_skpd" class="form-label"></label>
                            <input type="text" style="border:none;background-color:white" class="form-control"
                                readonly id="nm_skpd_realisasi_pendapatan">
                        </div>
                    </div>
                    {{-- Unit --}}
                    <div class="mb-3 row" id="pilih_unit_realisasi_pendapatan">
                        <div class="col-md-6">
                            <label for="kd_unit" class="form-label">Unit</label>
                            <select class="form-control select2-realisasi_pendapatan" style=" width: 100%;"
                                id="kd_unit_realisasi_pendapatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_skpd as $skpd)
                                    <option value="{{ $skpd->kd_skpd }}" data-nama="{{ $skpd->nm_skpd }}">
                                        {{ $skpd->kd_skpd }} | {{ $skpd->nm_skpd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nm_unit" class="form-label"></label>
                            <input type="text" style="border:none;background-color:white" class="form-control"
                                readonly id="nm_unit_realisasi_pendapatan">
                        </div>
                    </div>
                    {{-- Periode dan Jenis --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="periode" class="form-label">Periode</label>
                            <select class="form-control select2-realisasi_pendapatan" style=" width: 100%;"
                                id="periode_realisasi_pendapatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="jenis" class="form-label">Jenis</label>
                            <select class="form-control select2-realisasi_pendapatan" id="jenis_realisasi_pendapatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="4">Jenis</option>
                                <option value="8">Objek</option>
                                <option value="12">Rincian Objek</option>
                            </select>
                        </div>
                    </div>
                    {{-- Anggaran dan Tanggal TTD --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="anggaran" class="form-label">Anggaran</label>
                            <select class="form-control select2-realisasi_pendapatan" style=" width: 100%;"
                                id="anggaran_realisasi_pendapatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($jns_anggaran as $anggaran)
                                    <option value="{{ $anggaran->kode }}" data-nama="{{ $anggaran->nama }}">
                                        {{ $anggaran->kode }} | {{ $anggaran->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_ttd" class="form-label">Tanggal TTD</label>
                            <input type="date" id="tgl_ttd_realisasi_pendapatan" class="form-control">
                        </div>
                    </div>
                    {{-- Penandatangan --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="penandatangan" class="form-label">Penandatangan</label>
                            <select class="form-control select2-realisasi_pendapatan" style=" width: 100%;"
                                id="ttd_realisasi_pendapatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($bud as $ttd)
                                    <option value="{{ $ttd->nip }}">{{ $ttd->nip }} | {{ $ttd->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="spasi_realisasi_pendapatan" class="form-label">Ukuran Baris</label>
                            <input type="number" value="1" min="1" class="form-control"
                                id="spasi_realisasi_pendapatan" name="spasi_realisasi_pendapatan">
                        </div>
                    </div>
                    {{-- Pilihan Cetak --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-md cetak_realisasi_pendapatan"
                                data-jenis="pdf"> PDF</button>
                            <button type="button" class="btn btn-dark btn-md cetak_realisasi_pendapatan"
                                data-jenis="layar">Layar</button>
                            <button type="button" class="btn btn-dark btn-md cetak_realisasi_pendapatan"
                                data-jenis="excel">Excel</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal cetak realisasi pendapatan  --}}

    {{-- modal cetak buku kas pembantu penerimaan --}}
    <div id="modal_pembantu_penerimaan" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><label for="labelcetak" id="labelcetak"></label></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Pilihan --}}
                    <div class="mb-3 row" id="row-hidden">
                        <div class="col-md-6">
                            <label for="" class="form-label">Pilih</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_tgl_pembantu_penerimaan" value="2">
                                <label class="form-check-label" for="pilihan">Per Tanggal</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_periode_pembantu_penerimaan" value="3">
                                <label class="form-check-label" for="pilihan">Per Periode</label>
                            </div>
                        </div>
                    </div>
                    {{-- Per Tanggal --}}
                    <div class="mb-3 row" id="pilih_tgl_pembantu_penerimaan">
                        <div class="col-md-6">
                            <label for="" class="form-label">Per Tanggal</label>
                            <input type="date" id="tgl_pembantu_penerimaan" class="form-control">
                        </div>
                    </div>
                    {{-- Per Periode --}}
                    <div class="mb-3 row" id="pilih_periode_pembantu_penerimaan">
                        <label for="kd_unit" class="form-label">Per Periode</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="periode1_pembantu_penerimaan">
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="periode2_pembantu_penerimaan">
                        </div>
                    </div>
                    {{-- Kuasa BUD --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="penandatangan" class="form-label">Kuasa BUD</label>
                            <select class="form-control select2-pembantu_penerimaan" style=" width: 100%;"
                                id="ttd_pembantu_penerimaan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($bud as $ttd)
                                    <option value="{{ $ttd->nip }}">{{ $ttd->nip }} | {{ $ttd->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- No. Halaman dan Spasi --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="halaman_pembantu_penerimaan" class="form-label">No. Halaman</label>
                            <input type="number" value="1" min="1" class="form-control"
                                id="halaman_pembantu_penerimaan">
                        </div>
                        <div class="col-md-6">
                            <label for="spasi_pembantu_penerimaan" class="form-label">Spasi</label>
                            <input type="number" value="1" min="1" class="form-control"
                                id="spasi_pembantu_penerimaan">
                        </div>
                    </div>
                    {{-- Pilihan Cetak --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-md cetak_pembantu_penerimaan"
                                data-jenis="pdf"> PDF</button>
                            <button type="button" class="btn btn-dark btn-md cetak_pembantu_penerimaan"
                                data-jenis="layar">Layar</button>
                            <button type="button" class="btn btn-dark btn-md cetak_pembantu_penerimaan"
                                data-jenis="excel">Excel</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal cetak buku kas pembantu penerimaan --}}

    {{-- modal cetak bku (b ix) --}}
    <div id="modal_bku" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><label for="labelcetak" id="labelcetak"></label></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Pilihan --}}
                    <div class="mb-3 row" id="row-hidden">
                        <div class="col-md-6">
                            <label for="" class="form-label">Pilih</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_tgl_bku" value="2">
                                <label class="form-check-label" for="pilihan">Per Tanggal</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_periode_bku" value="3">
                                <label class="form-check-label" for="pilihan">Per Periode</label>
                            </div>
                        </div>
                    </div>
                    {{-- Per Tanggal --}}
                    <div class="mb-3 row" id="pilih_tgl_bku">
                        <div class="col-md-6">
                            <label for="" class="form-label">Per Tanggal</label>
                            <input type="date" id="tgl_bku" class="form-control">
                        </div>
                    </div>
                    {{-- Per Periode --}}
                    <div class="mb-3 row" id="pilih_periode_bku">
                        <label for="kd_unit" class="form-label">Per Periode</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="periode1_bku">
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="periode2_bku">
                        </div>
                    </div>
                    {{-- Kuasa BUD dan Jenis --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="ttd_bku" class="form-label">Kuasa BUD</label>
                            <select class="form-control select2-bku" style=" width: 100%;" id="ttd_bku">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($bud as $ttd)
                                    <option value="{{ $ttd->nip }}">{{ $ttd->nip }} | {{ $ttd->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="jenis" class="form-label">Jenis</label>
                            <select class="form-control select2-bku" style=" width: 100%;" id="jenis_bku">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1"> Tanpa Tanggal </option>
                                <option value="2"> Dengan Tanggal </option>
                                <option value="3"> Dengan Tanggal (Tanpa BLUD dan Jaspel)</option>
                                <option value="4"> Rincian (Sementara Hanya pertanggal)</option>
                            </select>
                        </div>
                    </div>
                    {{-- No. Halaman dan Nomor Urut --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="halaman_bku" class="form-label">No. Halaman</label>
                            <input type="number" value="1" min="1" class="form-control" id="halaman_bku">
                        </div>
                        <div class="col-md-6">
                            <label for="no_urut_bku" class="form-label">Nomor Urut</label>
                            <input type="number" value="1" min="1" class="form-control" id="no_urut_bku">
                        </div>
                    </div>
                    {{-- Pilihan Cetak --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-md cetak_bku" data-jenis="pdf"> PDF</button>
                            <button type="button" class="btn btn-dark btn-md cetak_bku"
                                data-jenis="layar">Layar</button>
                            <button type="button" class="btn btn-dark btn-md cetak_bku"
                                data-jenis="excel">Excel</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal cetak bku (b ix) --}}

    {{-- modal cetak penerimaan pajak daerah --}}
    <div id="modal_pajak_daerah" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><label for="labelcetak" id="labelcetak"></label></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Pilihan --}}
                    <div class="mb-3 row" id="row-hidden">
                        <div class="col-md-12">
                            <label for="" class="form-label">Pilih</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="pilihan_bulan_pajak_daerah"
                                    name="inlineRadioOptions">
                                <label class="form-check-label" for="pilihan">Per Bulan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="pilihan_tanggal_pajak_daerah"
                                    name="inlineRadioOptions">
                                <label class="form-check-label" for="pilihan">Per Tanggal</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="pilihan_pengirim_pajak_daerah"
                                    name="inlineRadioOptions">
                                <label class="form-check-label" for="pilihan">Per Pengirim</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="pilihan_wilayah_pajak_daerah"
                                    name="inlineRadioOptions">
                                <label class="form-check-label" for="pilihan">Per Wilayah</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="pilihan_rekap_pajak_daerah"
                                    name="inlineRadioOptions">
                                <label class="form-check-label" for="pilihan">Rekap</label>
                            </div>
                        </div>
                    </div>
                    {{-- Per Bulan --}}
                    <div class="mb-3 row" id="pilih_bulan_pajak_daerah">
                        <div class="col-md-6">
                            <label for="" class="form-label">Bulan</label>
                            <select id="bulan_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1"> Januari </option>
                                <option value="2"> Februari </option>
                                <option value="3"> Maret </option>
                                <option value="4"> April </option>
                                <option value="5"> Mei </option>
                                <option value="6"> Juni </option>
                                <option value="7"> Juli </option>
                                <option value="8"> Agustus </option>
                                <option value="9"> September </option>
                                <option value="10"> Oktober </option>
                                <option value="11"> November </option>
                                <option value="12"> Desember </option>
                            </select>
                        </div>
                    </div>
                    {{-- Per Tanggal --}}
                    <div class="mb-3 row" id="pilih_tanggal_pajak_daerah">
                        <div class="col-md-6">
                            <label for="" class="form-label">Tanggal Kas </label>
                            <input type="date" class="form-control" id="tgl_kas_pajak_daerah">
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Tanggal Kas Sebelumnya</label>
                            <input type="date" class="form-control" id="tgl_kas_sbl_pajak_daerah">
                        </div>
                    </div>
                    {{-- Per Pengirim --}}
                    <div class="mb-3 row" id="pilih_pengirim_pajak_daerah">
                        <label for="" class="form-label">Nama Pengirim</label>
                        <div class="col-md-6">
                            <select id="pengirim_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_pengirim as $pengirim)
                                    <option value="{{ $pengirim->kd_pengirim }}"
                                        data-nama="{{ $pengirim->nm_pengirim }}">{{ $pengirim->kd_pengirim }} |
                                        {{ $pengirim->nm_pengirim }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="nm_pengirim_pajak_daerah" readonly>
                        </div>
                        <br>
                        <div class="col-md-12">
                            <div class="form-check form-check-right">
                                <input class="form-check-input" type="radio" name="formRadiosRight"
                                    id="pilihan_tgl_pengirim_pajak_daerah">
                                <label class="form-check-label">
                                    Per Tanggal
                                </label>
                            </div>
                            <div class="form-check form-check-right">
                                <input class="form-check-input" type="radio" name="formRadiosRight"
                                    id="pilihan_bulan_pengirim_pajak_daerah">
                                <label class="form-check-label">
                                    Per Bulan
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- Per Tanggal Pengirim --}}
                    <div class="mb-3 row" id="pilih_tgl_pengirim_pajak_daerah">
                        <div class="col-md-6">
                            <label for="" class="form-label">Tanggal Kas </label>
                            <input type="date" class="form-control" id="tgl_kas_pengirim_pajak_daerah">
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Tanggal Kas Sebelumnya</label>
                            <input type="date" class="form-control" id="tgl_kas_sbl_pengirim_pajak_daerah">
                        </div>
                    </div>
                    {{-- Per Bulan Pengirim --}}
                    <div class="mb-3 row" id="pilih_bulan_pengirim_pajak_daerah">
                        <div class="col-md-6">
                            <label for="" class="form-label">Bulan</label>
                            <select id="bulan_pengirim1_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1"> Januari </option>
                                <option value="2"> Februari </option>
                                <option value="3"> Maret </option>
                                <option value="4"> April </option>
                                <option value="5"> Mei </option>
                                <option value="6"> Juni </option>
                                <option value="7"> Juli </option>
                                <option value="8"> Agustus </option>
                                <option value="9"> September </option>
                                <option value="10"> Oktober </option>
                                <option value="11"> November </option>
                                <option value="12"> Desember </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">s/d</label>
                            <select id="bulan_pengirim2_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1"> Januari </option>
                                <option value="2"> Februari </option>
                                <option value="3"> Maret </option>
                                <option value="4"> April </option>
                                <option value="5"> Mei </option>
                                <option value="6"> Juni </option>
                                <option value="7"> Juli </option>
                                <option value="8"> Agustus </option>
                                <option value="9"> September </option>
                                <option value="10"> Oktober </option>
                                <option value="11"> November </option>
                                <option value="12"> Desember </option>
                            </select>
                        </div>
                    </div>
                    {{-- Per Wilayah --}}
                    <div class="mb-3 row" id="pilih_wilayah_pajak_daerah">
                        <label for="" class="form-label">Nama Wilayah</label>
                        <div class="col-md-6">
                            <select id="wilayah_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_wilayah as $wilayah)
                                    <option value="{{ $wilayah->kd_wilayah }}" data-nama="{{ $wilayah->nm_wilayah }}">
                                        {{ $wilayah->kd_wilayah }} |
                                        {{ $wilayah->nm_wilayah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="nm_wilayah_pajak_daerah" readonly>
                        </div>
                        <br>
                        <div class="col-md-12">
                            <div class="form-check form-check-right">
                                <input class="form-check-input" type="radio" name="formRadiosRight"
                                    id="pilihan_tgl_wilayah_pajak_daerah">
                                <label class="form-check-label">
                                    Per Tanggal
                                </label>
                            </div>
                            <div class="form-check form-check-right">
                                <input class="form-check-input" type="radio" name="formRadiosRight"
                                    id="pilihan_bulan_wilayah_pajak_daerah">
                                <label class="form-check-label">
                                    Per Bulan
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- Per Tanggal Wilayah --}}
                    <div class="mb-3 row" id="pilih_tgl_wilayah_pajak_daerah">
                        <div class="col-md-6">
                            <label for="" class="form-label">Tanggal Kas </label>
                            <input type="date" class="form-control" id="tgl_kas_wilayah_pajak_daerah">
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">Tanggal Kas Sebelumnya</label>
                            <input type="date" class="form-control" id="tgl_kas_sbl_wilayah_pajak_daerah">
                        </div>
                    </div>
                    {{-- Per Bulan Wilayah --}}
                    <div class="mb-3 row" id="pilih_bulan_wilayah_pajak_daerah">
                        <div class="col-md-6">
                            <label for="" class="form-label">Bulan</label>
                            <select id="bulan_wilayah1_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1"> Januari </option>
                                <option value="2"> Februari </option>
                                <option value="3"> Maret </option>
                                <option value="4"> April </option>
                                <option value="5"> Mei </option>
                                <option value="6"> Juni </option>
                                <option value="7"> Juli </option>
                                <option value="8"> Agustus </option>
                                <option value="9"> September </option>
                                <option value="10"> Oktober </option>
                                <option value="11"> November </option>
                                <option value="12"> Desember </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">s/d</label>
                            <select id="bulan_wilayah2_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1"> Januari </option>
                                <option value="2"> Februari </option>
                                <option value="3"> Maret </option>
                                <option value="4"> April </option>
                                <option value="5"> Mei </option>
                                <option value="6"> Juni </option>
                                <option value="7"> Juli </option>
                                <option value="8"> Agustus </option>
                                <option value="9"> September </option>
                                <option value="10"> Oktober </option>
                                <option value="11"> November </option>
                                <option value="12"> Desember </option>
                            </select>
                        </div>
                    </div>
                    {{-- Rekap --}}
                    <div class="mb-3 row" id="pilih_rekap_pajak_daerah">
                        <div class="col-md-6">
                            <label for="" class="form-label">Bulan</label>
                            <select id="bulan_rekap1_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1"> Januari </option>
                                <option value="2"> Februari </option>
                                <option value="3"> Maret </option>
                                <option value="4"> April </option>
                                <option value="5"> Mei </option>
                                <option value="6"> Juni </option>
                                <option value="7"> Juli </option>
                                <option value="8"> Agustus </option>
                                <option value="9"> September </option>
                                <option value="10"> Oktober </option>
                                <option value="11"> November </option>
                                <option value="12"> Desember </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="form-label">s/d</label>
                            <select id="bulan_rekap2_pajak_daerah" class="form-control select2-pajak_daerah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1"> Januari </option>
                                <option value="2"> Februari </option>
                                <option value="3"> Maret </option>
                                <option value="4"> April </option>
                                <option value="5"> Mei </option>
                                <option value="6"> Juni </option>
                                <option value="7"> Juli </option>
                                <option value="8"> Agustus </option>
                                <option value="9"> September </option>
                                <option value="10"> Oktober </option>
                                <option value="11"> November </option>
                                <option value="12"> Desember </option>
                            </select>
                        </div>
                    </div>
                    {{-- No. Halaman --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="halaman_pajak_daerah" class="form-label">No. Halaman</label>
                            <input type="number" value="1" min="1" class="form-control"
                                id="halaman_pajak_daerah">
                        </div>
                    </div>
                    {{-- Pilihan Cetak --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-md cetak_pajak_daerah" data-jenis="pdf">
                                PDF</button>
                            <button type="button" class="btn btn-dark btn-md cetak_pajak_daerah"
                                data-jenis="layar">Layar</button>
                            <button type="button" class="btn btn-dark btn-md cetak_pajak_daerah"
                                data-jenis="excel">Excel</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal cetak penerimaan pajak daerah --}}

    {{-- modal cetak rekap gaji --}}
    <div id="modal_rekap_gaji" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><label for="labelcetak" id="labelcetak"></label></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Pilihan --}}
                    <div class="mb-3 row" id="row-hidden">
                        <div class="col-md-6">
                            <label for="kd_skpd" class="form-label">Pilih</label><br>
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_keseluruhan_rekap_gaji">
                                <label class="form-check-label" for="pilihan">Keseluruhan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="pilihan_skpd_rekap_gaji">
                                <label class="form-check-label" for="pilihan">Per SKPD</label>
                            </div>
                        </div>
                    </div>
                    <hr style="border: 1px solid black">
                    {{-- SKPD --}}
                    <div class="mb-3 row" id="pilih_skpd_rekap_gaji">
                        <div class="col-md-6">
                            <label for="kd_skpd" class="form-label">SKPD</label>
                            <select class="form-control select2-rekap_gaji" style=" width: 100%;"
                                id="kd_skpd_rekap_gaji">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_skpd as $skpd)
                                    <option value="{{ $skpd->kd_skpd }}" data-nama="{{ $skpd->nm_skpd }}">
                                        {{ $skpd->kd_skpd }} | {{ $skpd->nm_skpd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nm_skpd" class="form-label"></label>
                            <input type="text" style="border:none;background-color:white" class="form-control"
                                readonly id="nm_skpd_rekap_gaji">
                        </div>
                    </div>
                    {{-- Keseluruhan --}}
                    <div class="mb-3 row" id="row-hidden">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formRadios"
                                    id="pilihan_keseluruhan_rekap_gaji1">
                                <label class="form-check-label">
                                    KESELURUHAN
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="mb-3 row" id="row-hidden">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formRadios"
                                    id="pilihan_bulan_rekap_gaji">
                                <label class="form-check-label">
                                    BULAN
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2-rekap_gaji" style=" width: 100%;" id="bulan_rekap_gaji">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    {{-- Periode --}}
                    <div class="mb-3 row" id="row-hidden">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="formRadios"
                                    id="pilihan_periode_rekap_gaji">
                                <label class="form-check-label">
                                    PERIODE
                                </label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <input type="date" id="periode1_rekap_gaji" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <input type="date" id="periode2_rekap_gaji" class="form-control">
                        </div>
                    </div>
                    {{-- Penandatangan --}}
                    {{-- <div class="mb-3 row">
                        <div class="col-md-2">
                            <label for="penandatangan" class="form-label">Penandatangan</label>
                        </div>
                        <div class="col-md-10">
                            <select class="form-control select2-rekap_gaji" style=" width: 100%;" id="ttd_rekap_gaji">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($pa_kpa as $ttd)
                                    <option value="{{ $ttd->nip }}">{{ $ttd->nip }} | {{ $ttd->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    {{-- Pilihan Cetak --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-md cetak_rekap_gaji" data-jenis="pdf">
                                PDF</button>
                            <button type="button" class="btn btn-dark btn-md cetak_rekap_gaji"
                                data-jenis="layar">Layar</button>
                            <button type="button" class="btn btn-dark btn-md cetak_rekap_gaji"
                                data-jenis="excel">Excel</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal cetak rekap gaji  --}}
@endsection
@section('js')
    @include('bud.laporan_bendahara.js.index')
@endsection
