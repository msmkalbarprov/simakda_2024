{{-- lamp neraca --}}
<div id="modal_cetak_lamp_neraca" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">SKPD</label>
                        <select class="form-control select_lamp_neraca @error('kd_skpd') is-invalid @enderror"
                            style=" width: 100%;" id="kd_skpd" name="kd_skpd">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('kd_skpd')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="rekobjek" class="form-label">Rekening</label>
                        <select class="form-control select_lamp_neraca @error('rekobjek') is-invalid @enderror"
                            style=" width: 100%;" id="rekobjek" name="rekobjek">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('rekobjek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="cetakan" class="form-label">Jenis Cetakan</label>
                        <select name="cetakan" class="form-control select_lamp_neraca" id="cetakan">
                            <option value="1">Cetak Biasa</option>
                            <option value="2">Cetak Dengan No Lampiran</option>
                        </select>
                    </div>
                </div>
                
          

                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--umur piutang--}}
<div id="modal_cetak_umur_piutang" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd_up" class="form-label">SKPD</label>
                        <select class="form-control select_umur_piutang @error('kd_skpd_up') is-invalid @enderror"
                            style=" width: 100%;" id="kd_skpd_up" name="kd_skpd_up">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('kd_skpd_up')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="cetakan" class="form-label">Tahun</label>
                            <?php $thang =   date("Y");
                            $thang_maks = $thang + 3 ;
                            $thang_min = $thang - 15 ;
                            echo '<select id="tahun" class="form-control select_umur_piutang" name="tahun" >';
                            echo "<option value=''> Pilih Tahun</option>";
                            for ($th=$thang_min ; $th<=$thang_maks ; $th++)
                            {
                                echo "<option value=$th>$th</option>";
                            }
                                echo '</select>';?>
                    </div>
                </div>
                
                
          

                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--penyisihan piutang--}}
<div id="modal_cetak_penyisihan_piutang" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd_up" class="form-label">SKPD</label>
                        <select class="form-control select_penyisihan_piutang @error('kd_skpd_up') is-invalid @enderror"
                            style=" width: 100%;" id="kd_skpd_piu" name="kd_skpd_up">
                            <option value="" disabled selected>Silahkan Pilih</option>
                        </select>
                        @error('kd_skpd_up')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="cetakan" class="form-label">Tahun</label>
                            <?php $thang =   date("Y");
                            $thang_maks = $thang + 3 ;
                            $thang_min = $thang - 15 ;
                            echo '<select id="tahun_piu" class="form-control select_penyisihan_piutang" name="tahun_piu" >';
                            echo "<option value=''> Pilih Tahun</option>";
                            for ($th=$thang_min ; $th<=$thang_maks ; $th++)
                            {
                                echo "<option value=$th>$th</option>";
                            }
                                echo '</select>';?>
                    </div>
                </div>
                
                
          

                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--penyisihan piutang--}}
<div id="modal_cetak_ikhtisar" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" class="form-control select_ikhtisar" id="bulan">
                                <option value="">Silahkan Pilih</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    <div class="col-md-6">
                        <label for="jns_anggaran" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran" class="form-control select_ikhtisar" id="jns_anggaran">
                            <option value="" selected disabled>Silahkan Pilih</option>
                            @foreach ($jns_anggaran as $anggaran)
                                <option value="{{ $anggaran->kode }}" data-nama="{{ $anggaran->nama }}">
                                    {{ $anggaran->kode }} | {{ $anggaran->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                
          

                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>