@extends('template.app')
@section('title', 'Tambah Upload KKPD | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Data Transaksi
                </div>
                <div class="card-body">
                    @csrf
                    {{-- Data Transaksi --}}
                    <div class="mb-3 row">
                        <label for="data_transaksi" class="col-md-12 col-form-label">Data Transaksi</label>
                        <div class="col-md-12">
                            <select name="data_transaksi" id="data_transaksi" class="form-control select2-multiple">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="proses_upload" class="btn btn-primary btn-md"><i class="uil-search-alt"></i>Proses
                            Upload</button>
                        <a href="{{ route('upl_kkpd.index') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Input Detail --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Data Upload
                </div>
                <div class="card-body table-responsive">
                    <table id="rincian_upload" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No Voucher</th>
                                <th>Tanggal Voucher</th>
                                <th>SKPD</th>
                                <th>Keterangan</th>
                                <th>Netto</th>
                                <th>Potongan</th>
                                <th>Nilai Pengeluaran</th>
                                <th>Status Upload</th>
                                <th>Rekening Awal</th>
                                <th>Nama Rekening Tujuan</th>
                                <th>Rekening Tujuan</th>
                                <th>Bank Tujuan</th>
                                <th>Ket Tujuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <hr>
                    <table style="width: 100%">
                        <tbody>
                            <tr>
                                <td style="padding-left: 600px">Total Transaksi</td>
                                <td>:</td>
                                <td style="text-align: right"><input type="text"
                                        style="border:none;background-color:white;text-align:right" class="form-control"
                                        readonly id="total_transaksi">
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 600px">Total Potongan</td>
                                <td>:</td>
                                <td style="text-align: right"></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 600px">Sisa Saldo Bank</td>
                                <td>:</td>
                                <td><input type="text" style="border:none;background-color:white;text-align:right"
                                        readonly id="sisa_saldo" class="form-control"
                                        value="{{ rupiah($sisa_bank->terima - $sisa_bank->keluar) }}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.upl_kkpd.js.create');
@endsection
