@extends('template.app')
@section('title', 'Pencairan SP2D | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-md" style="pointer-events: none">SP2D Sudah Cair</button>
                    <button class="btn btn-light btn-md" style="pointer-events: none;border:1px solid black">SP2D Belum
                        Cair</button>
                    <button class="btn btn-primary btn-md" id="filter"><i class="fa fa-filter"></i>Filter</button>
                    <input type="hidden" name="tipe" id="tipe">
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="cair_sp2d" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 100px;text-align:center">Nomor SP2D</th>
                                        <th style="width: 100px;text-align:center">Nomor SPM</th>
                                        <th style="width: 100px;text-align:center">Tanggal</th>
                                        <th style="width: 100px;text-align:center">SKPD</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    <div id="modal_filter" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FILTER</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-md btn-info filter" data-jenis="online_cair">
                                SP2D ONLINE CAIR
                            </button>
                            <button type="button" class="btn btn-md btn-danger filter" data-jenis="online_blmcair">
                                SP2D ONLINE BELUM CAIR
                            </button>
                            <button type="button" class="btn btn-md btn-info filter" data-jenis="offline_cair">
                                SP2D OFFLINE CAIR
                            </button>
                            <button type="button" class="btn btn-md btn-danger filter" data-jenis="offline_blmcair">
                                SP2D OFFLINE BELUM CAIR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CALLBACK SP2D --}}
    <div id="modal_callback" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaksi SP2D Bank Kalbar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            DETAIL SP2D
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label for="no_sp2d_callback" class="form-label">No. SP2D*</label>
                                    <input type="text" readonly class="form-control" id="no_sp2d_callback"
                                        name="no_sp2d_callback">
                                    <input type="text" readonly class="form-control" id="no_spm_callback"
                                        name="no_spm_callback" hidden>
                                </div>
                                <div class="col-md-4">
                                    <label for="tgl_transaksi" class="form-label">Tanggal Transaksi*</label>
                                    <input type="text" readonly class="form-control" id="tgl_transaksi"
                                        name="tgl_transaksi">
                                </div>
                                <div class="col-md-4">
                                    <label for="nilai_transaksi" class="form-label">Nilai Transaksi*</label>
                                    <input type="text" readonly class="form-control" id="nilai_transaksi"
                                        name="nilai_transaksi" style="text-align: right">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label for="npwp" class="form-label">NPWP*</label>
                                    <input type="text" readonly class="form-control" id="npwp" name="npwp">
                                </div>
                                <div class="col-md-4">
                                    <label for="skpd" class="form-label">SKPD*</label>
                                    <input type="text" readonly class="form-control" id="skpd" name="skpd">
                                </div>
                                <div class="col-md-4">
                                    <label for="penerima" class="form-label">Penerima*</label>
                                    <input type="text" readonly class="form-control" id="penerima" name="penerima">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label for="rekening" class="form-label">Rekening*</label>
                                    <input type="text" readonly class="form-control" id="rekening" name="rekening">
                                </div>
                                <div class="col-md-4">
                                    <label for="bank" class="form-label">Bank*</label>
                                    <input type="text" readonly class="form-control" id="bank" name="bank">
                                </div>
                                <div class="col-md-4">
                                    <label for="jumlah_bayar" class="form-label">Jumlah Dibayar*</label>
                                    <input type="text" readonly class="form-control" id="jumlah_bayar"
                                        name="jumlah_bayar" style="text-align: right">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="keperluan" class="form-label">Keperluan*</label>
                                    <input type="text" readonly class="form-control" id="keperluan" name="keperluan">
                                </div>
                                <div class="col-md-6">
                                    <label for="status_callback" class="form-label">Status*</label>
                                    <input type="text" readonly class="form-control" id="status_callback"
                                        name="status_callback">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            DETAIL POTONGAN MPN
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" id="detail_potongan_mpn">
                                <thead>
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th>Nilai Potongan</th>
                                        <th>ID Billing</th>
                                        <th>NTPN</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            DETAIL POTONGAN NON MPN
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" id="detail_potongan_nonmpn">
                                <thead>
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th>Kode Map</th>
                                        <th>Nilai Potongan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-12" style="text-align: right">
                            <button type="button" id="callback" class="btn btn-md btn-primary">Update</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <style>
        .orange {
            background-color: #FFA500;
            color: white
        }
    </style>
    @include('penatausahaan.pengeluaran.pencairan_sp2d.js.cetak')
@endsection
