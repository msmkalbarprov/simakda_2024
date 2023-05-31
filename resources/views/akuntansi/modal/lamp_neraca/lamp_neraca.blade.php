{{-- modal cetak PERDA --}}


<div id="modal_cetak_lamp_neraca" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">SKPD</label>
                        <select class="form-control select_lamp_neraca @error('rek6') is-invalid @enderror"
                            style=" width: 100%;" id="kd_skpd" name="kd_skpd">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('kd_skpd')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="rekobjek" class="form-label">Rekening</label>
                        <select class="form-control select_lamp_neraca @error('rekobjek') is-invalid @enderror"
                            style=" width: 100%;" id="rekobjek" name="rekobjek">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('rekobjek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="cetakan" class="form-label">Jenis Cetakan</label>
                        <select name="cetakan" class="form-control select_lamp_neraca" id="cetakan">
                            <option value="1">Cetak Biasa</option>
                            <option value="2">Cetak Dengan No Lampiran</option>
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