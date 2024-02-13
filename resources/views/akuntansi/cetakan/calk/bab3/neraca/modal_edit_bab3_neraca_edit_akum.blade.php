{{-- modal cetak PERDA --}}


<div id="modal_edit_bab3_neraca_edit_akum" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" id="status_input" style="border:0;width: 200px;" readonly="true;" hidden /></td>
                <h5 class="modal-title"><label  >Edit Uraian Bab III Neraca Akumulasi</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <table align="center" style="width:100%;" border="0">
                
            <tr>
                <td width="30%">Kode SKPD</td>
                <td width="1%">:</td>
                <td>
                    <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly>
                </td>
            </tr> 

            <tr>
                <td width="30%">Nama SKPD</td>
                <td width="1%">:</td>
                <td>
                    <input class="form-control" type="text" id="nm_skpd" name="nm_skpd" required readonly>
                </td>
            </tr>
            <tr>
                <td width="30%">Koreksi</td>
                <td width="1%">:</td>
                <td> 
                    <select class="form-control select_ @error('rek') is-invalid @enderror" style=" width: 150px;" id="rek" name="rek">
                        <option value="" disabled selected>Silahkan Pilih Koreksi</option>
                    </select>
                    @error('rek')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
            </tr> 
            <tr>
                <td width="30%">Rincian Koreksi</td>
                <td width="1%">:</td>
                <td> 
                    <select class="form-control select_ @error('rek2') is-invalid @enderror" style=" width: 150px;" id="rek2" name="rek2">
                        <option value="" disabled selected>Silahkan Pilih Rincian Koreksi</option>
                    </select>
                    @error('rek2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
            </tr> 
            <tr>
                <td width="30%">Keterangan</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <textarea class="form-control" style="width: 100%" id="ket" name="ket"></textarea>
                    </div>
                </td>  
            </tr>
                <td width="30%">Nilai</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="nilai"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" />
                    </div>
                </td>  
            </tr>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="5" align="center">
                    <button id="simpan" class="btn btn-md btn-primary" onclick="javascript:simpan();">Simpan</button>
                    <button type="button" class="btn btn-dark btn-md" data-bs-dismiss="modal">Keluar</button>
                </td>                
            </tr>
        </table>
        </div>
        </div>
    </div>
</div>