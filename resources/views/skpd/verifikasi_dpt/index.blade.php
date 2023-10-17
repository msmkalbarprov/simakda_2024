@extends('template.app')
@section('title', 'VERIFIKASI DPT | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Daftar Verifikasi Pembayaran Tagihan (DPT)
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="verifikasi_dpt" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 100px;text-align:center">Nomor DPT</th>
                                        <th style="width: 100px;text-align:center">Tanggal</th>
                                        <th style="width: 100px;text-align:center">SKPD</th>
                                        <th style="width: 100px;text-align:center">Total</th>
                                        <th style="width: 50px;text-align:center">VER</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_lihat" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Pembayaran Tagihan (DPT)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    {{-- NOMOR DAN TANGGAL DPT --}}
                    <div class="mb-3 row">
                        <label for="no_dpt" class="col-md-2 col-form-label">No. DPT</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_dpt" name="no_dpt" readonly>
                            <input type="text" class="form-control" id="status" name="status" readonly hidden>
                            <input type="text" class="form-control" id="status_verifikasi" name="status_verifikasi"
                                readonly hidden>
                        </div>
                        <label for="tgl_dpt" class="col-md-2 col-form-label">Tanggal DPT</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_dpt" name="tgl_dpt" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="no_dpr" class="col-md-2 col-form-label">No. DPR</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_dpr" name="no_dpr" readonly>
                        </div>
                        <label for="tgl_dpr" class="col-md-2 col-form-label">Tanggal DPR</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_dpr" name="tgl_dpr" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tgl_verifikasi" class="col-md-2 col-form-label">Tanggal Verifikasi</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_verifikasi" name="tgl_verifikasi">
                        </div>
                    </div>
                    {{-- SKPD DAN NAMA SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="kd_skpd" readonly>
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_skpd" readonly>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Detail Daftar Pembayaran Transaksi
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" id="detail_dpt">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kegiatan</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Kode Rekening</th>
                                        <th>Nama Rekening</th>
                                        <th>Rupiah</th>
                                        <th>Sumber</th>
                                        <th>Bukti</th>
                                        <th>Uraian</th>
                                        <th>Pembayaran</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="mb-2 mt-2 row">
                                <label for="total_belanja" class="col-md-8 col-form-label" style="text-align: right">Total
                                    Belanja</label>
                                <div class="col-md-4">
                                    <input type="text" style="text-align: right;background-color:white;border:none;"
                                        readonly class="form-control" id="total_belanja" name="total_belanja">
                                </div>
                                <label for="sisa_kas" class="col-md-8 col-form-label" style="text-align: right">Sisa
                                    Kas</label>
                                <div class="col-md-4">
                                    <input type="text" style="text-align: right;background-color:white;border:none;"
                                        readonly class="form-control" id="sisa_kas" name="sisa_kas"
                                        value="{{ rupiah($sisa_kas->terima - $sisa_kas->keluar) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-primary btn-md simpan terima" data-jenis="terima">Verifikasi</button>
                            <button class="btn btn-danger btn-md simpan batal" data-jenis="batal">Batal</button>
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
    @include('skpd.verifikasi_dpt.js.index')
@endsection
