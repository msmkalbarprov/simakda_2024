{{-- modal cetak SPJ --}}

{{-- lra --}}
<div id="modal_lralapkeu" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        @if (Auth::user()->is_admin == '1')
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="pilihan0"
                                    value="keseluruhan">
                                <label class="form-check-label" for="pilihan">Keseluruhan</label>
                            </div>
                        @else
                        @endif
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="pilihan1"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="pilihan2"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="col-md-6" >
                        <div id="baris_skpd">
                            <label for="kd_skpd" class="form-label">Kode SKPD</label>
                            <select class="form-control select2  @error('kd_skpd') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd" name="kd_skpd">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode" id="pilihan1"
                                value="periode">
                            <label class="form-check-label" for="pilihan">Periode</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode" id="pilihan2"
                                value="bulan">
                            <label class="form-check-label" for="pilihan">Bulan</label>
                        </div>
                    </div>
                    {{-- PERIODE /BULAN --}}
                        <div id="baris_periode1" class="col-md-3">
                            <label for="periode1" class="form-label">Periode</label>
                            <input type="date" id="tanggal1" name="tanggal1" class="form-control">
                        </div>
                        <div id="baris_periode2" class="col-md-3">
                            <label for="periode2" class="form-label">&nbsp;</label>
                            <input type="date" id="tanggal2" name="tanggal2" class="form-control">
                            
                        </div>
                        <div id="baris_bulan" class="col-md-6">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" class="form-control select2" id="bulan">
                                <option value="">Silahkan Pilih</option>
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

                {{-- SKPD --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="jns_anggaran" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran" class="form-control select2" id="jns_anggaran">
                            <option value="" selected disabled>Silahkan Pilih</option>
                            @foreach ($jns_anggaran as $anggaran)
                                <option value="{{ $anggaran->kode }}" data-nama="{{ $anggaran->nama }}">
                                    {{ $anggaran->kode }} | {{ $anggaran->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="pa_kpa_lra" class="form-label">PA/KPA</label>
                        <select class="form-control select2 @error('pa_kpa_lra') is-invalid @enderror"
                            style=" width: 100%;" id="pa_kpa_lra" name="pa_kpa_lra">
                            <option value="" disabled selected>Silahkan Pilihh</option>
                            <option value="-">HARISSON</option>
                        </select>
                        @error('pa_kpa_lra')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- PA/KPA --}}
                    <div class="col-md-6">
                        <label for="tgl_ttd" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd" name="tgl_ttd" class="form-control">
                    </div>
                </div>

                <div class="mb-3 row" id="row-hidden3">
                    
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

{{-- neraca --}}
<div id="modal_cetak_neraca" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        @if (Auth::user()->is_admin == '1')
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pilihan_neraca" id="pilihan0"
                                    value="keseluruhan">
                                <label class="form-check-label" for="pilihan">Keseluruhan</label>
                            </div>
                        @else
                        @endif
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_neraca" id="pilihan1"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_neraca" id="pilihan2"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="col-md-6" >
                        <div id="baris_skpd_neraca">
                            <label for="kd_skpd_neraca" class="form-label">Kode SKPD</label>
                            <select class="form-control select_neraca  @error('kd_skpd') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_neraca" name="kd_skpd_neraca">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_neraca')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="mb-3 row">
                    
                    {{-- PERIODE /BULAN --}}
                        

                        <div id="baris_bulan" class="col-md-6">
                            <label for="bulan_neraca" class="form-label">Bulan</label>
                            <select name="bulan_neraca" class="form-control select_neraca" id="bulan_neraca">
                                <option value="">Silahkan Pilih</option>
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
                        <div class="col-md-6">
                            <label for="cetakan" class="form-label">Jenis Cetakan</label>
                            <select name="cetakan" class="form-control select_neraca" id="cetakan">
                                <option value="">Silahkan Pilih</option>
                                <option value="1">Cetak Biasa</option>
                                <option value="2">Cetak Per Obyek</option>
                                @if (Auth::user()->is_admin == '1')
                                    <option value="3">Cetak Per Obyek Aset Tetap</option>
                                @else
                                @endif
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

{{-- lo --}}
<div id="modal_cetak_lo" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        @if (Auth::user()->is_admin == '1')
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pilihan_lo" id="pilihan0"
                                    value="keseluruhan">
                                <label class="form-check-label" for="pilihan">Keseluruhan</label>
                            </div>
                        @else
                        @endif
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_lo" id="pilihan1"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_lo" id="pilihan2"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="col-md-6" >
                        <div id="baris_skpd_lo">
                            <label for="kd_skpd_lo" class="form-label">Kode SKPD</label>
                            <select class="form-control select_lo  @error('kd_skpd') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_lo" name="kd_skpd_lo">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_lo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="mb-3 row">
                    

                        <div id="baris_bulan" class="col-md-6">
                            <label for="bulan_lo" class="form-label">Bulan</label>
                            <select name="bulan_lo" class="form-control select_lo" id="bulan_lo">
                                <option value="">Silahkan Pilih</option>
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
                        <div class="col-md-6">
                            <label for="cetakan_lo" class="form-label">Jenis Cetakan</label>
                            <select name="cetakan_lo" class="form-control select_lo" id="cetakan_lo">
                                <option value="">Silahkan Pilih</option>
                                <option value="1">LO</option>
                                <option value="2">LO Rincian</option>
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

{{-- lpe --}}
<div id="modal_cetak_lpe" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        @if (Auth::user()->is_admin == '1')
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pilihan_lpe" id="pilihan0"
                                    value="keseluruhan">
                                <label class="form-check-label" for="pilihan">Keseluruhan</label>
                            </div>
                        @else
                        @endif
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_lpe" id="pilihan1"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_lpe" id="pilihan2"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="col-md-6" >
                        <div id="baris_skpd_lpe">
                            <label for="kd_skpd_lpe" class="form-label">Kode SKPD</label>
                            <select class="form-control select_lpe  @error('kd_skpd') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_lpe" name="kd_skpd_lpe">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_lpe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="mb-3 row">
                    
                    {{-- PERIODE /BULAN --}}
                        

                        <div id="baris_bulan" class="col-md-6">
                            <label for="bulan_lpe" class="form-label">Bulan</label>
                            <select name="bulan_lpe" class="form-control select_lpe" id="bulan_lpe">
                                <option value="">Silahkan Pilih</option>
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

{{-- lak --}}
<div id="modal_cetak_lak" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3 row">
                    
                    {{-- BULAN --}}
                        

                        <div id="baris_bulan" class="col-md-6">
                            <label for="bulan_lak" class="form-label">Bulan</label>
                            <select name="bulan_lak" class="form-control select_lak" id="bulan_lak">
                                <option value="">Silahkan Pilih</option>
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

{{-- lra semester sap --}}
<div id="modal_cetak_semester" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        @if (Auth::user()->is_admin == '1')
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pilihan_semester" id="pilihan0_semester"
                                    value="keseluruhan">
                                <label class="form-check-label" for="pilihan">Keseluruhan</label>
                            </div>
                        @else
                        @endif
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_semester" id="pilihan1_semester"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_semester" id="pilihan2_semester"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="col-md-6" >
                        <div id="baris_skpd_semester">
                            <label for="kd_skpd_semester" class="form-label">Kode SKPD</label>
                            <select class="form-control select_semester  @error('kd_skpd_semester') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_semester" name="kd_skpd_semester">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd_semester" class="form-label">Pilih</label><br>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode_semester" id="pilihan1_semester"
                                value="periode">
                            <label class="form-check-label" for="pilihan">Periode</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode_semester" id="pilihan2_semester"
                                value="bulan">
                            <label class="form-check-label" for="pilihan">Bulan</label>
                        </div>
                    </div>
                    {{-- PERIODE /BULAN --}}
                        <div id="baris_periode1_semester" class="col-md-3">
                            <label for="periode1" class="form-label">Periode</label>
                            <input type="date" id="tanggal1_semester" name="tanggal1_semester" class="form-control">
                        </div>
                        <div id="baris_periode2_semester" class="col-md-3">
                            <label for="periode2_semester" class="form-label">&nbsp;</label>
                            <input type="date" id="tanggal2_semester" name="tanggal2_semester" class="form-control">
                            
                        </div>
                        <div id="baris_bulan_semester" class="col-md-6">
                            <label for="bulan_semester" class="form-label">Bulan</label>
                            <select name="bulan_semester" class="form-control select_semester" id="bulan_semester">
                                <option value="">Silahkan Pilih</option>
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

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="jns_anggaran_semester" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_semester" class="form-control select_semester" id="jns_anggaran_semester">
                            <option value="" selected disabled>Silahkan Pilih</option>
                            @foreach ($jns_anggaran as $anggaran)
                                <option value="{{ $anggaran->kode }}" data-nama="{{ $anggaran->nama }}">
                                    {{ $anggaran->kode }} | {{ $anggaran->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="jenis_data" class="form-label">Jenis Data</label>
                        <select name="jenis_data" class="form-control select_semester" id="jenis_data_semester">
                            @if (Auth::user()->is_admin == '1')
                                <option value="">Silahkan Pilih</option>
                                <option value="1">SP2D terbit & SPJ Pendapatan</option>
                                <option value="2">SP2D Advice & SPJ Pendapatan</option>
                                <option value="3">SP2D Lunas & SPJ Pendapatan</option>
                                <option value="4">SPJ Fungsional & Pendapatan</option>
                                <option value="5">Jurnal</option>
                            @else
                                <option value="">Silahkan Pilih</option>
                                <option value="4">SPJ Fungsional & Pendapatan</option>
                                <option value="5">Jurnal</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="pa_kpa" class="form-label">PA/KPA</label>
                        <select class="form-control select_semester @error('pa_kpa') is-invalid @enderror"
                            style=" width: 100%;" id="pa_kpa" name="pa_kpa">
                            <option value="" disabled selected>Silahkan Pilihh</option>
                            <option value="-">HARISSON</option>
                        </select>
                        @error('pa_kpa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- PA/KPA --}}
                    <div class="col-md-6">
                        <label for="tgl_ttd_semester" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_semester" name="tgl_ttd_semester" class="form-control">
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

{{-- lra semester rinci --}}
<div id="modal_cetak_semesterrinci" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        @if (Auth::user()->is_admin == '1')
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pilihan_semesterrinci" id="pilihan0_semesterrinci"
                                    value="keseluruhan">
                                <label class="form-check-label" for="pilihan">Keseluruhan</label>
                            </div>
                        @else
                        @endif
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_semesterrinci" id="pilihan1_semesterrinci"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_semesterrinci" id="pilihan2_semesterrinci"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="col-md-6" >
                        <div id="baris_skpd_semesterrinci">
                            <label for="kd_skpd_semesterrinci" class="form-label">Kode SKPD</label>
                            <select class="form-control select_semesterrinci  @error('kd_skpd_semesterrinci') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_semesterrinci" name="kd_skpd_semesterrinci">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_semesterrinci')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd_semesterrinci" class="form-label">Pilih</label><br>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode_semesterrinci" id="pilihan1_semesterrinci"
                                value="periode">
                            <label class="form-check-label" for="pilihan">Periode</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode_semesterrinci" id="pilihan2_semesterrinci"
                                value="bulan">
                            <label class="form-check-label" for="pilihan">Bulan</label>
                        </div>
                    </div>
                    {{-- PERIODE /BULAN --}}
                        <div id="baris_periode1_semesterrinci" class="col-md-3">
                            <label for="periode1" class="form-label">Periode</label>
                            <input type="date" id="tanggal1_semesterrinci" name="tanggal1_semesterrinci" class="form-control">
                        </div>
                        <div id="baris_periode2_semesterrinci" class="col-md-3">
                            <label for="periode2_semesterrinci" class="form-label">&nbsp;</label>
                            <input type="date" id="tanggal2_semesterrinci" name="tanggal2_semesterrinci" class="form-control">
                            
                        </div>
                        <div id="baris_bulan_semesterrinci" class="col-md-6">
                            <label for="bulan_semesterrinci" class="form-label">Bulan</label>
                            <select name="bulan_semesterrinci" class="form-control select_semesterrinci" id="bulan_semesterrinci">
                                <option value="">Silahkan Pilih</option>
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

                {{-- SKPD --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="jns_anggaran_semesterrinci" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_semesterrinci" class="form-control select_semesterrinci" id="jns_anggaran_semesterrinci">
                            <option value="" selected disabled>Silahkan Pilih</option>
                            @foreach ($jns_anggaran as $anggaran)
                                <option value="{{ $anggaran->kode }}" data-nama="{{ $anggaran->nama }}">
                                    {{ $anggaran->kode }} | {{ $anggaran->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="jenis_data" class="form-label">Jenis Data</label>
                        <select name="jenis_data" class="form-control select_semesterrinci" id="jenis_data_semesterrinci">
                            @if (Auth::user()->is_admin == '1')
                                <option value="">Silahkan Pilih</option>
                                <option value="1">SP2D terbit & SPJ Pendapatan</option>
                                <option value="2">SP2D Advice & SPJ Pendapatan</option>
                                <option value="3">SP2D Lunas & SPJ Pendapatan</option>
                                <option value="4">SPJ Fungsional & Pendapatan</option>
                                <option value="5">Jurnal</option>
                            @else
                                <option value="">Silahkan Pilih</option>
                                <option value="4">SPJ Fungsional & Pendapatan</option>
                                <option value="5">Jurnal</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="panjang_data" class="form-label">Rincian</label>
                        <select name="panjang_data" class="form-control select_semesterrinci" id="panjang_data_semesterrinci">
                            <option value="">Silahkan Pilih</option>
                            <option value="4">Jenis</option>
                            <option value="6">Objek</option>
                            <option value="8">RincianObjek</option>
                            <option value="12">Sub Rincian Objek</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="pa_kpa_rinci" class="form-label">PA/KPA</label>
                        <select class="form-control select_semesterrinci @error('pa_kpa_rinci') is-invalid @enderror"
                            style=" width: 100%;" id="pa_kpa_rinci" name="pa_kpa_rinci">
                            <option value="" disabled selected>Silahkan Pilihh</option>
                            <option value="-">HARISSON</option>
                        </select>
                        @error('pa_kpa_rinci')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- PA/KPA --}}
                    <div class="col-md-6">
                        <label for="tgl_ttd_semesterrinci" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_semesterrinci" name="tgl_ttd_semesterrinci" class="form-control">
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

{{-- lra semester sap untuk bpkp--}}
<div id="modal_cetak_semester_bpkp" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilihan 1</label><br>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_semester_bpkp" id="pilihan0_semester_bpkp"
                                value="keseluruhan">
                            <label class="form-check-label" for="pilihan">Keseluruhan</label>
                        </div>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_semester_bpkp" id="pilihan1_semester_bpkp"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_semester_bpkp" id="pilihan2_semester_bpkp"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="col-md-6" >
                        <div id="baris_skpd_semester_bpkp">
                            <label for="kd_skpd_semester_bpkp" class="form-label">Kode SKPD</label>
                            <select class="form-control select_semester_bpkp  @error('kd_skpd_semester_bpkp') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_semester_bpkp" name="kd_skpd_semester_bpkp">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_semester_bpkp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd_semester_bpkp" class="form-label">Pilihan 2</label><br>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode_semester_bpkp" id="pilihan0_semester_bpkp"
                                value="tahun">
                            <label class="form-check-label" for="pilihan">Keseluruhan</label>
                        </div>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode_semester_bpkp" id="pilihan1_semester_bpkp"
                                value="periode">
                            <label class="form-check-label" for="pilihan">Periode</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihanperiode_semester_bpkp" id="pilihan2_semester_bpkp"
                                value="bulan">
                            <label class="form-check-label" for="pilihan">Bulan</label>
                        </div>
                    </div>
                    {{-- PERIODE /BULAN --}}
                        <div id="baris_periode1_semester_bpkp" class="col-md-3">
                            <label for="periode1" class="form-label">Periode</label>
                            <input type="date" id="tanggal1_semester_bpkp" name="tanggal1_semester_bpkp" class="form-control">
                        </div>
                        <div id="baris_periode2_semester_bpkp" class="col-md-3">
                            <label for="periode2_semester_bpkp" class="form-label">&nbsp;</label>
                            <input type="date" id="tanggal2_semester_bpkp" name="tanggal2_semester_bpkp" class="form-control">
                            
                        </div>
                        <div id="baris_bulan_semester_bpkp" class="col-md-6">
                            <label for="bulan_semester_bpkp" class="form-label">Bulan</label>
                            <select name="bulan_semester_bpkp" class="form-control select_semester_bpkp" id="bulan_semester_bpkp">
                                <option value="">Silahkan Pilih</option>
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

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="panjang_data" class="form-label">Rincian</label>
                        <select name="panjang_data" class="form-control select_semester_bpkp" id="panjang_data_semester_bpkp">
                            <option value="">Silahkan Pilih</option>
                            <option value="4">Jenis</option>
                            <option value="6">Objek</option>
                            <option value="8">RincianObjek</option>
                            <option value="12">Sub Rincian Objek</option>
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