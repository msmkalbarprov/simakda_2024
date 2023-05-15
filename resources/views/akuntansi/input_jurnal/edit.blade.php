@extends('template.app')
@section('title', 'Input Jurnal Umum | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Jurnal Umum
                </div>
                <div class="card-body">
                    @csrf
                    {{-- NO TERSIMPAN --}}
                    <div class="mb-3 row">
                        <label for="no_tersimpan" class="col-md-2 col-form-label">No. Tersimpan</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_tersimpan" name="no_tersimpan" required
                                readonly value="{{ $jurnal->no_voucher }}">
                        </div>
                        <label for="" class="col-md-6 col-form-label"><i>Tidak Perlu diisi atau di Edit</i></label>
                    </div>
                    {{-- NO Voucher dan Tanggal Voucher --}}
                    <div class="mb-3 row">
                        <label for="no_voucher" class="col-md-2 col-form-label">No. Voucher</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="no_voucher" name="no_voucher" required
                                value="{{ $jurnal->no_voucher }}">
                        </div>
                        <label for="tgl_voucher" class="col-md-2 col-form-label">Tanggal Voucher</label>
                        <div class="col-md-4">
                            <input class="form-control" type="date" id="tgl_voucher" name="tgl_voucher" required
                                value="{{ $jurnal->tgl_voucher }}">
                        </div>
                    </div>
                    {{-- SKPD DAN NAMA SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $jurnal->kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ nama_skpd($jurnal->kd_skpd) }}">
                        </div>
                    </div>
                    {{-- Keterangan --}}
                    <div class="mb-3 row">
                        <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-10">
                            <textarea type="text" class="form-control" id="keterangan" name="keterangan">{{ $jurnal->ket }}</textarea>
                        </div>
                    </div>
                    {{-- Jenis Jurnal --}}
                    <div class="mb-3 row">
                        <label for="jenis_jurnal" class="col-md-2 col-form-label">Jenis Jurnal</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="jenis_jurnal"
                                name="jenis_jurnal">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="0" {{ $jurnal->reev == 0 ? 'selected' : '' }}>UMUM</option>
                                <option value="2" {{ $jurnal->reev == 2 ? 'selected' : '' }}>Koreksi Persediaan
                                </option>
                                <option value="1" {{ $jurnal->reev == 1 ? 'selected' : '' }}>Revaluasi</option>
                                <option value="3" {{ $jurnal->reev == 3 ? 'selected' : '' }}>Lain - Lain</option>
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Umum --}}
                    <div class="mb-3 row" id="pilihan_umum">
                        <label for="umum" class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="umum" name="umum">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="0" {{ $jurnal->tgl_real == 0 ? 'selected' : '' }}>Jurnal Umum</option>
                                <option value="20" {{ $jurnal->tgl_real == 20 ? 'selected' : '' }}>Extracomptable
                                </option>
                                <option value="21" {{ $jurnal->tgl_real == 21 ? 'selected' : '' }}>Penghapusan</option>
                                <!-- <option value="22">Hibah Pemerintah Lainnya</option> -->
                                <!-- <option value="25">Koreksi Lain-Lain</option> -->
                                <option value="29" {{ $jurnal->tgl_real == 29 ? 'selected' : '' }}>Reklas Aset
                                    Lain-Lain
                                </option>
                                <option value="69" {{ $jurnal->tgl_real == 69 ? 'selected' : '' }}>Hibah Masuk</option>
                                <option value="70" {{ $jurnal->tgl_real == 70 ? 'selected' : '' }}>Hibah Keluar
                                </option>
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Lain Lain --}}
                    <div class="mb-3 row" id="pilihan_lain">
                        <label for="lain-lain" class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="lain_lain"
                                name="lain_lain">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="99" {{ $jurnal->tgl_real == 99 ? 'selected' : '' }}>Saldo Awal Neraca
                                </option>
                                <option value="89" {{ $jurnal->tgl_real == 89 ? 'selected' : '' }}>Saldo Awal LO
                                </option>
                                <option value="1" {{ $jurnal->tgl_real == 1 ? 'selected' : '' }}>Penyisihan Piutang
                                </option>
                                <option value="2" {{ $jurnal->tgl_real == 2 ? 'selected' : '' }}>Koreksi Penyusutan
                                </option>
                                <!-- <option value="3">Hibah Keluar</option> -->
                                <option value="4" {{ $jurnal->tgl_real == 4 ? 'selected' : '' }}>Mutasi Masuk antar
                                    OPD</option>
                                <option value="5" {{ $jurnal->tgl_real == 5 ? 'selected' : '' }}>Mutasi Keluar antar
                                    OPD</option>
                                <option value="6" {{ $jurnal->tgl_real == 6 ? 'selected' : '' }}>Penghapusan TPTGR
                                </option>
                                <option value="7" {{ $jurnal->tgl_real == 7 ? 'selected' : '' }}>Perubahan Kode
                                    Rekening</option>
                                <option value="8" {{ $jurnal->tgl_real == 8 ? 'selected' : '' }}>Koreksi Tanah
                                </option>
                                <option value="9" {{ $jurnal->tgl_real == 9 ? 'selected' : '' }}>Koreksi Utang
                                    Belanja</option>
                                <option value="10" {{ $jurnal->tgl_real == 10 ? 'selected' : '' }}>Reklass Antar Akun
                                </option>
                                <option value="11" {{ $jurnal->tgl_real == 11 ? 'selected' : '' }}>Tagihan Penjualan
                                    Angsuran</option>
                                <option value="12" {{ $jurnal->tgl_real == 12 ? 'selected' : '' }}>Penyertaan Modal
                                </option>
                                <option value="13" {{ $jurnal->tgl_real == 13 ? 'selected' : '' }}>Persediaan APBN
                                    yang
                                    belum Tercatat TA {{ tahun_anggaran() - 1 }}
                                </option>
                                <option value="15" {{ $jurnal->tgl_real == 15 ? 'selected' : '' }}>Koreksi Dana
                                    Transfer Pemerintah Pusat</option>
                                <option value="16" {{ $jurnal->tgl_real == 16 ? 'selected' : '' }}>Koreksi Gedung dan
                                    Bangunan</option>
                                <!-- <option value="17">Koreksi Persediaan</option> -->
                                <option value="18" {{ $jurnal->tgl_real == 18 ? 'selected' : '' }}>Koreksi Kas
                                </option>
                                <option value="19" {{ $jurnal->tgl_real == 19 ? 'selected' : '' }}>Extracomptable
                                </option>
                                <option value="23" {{ $jurnal->tgl_real == 23 ? 'selected' : '' }}>Koreksi Peralatan
                                    dan Mesin</option>
                                <option value="24" {{ $jurnal->tgl_real == 24 ? 'selected' : '' }}>Koreksi Jaringan
                                    Irigasi Jembatan</option>
                                <option value="26" {{ $jurnal->tgl_real == 26 ? 'selected' : '' }}>Koreksi Aset Tetap
                                    Lainnya</option>
                                <option value="27" {{ $jurnal->tgl_real == 27 ? 'selected' : '' }}>Koreksi Piutang
                                </option>
                                <option value="28" {{ $jurnal->tgl_real == 28 ? 'selected' : '' }}>Koreksi Aset Lain
                                    Lain</option>
                                <option value="30" {{ $jurnal->tgl_real == 30 ? 'selected' : '' }}>Pelimpahan Masuk
                                </option>
                                <option value="31" {{ $jurnal->tgl_real == 31 ? 'selected' : '' }}>Pelimpahan Keluar
                                </option>
                                <option value="32" {{ $jurnal->tgl_real == 32 ? 'selected' : '' }}>Penghapusan Utang
                                </option>
                            </select>
                        </div>
                    </div>
                    {{-- Hibah --}}
                    <div class="mb-3 row" id="pilihan_hibah">
                        <label for="hibah" class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="hibah"
                                name="hibah">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_hibah as $hibah)
                                    <option value="{{ $hibah->kd_rek6 }}" data-nama="{{ $hibah->nm_rek6 }}"
                                        {{ $hibah1->hibah == $hibah->kd_rek6 ? 'selected' : '' }}>
                                        {{ $hibah->kd_rek6 }} | {{ $hibah->nm_rek6 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Mutasi --}}
                    <div class="mb-3 row" id="pilihan_mutasi">
                        <label for="mutasi" class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="mutasi"
                                name="mutasi">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_skpd as $skpd)
                                    <option value="{{ $skpd->kd_skpd }}" data-nama="{{ $skpd->nm_skpd }}"
                                        {{ $jurnal->kd_skpd_mutasi == $skpd->kd_skpd ? 'selected' : '' }}>
                                        {{ $skpd->kd_skpd }} | {{ $skpd->nm_skpd }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-6 row" style="text-align;center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('input_jurnal.index') }}" class="btn btn-warning btn-md">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekening --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Rekening
                    <button type="button" style="float: right" id="tambah_rincian"
                        class="btn btn-success btn-md">Tambah Kegiatan</button>
                </div>
                <div class="card-body table-responsive">
                    <table id="detail_rincian" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Nomor Voucher</th>
                                <th>Kegiatan</th>
                                <th>Nama Kegiatan</th>
                                <th>Anggaran</th>
                                <th>Kode Rek</th>
                                <th>Nama Rekening</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>D/K</th>
                                <th>Jenis</th>
                                <th>Posting</th>
                                <th>Hibah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail_jurnal as $detail)
                                <tr>
                                    <td>{{ $detail->no_voucher }}</td>
                                    <td>{{ $detail->kd_sub_kegiatan }}</td>
                                    <td>{{ $detail->nm_sub_kegiatan }}</td>
                                    <td>{{ $detail->kd_rek6 }}</td>
                                    <td>{{ $detail->map_real }}</td>
                                    <td>{{ $detail->nm_rek6 }}</td>
                                    <td>{{ rupiah($detail->debet) }}</td>
                                    <td>{{ rupiah($detail->kredit) }}</td>
                                    <td>{{ $detail->rk }}</td>
                                    <td>{{ $detail->jns }}</td>
                                    <td>{{ $detail->pos }}</td>
                                    <td>{{ $detail->hibah }}</td>
                                    <td>
                                        <a href="javascript:void(0);"
                                            onclick="hapus('{{ $detail->no_voucher }}','{{ $detail->kd_sub_kegiatan }}','{{ $detail->kd_rek6 }}','{{ $detail->debet }}','{{ $detail->kredit }}','{{ $detail->rk }}')"
                                            class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mb-2 mt-2 row">
                        <label for="total_debet" class="col-md-2 col-form-label" style="text-align: right">Total
                            Debet</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total_debet" name="total_debet"
                                value="{{ rupiah($jurnal->total_d) }}">
                        </div>
                        <label for="total_kredit" class="col-md-2 col-form-label" style="text-align: right">Total
                            Kredit</label>
                        <div class="col-md-4">
                            <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                                class="form-control" id="total_kredit" name="total_kredit"
                                value="{{ rupiah($jurnal->total_k) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modul tambah rincian --}}
    <div id="modal_rincian" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Jenis -->
                    <div class="mb-3 row">
                        <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-10">
                            <select class="form-control select2-modal" style=" width: 100%;" id="jenis"
                                name="jenis">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="0">0 || Perubahan SAL</option>
                                <option value="1">1 || Aset</option>
                                <option value="2">2 || Hutang</option>
                                <option value="3">3 || Ekuitas</option>
                                <option value="4">4 || Pendapatan</option>
                                <option value="5">5 || Belanja</option>
                                <option value="6">6 || Pembiayaan</option>
                                <option value="7">7 || Pendapatan LO</option>
                                <option value="8">8 || Beban LO</option>
                            </select>
                        </div>
                    </div>
                    {{-- Debet/Kredit --}}
                    <div class="mb-3 row">
                        <label for="debet_kredit" class="col-md-2 col-form-label">Debet/Kredit</label>
                        <div class="col-md-10">
                            <select class="form-control select2-modal" style=" width: 100%;" id="debet_kredit"
                                name="debet_kredit">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="D">Debet</option>
                                <option value="K">Kredit</option>
                            </select>
                        </div>
                    </div>
                    <!-- Kode Kegiatan -->
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Kode Kegiatan</label>
                        <div class="col-md-10">
                            <select class="form-control select2-modal" style=" width: 100%;" id="kd_sub_kegiatan"
                                name="kd_sub_kegiatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    <!-- Kode Rekening -->
                    <div class="mb-3 row">
                        <label for="kd_rek6" class="col-md-2 col-form-label">Kode Rekening</label>
                        <div class="col-md-10">
                            <select class="form-control select2-modal" style=" width: 100%;" id="kd_rek6"
                                name="kd_rek6">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>
                    {{-- Nilai --}}
                    <div class="mb-3 row">
                        <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="nilai" id="nilai"
                                style="text-align: right" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                        </div>
                    </div>
                    {{-- Unposting --}}
                    <div class="mb-3 row">
                        <label for="unposting" class="col-md-2 col-form-label">Un-posting</label>
                        <div class="col-md-2">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="unposting">
                            </div>
                        </div>
                    </div>
                    {{-- Simpan --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button id="simpan_rincian" class="btn btn-md btn-primary">Simpan</button>
                            <button type="button" class="btn btn-md btn-warning" data-bs-dismiss="modal">Keluar</button>
                        </div>
                    </div>
                </div>
                <div class="mb-2 mt-2 row">
                    <label for="total_debet1" class="col-md-2 col-form-label" style="text-align: right">Total
                        Debet</label>
                    <div class="col-md-4">
                        <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                            class="form-control" id="total_debet1" name="total_debet1"
                            value="{{ rupiah($jurnal->total_d) }}">
                    </div>
                    <label for="total_kredit1" class="col-md-2 col-form-label" style="text-align: right">Total
                        Kredit</label>
                    <div class="col-md-4">
                        <input type="text" style="text-align: right;background-color:white;border:none;" readonly
                            class="form-control" id="total_kredit1" name="total_kredit1"
                            value="{{ rupiah($jurnal->total_k) }}">
                    </div>
                </div>
                <div class="card" style="margin: 4px">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered border-primary mb-0" style="width: 100%"
                                id="input_rekening">
                                <thead>
                                    <tr>
                                        <th>Nomor Voucher</th>
                                        <th>Kegiatan</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Anggaran</th>
                                        <th>Kode Rek</th>
                                        <th>Nama Rekening</th>
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                        <th>D/K</th>
                                        <th>Jenis</th>
                                        <th>Posting</th>
                                        <th>Hibah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail_jurnal as $detail)
                                        <tr>
                                            <td>{{ $detail->no_voucher }}</td>
                                            <td>{{ $detail->kd_sub_kegiatan }}</td>
                                            <td>{{ $detail->nm_sub_kegiatan }}</td>
                                            <td>{{ $detail->kd_rek6 }}</td>
                                            <td>{{ $detail->map_real }}</td>
                                            <td>{{ $detail->nm_rek6 }}</td>
                                            <td>{{ rupiah($detail->debet) }}</td>
                                            <td>{{ rupiah($detail->kredit) }}</td>
                                            <td>{{ $detail->rk }}</td>
                                            <td>{{ $detail->jns }}</td>
                                            <td>{{ $detail->pos }}</td>
                                            <td>{{ $detail->hibah }}</td>
                                            <td>
                                                <a href="javascript:void(0);"
                                                    onclick="hapus('{{ $detail->no_voucher }}','{{ $detail->kd_sub_kegiatan }}','{{ $detail->kd_rek6 }}','{{ $detail->debet }}','{{ $detail->kredit }}','{{ $detail->rk }}')"
                                                    class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                            </td>
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
    @include('akuntansi.input_jurnal.js.edit');
@endsection
