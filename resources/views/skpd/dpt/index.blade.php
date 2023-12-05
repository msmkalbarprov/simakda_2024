@extends('template.app')
@section('title', 'Daftar Pembayaran Tagihan | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Daftar Pembayaran Tagihan
                    <a href="{{ route('dpt.create') }}" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="dpt" class="table" style="width: 100%">
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

    <div id="modal_cetak" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cetak DPT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="no_dpt" class="col-md-2 col-form-label">No. DPT</label>
                        <div class="col-md-10">
                            <input type="text" readonly class="form-control" id="no_dpt" name="no_dpt">
                            <input type="text" hidden class="form-control" id="no_dpr" name="no_dpr">
                            <input type="text" hidden class="form-control" id="kd_skpd" name="kd_skpd">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ttd" class="col-md-2 col-form-label">PA/KPA</label>
                        <div class="col-md-10">
                            <select name="ttd" class="form-control select2-modal" id="ttd">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($daftar_ttd as $ttd)
                                    <option value="{{ $ttd->nip }}">
                                        {{ $ttd->nip }} | {{ $ttd->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="sptb" class="col-md-12 col-form-label">
                            Ukuran Margin Untuk Cetakan PDF (Milimeter)
                        </label>
                        <label for="sptb" class="col-md-2 col-form-label"></label>
                        <label for="" class="col-md-1 col-form-label">Kiri</label>
                        <div class="col-md-1">
                            <input type="number" class="form-control" id="margin_kiri" name="margin_kiri" value="10">
                        </div>
                        <label for="" class="col-md-1 col-form-label">Kanan</label>
                        <div class="col-md-1">
                            <input type="number" class="form-control" id="margin_kanan" name="margin_kanan" value="10">
                        </div>
                        <label for="" class="col-md-1 col-form-label">Atas</label>
                        <div class="col-md-1">
                            <input type="number" class="form-control" id="margin_atas" name="margin_atas" value="10">
                        </div>
                        <label for="" class="col-md-1 col-form-label">Bawah</label>
                        <div class="col-md-1">
                            <input type="number" class="form-control" id="margin_bawah" name="margin_bawah" value="10">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-md cetak" data-jenis="pdf">PDF</button>
                            <button type="button" class="btn btn-dark btn-md cetak" data-jenis="layar">Layar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.dpt.js.index')
@endsection
