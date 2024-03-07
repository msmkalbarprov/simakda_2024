<div id="modal_buku_kasda" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak" id="labelcetak">BKU</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Bulan --}}
                <div class="mb-3 row">
                    <label for="bulan_kasda" class="form-label">Bulan</label>
                    <div class="col-md-12">
                        <select class="form-control select2-buku_kasda" style=" width: 100%;" id="bulan_kasda">
                            <option value="" disabled selected>Silahkan Pilih</option>
                            <option value="1"> Januari </option>
                            <option value="2"> Februari </option>
                            <option value="3"> Maret </option>
                            <option value="4"> April </option>
                            <option value="5"> Mei </option>
                            <option value="6"> Juni </option>
                            <option value="7"> Juli </option>
                            <option value="8"> Agustus </option>
                            <option value="9"> September </option>
                            <option value="10"> Oktober </option>
                            <option value="11"> November </option>
                            <option value="12"> Desember </option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jenis_kasda" class="form-label">Jenis</label>
                    <div class="col-md-12">
                        <select class="form-control select2-buku_kasda" style=" width: 100%;" id="jenis_kasda">
                            <option value="" disabled selected>Silahkan Pilih</option>
                            <option value="1"> Pengeluaran </option>
                            <option value="2"> Penerimaan </option>
                        </select>
                    </div>
                </div>
                {{-- Pilihan Cetak --}}
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md cetak_buku_kasda" data-jenis="pdf">
                            PDF</button>
                        <button type="button" class="btn btn-dark btn-md cetak_buku_kasda"
                            data-jenis="layar">Layar</button>
                        <button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
