@extends('template.app')
@section('title', 'Input SPB | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input SPB
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NO SPB dan Tanggal SPB --}}
                    <div class="mb-3 row">
                        <label for="no_spb" class="col-md-2 col-form-label">No. SPB</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_spb" name="no_spb" required readonly
                                value="{{ $spb->status == 1 || $spb->status ? $spb->no_spb : $no_spb }}">
                            <input class="form-control" type="text" id="no_urut" name="no_urut" required readonly
                                hidden value="{{ $no_urut }}">
                        </div>
                        <label for="tgl_spb" class="col-md-2 col-form-label">Tanggal SPB</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_spb" name="tgl_spb" required
                                value="{{ $spb->status == 1 || $spb->status ? $spb->tgl_spb : '' }}">
                        </div>
                    </div>
                    {{-- SKPD dan Nama SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $spb->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ nama_skpd($spb->kd_skpd) }}">
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- NO SP2B dan Tanggal SP2B --}}
                    <div class="mb-3 row">
                        <label for="no_sp2b" class="col-md-2 col-form-label">No. SP2B</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_sp2b" name="no_sp2b" required readonly
                                value="{{ $spb->no_sp2b }}">
                        </div>
                        <label for="tgl_sp2b" class="col-md-2 col-form-label">Tanggal SP2B</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_sp2b" name="tgl_sp2b" required readonly
                                value="{{ $spb->tgl_sp2b }}">
                        </div>
                    </div>
                    {{-- Sub Kegiatan --}}
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Sub Kegiatan</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="kd_sub_kegiatan"
                                name="kd_sub_kegiatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_kegiatan as $kegiatan)
                                    <option value="{{ $kegiatan->kd_sub_kegiatan }}"
                                        data-nama="{{ $kegiatan->nm_sub_kegiatan }}"
                                        {{ $spb->kd_sub_kegiatan == $kegiatan->kd_sub_kegiatan ? 'selected' : '' }}>
                                        {{ $kegiatan->kd_sub_kegiatan }} |
                                        {{ $kegiatan->nm_sub_kegiatan }}</option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Jenis --}}
                    <div class="mb-3 row">
                        <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="jenis" name="jenis">
                                <option value="1">SP2B</option>
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan" readonly>{{ $spb->keterangan }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-6 row" style="text-align;center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" class="btn btn-primary btn-md"
                                {{ $spb->status == 1 || $spb->status == 2 ? 'hidden' : '' }}>Simpan</button>
                            <a href="{{ route('spb_bos.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Detail SP2B --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Detail SP2B
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="tgl_transaksi" class="col-md-12 col-form-label">Tanggal Transaksi</label>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="tgl_awal" value="{{ $spb->tgl_awal }}"
                                readonly>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="tgl_akhir" value="{{ $spb->tgl_akhir }}"
                                readonly>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="detail_sp2b" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>No Bukti</th>
                                <th>Sub Kegiatan</th>
                                <th>Nama Sub Kegiatan</th>
                                <th>Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($detail_sp2b as $detail)
                                @php
                                    $total += $detail->nilai;
                                @endphp
                                <tr>
                                    <td>{{ $detail->kd_skpd }}</td>
                                    <td>{{ $detail->no_bukti }}</td>
                                    <td>{{ $detail->kd_sub_kegiatan }}</td>
                                    <td>{{ $detail->nm_sub_kegiatan }}</td>
                                    <td>{{ $detail->kd_rek6 }}</td>
                                    <td>{{ $detail->nm_rek6 }}</td>
                                    <td>{{ rupiah($detail->nilai) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total" class="col-md-8 col-form-label" style="text-align: right">Total</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total" name="total" value="{{ rupiah($total) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('bud.spb_bos.js.show');
@endsection
