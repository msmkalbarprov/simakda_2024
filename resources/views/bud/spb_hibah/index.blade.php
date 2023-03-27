@extends('template.app')
@section('title', 'SPB HIBAH | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List SPB HIBAH
                    <a href="{{ route('spb_hibah.create') }}" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="spb" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 50px;text-align:center">No STS</th>
                                        <th style="width: 100px;text-align:center">Tanggal</th>
                                        <th style="width: 50px;text-align:center">SKPD</th>
                                        <th style="width: 50px;text-align:center">TOTAL</th>
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

    {{-- modul cetak --}}
    <div id="modal_cetak" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cetak SPB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No. SPB --}}
                    <div class="mb-3 row">
                        <label for="no_spb" class="col-md-12 col-form-label">No. SPB</label>
                        <div class="col-md-12">
                            <input type="text" readonly class="form-control" id="no_spb" name="no_spb">
                            <input type="text" hidden class="form-control" id="jenis" name="jenis">
                            <input type="text" hidden class="form-control" id="kd_skpd" name="kd_skpd">
                        </div>
                    </div>
                    {{-- Tgl. Cetak SPB HIBAH --}}
                    <div class="mb-3 row">
                        <label for="tgl_spb" class="col-md-12 col-form-label">Tanggal SPB</label>
                        <div class="col-md-12">
                            <input type="date" class="form-control" id="tgl_spb" name="tgl_spb">
                        </div>
                    </div>
                    {{-- Kuasa BUD --}}
                    <div class="mb-3 row">
                        <label for="bud" class="col-md-12 col-form-label">Kuasa BUD</label>
                        <div class="col-md-12">
                            <select name="bud" class="form-control select-modal" id="bud">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($ttd1 as $bud)
                                    <option value="{{ $bud->nip }}">
                                        {{ $bud->nip }} | {{ $bud->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Margin --}}
                    <div class="mb-3 row">
                        <label for="sptb" class="col-md-12 col-form-label">
                            Ukuran Margin Untuk Cetakan PDF (Milimeter)
                        </label>
                        <label for="" class="col-md-2 col-form-label">Kiri</label>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="kiri" name="kiri" value="15">
                        </div>
                        <label for="" class="col-md-2 col-form-label">Kanan</label>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="kanan" name="kanan" value="15">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-2 col-form-label">Atas</label>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="atas" name="atas" value="15">
                        </div>
                        <label for="" class="col-md-2 col-form-label">Bawah</label>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="bawah" name="bawah" value="15">
                        </div>
                    </div>
                    {{-- Cetak --}}
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-dark btn-md cetak" data-jenis="layar">Layar</button>
                            <button type="button" class="btn btn-danger btn-md cetak" data-jenis="pdf">PDF</button>
                            <button type="button" class="btn btn-md btn-warning" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('bud.spb_hibah.js.index')
@endsection
