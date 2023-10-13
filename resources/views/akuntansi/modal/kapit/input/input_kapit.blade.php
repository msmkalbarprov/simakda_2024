<div id="modal_input_kapit" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" id="status_input" style="border:0;width: 200px;" readonly="true;" hidden />
               <h5 class="modal-title"><label  >Input Kapitalisasi</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <label for="kd_rek6input" class="form-label">Rekening</label>
                    <select class="form-control select_tambah_kapit @error('kd_rek6input') is-invalid @enderror"
                        style=" width: 100%;" id="kd_rek6input" name="kd_rek6input">
                        <option value="" disabled selected>Silahkan Pilih</option>
                    </select>
                    @error('kd_rek6input')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="anggaran_input" class="form-label">Anggaran</label>
                    <input class="form-control" type="text" id="anggaran_input"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" />
                </div>
                <div class="col-md-6">
                    <label for="kapit_input" class="form-label">kapitalisasi</label>
                    <input class="form-control" type="text" id="kapit_input"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" />
                </div>
                <div class="col-md-6">
                    <label for="trans_input" class="form-label">Anggaran</label>
                    <input class="form-control" type="text" id="trans_input"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" />
                </div>
                <div class="col-md-12">
                    <label for="jenis_input" class="form-label">Jenis</label>
                    <select class="form-control select_tambah_kapit "
                        style=" width: 100%;" id="jenis_input" name="jenis_input">
                        <option value=""> Pilih Jenis</option>
                                <option value="Y"> Y</option>
                                <option value="N"> N</option>
                                <option value="X"> X</option>
                    </select>
                </div>
                <div class="col-md-12" align="center">
                    <button id="simpan" class="btn btn-md btn-primary" onclick="javascript:simpan_tr();">Simpan</button>
                    <button type="button" class="btn btn-dark btn-md" data-bs-dismiss="modal">Keluar</button>
                </div>
            </div>
        </div>
    </div>
</div>