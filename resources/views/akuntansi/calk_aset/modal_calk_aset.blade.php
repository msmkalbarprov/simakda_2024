{{-- lap aset --}}
<div id="modal_cetak_lap_aset" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Aset </label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="rekobjek_lap_aset" class="form-label">Rekening</label>
                        <select class="form-control select_lap_aset @error('rekobjek_lap_aset') is-invalid @enderror"
                            style=" width: 100%;" id="rekobjek_lap_aset" name="rekobjek_lap_aset">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('rekobjek_lap_aset')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_lap_aset" class="form-label">Jenis</label>
                        <select name="jenis_lap_aset" class="form-control select_lap_aset" id="jenis_lap_aset">
                            <option value="1">Global</option>
                            <option value="2">Rinci</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="format_lap_aset" class="form-label">Format</label>
                        <select name="format_lap_aset" class="form-control select_lap_aset" id="format_lap_aset">
                            <option value="1">Lampiran</option>
                            <option value="2">Neraca</option>
                        </select>
                    </div>
                </div>
                
          

                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- lap penyusutan aset --}}
<div id="modal_cetak_lap_penyu_aset" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Penyusutan Aset</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="rek_lap_penyu_aset" class="form-label">Rekening Objek</label>
                        <select name="rek_lap_penyu_aset" class="form-control select_lap_penyu_aset" id="rek_lap_penyu_aset">
                            <option value="">Pilih Rekening Objek</option>
                            <option value="1302">1302 || Peralatan dan Mesin</option>
                            <option value="1303">1303 || Gedung dan Bangunan</option>
                            <option value="1304">1304 || Jalan, Jaringan dan Irigasi</option>
                            <option value="1305">1305 || Aset Tetap Lainnya</option>
                        </select>
                    </div>
                </div>
                
          

                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- lap Amortisasi --}}
<div id="modal_cetak_lap_amortisasi" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Amortisasi</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- lap Pengadaan Aset --}}
<div id="modal_cetak_lap_peng_aset" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Amortisasi</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- lap Penjelasan LRA lO --}}
<div id="modal_cetak_lap_pen_lralo" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Penjelasan LRA lO</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="format_lap_pen_lralo" class="form-label">Format</label>
                        <select name="format_lap_pen_lralo" class="form-control select_lap_pen_lralo" id="format_lap_pen_lralo">
                            <option value="">Pilih Format</option>
                            <option value="1">Biasa</option>
                            <option value="2">Rinci Pendapatan</option>
                            <option value="3">Rinci Belanja/Beban</option>
                        </select>
                    </div>
                </div>
                
          

                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- lap Penjelasan Komulatif --}}
<div id="modal_cetak_lap_pen_komulatif" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Penjelasan Komulatif</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- lap Penjelasan lo --}}
<div id="modal_cetak_lap_pen_lo" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Penjelasan LO</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hambatan CALK --}}
<div id="modal_cetak_hambatan_calk" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Hambatan CALK</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Rekap Belanja Pegawai dan Barang --}}
<div id="modal_cetak_rekap_bel_peg_brg" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Rekap Belanja Pegawai dan Barang</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Rekap Pendapatan --}}
<div id="modal_cetak_rekap_pendapatan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Rekap Pendapatan</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Rekap Beban --}}
<div id="modal_cetak_rekap_beban" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Rekap Beban</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Penjelasan Pendapatan --}}
<div id="modal_cetak_penjelasan_pendapatan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Penjelasan Pendapatan</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lap. CALK LO Beban --}}
<div id="modal_cetak_lap_calk_lo_beban" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. CALK LO Beban</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="jenis_lap_calk_lo_beban" class="form-label">Jenis</label>
                        <select name="jenis_lap_calk_lo_beban" class="form-control select_lap_calk_lo_beban" id="jenis_lap_calk_lo_beban">
                            <option value="">--Pilih Jenis--</option>
                            <option value="1">Pajak - LO</option>
                            <option value="2">Retribusi - LO</option>
                            <option value="3">Lain-lain PAD yang Sah - LO</option>
                            <option value="4">Beban Pegawai</option>
                            <option value="5">Beban Barang Jasa</option>
                            <option value="6">Beban Persediaan</option>
                            <option value="7">Beban Pemeliharaan</option>
                            <option value="8">Beban Perjalanan Dinas</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lap. CALK Aset --}}
<div id="modal_cetak_lap_calk_aset" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. CALK Aset</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="rek_lap_calk_aset" class="form-label">Rekening</label>
                        <select name="rek_lap_calk_aset" class="form-control select_lap_calk_aset" id="rek_lap_calk_aset">
                            <option value="">--Pilih Rekening--</option>
                            <option value="1301">1301 || Tanah</option>
                            <option value="1302">1302 || Peralatan dan Mesin</option>
                            <option value="1303">1303 || Gedung dan Bangunan</option>
                            <option value="1304">1304 || Jalan, Jaringan, dan Irigasi</option>
                            <option value="1305">1305 || Aset Tetap Lainnya</option>
                            <option value="1306">1306 || Konstruksi Dalam Pengerjaan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_lap_calk_aset" class="form-label">Jenis</label>
                        <select name="jenis_lap_calk_aset" class="form-control select_lap_calk_aset" id="jenis_lap_calk_aset">
                            <option value="">--Pilih Jenis--</option>
                            <option value="2">Mutasi Bertambah</option>
                            <option value="3">Mutasi Berkurang</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lap. CALK Penyajian Data --}}
<div id="modal_cetak_lap_calk_penyajian_data" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. CALK Penyajian Data</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="rek_lap_calk_penyajian_data" class="form-label">Rekening</label>
                        <select name="rek_lap_calk_penyajian_data" class="form-control select_lap_calk_penyajian_data" id="rek_lap_calk_penyajian_data">
                            <option value="">--Pilih Rekening--</option>
                            <option value="1301">1301 || Tanah</option>
                            <option value="1302">1302 || Peralatan dan Mesin</option>
                            <option value="1303">1303 || Gedung dan Bangunan</option>
                            <option value="1304">1304 || Jalan, Jaringan, dan Irigasi</option>
                            <option value="1305">1305 || Aset Tetap Lainnya</option>
                            <option value="1306">1503 || Aset Lainnya</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lap. CALK Kewajiban --}}
<div id="modal_cetak_lap_calk_kewajiban" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. CALK Kewajiban</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="rek_lap_calk_kewajiban" class="form-label">Rekening</label>
                        <select name="rek_lap_calk_kewajiban" class="form-control select_lap_calk_kewajiban" id="rek_lap_calk_kewajiban">
                            <option value="">--Pilih Rekening--</option>
                            <option value="211">Utang PPh Pusat</option>
                            <option value="212">Utang PPN Pusat</option>
                            <option value="213">Utang Perhitungan Pihak Ketiga Lainnya</option>
                            <option value="221">Pendapatan Diterima Dimuka lainnya</option>
                            <option value="231">Utang Belanja Pegawai</option>
                            <option value="232">Utang Belanja Barang dan Jasa</option>
                            <option value="233">Utang Belanja Modal</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- LPE Lain-lain --}}
<div id="modal_cetak_lap_calk_lpe_lain_lain" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak LPE Lain-lain</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Penjelasan CALK --}}
<div id="modal_cetak_penjelasan_calk" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Penjelasan CALK</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="rek_penjelasan_calk" class="form-label">Rekening</label>
                        <select name="rek_penjelasan_calk" class="form-control select_penjelasan_calk" id="rek_penjelasan_calk">
                            <option value="">--Pilih Rekening--</option>
                            <option value="1301">1301 || Tanah</option>
                            <option value="1302">1302 || Peralatan dan Mesin</option>
                            <option value="1303">1303 || Gedung dan Bangunan</option>
                            <option value="1304">1304 || Jalan, Jaringan, dan Irigasi</option>
                            <option value="1305">1305 || Aset Tetap Lainnya</option>
                            <option value="1306">1306 || Konstruksi Dalam Pengerjaan</option>
                            <option value="1503">1503 || Aset Tidak Berwujud</option>
                            <option value="1504">1504 || Konstruksi Dalam Pengerjaan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="format_penjelasan_calk" class="form-label">Format</label>
                        <select name="format_penjelasan_calk" class="form-control select_penjelasan_calk" id="format_penjelasan_calk">
                            <option value="">--Pilih Format--</option>
                            <option value="1">Tanpa Penjelasan</option>
                            <option value="2">Dengan Penjelasan</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lap. Beban Penyusutan --}}
<div id="modal_cetak_lap_beban_penyusutan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Beban Penyusutan</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Laporan Jaminan Pemeliharaan --}}
<div id="modal_cetak_lap_jaminan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Laporan Jaminan Pemeliharaan</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_jaminan" id="pilihan0"
                                value="keseluruhan">
                            <label class="form-check-label" for="pilihan">Keseluruhan</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_jaminan" id="pilihan2"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div id="baris_skpd_jaminan">
                            <label for="kd_skpd_jaminan" class="form-label">Kode SKPD</label>
                            <select class="form-control select_lap_jaminan  @error('kd_skpd') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_jaminan" name="kd_skpd_jaminan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_jaminan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lap. CALK Aset --}}
<div id="modal_cetak_lap_akumulasi_penyusutan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title">
                    <label for="labelcetak_semester" id="labelcetak_semester">Cetak Lap. Akumulasi Penyusutan</label>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="rek_lap_akumulasi_penyusutan" class="form-label">Rekening</label>
                        <select name="rek_lap_akumulasi_penyusutan" class="form-control select_lap_akumulasi_penyusutan" id="rek_lap_akumulasi_penyusutan">
                            <option value="">--Pilih Rekening--</option>
                            <option value="130701">130701 || Akumulasi Penyusutan Peralatan dan Mesin</option>
                            <option value="130702">130702 || Akumulasi Penyusutan Gedung dan Bangunan</option>
                            <option value="130703">130703 || Akumulasi Penyusutan Jalan, Jaringan, danIrigasi</option>
                            <option value="130704">130704 || Akumulasi Penyusutan Aset Tetap Lainnya</option>
                            <option value="150501">150501 || Akumulasi Amortisasi Aset Tidak Berwujud</option>
                            <option value="150601">150601 || Akumulasi Penyusutan Aset Lainnya</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


