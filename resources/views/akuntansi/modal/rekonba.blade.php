{{-- modal cetak PERDA --}}


<div id="modal_cetak_rekonba" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
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
                        <label for="kd_skpd_rekonba" class="form-label">Pilih</label><br>
                        
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_rekonba" id="pilihan1"
                                value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pilihan_rekonba" id="pilihan2"
                                value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div id="baris_skpd_rekonba">
                            <label for="kd_skpd_rekonba" class="form-label">Kode SKPD</label>
                            <select class="form-control select_rekonba  @error('kd_skpd') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd_rekonba" name="kd_skpd_rekonba">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd_rekonba')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>
                <div class="mb-3 row">
                    
                    {{-- PERIODE --}}
                        

                        <div id="baris_periode1_inflasi" class="col-md-3">
                            <label for="periode1" class="form-label">Periode</label>
                            <input type="date" id="tanggal1_inflasi" name="tanggal1_inflasi" class="form-control">
                        </div>
                        <div id="baris_periode2_inflasi" class="col-md-3">
                            <label for="periode2" class="form-label">&nbsp;</label>
                            <input type="date" id="tanggal2_inflasi" name="tanggal2_inflasi" class="form-control">
                            
                        </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="ttd_kasubbid" class="form-label">Penandatangan Kasubbid</label>
                        <select class="form-control select_rekonba @error('rek6') is-invalid @enderror"
                            style=" width: 100%;" id="ttd_kasubbid" name="ttd_kasubbid">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('ttd_kasubbid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jns_anggaran" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran_rekonba" class="form-control select_rekonba" id="jns_anggaran_rekonba">
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
                        <label for="jenis_rekonba" class="form-label">Jenis Cetakan</label>
                        <select name="jenis_rekonba" class="form-control select_rekonba" id="jenis_rekonba">
                            <option value="">Silahkan Pilih</option>
                            <option value="1">PENGELUARAN</option>
                            <option value="2">PENERIMAAN</option>
                            <option value="3">LRA</option>
                            <option value="4">LO</option>
                            <option value="5">NERACA</option>
                            <option value="6">LPE</option>
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