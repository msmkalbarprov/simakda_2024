{{-- modal cetak PERDA --}}


<div id="modal_input_lamp_neraca" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"><label  >Input Lampiran Neraca</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table align="center" style="width:100%;" border="0">
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
                        <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <input id="dn" name="dn" style="width: 130px;" >&nbsp;&nbsp;&nbsp; 
                            <input id="nmskpd" name="nmskpd"  readonly="true" style="width:450px; border: 0;"/> 
                        </td> 
                    </tr> 
     
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nomor Lamp.</td>
                        <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <input id="nomor" name="nomor" style="width: 150px;" > 
                    </tr> 

                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rek. Kelompok</td>
                        <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <input id="rek3" name="rek3" style="width: 190px;" > &nbsp;&nbsp;&nbsp; 
                            <input id="nm_rek3" name="nm_rek3"  readonly="true" style="width:300px; border: 0;"/> 
                        </td> 
                    </tr> 
     
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
                        <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <input id="rek5" name="rek5" style="width: 190px;" > &nbsp;&nbsp;&nbsp; 
                            <input id="nm_rek5" name="nm_rek5"  readonly="true" style="width:550px; border: 0;"/> 
                        </td> 
                    </tr>

                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                    </tr>

                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening Rinci</td>
                        <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > 
                            <input id="rek6" name="rek6" style="width: 190px;" > &nbsp;&nbsp;&nbsp; 
                            <input id="nm_rek6" name="nm_rek6"  readonly="true" style="width:550px; border: 0;"/> 
                        </td> 
                    </tr>

                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td id = "rek_subrinci1" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Rekening Sub Rinci</td>
                        <td id = "rek_subrinci0" colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" hidden = "true" > 
                            <input id="rek7" name="rek7" style="width: 190px;" > &nbsp;&nbsp;&nbsp; 
                            <input id="nm_rek7" name="nm_rek7"  readonly="true" style="width:550px; border: 0;"/> 
                        </td> 
                    </tr>
     
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Status Aset
                        </td>
                        <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" >
                            <select name="status_aset" id="status_aset" class="easyui-combobox" style=" width:140px;"> 
                                <option value=""> Pilih Status Aset</option>
                                <option value="1"> Lama</option>
                                <option value="2"> Pengadaan</option>   
                        </td>
                    </tr>

                    <tr style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;">
                        <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;" colspan="5">
                            <div>
                                <table>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "status_asuransi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jenis Beban Sewa</td>
                                        <td id = "status_asuransi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <select name="asuransi" id="asuransi" class="easyui-combobox" style=" width:180px;">
                                                <option value=""> Pilih Jenis Beban Sewa </option>     
                                                <option value="asuransi"> Asuransi</option>
                                                <option value="sewa"> Sewa</option>
                                        </td>
                                        <td id = "tahun_oleh1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            Tahun Perolehan
                                        </td>
                                        <td id = "tahun_oleh0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"> 
                                            <?php $thang =  date("Y");
                                            $thang_maks = $thang + 1 ;
                                            $thang_min = $thang - 5 ;
                                            echo '<select id="tahun" class="easyui-combobox" name="tahun" style="width:140px;">';
                                            echo "<option value=''> Pilih Tahun</option>";
                                            for ($th=$thang_min ; $th<=$thang_maks ; $th++)
                                            {
                                                echo "<option value=$th>$th</option>";
                                            }
                                            echo '</select>';?>                                                                    
                                        </td>
                                        <td id = "bulan_oleh1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            Bulan Perolehan
                                        </td>
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
                                        </td>
                                        <td id = "merk1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Merk/Type</td>
                                        <td id = "merk0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="merk" style="width: 140px;" /></td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "masa1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Masa Manfaat</td>
                                        <td id = "masa0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="masa"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);" /></td>
                                        <td id = "tmasa1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jenis Aset</td>
                                        <td id = "tmasa0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="tmasa"  style="width: 140px;text-align: right;"/></td>
                                        <td id = "no_polis1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Perjanjian</td>
                                        <td id = "no_polis0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="no_polis"/></td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "nama_perusahaan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Nama Perusahaan</td>
                                        <td id = "nama_perusahaan0"style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="nama_perusahaan" style="width: 140px;" /></td>
                                        <td id = "jenis_aset1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jenis Aset</td>
                                        <td id = "jenis_aset0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="jenis_aset"/></td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "tgl_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Tgl Awal Perjanjian</td>
                                        <td id = "tgl_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="tgl_awal"/></td>
                                        <td id = "tgl_akhir1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Tgl Akhir Perjanjian</td>
                                        <td id = "tgl_akhir0"style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="tgl_akhir" style="width: 140px;" /></td>
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "jam1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Waktu Perjanjian</td>
                                        <td id = "jam0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="jam" style="width: 140px;" /></td>   
                                        <td id = "realisasi_janji1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Realisasi Perjanjian</td>
                                        <td id = "realisasi_janji0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="realisasi_janji"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td> 
                                    </tr>
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "no_polisi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Polisi</td>
                                        <td id = "no_polisi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="no_polisi"/></td>
                                        <td id = "fungsi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Fungsi</td>
                                        <td id = "fungsi0"style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="fungsi" style="width: 140px;" /></td>
                                    </tr>

                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "hukum1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Dasar Hukum
                                        </td>
                                        <td id = "hukum0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="hukum"/>
                                        </td>
                                        <td id = "lokasi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Lokasi</td>
                                        <td id = "lokasi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input id="lokasi" name="lokasi" style="width: 190px;" >
                                                    <!-- <select name="lokasi" id="lokasi" class="easyui-combobox" style=" width:200px;">
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
                                                    <option value="Lain-lain"> Lain-lain</option> -->
                                        </td>
                                        <td id = "sekolah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Sekolah
                                        </td>
                                        <td id = "sekolah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input id="sekolah" name="sekolah" style="width: 190px;" >
                                        </td>
                                    </tr>
                                    
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "alamat1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">ALamat</td>
                                        <td id = "alamat0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="alamat" style="width: 140px;" /></td>   
                                        <td id = "sert1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Sertifikat</td>
                                        <td id = "sert0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="sert" style="width: 140px;" />
                                        </td>   
                                    </tr>

                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "jumlah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jumlah</td>
                                        <td id = "jumlah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="number" id="jumlah"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);" />
                                        </td>   
                                        <td id = "luas1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Luas</td>
                                        <td id = "luas0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="luas"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);replaceChars(document.nilai.a.value);" />
                                        </td>
                                    </tr>

                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "harga_satuan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Harga Satuan</td>
                                        <td id = "harga_satuan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="harga_satuan"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_saldo_awal();" />
                                        </td>   
                                        <td id = "satuan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Satuan</td>
                                        <td id = "satuan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="satuan" style="width: 140px;" />
                                        </td>
                                    </tr>

                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "rincian_bebas1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Rincian Beban</td>
                                        <td id = "rincian_bebas0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="rincian_bebas" style="width: 140px;" />
                                        </td>
                                        <td id = "piutang_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Awal</td>
                                        <td id = "piutang_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="piutang_awal"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                        </td>
                                    </tr>
                                                
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "piutang_koreksi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Koreksi</td>
                                        <td id = "piutang_koreksi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="piutang_koreksi"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_piutang_koreksi();"/>
                                        </td>  
                                        <td id = "piutang_sudah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Setelah Koreksi</td>
                                        <td id = "piutang_sudah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="piutang_sudah"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/>
                                        </td>
                                        <td id = "milik1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kepemilikan (%)</td>
                                        <td id = "milik0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="milik"  style="width: 140px;text-align: right;" />
                                        </td>
                                    </tr>

                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "harga_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jumlah x Harga Satuan</td>
                                        <td id = "harga_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="harga_awal" style="width: 140px;text-align: right; background-color:yellow;" readonly="true;"  />
                                        </td>
                                    </tr>
                                    
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "investasi_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Investasi Awal</td>
                                        <td id = "investasi_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="investasi_awal"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                        </td>
                                        <td id = "sal_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Saldo Awal</td>
                                        <td id = "sal_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="sal_awal" style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"  />
                                        </td>
                                    </tr>
                                                
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "kurang1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Berkurang</td>
                                        <td id = "kurang0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="kurang"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                        </td>
                                        <td id = "tambah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Bertambah</td>
                                        <td id = "tambah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="bertambah"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                        </td>
                                    </tr>                                                                                                                       
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "tahun_n1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Dana/Pengadaan Tahun </td>
                                        <td id = "tahun_n0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="tahun_n"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                            <a id ="hitung_asuransi1" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:hitung_asuransi();">Hitung Beban Dibayar Dimuka</a>
                                            <a id ="hitung_pendapatan" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:hitung_pendapatan();">Hitung Pendapatan Dibayar Dimuka</a>
                                        </td>
                                        <td id = "akhir1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Saldo Akhir</td>
                                        <td id = "akhir0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="text" id="akhir" style="width: 140px;" onkeypress="return(currencyFormat(this,',','.',event))"  />
                                        </td>
                                    </tr>
                                                
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "sisa_umur1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Sisa Masa Manfaat</td>
                                        <td id = "sisa_umur0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="sisa_umur"  style="width: 140px;text-align: right;" onkeyup="formatangka(this)" />
                                        </td>
                                        <td id = "akum_penyu1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Akum. Penyusutan Lama</td>
                                        <td id = "akum_penyu0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="akum_penyu"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                        </td>
                                        <td id = "akum_penyub1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Akum. Penyusutan Baru</td>
                                        <td id = "akum_penyub0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="akum_penyub"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                        </td>
                                    </tr>   
                                                
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "korplus1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Koreksi Tambah</td>
                                        <td id = "korplus0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="korplus"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                        </td>
                                        <td id = "kormin1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Koreksi Kurang</td>
                                        <td id = "kormin0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="kormin"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                        </td>
                                    </tr>   
                                                
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "kondisi_b1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi B </td>
                                        <td id = "kondisi_b0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="number" id="kondisi_b"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);"/>
                                        </td>   
                                        <td id = "kondisi_rr1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi RR</td>
                                        <td id = "kondisi_rr0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="number" id="kondisi_rr"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);" />
                                        </td>
                                    </tr>
                                    
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "kondisi_rb1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi RB</td>
                                        <td id = "kondisi_rb0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="number" id="kondisi_rb"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);" />
                                        </td>
                                    </tr>
                                    
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "kondisi_x1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi X</td>
                                        <td id = "kondisi_x0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="number" id="kondisi_x"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);" />
                                        </td>   
                                        <td colspan ="2" id = "kondisi_x2" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">* Kondisi X merupakan kondisi barang yang telah dihapuskan/habis
                                        </td>
                                    </tr>
                                                
                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "nil_kurang_excomp1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Nilai Pengurangan Excomp</td>
                                        <td id = "nil_kurang_excomp0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <input type="numeric" id="nil_kurang_excomp"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="formatangka(this);" />
                                        </td>
                                    </tr>

                                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                        <td id = "keterangan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Keterangan</td>
                                        <td colspan ="3" id = "keterangan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">
                                            <textarea name="keterangan" id="keterangan" cols="100" rows="3" ></textarea>
                                        </td>
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
                    <tr>
                        <td colspan="5" align="center">
                            <button id="simpan" class="btn btn-md btn-primary" onclick="javascript:simpan_pengesahan();">Simpan</button>
                            <button type="button" class="btn btn-dark btn-md" data-bs-dismiss="modal">Keluar</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>