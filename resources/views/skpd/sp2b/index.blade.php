@extends('template.app')
@section('title', 'SP2B | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List SP2B
                    <a href="{{ route('sp2b.create') }}" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="sp2b" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 50px;text-align:center">No SP2B</th>
                                        <th style="width: 100px;text-align:center">Tanggal</th>
                                        <th style="width: 50px;text-align:center">SKPD</th>
                                        <th style="width: 50px;text-align:center">Keterangan</th>
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
                    <h5 class="modal-title">Cetak SP2B</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No. SP2B --}}
                    <div class="mb-3 row">
                        <label for="no_sp2b" class="col-md-12 col-form-label">No. SP2B</label>
                        <div class="col-md-12">
                            <input type="text" readonly class="form-control" id="no_sp2b" name="no_sp2b">
                            <input type="text" hidden class="form-control" id="jenis" name="jenis">
                            <input type="text" hidden class="form-control" id="kd_skpd" name="kd_skpd">
                        </div>
                    </div>
                    {{-- Pengguna Anggaran --}}
                    <div class="mb-3 row">
                        <label for="pa_kpa" class="col-md-12 col-form-label">Pengguna Anggaran</label>
                        <div class="col-md-12">
                            <select name="pa_kpa" class="form-control select-modal" id="pa_kpa">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($ttd1 as $pa)
                                    <option value="{{ $pa->nip }}">
                                        {{ $pa->nip }} | {{ $pa->nama }}</option>
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
    @include('skpd.sp2b.js.index')
@endsection
