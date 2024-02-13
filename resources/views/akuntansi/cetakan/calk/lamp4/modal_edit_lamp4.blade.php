{{-- modal cetak PERDA --}}


<div id="modal_edit_lamp4" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"><label  >Edit Uraian Lamp 4</label></h5>
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
                <td width="30%">Uraian</td>
                <td width="1%">:</td>
                <td><textarea class="form-control" style="width: 100%" id="uraian" name="uraian"></textarea></td>  
            </tr>

            <tr>
                <td width="30%">Lokasi</td>
                <td width="1%">:</td>
                <td>
                    <select class="form-control select_ @error('lokasi') is-invalid @enderror"
                            style=" width: 100%;" id="lokasi" name="lokasi">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                </td>
            </tr>
            <tr>
                <td width="30%">Alamat</td>
                <td width="1%">:</td>
                <td><textarea class="form-control" style="width: 100%" id="alamat" name="alamat"></textarea></td>  
            </tr>
            <tr>
                <td>Tahun </td>
                 <td></td>
                <td>
                  <?php $thang =  date("Y");
                        $thang_maks = $thang + 20 ;
                        $thang_min = $thang - 50 ;
                        echo '<select id="tahun" class="form-control select_" name="tahun" style=" width: 100%;">';
                        echo "<option value=''> Pilih Tahun</option>";
                        for ($th=$thang_min ; $th<=$thang_maks ; $th++)
                        {
                            echo "<option value=$th>$th</option>";
                        }
                        echo '</select>';
                    ?>                                                                    
                </td>
               
            </tr>
            <tr>
                <td width="30%">Saldo Awal</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="saldo_awal"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"/></td> 
                    </div>
                </td>  
            </tr>
            <tr>
                <td width="30%">Berkurang</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="berkurang"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"/></td> 
                    </div>
                </td>  
            </tr>
            <tr>
                <td width="30%">Bertambah</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="bertambah"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"/></td> 
                    </div>
                </td>  
            </tr>
            <tr>
                <td width="30%">Pengadaan</td>
                <td width="1%">:</td>
                <td>
                    <div class="col-md-10">
                        <input class="form-control" type="text" id="tahun_n"  style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"/></td> 
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