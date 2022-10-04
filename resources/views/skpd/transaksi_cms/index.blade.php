@extends('template.app')
@section('title', 'Transaksi CMS | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    List Daftar Transaksi Non Tunai (CMS)
                    <a href="{{ route('skpd.transaksi_cms.create') }}" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="transaksi_cms" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 100px;text-align:center">Nomor Bukti</th>
                                        <th style="width: 100px;text-align:center">Tanggal</th>
                                        <th style="width: 100px;text-align:center">Nama SKPD</th>
                                        <th style="width: 100px;text-align:center">Keterangan</th>
                                        <th style="width: 50px;text-align:center">UPL</th>
                                        <th style="width: 50px;text-align:center">VAL</th>
                                        <th style="width: 50px;text-align:center">POT</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 0;
                                    @endphp
                                    @foreach ($data_cms->chunk(5) as $data)
                                        @foreach ($data as $cms)
                                            @if ($cms->status_upload == '1' && $cms->status_validasi == '1')
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td style="background-color: #00a5ff">{{ $cms->no_voucher }}</td>
                                                    <td style="background-color: #00a5ff">{{ $cms->tgl_voucher }}</td>
                                                    <td style="background-color: #00a5ff">{{ $cms->kd_skpd }}</td>
                                                    <td style="background-color: #00a5ff">{{ Str::limit($cms->ket, 20) }}
                                                    </td>
                                                    <td style="background-color: #00a5ff">{{ $cms->status_upload }}</td>
                                                    <td style="background-color: #00a5ff">{{ $cms->status_validasi }}</td>
                                                    <td style="background-color: #00a5ff">{{ $cms->status_trmpot }}</td>
                                                    <td></td>
                                                </tr>
                                            @elseif ($cms->status_upload == '1')
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td style="background-color: #12cc2e">{{ $cms->no_voucher }}</td>
                                                    <td style="background-color: #12cc2e">{{ $cms->tgl_voucher }}</td>
                                                    <td style="background-color: #12cc2e">{{ $cms->kd_skpd }}</td>
                                                    <td style="background-color: #12cc2e">{{ Str::limit($cms->ket, 20) }}
                                                    </td>
                                                    <td style="background-color: #12cc2e">{{ $cms->status_upload }}</td>
                                                    <td style="background-color: #12cc2e">{{ $cms->status_validasi }}</td>
                                                    <td style="background-color: #12cc2e">{{ $cms->status_trmpot }}</td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @endforeach
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
    @include('skpd.transaksi_cms.js.index')
@endsection
