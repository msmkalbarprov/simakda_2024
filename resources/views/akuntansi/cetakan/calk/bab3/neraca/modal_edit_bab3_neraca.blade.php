{{-- modal cetak PERDA --}}


<div id="modal_edit_bab3_neraca" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"><label  >Edit Uraian Bab III Neraca </label></h5>
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
                <td width="30%">Kode Rekening</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_rek" name="kd_rek" class="form-control" readonly></td>  
            </tr>
            <tr>
                <td width="30%">Nama Rekening</td>
                <td width="1%">:</td>
                <td><input type="text" id="nm_rek" name="nm_rek" class="form-control" readonly></td>  
            </tr>
            <tr>
                <td width="30%">Uraian</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <textarea class="form-control" style="width: 100%" id="uraian" name="uraian"></textarea>
                    </div>
                </td>  
            </tr>
            <tr>
                <td width="30%">Nilai</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="nilai"  style="width: 200px;text-align: right;"   />
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