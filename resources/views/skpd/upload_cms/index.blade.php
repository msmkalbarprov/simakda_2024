@extends('template.app')
@section('title', 'Upload CMS | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Data Transaksi
                    {{-- <a href="{{ route('skpd.transaksi_cms.create') }}" class="btn btn-primary" style="float: right;">Tambah</a> --}}
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="tgl_voucher" class="col-md-1 col-form-label">Tanggal</label>
                        <div class="col-md-2">
                            <input type="date" class="form-control @error('tgl_voucher') is-invalid @enderror"
                                id="tgl_voucher" name="tgl_voucher">
                        </div>
                        <div class="col-md-2">
                            <button id="cetak_cms" class="btn btn-dark btn-md">Cari</button>
                        </div>
                    </div>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="upload_cms" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 50px;text-align:center">Nomor Transaksi</th>
                                        <th style="width: 100px;text-align:center">Tanggal Voucher</th>
                                        <th style="width: 100px;text-align:center">Tanggal Upload</th>
                                        <th style="width: 100px;text-align:center">SKPD</th>
                                        <th style="width: 50px;text-align:center">Keterangan</th>
                                        <th style="width: 50px;text-align:center">Nilai Pengeluaran</th>
                                        <th style="width: 50px;text-align:center">STT</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                        <th>Bersih</th>
                                        <th>Rek Bend</th>
                                        <th>Nama Rek</th>
                                        <th>Rek Tujuan</th>
                                        <th>Bank Tujuan</th>
                                        <th>Ket. Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @php
                                        $no = 0;
                                    @endphp
                                    @foreach ($upload_cms->chunk(5) as $data)
                                        @foreach ($data as $cms)
                                            <tr>
                                                @if ($cms->status_upload == '1' && $cms->status_validasi == '1')
                                                    <td style="background-color:#B0E0E6">{{ ++$no }}</td>
                                                    <td style="background-color:#B0E0E6">{{ $cms->no_voucher }}</td>
                                                    <td style="background-color:#B0E0E6">{{ $cms->tgl_voucher }}</td>
                                                    <td style="background-color:#B0E0E6">{{ $cms->tgl_upload }}</td>
                                                    <td style="background-color:#B0E0E6">{{ $cms->kd_skpd }}</td>
                                                    <td style="background-color:#B0E0E6">{{ Str::limit($cms->ket, 20) }}
                                                    </td>
                                                    <td style="text-align: right;background-color:#B0E0E6">
                                                        {{ rupiah($cms->total) }}</td>
                                                    <td style="background-color:#B0E0E6">&#10004</td>
                                                @elseif ($cms->status_upload == '1')
                                                    <td style="background-color:#90EE90">{{ ++$no }}</td>
                                                    <td style="background-color:#90EE90">{{ $cms->no_voucher }}</td>
                                                    <td style="background-color:#90EE90">{{ $cms->tgl_voucher }}</td>
                                                    <td style="background-color:#90EE90">{{ $cms->tgl_upload }}</td>
                                                    <td style="background-color:#90EE90">{{ $cms->kd_skpd }}</td>
                                                    <td style="background-color:#90EE90">{{ Str::limit($cms->ket, 20) }}
                                                    </td>
                                                    <td style="text-align: right;background-color:#90EE90">
                                                        {{ rupiah($cms->total) }}</td>
                                                    <td style="background-color:#90EE90">X</td>
                                                @else
                                                    <td>{{ ++$no }}</td>
                                                    <td>{{ $cms->no_voucher }}</td>
                                                    <td>{{ $cms->tgl_voucher }}</td>
                                                    <td>{{ $cms->tgl_upload }}</td>
                                                    <td>{{ $cms->kd_skpd }}</td>
                                                    <td>{{ Str::limit($cms->ket, 20) }}</td>
                                                    <td style="text-align: right">{{ rupiah($cms->total) }}</td>
                                                    <td>X</td>
                                                @endif
                                                <td style="width:200px">
                                                    <a href="{{ route('skpd.transaksi_cms.edit', $cms->no_voucher) }}"
                                                        class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0);"
                                                        onclick="deleteData('{{ $cms->no_voucher }}');"
                                                        class="btn btn-danger btn-sm" id="delete"><i
                                                            class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach --}}
                                </tbody>
                            </table>
                            <hr>
                            <table style="width: 100%">
                                <tbody>
                                    <tr>
                                        <td style="padding-left: 700px">Total Transaksi</td>
                                        <td>:</td>
                                        <td style="text-align: right"></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 700px">Total Potongan</td>
                                        <td>:</td>
                                        <td style="text-align: right"></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 700px">Sisa Saldo Bank</td>
                                        <td>:</td>
                                        <td style="text-align: right">{{ rupiah($sisa_bank->sisa) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    <div id="modal_lihat" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Data Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- SKPD --}}
                    <div class="mb-1 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-10">
                            <input type="text" readonly style="border:none;background-color:white" class="form-control"
                                id="nm_skpd" name="nm_skpd">
                            <input type="text" class="form-control" id="kd_skpd" name="kd_skpd" hidden>
                        </div>
                    </div>
                    {{-- Nomor, Tanggal, SP2D --}}
                    <div class="mb-1 row">
                        <label for="nomor" class="col-md-2 col-form-label">Nomor</label>
                        <div class="col-md-2">
                            <input type="text" readonly style="border:none;background-color:white" class="form-control"
                                id="nomor" name="nomor">
                        </div>
                        <label for="tanggal" class="col-md-1 col-form-label">Tanggal</label>
                        <div class="col-md-3">
                            <input type="text" readonly style="border:none;background-color:white" class="form-control"
                                id="tanggal" name="tanggal">
                        </div>
                        <label for="no_sp2d" class="col-md-1 col-form-label">SP2D</label>
                        <div class="col-md-3">
                            <input type="text" readonly style="border:none;background-color:white" class="form-control"
                                id="no_sp2d" name="no_sp2d">
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-1 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea name="keterangan" id="keterangan" cols="30"class="form-control"
                                style="border:none;background-color:white;text-align:justify"></textarea>
                        </div>
                    </div>
                    <hr style="border: 1px solid black">
                    {{-- Kegiatan --}}
                    <div class="row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Kegiatan</label>
                        <div class="col-md-10">
                            <input type="text" readonly style="border:none;background-color:white"
                                class="form-control" id="kd_sub_kegiatan" name="kd_sub_kegiatan">
                        </div>
                    </div>
                    {{-- Nama Kegiatan --}}
                    <div class="mb-1 row">
                        <label for="nm_sub_kegiatan" class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                            <input type="text" readonly style="border:none;background-color:white"
                                class="form-control" id="nm_sub_kegiatan" name="nm_sub_kegiatan">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Rekening Transaksi
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" id="rekening_transaksi">
                                <thead>
                                    <tr>
                                        <th>Kode Rek</th>
                                        <th>Nama Rekening</th>
                                        <th>Nilai</th>
                                        <th>Sumber</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Rekening Potongan Transaksi
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" id="rekening_potongan">
                                <thead>
                                    <tr>
                                        <th>Kode Rek</th>
                                        <th>Nama Rekening</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Rekening Tujuan Transfer
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" id="rekening_tujuan">
                                <thead>
                                    <tr>
                                        <th>No Bukti</th>
                                        <th>Tanggal Bukti</th>
                                        <th>Rek. Bendahara</th>
                                        <th>Atas Nama Rek. Tujuan</th>
                                        <th>Rek. Tujuan</th>
                                        <th>Kd Skpd</th>
                                        <th>Nilai</th>
                                        <th>Bank</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-md btn-warning" data-bs-dismiss="modal"><i
                                    class="fa fa-undo"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('skpd.upload_cms.js.index')
@endsection
