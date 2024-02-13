{{-- modal cetak PERDA --}}


<div id="modal_edit_bab3_neraca_edit_tambah" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" id="status_input" style="border:0;width: 200px;" readonly="true;" hidden /></td>
                <h5 class="modal-title"><label  >Edit Uraian Bab III Neraca</label></h5>
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
            @if($kd_rek=="131")
                <tr>
                    <td width="30%">Kode</td>
                    <td width="1%">:</td>
                    <td><select class="form-control select_ " style=" width: 100%;" name="kd_rek" id="kd_rek">
                        <option value="">SILAHKAN PILIH</option>
                        <option value="">-- MUTASI BERTAMBAH --</option>
                        <option value="13122">13122  - Hibah</option>
                        <option value="13123">13123  - Beban</option>
                        <option value="13124">13124  - Mutasi Antar SKPD</option>
                        <option value="13125">13125  - Reklas</option>
                        <option value="13126">13126  - Revaluasi</option>
                        <option value="13127">13127  - Koreksi</option>
                        <option value="13128">13128  - Mutasi Nomenklatur</option>
                        <option value="">-- MUTASI BERKURANG --</option>
                        <option value="13131">13131 - Hibah</option>
                        <option value="13132">13132 - Penghapusan</option>
                        <option value="13133">13133 - Mutasi Antar SKPD</option>
                        <option value="13134">13134 - Reklas</option>
                        <option value="13135">13135 - Revaluasi</option>
                        <option value="13136">13136 - Koreksi</option>
                        <option value="13137">13137 - Rusak Berat</option>
                        <option value="13138">13138 - Beban</option>
                        <option value="13139">13139 - Mutasi Nomenklatur</option>
                     </select></td>  
                </tr>
            @elseif($kd_rek=="132")
                <tr>
                    <td width="30%">Kode</td>
                    <td width="1%">:</td>
                    <td><select class="form-control select_ " style=" width: 100%;" name="kd_rek" id="kd_rek">
                        <option value="">SILAHKAN PILIH</option>
                        <option value="">-- MUTASI BERTAMBAH --</option>
                        <option value="13222">13222  - Hibah</option>
                        <option value="13223">13223  - Beban</option>
                        <option value="13224">13224  - Mutasi Antar SKPD</option>
                        <option value="13225">13225  - Reklas</option>
                        <option value="13226">13226  - Revaluasi</option>
                        <option value="13227">13227  - Koreksi</option>
                        <option value="13228">13228  - Pengadaan dari Belanja Tidak Terduga</option>
                        <option value="13229">13229  - Mutasi Nomenklatur</option>
                        <option value="">-- MUTASI BERKURANG --</option>
                        <option value="13231">13231 - Hibah</option>
                        <option value="13232">13232 - Penghapusan</option>
                        <option value="13233">13233 - Mutasi Antar SKPD</option>
                        <option value="13234">13234 - Reklas</option>
                        <option value="13235">13235 - Revaluasi</option>
                        <option value="13236">13236 - Koreksi</option>
                        <option value="13237">13237 - Rusak Berat</option>
                        <option value="13238">13238 - Beban</option>
                        <option value="13239">13239 - Ekstracomptable</option>
                        <option value="132310">132310 - Mutasi Nomenklatur</option>
                     </select></td>  
                </tr>
            @elseif($kd_rek=="133")
                <tr>
                    <td width="30%">Kode</td>
                    <td width="1%">:</td>
                    <td><select class="form-control select_ " style=" width: 100%;" name="kd_rek" id="kd_rek">
                        <option value="">SILAHKAN PILIH</option>
                        <option value="">-- MUTASI BERTAMBAH --</option>
                        <option value="13322">13322  - Hibah</option>
                        <option value="13323">13323  - Beban</option>
                        <option value="13324">13324  - Mutasi Antar SKPD</option>
                        <option value="13325">13325  - Reklas</option>
                        <option value="13326">13326  - Revaluasi</option>
                        <option value="13327">13327  - Koreksi</option>
                        <option value="13328">13328  - Mutasi Nomenklatur</option>
                        <option value="">-- MUTASI BERKURANG --</option>
                        <option value="13331">13331 - Hibah</option>
                        <option value="13332">13332 - Penghapusan</option>
                        <option value="13333">13333 - Mutasi Antar SKPD</option>
                        <option value="13334">13334 - Reklas</option>
                        <option value="13335">13335 - Revaluasi</option>
                        <option value="13336">13336 - Koreksi</option>
                        <option value="13337">13337 - Rusak Berat</option>
                        <option value="13338">13338 - Beban</option>
                        <option value="13339">13339 - Mutasi Nomenklatur</option>
                     </select></td>  
                </tr>
            @elseif($kd_rek=="134")
                <tr>
                    <td width="30%">Kode</td>
                    <td width="1%">:</td>
                    <td><select class="form-control select_ " style=" width: 100%;" name="kd_rek" id="kd_rek">
                        <option value="">SILAHKAN PILIH</option>
                        <option value="">-- MUTASI BERTAMBAH --</option>
                        <option value="13422">13422  - Hibah</option>
                        <option value="13423">13423  - Beban</option>
                        <option value="13424">13424  - Mutasi Antar SKPD</option>
                        <option value="13425">13425  - Reklas</option>
                        <option value="13426">13426  - Revaluasi</option>
                        <option value="13427">13427  - Koreksi</option>
                        <option value="13428">13428  - Mutasi Nomenklatur</option>
                        <option value="">-- MUTASI BERKURANG --</option>
                        <option value="13431">13431 - Hibah</option>
                        <option value="13432">13432 - Penghapusan</option>
                        <option value="13433">13433 - Mutasi Antar SKPD</option>
                        <option value="13434">13434 - Reklas</option>
                        <option value="13435">13435 - Revaluasi</option>
                        <option value="13436">13436 - Koreksi</option>
                        <option value="13437">13437 - Rusak Berat</option>
                        <option value="13438">13438 - Beban</option>
                        <option value="13439">13439 - Mutasi Nomenklatur</option>
                     </select></td>  
                </tr>
            @elseif($kd_rek=="135")
                <tr>
                    <td width="30%">Kode</td>
                    <td width="1%">:</td>
                    <td><select class="form-control select_ " style=" width: 100%;" name="kd_rek" id="kd_rek">
                        <option value="">SILAHKAN PILIH</option>
                        <option value="">-- MUTASI BERTAMBAH --</option>
                        <option value="13522">13522  - Hibah</option>
                        <option value="13523">13523  - Beban</option>
                        <option value="13524">13524  - Mutasi Antar SKPD</option>
                        <option value="13525">13525  - Reklas</option>
                        <option value="13526">13526  - Revaluasi</option>
                        <option value="13527">13527  - Koreksi</option>
                        <option value="13528">13528  - Mutasi Nomenklatur</option>
                        <option value="">-- MUTASI BERKURANG --</option>
                        <option value="13531">13531 - Hibah</option>
                        <option value="13532">13532 - Penghapusan</option>
                        <option value="13533">13533 - Mutasi Antar SKPD</option>
                        <option value="13534">13534 - Reklas</option>
                        <option value="13535">13535 - Revaluasi</option>
                        <option value="13536">13536 - Koreksi</option>
                        <option value="13537">13537 - Rusak Berat</option>
                        <option value="13538">13538 - Beban</option>
                        <option value="13539">13539 - Mutasi Nomenklatur</option>
                     </select></td>  
                </tr>
            @elseif($kd_rek=="136")
                <tr>
                    <td width="30%">Kode</td>
                    <td width="1%">:</td>
                    <td><select class="form-control select_ " style=" width: 100%;" name="kd_rek" id="kd_rek">
                        <option value="">SILAHKAN PILIH</option>
                        <option value="">-- MUTASI BERTAMBAH --</option>
                        <option value="13622">13622  - Hibah</option>
                        <option value="13623">13623  - Beban</option>
                        <option value="13624">13624  - Mutasi Antar SKPD</option>
                        <option value="13625">13625  - Reklas</option>
                        <option value="13626">13626  - Revaluasi</option>
                        <option value="13627">13627  - Koreksi</option>
                        <option value="13628">13628  - Mutasi Nomenklatur</option>
                        <option value="">-- MUTASI BERKURANG --</option>
                        <option value="13631">13631 - Hibah</option>
                        <option value="13632">13632 - Penghapusan</option>
                        <option value="13633">13633 - Mutasi Antar SKPD</option>
                        <option value="13634">13634 - Reklas</option>
                        <option value="13635">13635 - Revaluasi</option>
                        <option value="13636">13636 - Koreksi</option>
                        <option value="13637">13637 - Rusak Berat</option>
                        <option value="13638">13638 - Beban</option>
                        <option value="13639">13639 - Mutasi Nomenklatur</option>
                     </select></td>  
                </tr>
            @elseif($kd_rek=="153")
                <tr>
                    <td width="30%">Kode</td>
                    <td width="1%">:</td>
                    <td><select class="form-control select_ " style=" width: 100%;" name="kd_rek" id="kd_rek">
                        <option value="">SILAHKAN PILIH</option>
                        <option value="">-- MUTASI BERTAMBAH --</option>
                        <option value="15322">15322  - Hibah</option>
                        <option value="15323">15323  - Beban</option>
                        <option value="15324">15324  - Mutasi Antar SKPD</option>
                        <option value="15325">15325  - Reklas</option>
                        <option value="15326">15326  - Revaluasi</option>
                        <option value="15327">15327  - Koreksi</option>
                        <option value="15328">15328  - Mutasi Nomenklatur</option>
                        <option value="">-- MUTASI BERKURANG --</option>
                        <option value="15331">15331 - Hibah</option>
                        <option value="15332">15332 - Penghapusan</option>
                        <option value="15333">15333 - Mutasi Antar SKPD</option>
                        <option value="15334">15334 - Reklas</option>
                        <option value="15335">15335 - Revaluasi</option>
                        <option value="15336">15336 - Koreksi</option>
                        <option value="15337">15337 - Rusak Berat</option>
                        <option value="15338">15338 - Beban</option>
                        <option value="15339">15339 - Mutasi Nomenklatur</option>
                     </select></td>  
                </tr>
            @elseif($kd_rek=="154")
                <tr>
                    <td width="30%">Kode</td>
                    <td width="1%">:</td>
                    <td><select class="form-control select_ " style=" width: 100%;" name="kd_rek" id="kd_rek">
                        <option value="">SILAHKAN PILIH</option>
                        <option value="">-- MUTASI BERTAMBAH --</option>
                        <option value="15422">15422  - Hibah</option>
                        <option value="15423">15423  - Beban</option>
                        <option value="15424">15424  - Mutasi Antar SKPD</option>
                        <option value="15425">15425  - Reklas</option>
                        <option value="15426">15426  - Revaluasi</option>
                        <option value="15427">15427  - Koreksi</option>
                        <option value="15428">15428  - Mutasi Nomenklatur</option>
                        <option value="">-- MUTASI BERKURANG --</option>
                        <option value="15431">15431 - Hibah</option>
                        <option value="15432">15432 - Penghapusan</option>
                        <option value="15433">15433 - Mutasi Antar SKPD</option>
                        <option value="15434">15434 - Reklas</option>
                        <option value="15435">15435 - Revaluasi</option>
                        <option value="15436">15436 - Koreksi</option>
                        <option value="15437">15437 - Rusak Berat</option>
                        <option value="15438">15438 - Beban</option>
                        <option value="15439">15439 - Mutasi Nomenklatur</option>
                     </select></td>  
                </tr>
            @else
            @endif
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