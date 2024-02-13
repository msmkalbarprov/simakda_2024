{{-- modal cetak PERDA --}}


<div id="modal_edit_lamp2" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"><label  >Edit Uraian Lamp 2</label></h5>
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
                <td width="30%">Jenis</td>
                <td width="1%">:</td>
                <td>
                    <select class="form-control select_ @error('jenis') is-invalid @enderror"
                            style=" width: 100%;" id="jenis" name="jenis">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">Kode</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek" name="kd_rek" class="form-control" readonly></td>  
            </tr>
            <tr>
                <td width="30%">Kode Rinci</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rinci" name="kd_rinci" class="form-control" readonly></td>  
            </tr>
            <tr>
                <td width="30%">Uraian</td>
                <td width="1%">:</td>
                <td>
                    <select class="form-control select_ @error('ket') is-invalid @enderror"
                            style=" width: 100%;" id="ket" name="ket">
                            <option value="" disabled selected>Silahkan Pilih Keterangan</option>
                        </select>
                        @error('ket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">Nilai</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="nilai"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"   /><input type="checkbox" name="minus" id="minus" value="minus"><b>Minus</b></td> 
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