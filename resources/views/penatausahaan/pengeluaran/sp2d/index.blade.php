@extends('template.app')
@section('title', 'SP2D | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('sp2d.create') }}" id="tambah_sp2d" class="btn btn-primary"
                        style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="sp2d" class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 100px;text-align:center">Nomor SP2D</th>
                                        <th style="width: 50px;text-align:center">Nomor SPM</th>
                                        <th style="width: 150px;text-align:center">Tanggal</th>
                                        <th style="width: 150px;text-align:center">SKPD</th>
                                        <th style="width: 150px;text-align:center">Keterangan</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_sp2d as $sp2d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $sp2d->no_sp2d }}</td>
                                            <td>{{ $sp2d->no_spm }}</td>
                                            <td>{{ tanggal($sp2d->tgl_sp2d) }}</td>
                                            <td>{{ $sp2d->kd_skpd }}</td>
                                            <td style="text-align: justify">{{ Str::limit($sp2d->keperluan, '20') }}</td>
                                            <td>

                                            </td>
                                        </tr>
                                    @endforeach
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
    @include('penatausahaan.pengeluaran.sp2d.js.cetak')
@endsection
