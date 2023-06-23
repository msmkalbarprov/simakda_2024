@extends('template.app')
@section('title', 'Koreksi Transaksi Rekening | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Koreksi Transaksi
                    <a href="{{ route('koreksi_rekening.create') }}" class="btn btn-primary {{kunci()->kunci_jurnal== 1 ? 'hidden' : ''}}" style="float: right;">Tambah</a>
                    <a href="javascript:void(0);" onclick="cetak()" class="btn btn-success btn-md"
                        style="float: right;margin-right:4px">Cetak</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="koreksi_rekening" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 50px;text-align:center">Nomor Bukti</th>
                                        <th style="width: 50px;text-align:center">Tanggal Bukti</th>
                                        <th style="width: 50px;text-align:center">SKPD</th>
                                        <th style="width: 50px;text-align:center">Keterangan</th>
                                        <th style="width: 50px;text-align:center">LPJ</th>
                                        <th style="width: 50px;text-align:center">SPJ</th>
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
        </div>
    </div>

    {{-- modul cetak --}}
    <div id="modal_cetak" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CETAK JURNAL KOREKSI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-5">
                            <input type="text" readonly class="form-control" id="kd_skpd" name="kd_skpd"
                                value="{{ Auth::user()->kd_skpd }}">
                        </div>
                        <div class="col-md-5">
                            <input type="text" readonly class="form-control" id="nm_skpd" name="nm_skpd"
                                value="{{ nama_skpd(Auth::user()->kd_skpd) }}">
                        </div>
                    </div>
                    {{-- PPK --}}
                    <div class="mb-3 row">
                        <label for="ppk" class="col-md-2 col-form-label">PPK</label>
                        <div class="col-md-5">
                            <select name="ppk" class="form-control" id="ppk">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($data_ppk as $ppk)
                                    <option value="{{ $ppk->nip }}" data-nama="{{ $ppk->nama }}">
                                        {{ $ppk->nip }} | {{ $ppk->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="nama_ppk" id="nama_ppk" class="form-control" readonly>
                        </div>
                    </div>
                    {{-- PA/KPA --}}
                    <div class="mb-3 row">
                        <label for="pa_kpa" class="col-md-2 col-form-label">PA/KPA</label>
                        <div class="col-md-5">
                            <select name="pa_kpa" class="form-control" id="pa_kpa">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($data_pa as $pa)
                                    <option value="{{ $pa->nip }}" data-nama="{{ $pa->nama }}">
                                        {{ $pa->nip }} | {{ $pa->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="nama_pa_kpa" id="nama_pa_kpa" class="form-control" readonly>
                        </div>
                    </div>
                    {{-- Periode --}}
                    <div class="mb-3 row">
                        <label for="periode" class="col-md-2 col-form-label">Periode</label>
                        <div class="col-md-5">
                            <input type="date" name="periode1" id="periode1" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="periode2" id="periode2" class="form-control">
                        </div>
                    </div>
                    {{-- TTD --}}
                    <div class="mb-3 row">
                        <label for="tgl_ttd" class="col-md-2 col-form-label">Tanggal TTD</label>
                        <div class="col-md-5">
                            <input type="date" name="tgl_ttd" id="tgl_ttd" class="form-control">
                        </div>
                    </div>
                    {{-- Cetak --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-md cetak" data-jenis="pdf"
                                name="cetak_pdf">PDF</button>
                            <button type="button" class="btn btn-dark btn-md cetak" data-jenis="layar"
                                name="cetak">Layar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.koreksi_rekening.js.index')
@endsection
