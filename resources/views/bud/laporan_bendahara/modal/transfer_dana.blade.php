<div id="modal_transfer_dana" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak" id="labelcetak">TRANSFER DANA</label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Tanggal --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="" class="form-label">Tanggal Cetak</label>
                        <input type="date" id="tgl_transfer_dana" class="form-control">
                    </div>
                        <div class="col-md-3">
                            <label for="kd_unit" class="form-label">Per Periode</label>
                            <input type="date" class="form-control" id="periode1_tfdana">
                        </div>
                        <div class="col-md-3">
                            <label for="kd_unit" class="form-label">&nbsp;</label>
                            <input type="date" class="form-control" id="periode2_tfdana">
                        </div>
                    
                </div>
                {{-- Kuasa BUD --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="penandatangan" class="form-label">Kuasa BUD</label>
                        <select class="form-control select2-transfer_dana" style=" width: 100%;" id="ttd_transfer_dana">
                            <option value="" disabled selected>Silahkan Pilih</option>
                            @foreach ($bud as $ttd)
                                <option value="{{ $ttd->nip }}">{{ $ttd->nip }} | {{ $ttd->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Pilihan Cetak --}}
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md cetak_transfer_dana"
                            data-jenis="pdf">PDF</button>
                        <button type="button" class="btn btn-dark btn-md cetak_transfer_dana"
                            data-jenis="layar">Layar</button>
                        <button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
