{{-- modal cetak PERDA --}}


<div id="modal_cetak_ped" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                <div class="mb-3 row">
                    
                    {{-- PERIODE --}}
                        

                        <div id="baris_periode1_ped" class="col-md-3">
                            <label for="periode1" class="form-label">Periode</label>
                            <input type="date" id="tanggal1_ped" name="tanggal1" class="form-control">
                        </div>
                        <div id="baris_periode2_ped" class="col-md-3">
                            <label for="periode2" class="form-label">&nbsp;</label>
                            <input type="date" id="tanggal2_ped" name="tanggal2_ped" class="form-control">
                            
                        </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="ttd_bud" class="form-label">Penandatangan</label>
                        <select class="form-control select_ped @error('rek6') is-invalid @enderror"
                            style=" width: 100%;" id="ttd_bud" name="ttd_bud">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('ttd_bud')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jns_anggaran" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran" class="form-control select_ped" id="jns_anggaran">
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