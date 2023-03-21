@extends('template.app')
@section('title', 'Ubah SP2H | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input SP2H
                </div>
                <div class="card-body">
                    @csrf
                    <div class="mb-3 row">
                        @if ($sp2h->status == '1')
                            <p style="font-size: x-large;color: red;">SP2H sudah dibuat SPB!!</p>
                        @endif
                    </div>
                    {{-- SKPD dan Nama SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $skpd->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ $skpd->nm_skpd }}">
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- No. Kas dan No SP2H Tersimpan --}}
                    <div class="mb-3 row">
                        <label for="no_kas" class="col-md-2 col-form-label">No. Kas</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_kas" name="no_kas" required readonly
                                value="{{ $sp2h->no_kas }}">
                        </div>
                        <label for="no_simpan" class="col-md-2 col-form-label">No. SP2H Tersimpan</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_simpan" name="no_simpan" required readonly
                                style="text-align: right" value="{{ $sp2h->no_sp2h }}">
                        </div>
                    </div>
                    {{-- NO SP2H dan Tanggal SP2H --}}
                    <div class="mb-3 row">
                        <label for="no_sp2h" class="col-md-2 col-form-label">No. SP2H</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_sp2h" name="no_sp2h" required
                                placeholder="No SP2H tanpa spasi" value="{{ $sp2h->no_sp2h }}">
                        </div>
                        <label for="tgl_sp2h" class="col-md-2 col-form-label">Tanggal SP2H</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_sp2h" name="tgl_sp2h" required
                                value="{{ $sp2h->tgl_sp2h }}">
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
                                        {{ $sp2h->kd_sub_kegiatan == $kegiatan->kd_sub_kegiatan ? 'selected' : '' }}>
                                        {{ $kegiatan->kd_sub_kegiatan }} |
                                        {{ $kegiatan->nm_sub_kegiatan }}</option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="satdik" class="col-md-2 col-form-label">SATDIK</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="satdik" name="satdik">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="2" data-nama="SMA/SMK SWASTA"
                                    {{ $sp2h->kd_satdik == 2 ? 'selected' : '' }}>SMA/SMK SWASTA</option>
                                <option value="3" data-nama="DIKSUS" {{ $sp2h->kd_satdik == 3 ? 'selected' : '' }}>
                                    DIKSUS</option>
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Jenis --}}
                    <div class="mb-3 row">
                        <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="jenis" name="jenis">
                                <option value="1">SP2H</option>
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea class="form-control" style="width: 100%" id="keterangan" name="keterangan">{{ $sp2h->keterangan }}</textarea>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-6 row" style="text-align;center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" class="btn btn-primary btn-md"
                                {{ $sp2h->status == 1 ? 'hidden' : '' }}>Simpan</button>
                            <a href="{{ route('sp2h.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Detail SP2H --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Detail SP2H
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="tgl_transaksi" class="col-md-12 col-form-label">Tanggal Transaksi</label>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="tgl_awal" readonly
                                value="{{ $sp2h->tgl_awal }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="tgl_akhir" readonly
                                value="{{ $sp2h->tgl_akhir }}">
                        </div>
                        <div class="col-md-8">
                            <button disabled class="btn btn-success" id="tampilkan"><i class="uil-eye"></i>
                                Tampilkan</button>
                            <button disabled class="btn btn-success" id="kosongkan"><i class="uil-trash"></i>
                                Kosongkan</button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="detail_sp2h" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>No Bukti</th>
                                <th>Sub Kegiatan</th>
                                <th>Nama Sub Kegiatan</th>
                                <th>Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($detail_sp2h as $detail)
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
                                    <td>
                                        <a href="javascript:void(0);"
                                            onclick="hapus('{{ $detail->no_bukti }}','{{ $detail->kd_rek6 }}','{{ $detail->nilai }}', '{{ $sp2h->status }}')"
                                            class="btn btn-danger btn-sm"><i class="uil-trash"></i></a>
                                    </td>
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
    @include('skpd.sp2h.js.edit');
@endsection
