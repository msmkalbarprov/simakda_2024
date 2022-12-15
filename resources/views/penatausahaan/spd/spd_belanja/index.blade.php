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
                    <a href="{{ route('spd.spj_belanja.create') }}" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="spd_belanja" class="table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 25px;text-align:center">No.</th>
                                    <th style="width: 100px;text-align:center">Nomor SPM</th>
                                    <th style="width: 50px;text-align:center">Tanggal</th>
                                    <th style="width: 150px;text-align:center">Nama SKPD</th>
                                    <th style="width: 150px;text-align:center">Jenis Beban</th>
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

@endsection
@section('js')
@include('penatausahaan.spd.spd_belanja.js.index')
@endsection