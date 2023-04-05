{{-- modal cetak PERDA --}}


<div id="modal_j_koreksi" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"><label  >Edit Akses Jurnal Koreksi</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <table align="center" style="width:100%;" border="0">
            <tr>
                <td width="30%">SKPD</td>
                <td width="1%">:</td>
                <td><input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly>
                </td>
            </tr> 
            <tr>
                <td width="30%">USERNAME</td>
                <td width="1%">:</td>
                <td>
                    <input class="form-control" type="text" id="username" name="username" required readonly>
                </td>
            </tr> 

            <tr>
                <td width="30%">NAMA</td>
                <td width="1%">:</td>
                <td>
                    <input class="form-control" type="text" id="nama" name="nama" required readonly>
                </td>
            </tr> 
            <tr>
                <td width="30%">Aksesnya</td>
                <td width="1%">:</td>
                <td><input type="checkbox" id="koreksi" /></td>
            </tr>

            
            <tr>
                <td colspan="5" align="center">
                    <button id="simpan" class="btn btn-md btn-primary" onclick="javascript:simpan_j_koreksi();">Simpan</button>
                    <button type="button" class="btn btn-dark btn-md" data-bs-dismiss="modal">Keluar</button>
                </td>                
            </tr>
        </table>
        </div>
        </div>
    </div>
</div>