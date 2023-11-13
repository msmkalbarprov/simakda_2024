@extends('template.app')
@section('title', 'Penerimaan Kas | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Data Penerimaan Kas
                    <a href="{{ route('penerimaan_kas.tambah') }}" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <select class="form-control select2-multiple" style="width: 100%" id="kd_skpd" name="kd_skpd">
                        <option value="" disabled selected>Silahkan Pilih</option>
                        @foreach ($daftar_skpd as $skpd)
                            <option value="{{ $skpd->kd_skpd }}" data-nama="{{ $skpd->nm_skpd }}">
                                {{ $skpd->kd_skpd }} | {{ $skpd->nm_skpd }}
                            </option>
                        @endforeach
                        </option>
                    </select>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="penerimaan_kas" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 50px;text-align:center">Nomor Kas</th>
                                        <th style="width: 50px;text-align:center">No STS</th>
                                        <th style="width: 50px;text-align:center">Tanggal</th>
                                        <th style="width: 50px;text-align:center">SKPD</th>
                                        <th style="width: 50px;text-align:center">Nilai</th>
                                        <th style="width: 50px;text-align:center">Keterangan</th>
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
@endsection
@section('js')
    @include('skpd.penerimaan_kas.js.index')
@endsection
