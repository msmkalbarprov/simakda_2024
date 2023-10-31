@extends('template.app')
@section('title', 'DETAIL VERIFIKASI DPR | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    VERIFIKASI DPR
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NOMOR DAN TANGGAL DPR --}}
                    <div class="mb-3 row">
                        <label for="no_dpr" class="col-md-2 col-form-label">No. DPR</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_dpr" name="no_dpr" readonly
                                value="{{ $dpr->no_dpr }}">
                        </div>
                        <label for="tgl_dpr" class="col-md-2 col-form-label">Tanggal DPR</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_dpr" name="tgl_dpr" readonly
                                value="{{ $dpr->tgl_dpr }}">
                        </div>
                    </div>
                    {{-- NOMOR URUT DAN JENIS BELANJA --}}
                    <div class="mb-3 row">
                        <label for="tgl_verifikasi" class="col-md-2 col-form-label">Tgl. Verifikasi</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="tgl_verifikasi" name="tgl_verifikasi"
                                value="{{ $dpr->tgl_verif }}">
                        </div>
                        <label for="jenis_belanja" class="col-md-2 col-form-label">Jenis Belanja</label>
                        <div class="col-md-4">
                            <select name="jenis_belanja" id="jenis_belanja" class="form-control select2-multiple">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                <option value="1" {{ $dpr->jenis_belanja == '1' ? 'selected' : '' }}>Perjalanan Dinas
                                </option>
                                <option value="2" {{ $dpr->jenis_belanja == '2' ? 'selected' : '' }}>Belanja Modal
                                </option>
                                <option value="3" {{ $dpr->jenis_belanja == '3' ? 'selected' : '' }}>Belanja
                                    Barang/Jasa</option>
                            </select>
                        </div>
                    </div>
                    {{-- SKPD DAN NAMA SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="kd_skpd" readonly
                                value="{{ Auth::user()->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_skpd" readonly
                                value="{{ nama_skpd(Auth::user()->kd_skpd) }}">
                        </div>
                    </div>
                    {{-- NOMOR DAN PEMEGANG KKPD --}}
                    <div class="mb-3 row">
                        <label for="no_kkpd" class="col-md-2 col-form-label">Nomor KKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_kkpd" readonly value="{{ $dpr->no_kkpd }}">
                        </div>
                        <label for="nm_kkpd" class="col-md-2 col-form-label">Nama KKPD</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nm_kkpd" readonly value="{{ $dpr->nm_kkpd }}">
                        </div>
                    </div>
                    {{-- KETERANGAN --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea name="keterangan" id="keterangan" class="form-control">{{ $dpr->keterangan_tolak }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="simpan"
                            class="btn btn-{{ $dpr->status_verifikasi == '1' ? 'danger' : 'primary' }} btn-md"
                            {{ $dpr->status == '1' ? 'hidden' : '' }}>
                            @if ($dpr->status_verifikasi == '1')
                                Batal Verif
                            @else
                                Verif
                            @endif
                        </button>
                        <a href="{{ route('dpr.index_verifikasi') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    DETAIL VERIFIKASI DPR
                </div>
                <div class="card-body table-responsive">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="verifikasi_dpr" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Kegiatan</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Kode Rekening</th>
                                        <th>Nama Rekening</th>
                                        <th>Rupiah</th>
                                        <th>Kode Sumber</th>
                                        <th>Sumber</th>
                                        <th>Bukti</th>
                                        <th>Status</th>
                                        <th>Urut</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rincian_dpr as $rincian)
                                        <tr>
                                            <td>{{ $rincian->kd_sub_kegiatan }}</td>
                                            <td>{{ $rincian->nm_sub_kegiatan }}</td>
                                            <td>{{ $rincian->kd_rek6 }}</td>
                                            <td>{{ $rincian->nm_rek6 }}</td>
                                            <td>{{ rupiah($rincian->nilai) }}</td>
                                            <td>{{ $rincian->sumber }}</td>
                                            <td>{{ nama_sumber_dana($rincian->sumber) }}</td>
                                            <td>{{ $rincian->bukti == '1' ? 'YA' : 'TIDAK' }}</td>
                                            <td>{{ $rincian->status }}</td>
                                            <td>{{ $rincian->urut }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
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
    @include('skpd.verifikasi_dpr.js.show')
@endsection
