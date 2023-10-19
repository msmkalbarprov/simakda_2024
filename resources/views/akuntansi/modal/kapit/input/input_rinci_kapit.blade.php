<div id="modal_input_rinci_kapit" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" id="status_input_rinci" style="border:0;width: 200px;" readonly="true;" hidden />
                <h5 class="modal-title"><label  >Input Kapitalisasi Rinci</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input class="form-control" type="text" id="jikd_rek6" style="border:0;width: 200px;" readonly="true;"  /></td>
                <table border='1' style="font-size:11px"  >
                    <tr>
                        <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                        <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true;"/></td>
                        <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;&nbsp;</td>
                        <td style="border-bottom: double 1px red;border-top: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    </tr> 
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                       <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
                    </tr>  

                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                       <td colspan="5" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" hidden = "true"> <input id="rek5x" name="rek5x" > 
                    </tr> 
             
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
                        <td style="border-bottom:hidden;border-spacing: 3px;" > 
                            <input class="form-control" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $data_skpd->kd_skpd }}" style="width: 200px;" >
                        </td> 
                    </tr> 
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nama SKPD</td>
                        <td style="border-bottom:hidden;border-spacing: 3px;" > 
                            <input class="form-control" id="nm_skpd" name="nm_skpd"  required readonly
                                value="{{ $data_skpd->nm_skpd }}" style="width:450px; border: 0;"/> 
                        </td> 
                    </tr> 
     
                    <tr style="border-bottom:hidden;border-spacing: 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nomor Lamp.</td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <input class="form-control" id="nomor" name="nomor" required readonly
                                style="width: 200px;" >&nbsp;&nbsp;&nbsp; 
                        </td>
                    </tr> 

                    <tr style="border-bottom:hidden;border-spacing: 3px;border-right-style:hidden;" >
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Total Trans.</td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <input  id="trans_tot" name="trans_tot" required
                                class="form-control" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" readonly>&nbsp;&nbsp;&nbsp; 
                        </td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nilai Kapitalisasi</td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <input  id="kapit_tot" name="kapit_tot" required
                                class="form-control" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">&nbsp;&nbsp;&nbsp; 
                        </td>
                    </tr> 

                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rek. Kelompok</td>
                        <td  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <select class="form-control select_tambah_rinci_kapit @error('rek3') is-invalid @enderror" style=" width: 150px;" id="rek3" name="rek3">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('rek3')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr> 
             
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden; display: none;">
                       <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
                         <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <input id="rek5" name="rek5" style="width: 190px;" type="hidden" > 
                       &nbsp;&nbsp;&nbsp; <input id="nm_rek5" name="nm_rek5"  readonly="true" style="width:300px; border: 0;" type="hidden" /> </td> 
                    </tr>
             
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening Rinci</td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <select class="form-control select_tambah_rinci_kapit @error('rek6') is-invalid @enderror" style=" width: 150px;" id="rek6" name="rek6">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('rek6')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </td> 
                    </tr>
             

                    <tr style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;">
             
                        <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;" colspan="5">
                            <div>
                                <table>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "tahun_oleh1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Tahun Perolehan</td>
                                        <td id = "tahun_oleh0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"> 
                                            <?php $thang =  date("Y");
                                                $thang_maks = $thang + 3 ;
                                                $thang_min = $thang - 15 ;
                                                echo '<select id="tahun" class="easyui-combobox" name="tahun" style="width:140px;">';
                                                echo "<option value=".$thang.">".$thang."</option>";
                                                for ($th=$thang_min ; $th<=$thang_maks ; $th++){
                                                    echo "<option value=$th>$th</option>";
                                                }
                                                    echo '</select>';
                                            ?>
                                        </td>
                                        <td id = "bulan_oleh1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Bulan Perolehan</td>
                                        <td id = "bulan_oleh0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <select name="bulan" id="bulan" class="easyui-combobox" style=" width:140px;">
                                                <option value=""> Pilih Bulan </option>     
                                                <option value="1"> Januari</option>
                                                <option value="2"> Februari</option>
                                                <option value="3"> Maret</option>
                                                <option value="4"> April</option>
                                                <option value="5"> Mei</option>
                                                <option value="6"> Juni</option>
                                                <option value="7"> Juli</option>
                                                <option value="8"> Agustus</option>
                                                <option value="9"> September</option>
                                                <option value="10"> Oktober</option>
                                                <option value="11"> November</option>
                                                <option value="12"> Desember</option>
                                            </select>
                                        </td>
                                        <td id = "merk1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Merk/Type</td>
                                        <td id = "merk0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="merk" style="width: 140px;" /></td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "no_polis1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Nama & No. Polis Asuransi</td>
                                        <td id = "no_polis0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="no_polis"/></td>
                                        <td id = "piutang_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jenis Aset</td>
                                        <td id = "piutang_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="piutang_awal"  style="width: 140px;text-align: right;"/></td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "no_polisi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Polisi</td>
                                        <td id = "no_polisi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="no_polisi"/></td>
                                        <td id = "fungsi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Fungsi</td>
                                        <td id = "fungsi0"style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="fungsi" style="width: 140px;" /></td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "hukum1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Dasar Hukum</td>
                                        <td id = "hukum0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="hukum"/></td>
                                        <td id = "lokasi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Lokasi</td>
                                        <td id = "lokasi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <select name="lokasi" id="lokasi" class="easyui-combobox" style=" width:200px;">
                                                <option value=""> Pilih Lokasi </option>     
                                                <option value="Kota Pontianak"> Kota Pontianak</option>
                                                <option value="Kota Singkawang"> Kota Singkawang</option>
                                                <option value="Kabupaten Mempawah"> Kabupaten Mempawah</option>
                                                <option value="Kabupaten Sanggau"> Kabupaten Sanggau</option>
                                                <option value="Kabupaten Sintang"> Kabupaten Sintang</option>
                                                <option value="Kabupaten Kapuas Hulu"> Kabupaten Kapuas Hulu</option>
                                                <option value="Kabupaten Ketapang"> Kabupaten Ketapang</option>
                                                <option value="Kabupaten Landak"> Kabupaten Landak</option>
                                                <option value="Kabupaten Bengkayang"> Kabupaten Bengkayang</option>
                                                <option value="Kabupaten Sambas"> Kabupaten Sambas</option>
                                                <option value="Kabupaten Sekadau"> Kabupaten Sekadau</option>
                                                <option value="Kabupaten Melawi"> Kabupaten Melawi</option>
                                                <option value="Kabupaten Kayong Utara"> Kabupaten Kayong Utara</option>
                                                <option value="Kabupaten Kubu Raya"> Kabupaten Kubu Raya</option>
                                                <option value="Lain-lain"> Lain-lain</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "alamat1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">ALamat</td>
                                        <td id = "alamat0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="alamat" style="width: 140px;" /></td>   
                                        <td id = "sert1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Sertifikat</td>
                                        <td id = "sert0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="sert" style="width: 140px;" /></td>   
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "jumlah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jumlah</td>
                                        <td id = "jumlah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="jumlah"  class="form-control" style="width: 200px;text-align: right;" onkeyup="formatangka(this);" onkeyup="javascript:hitung_harga_satuan();"   /></td>   
                                        <td id = "luas1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Luas</td>
                                        <td id = "luas0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="luas"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);replaceChars(document.nilai.a.value);" /></td>   
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "harga_satuan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Harga Satuan</td>
                                        <td id = "harga_satuan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="harga_satuan"  class="form-control" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"onkeyup="javascript:hitung_saldo_awal();"/></td>   
                                        <td id = "satuan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Satuan</td>
                                        <td id = "satuan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="satuan" class="form-control" style="width: 200px;" /></td>   
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "rincian_bebas1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Rincian Beban</td>
                                        <td id = "rincian_bebas0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="rincian_bebas" style="width: 140px;" /></td>   
                                    </tr>
                                    
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "piutang_koreksi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Koreksi</td>
                                        <td id = "piutang_koreksi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="piutang_koreksi"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_piutang_koreksi();"/> </td>  
                                        <td id = "piutang_sudah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Setelah Koreksi</td>
                                        <td id = "piutang_sudah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="piutang_sudah"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                                        <td id = "milik1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kepemilikan (%)</td>
                                        <td id = "milik0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="milik"  style="width: 140px;text-align: right;" /></td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "harga_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jumlah x Harga Satuan</td>
                                        <td id = "harga_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="harga_awal" class="form-control" style="width: 200px;text-align: right; background-color:yellow;" readonly="true;"  /></td>   
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "investasi_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Investasi Awal</td>
                                        <td id = "investasi_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="investasi_awal"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                                        <td id = "sal_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Saldo Awal</td>
                                        <td id = "sal_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="sal_awal" style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"  /></td>   
                                    </tr>
                                    
                                    
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "kurang1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Berkurang</td>
                                        <td id = "kurang0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="kurang"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                                        <td id = "tambah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Bertambah</td>
                                        <td id = "tambah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="tambah"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                                        </tr>                                                                                                                       
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "tahun_n1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Dana/Pengadaan Tahun </td>
                                        <td id = "tahun_n0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="tahun_n"  class="form-control" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" /></td>
                                        <td id = "akhir1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Saldo Akhir</td>
                                        <td id = "akhir0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="akhir" style="width: 140px;" onkeypress="return(currencyFormat(this,',','.',event))"  /></td>   
                                        </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "kondisi_b1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi B </td>
                                        <td id = "kondisi_b0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="kondisi_b" style="width: 140px;" /></td>   
                                        <td id = "kondisi_rr1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi RR</td>
                                        <td id = "kondisi_rr0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="kondisi_rr" style="width: 140px;" /></td>   
                                        </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "kondisi_rb1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi RB</td>
                                        <td id = "kondisi_rb0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="kondisi_rb" style="width: 140px;" /></td>   
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "keterangan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Keterangan</td>
                                        <td colspan ="3" id = "keterangan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><textarea name="keterangan" id="keterangan" cols="30" rows="1" ></textarea></td>   
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden>
                                        <td id = "sat_kap0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >Satuan+Kap.</td>
                                        <td id = "sat_kap1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" ><input type="text" id="sat_kap" class="form-control" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" /></td>   
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden>
                                        <td id = "nil_kap0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >Nilai+Kap.</td>
                                        <td id = "nil_kap1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" ><input type="text" id="nil_kap" class="form-control" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" /></td>   
                                    </tr>
                                </table> 
                            </div>
                        </td>                
                    </tr>
             
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                       <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
                    </tr>
                </table>
                <div class="col-md-12" align="center">
                    <button id="simpan" class="btn btn-md btn-primary" onclick="javascript:hsimpan();">Simpan</button>
                    <button type="button" class="btn btn-dark btn-md" data-bs-dismiss="modal">Keluar</button>
                </div>
            </div>
        </div>
    </div>
</div>