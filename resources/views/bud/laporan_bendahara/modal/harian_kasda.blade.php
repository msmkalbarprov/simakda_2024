<div id="modal_harian_kasda" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak" id="labelcetak">LAPORAN KAS HARIAN KASDA</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Tanggal --}}
                {{-- <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="" class="form-label">Tanggal</label>
                        <input type="date" id="tgl_harian_kasda" class="form-control">
                    </div>
                </div> --}}
                {{-- Pilihan --}}
                <div class="mb-3 row" id="row-hidden">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                id="pilihan_periode_harian_kasda" value="2">
                            <label class="form-check-label" for="pilihan">PERIODE</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                id="pilihan_bulan_harian_kasda" value="3">
                            <label class="form-check-label" for="pilihan">BULAN</label>
                        </div>
                    </div>
                </div>
                {{-- PERIODE --}}
                <div class="mb-3 row" id="pilih_periode_harian_kasda">
                    <div class="col-md-6">
                        <label for="" class="form-label">Periode 1</label>
                        <input type="date" id="periode1_harian_kasda" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Periode 2</label>
                        <input type="date" id="periode2_harian_kasda" class="form-control">
                    </div>
                </div>
                {{-- BULAN --}}
                <div class="mb-3 row" id="pilih_bulan_harian_kasda">
                    <div class="col-md-6">
                        <label for="periode" class="form-label">Bulan</label>
                        <select class="form-control select2-harian_kasda" style=" width: 100%;" id="bulan_harian_kasda">
                            <option value="" disabled selected>Silahkan Pilih</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                </div>
                {{-- Kuasa BUD --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="penandatangan" class="form-label">Kuasa BUD</label>
                        <select class="form-control select2-harian_kasda" style=" width: 100%;" id="ttd_harian_kasda">
                            <option value="" disabled selected>Silahkan Pilih</option>
                            @foreach ($bud as $ttd)
                                <option value="{{ $ttd->nip }}">{{ $ttd->nip }} | {{ $ttd->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- No. Halaman dan Spasi --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="halaman_harian_kasda" class="form-label">No. Halaman</label>
                        <input type="number" value="1" min="1" class="form-control"
                            id="halaman_harian_kasda">
                    </div>
                    <div class="col-md-6">
                        <label for="spasi_harian_kasda" class="form-label">Spasi</label>
                        <input type="number" value="1" min="1" class="form-control"
                            id="spasi_harian_kasda">
                    </div>
                </div>
                {{-- Pilihan Cetak --}}
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md cetak_harian_kasda"
                            data-jenis="pdf">PDF</button>
                        <button type="button" class="btn btn-dark btn-md cetak_harian_kasda"
                            data-jenis="layar">Layar</button>
                        <button type="button" class="btn btn-dark btn-md cetak_harian_kasda"
                            data-jenis="excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
