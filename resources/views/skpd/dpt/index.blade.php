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
@endsection
@section('js')
    @include('skpd.dpt.js.index')
@endsection
