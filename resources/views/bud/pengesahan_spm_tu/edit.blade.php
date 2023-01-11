@extends('template.app')
@section('title', 'Pengesahan SPP TU | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    INPUT SPP
                </div>
                <div class="card-body">
                    @csrf
                    @if ($spp->status == 1 && $spp->sts_setuju == 1)
                        <div class="alert alert-danger" role="alert">
                            Sudah disahkan dan dibuat SPM...!!!
                        </div>
                    @elseif ($spp->status == 1 && ($spp->sts_setuju == 0 || $spp->sts_setuju == ''))
                        <div class="alert alert-danger" role="alert">
                            Sudah dibuat SPM...!!!
                        </div>
                    @elseif (($spp->status == 0 || $spp->status == '') && $spp->sts_setuju == 1)
                        <div class="alert alert-danger" role="alert">
                            Sudah disahkan...!!!
                        </div>
                    @endif
                    {{-- No SPP dan Tanggal SPP --}}
                    <div class="mb-3 row">
                        <label for="no_spp" class="col-md-2 col-form-label">No. SPP</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_spp" name="no_spp"
                                value="{{ $spp->no_spp }}" required readonly>
                        </div>
                        <label for="tgl_spp" class="col-md-2 col-form-label">Tanggal</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_spp" name="tgl_spp" required
                                value="{{ $spp->tgl_spp }}" readonly>
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- SKPD dan Nama SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $spp->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ $spp->nm_skpd }}">
                        </div>
                    </div>
                    {{-- No SPD dan Beban --}}
                    <div class="mb-3 row">
                        <label for="no_spd" class="col-md-2 col-form-label">No SPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_spd" name="no_spd" required readonly
                                value="{{ $spp->no_spd }}">
                        </div>
                        <label for="beban" class="col-md-2 col-form-label">Beban</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="beban" name="beban" required readonly
                                value="SPP TU">
                        </div>
                    </div>
                    {{-- Bank dan Nama Bank --}}
                    <div class="mb-3 row">
                        <label for="bank" class="col-md-2 col-form-label">Bank</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="bank" name="bank" required readonly
                                value="{{ $spp->bank }}">
                        </div>
                        <label for="nama_bank" class="col-md-2 col-form-label">Nama Bank</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nama_bank" name="nama_bank" required readonly
                                value="{{ nama_bank($spp->bank) }}">
                        </div>
                    </div>
                    {{-- Rekening dan Bulan --}}
                    <div class="mb-3 row">
                        <label for="rekening" class="col-md-2 col-form-label">Rekening</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="rekening" name="rekening" required readonly
                                value="{{ $spp->no_rek }}">
                        </div>
                        <label for="bulan" class="col-md-2 col-form-label">Bulan</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="bulan" name="bulan" required readonly
                                value="{{ bulan($spp->bulan) }}">
                        </div>
                    </div>
                    {{-- Keperluan --}}
                    <div class="mb-3 row">
                        <label for="keperluan" class="col-md-2 col-form-label">Keperluan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keperluan" name="keperluan" readonly>{{ $spp->keperluan }}</textarea>
                        </div>
                    </div>
                    <!-- SETUJUI, BATAL SETUJUI DAN KEMBALI -->
                    <div class="mb-3 row" style="float: right;">
                        <div class="col-md-12" style="text-align: center">
                            <button id="setuju" class="btn btn-primary btn-md">Setujui</button>
                            <button id="batal_setuju" class="btn btn-danger btn-md">Batal Setujui</button>
                            <a href="{{ route('pengesahan_spp_tu.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Detail SPP --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Detail SPP
                </div>
                <div class="card-body table-responsive">
                    <table id="detail_spp" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kegiatan</th>
                                <th>Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total" class="col-md-8 col-form-label" style="text-align: right">Jumlah</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total" name="total">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('bud.pengesahan_spp_tu.js.edit');
@endsection
