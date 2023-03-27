@extends('template.app')
@section('title', 'Ubah SPB | SIMAKDA')
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
                    {{-- NO SPB HIBAH dan Tanggal SPB HIBAH --}}
                    <div class="mb-3 row">
                        <label for="no_spb" class="col-md-2 col-form-label">No. SPB HIBAH</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_spb" name="no_spb" required readonly
                                value="{{ $spb->no_spb_hibah }}">
                            <input class="form-control" type="text" id="no_urut" name="no_urut" required readonly
                                hidden value="{{ $spb->no_urut }}">
                        </div>
                        <label for="tgl_spb" class="col-md-2 col-form-label">Tanggal SPB HIBAH</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_spb" name="tgl_spb" required
                                value="{{ $spb->tgl_spb_hibah }}">
                        </div>
                    </div>
                    {{-- SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="kd_skpd" name="kd_skpd">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_skpd as $skpd)
                                    <option value="{{ $skpd->kd_skpd }}" data-nama="{{ $skpd->nm_skpd }}"
                                        {{ $spb->kd_skpd == $skpd->kd_skpd ? 'selected' : '' }}>
                                        {{ $skpd->kd_skpd }} |
                                        {{ $skpd->nm_skpd }}</option>
                                @endforeach
                                </option>
                            </select>
                            <input class="form-control" type="text" id="tahun_anggaran" name="tahun_anggaran" required
                                readonly hidden value="{{ tahun_anggaran() }}">
                        </div>
                    </div>
                    {{-- Kode Sub Kegiatan --}}
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Kode Sub Kegiatan</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="kd_sub_kegiatan"
                                name="kd_sub_kegiatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    {{-- Kode Rekening --}}
                    <div class="mb-3 row">
                        <label for="rekening" class="col-md-2 col-form-label">Kode Rekening</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="rekening"
                                name="rekening">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_rekening as $rekening)
                                    <option value="{{ $rekening->kd_rek6 }}" data-nama="{{ $rekening->nm_rek6 }}"
                                        {{ $spb->kd_rek6 == $rekening->kd_rek6 ? 'selected' : '' }}>
                                        {{ $rekening->kd_rek6 }} |
                                        {{ $rekening->nm_rek6 }}</option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Kategori dan Gelombang --}}
                    <div class="mb-3 row">
                        <label for="kategori" class="col-md-2 col-form-label">Kategori</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%" id="kategori"
                                name="kategori">
                                @foreach ($daftar_kategori as $kategori)
                                    <option value="{{ $kategori->kategori }}"
                                        {{ $spb->kategori == $kategori->kategori ? 'selected' : '' }}>
                                        {{ $kategori->kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="gelombang" class="col-md-2 col-form-label">Gelombang</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%" id="gelombang"
                                name="gelombang">
                                <option value="I" {{ $spb->gelombang == 'I' ? 'selected' : '' }}> I </option>
                                <option value="II" {{ $spb->gelombang == 'II' ? 'selected' : '' }}> II </option>
                                <option value="III" {{ $spb->gelombang == 'III' ? 'selected' : '' }}> III</option>
                                <option value="IV" {{ $spb->gelombang == 'IV' ? 'selected' : '' }}> IV </option>
                            </select>
                        </div>
                    </div>
                    {{-- Tahapan --}}
                    <div class="mb-3 row">
                        <label for="tahapan" class="col-md-2 col-form-label">Tahapan</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple" style="width: 100%" id="tahapan" name="tahapan">
                                <option value="I" {{ $spb->tahapan == 'I' ? 'selected' : '' }}> I </option>
                                <option value="II" {{ $spb->tahapan == 'II' ? 'selected' : '' }}> II </option>
                                <option value="III" {{ $spb->tahapan == 'III' ? 'selected' : '' }}> III</option>
                                <option value="IV" {{ $spb->tahapan == 'IV' ? 'selected' : '' }}> IV </option>
                            </select>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-6 row" style="text-align;center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('spb_hibah.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Detail SPB --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Detail SPB
                    <button type="button" style="float: right" id="tambah_rincian"
                        class="btn btn-success btn-md">Tambah Rincian</button>
                </div>
                <div class="card-body table-responsive">
                    <table id="detail_spb" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Tanggal SP2H</th>
                                <th>No SP2H</th>
                                <th>Kode Satdik</th>
                                <th>Nama Satdik</th>
                                <th>Rupiah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($detail_spb as $detail)
                                @php
                                    $total += $detail->nilai;
                                @endphp
                                <tr>
                                    <td>{{ $spb->tgl_spb_hibah }}</td>
                                    <td>{{ $detail->no_sp2h }}</td>
                                    <td>{{ $detail->kd_satdik }}</td>
                                    <td>{{ $detail->nm_satdik }}</td>
                                    <td>{{ rupiah($detail->nilai) }}</td>
                                    <td>
                                        <a href="#"
                                            onclick="hapus('{{ $detail->no_sp2h }}','{{ $detail->kd_satdik }}','{{ $detail->nilai }}')"
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

    {{-- modul tambah rincian --}}
    <div id="modal_rincian" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Rekening</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No. SP2H --}}
                    <div class="mb-3 row">
                        <label for="no_sp2h" class="col-md-2 col-form-label">No. SP2H</label>
                        <div class="col-md-10">
                            <select name="no_sp2h" class="form-control select-modal" id="no_sp2h">
                                <option value="" selected disabled>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    {{-- Nilai --}}
                    <div class="mb-3 row">
                        <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-10">
                            <input type="text" name="nilai" id="nilai" style="text-align: right"
                                class="form-control" data-type="currency">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" id="pilih" class="btn btn-md btn-success">Pilih</button>
                            <button type="button" class="btn btn-md btn-warning"
                                data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('bud.spb_hibah.js.edit');
@endsection
