{{-- modal cetak PERDA --}}


<div id="modal_edit_lamp3" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"><label  >Edit Uraian Lamp 3</label></h5>
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
                <td width="30%">Kode Sub Kegiatan</td>
                <td width="1%">:</td>
                <td>
                    <select class="form-control select_ @error('sub_kegiatan') is-invalid @enderror"
                            style=" width: 100%;" id="sub_kegiatan" name="sub_kegiatan">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('sub_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">Nilai Kontrak</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="nilai_kon"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"/></td> 
                    </div>
                </td>  
            </tr>
            <tr>
                <td width="30%">Pelaksana</td>
                <td width="1%">:</td>
                <td><textarea class="form-control" style="width: 100%" id="pelaksana" name="pelaksana"></textarea></td>  
            </tr>
            <tr>
                <td width="30%">Nilai Jaminan Pemeliharaan</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="nilai_jam"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"/></td> 
                    </div>
                </td>  
            </tr>
            <tr>
                <td width="30%">TanggaL Awal</td>
                <td width="1%">:</td>
                <td><input type="date" id="masa_awal" name="masa_awal" class="form-control" ></td>  
            </tr>
            <tr>
                <td width="30%">TanggaL Akhir</td>
                <td width="1%">:</td>
                <td><input type="date" id="masa_akhir" name="masa_akhir" class="form-control"></td>  
            </tr>
            <tr>
                <td width="30%">Nama Penerbit Jaminan Pemeliharaan  </td>
                <td width="1%">:</td>
                <td><textarea class="form-control" style="width: 100%" id="nm_penerbit" name="nm_penerbit"></textarea></td>  
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