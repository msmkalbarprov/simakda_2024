@extends('template.app')
@section('title', 'SPD Belanja | SIMAKDA')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 style="font-weight: bold; text-align:center">SPD BELANJA</h5>
                <h5 style="font-weight: bold; color:red">PERHATIAN!!!</h5>
                <ol>
                    <li>Sebelum membuat SPD pastikan <b>Anggaran Kas Belanja</b> dan DPA sudah disahkan.</li>
                    <li>Setelah membuat SPD, silahkan cek nilai SPD dengan <b>Anggaran Kas Belanja</b> sesuai Triwulan yang dipilih.</>
                    <li>Nilai Total <b>Anggaran Kas Belanja</b> akan sama nilainya dengan nilai total SPD.</>
                    <li>Jika terdapat SPD sebelumnya makan Nilai <b>Anggaran Kas Belanja</b> akan sama dengan nilai total SPD yang dibuat ditambah dengan SPD sebelumnya pada triwulan yang dipilih.</>
                    <li>Jika terdapat SPD revisi, maka nilai <b>Anggaran Kas Belanja</b> akan sama dengan SPD Revisi terbaru sesuai triwulan yang dipilih.</>
                </ol>
                <hr>
                <div>
                    List SPD Belanja
                    <a href="{{ route('spd.spd_belanja.create') }}" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="spd_belanja" class="table" style="width: 100%">
                            <thead>
                                <tr>
                                    <!-- <th style="width: 25px;text-align:center">No.</th> -->
                                    <th style="width: 100px;text-align:center">Nomor SPM</th>
                                    <th style="width: 50px;text-align:center">Tanggal</th>
                                    <th style="width: 150px;text-align:center">Nama SKPD</th>
                                    <th style="width: 150px;text-align:center">Jenis Beban</th>
                                    <th style="width: 150px;text-align:center">Jenis Anggaran</th>
                                    <th style="width: 150px;text-align:center">Revisi</th>
                                    <th style="width: 150px;text-align:center">Aksi</th>
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

{{-- modal cetak sppls --}}
<div id="modal_cetak" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak SPD</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- No SPD --}}
                <div class="mb-2 row">
                    <label for="no_spd" class="col-md-12 col-form-label">Nomor SPD</label>
                    <div class="col-md-12">
                        <input type="text" readonly class="form-control" id="no_spd" name="no_spd">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nip" class="col-md-12 col-form-label">Bendahara PPKD</label>
                    <div class="col-md-12">
                        <select name="nip" class="form-control" id="nip">
                            <option value="" selected disabled>Silahkan Pilih</option>
                            @foreach ($ppkd as $ttd)
                            <option value="{{ $ttd->nip }}">{{ $ttd->nip }} | {{ $ttd->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="tambahan" name="tambahan">
                            <label class="form-check-label" for="tambahan">Tambahan</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="jenis" class="col-md-12 col-form-label">Jenis Cetakkan</label>
                    <div class="col-md-12">
                        <select name="jenis" class="form-control" id="jenis">
                            <option value="" selected disabled>Silahkan Pilih</option>
                            <option value="layar">Layar</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <a id="cetak-otorisasi" data-url="{{ route('spd.spd_belanja.cetak_otorisasi') }}" href="#" class="btn btn-md btn-success">Cetak Otori <i class="fa fa-print"></i></a> &nbsp;
                        <a id="cetak-lampiran" data-url="{{ route('spd.spd_belanja.cetak_lampiran') }}" href="#" class="btn btn-md btn-success">Cetak Lampiran <i class="fa fa-print"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
@include('penatausahaan.spd.spd_belanja.js.index')
@endsection