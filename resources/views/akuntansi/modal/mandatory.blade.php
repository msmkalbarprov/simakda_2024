{{-- modal cetak PERDA --}}


<div id="modal_cetak_mandatory" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak_semester" id="labelcetak_semester"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <select class="form-control select_mandatory" style=" width: 100%;" id="bidang_mandatory"
                            name="bidang_mandatory">
                            <option value="1">Pendidikan</option>
                            <option value="2">Kesehatan</option>
                            <option value="3">Infrastruktur</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="format_mandatory" class="form-control select_mandatory" id="format_mandatory">
                            <option value="1">Rekap</option>
                            <option value="2">Rinci</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <select class="form-control select_mandatory" style=" width: 100%;" id="anggaran_mandatory"
                            name="anggaran_mandatory">
                            @foreach ($jns_anggaran as $anggaran)
                                <option value="{{ $anggaran->kode }}">{{ $anggaran->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf" name="bku_pdf">
                            PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                            name="bku_excel">Excel</button>
                        <button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
