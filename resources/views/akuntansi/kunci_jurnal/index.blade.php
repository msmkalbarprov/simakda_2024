@extends('template.app')
@section('title', 'KUNCI JURNAL KOREKSI | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Buka Kunci
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-md btn-info"><i class="fa fa-lock" aria-hidden="true"></i> : Biru
                        Artinya Terkunci</button>
                    <button type="button" class="btn btn-md btn-danger"><i class="fas fa-lock-open"></i> : Merah
                        Artinya Tidak Terkunci</button>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="kunci_jurnal" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 50px;text-align:center">Nama SKPD</th>
                                        <th style="width: 10px;text-align:center">Jurnal Koreksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kunci_semua" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kunci/Buka Semua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <input type="text" name="kd_skpd" id="kd_skpd" hidden>
                            <button type="button" class="btn btn-md btn-info kunci" data-nilai="0">Kunci Semua</button>
                            <button type="button" class="btn btn-md btn-danger kunci" data-nilai="1">Buka Semua</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('akuntansi.kunci_jurnal.js.index')
@endsection
