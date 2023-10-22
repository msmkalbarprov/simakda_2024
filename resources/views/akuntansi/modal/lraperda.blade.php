{{-- modal cetak PERDA --}}

{{--perda i.4 urusan--}}
<div id="modal_cetak_i4_urusan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">PERDA I.4 URUSAN</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                <div class="mb-3 row">
                    
                    {{-- PERIODE /BULAN --}}
                        

                        <div class="col-md-6">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" class="form-control select_i4_urusan" id="bulan">
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
                        <label for="jns_anggaran" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran" class="form-control select_i4_urusan" id="jns_anggaran">
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
                        <label for="jenis" class="form-label">Jenis</label>
                        <select name="jenis" class="form-control select_i4_urusan" id="jenis">
                            <option value="">Silahkan Pilih</option>
                            <option value="1">SKPD LRA SAP</option>
                            <option value="2">SKPD SPJ</option>
                            <option value="3">GLOBAL</option>
                            <option value="4">RINCI 2</option>
                            <option value="5">SKPD ORGANISASI (KONSOL)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tgl_ttd" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd" name="tgl_ttd" class="form-control">
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

{{--perda1.1--}}
<div id="modal_cetak_i1" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">PERDA I.1</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                <div class="mb-3 row">
                    
                    
                        

                        <div class="col-md-6">
                        <label for="tgl_ttd_i1" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_i1" name="tgl_ttd_i1" class="form-control ">
                    </div>
                        <div class="col-md-6">
                        <label for="jns_anggaran_i1" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_i1" class="form-control select_i1" id="jns_anggaran_i1">
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

{{--perda1.1 Ringkasan--}}
<div id="modal_cetak_i1_ringkasan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">PERDA I.1 RINGKASAN</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                <div class="mb-3 row">
                        <div class="col-md-6">
                        <label for="tgl_ttd_i1_ringkasan" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_i1_ringkasan" name="tgl_ttd_i1_ringkasan" class="form-control ">
                    </div>
                        <div class="col-md-6">
                        <label for="jns_anggaran_i1_ringkasan" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_i1_ringkasan" class="form-control select_i1_ringkasan" id="jns_anggaran_i1_ringkasan">
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
                        <label for="bulan_i1_ringkasan" class="form-label">Bulan</label>
                        <select name="bulan_i1_ringkasan" class="form-control select_i1_ringkasan" id="bulan_i1_ringkasan">
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

{{--perda1.2--}}
<div id="modal_cetak_i2" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">PERDA I.2</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd_i2" class="form-label">Pilih</label><br>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_i2" id="pilihan0"
                                value="keseluruhan">
                            <label class="form-check-label" for="pilihan">Keseluruhan</label>
                        </div>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_i2" id="pilihan1"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_i2" id="pilihan2"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div id="baris_skpd_i2">
                            <label for="kd_skpd_i2" class="form-label">Kode SKPD</label>
                            <select class="form-control select_i2  @error('kd_skpd_i2') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_i2" name="kd_skpd_i2">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_i2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>


                <div class="mb-3 row">
                        <div class="col-md-6">
                        <label for="tgl_ttd_i2" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_i2" name="tgl_ttd_i2" class="form-control ">
                    </div>
                        <div class="col-md-6">
                        <label for="jns_anggaran_i2" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_i2" class="form-control select_i2" id="jns_anggaran_i2">
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
                        <label for="bulan_i2" class="form-label">Bulan</label>
                        <select name="bulan_i2" class="form-control select_i2" id="bulan_i2">
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

{{--perda i.3 rincian--}}
<div id="modal_cetak_i3_rincian" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">I.3 RINCIAN</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd_i3_rincian" class="form-label">Pilih</label><br>
                        
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_i3_rincian" id="pilihan1"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_i3_rincian" id="pilihan2"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div id="baris_skpd_i3_rincian">
                            <label for="kd_skpd_i3_rincian" class="form-label">Kode SKPD</label>
                            <select class="form-control select_i3_rincian  @error('kd_skpd_i3_rincian') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_i3_rincian" name="kd_skpd_i3_rincian">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_i3_rincian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>


                <div class="mb-3 row">
                        <div class="col-md-6">
                        <label for="tgl_ttd_i3_rincian" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_i3_rincian" name="tgl_ttd_i3_rincian" class="form-control ">
                    </div>
                        <div class="col-md-6">
                        <label for="jns_anggaran_i3_rincian" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_i3_rincian" class="form-control select_i3_rincian" id="jns_anggaran_i3_rincian">
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
                        <label for="bulan_i3_rincian" class="form-label">Bulan</label>
                        <select name="bulan_i3_rincian" class="form-control select_i3_rincian" id="bulan_i3_rincian">
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
                        <label for="panjang_data_i3_rincian" class="form-label">Rincian</label>
                        <select name="panjang_data_i3_rincian" class="form-control select_i3_rincian" id="panjang_data_i3_rincian">
                            <option value="">Silahkan Pilih</option>
                            <option value="3">Jenis</option>
                            <option value="5">Objek</option>
                            <option value="7">Rincian Objek</option>
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

{{--perda i.6 Piutang--}}
<div id="modal_cetak_i6_piutang" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">Perda Lamp I.6 Piutang</label></h5>
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

{{--perda i.8 Aset--}}
<div id="modal_cetak_i8_aset_tetap" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">Perda Lamp I.8 Aset Tetap</label></h5>
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

{{--perda D1 KESELARASAN--}}
<div id="modal_cetak_d1" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">PERDA D1 KESELARASAN</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                <div class="mb-3 row">
                        <div class="col-md-6">
                        <label for="tgl_ttd_d1" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_d1" name="tgl_ttd_d1" class="form-control ">
                    </div>
                        <div class="col-md-6">
                        <label for="jns_anggaran_d1" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_d1" class="form-control select_d1" id="jns_anggaran_d1">
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
                        <label for="bulan_d1" class="form-label">Bulan</label>
                        <select name="bulan_d1" class="form-control select_d1" id="bulan_d1">
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

{{--perda D3--}}
<div id="modal_cetak_d3" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">PERDA D3 KESELARASAN</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="tgl_ttd_d3" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_d3" name="tgl_ttd_d3" class="form-control ">
                    </div>
                    <div class="col-md-6">
                        <label for="jns_anggaran_d3" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_d3" class="form-control select_d3" id="jns_anggaran_d3">
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

{{--perda D4--}}
<div id="modal_cetak_d4" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester">PERDA D4 KESELARASAN</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="tgl_ttd_d4" class="form-label">Tanggal TTD</label>
                        <input type="date" id="tgl_ttd_d4" name="tgl_ttd_d4" class="form-control ">
                    </div>
                    <div class="col-md-6">
                        <label for="jns_anggaran_d4" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_d4" class="form-control select_d4" id="jns_anggaran_d4">
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