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
