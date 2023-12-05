{{-- modal cetak PERDA --}}


<div id="modal_edit_bab2_hambatan" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"><label  >Edit Hambatan</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <table align="center" style="width:100%;" border="0">
                <input class="form-control" type="text" id="kode" name="kode" required readonly hidden>
            <tr>
                <td width="30%">Kode Program</td>
                <td width="1%">:</td>
                <td>
                    <input class="form-control" type="text" id="kode2" name="kode2" required readonly>
                </td>
            </tr> 
            <tr>
                <td width="30%">Nama Program</td>
                <td width="1%">:</td>
                <td><input type="text" id="bidang" name="bidang" class="form-control" readonly></td>  
            </tr>
            <tr>
                <td width="30%">Anggaran</td>
                <td width="1%">:</td>
                <td>
                    <input type="text" class="form-control" name="anggaran" id="anggaran"
                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" style="text-align: right" readonly>
                </td>  
            </tr>
            <tr>
                <td width="30%">Realisasi</td>
                <td width="1%">:</td>
                <td><input type="text" class="form-control" name="realisasi" id="realisasi"
                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" style="text-align: right" readonly></td>  
            </tr>
            <tr>
                <td width="30%">Persen</td>
                <td width="1%">:</td>
                <td><input type="text" class="form-control" name="persen" id="persen"
                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" style="text-align: right" readonly></td>  
            </tr>
            <tr>
                <td width="30%">Hambatan / Kendala</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <textarea class="form-control" style="width: 100%" id="hambatan" name="hambatan"></textarea>
                    </div>
                </td>  
            </tr>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="5" align="center">
                    <button id="simpan" class="btn btn-md btn-primary" onclick="javascript:simpan_hambatan();">Simpan</button>
                    <button type="button" class="btn btn-dark btn-md" data-bs-dismiss="modal">Keluar</button>
                </td>                
            </tr>
        </table>
        </div>
        </div>
    </div>
</div>