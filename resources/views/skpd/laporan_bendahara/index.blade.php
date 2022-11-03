@extends('template.app')
@section('title', 'Transaksi CMS | SIMAKDA')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{'Laporan bendahara'}}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{'App'}}</a></li>
                    <li class="breadcrumb-item">{{'Laporan Bendahara'}}</li>
                    <li class="breadcrumb-item active">{{'Pengeluaran '}}</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-md-6">
      <div class="card card-info collapsed-card card-outline" id="lapbku">
        <div class="card-body">
            {{'BKU (Buku Kas Umum)'}}
            <a class="card-block stretched-link" href="#">
            
            </a>
                <i class="fa fa-chevron-right float-end mt-2"></i>
                
        </div>
      </div>
      {{-- <div class="card card-info collapsed-card card-outline">
        <a class="card-block stretched-link" href>
            {{'BKU (Buku Kas Umum)'}}
        </a>
    </div> --}}
    </div>
    <div class="col-md-6">
        <div class="card card-info collapsed-card card-outline">
          <div class="card-header">
           {{' SPJ (Surat Pertanggung Jawaban)'}}
            <!-- /.card-tools -->
          </div>
        </div>
      </div>
</div>

  {{-- modal cetak sppls --}}
  <div id="modal_cetak" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak BKU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- SKPD --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Kode SKPD</label>
                        <input type="text"  class="form-control" id="kd_skpd" name="kd_skpd" value="{{ $data_skpd->kd_skpd }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nm_skpd" class="form-label">Nama SKPD</label>
                        <input type="text"  class="form-control" id="nm_skpd" name="nm_skpd" value="{{ $data_skpd->nm_skpd }}" readonly>
                    </div>
                </div>
                
                {{-- Bulan --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" class="form-control" id="bulan">
                            <option value="">Silahkan Pilih</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    {{-- PA/KPA --}}
                    <div class="col-md-6">
                        <label for="pa_kpa" class="form-label">Tanggal TTD</label>
                            <input type="date" id="tgl_ttd" name="tgl_ttd" class="form-control">
                    </div>
                </div>

                {{-- Bendahara --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="bendahara" class="form-label">Bendahara</label>
                        <select name="bendahara" class="form-control" id="bendahara">
                            <option value="" selected disabled>Silahkan Pilih</option>
                            @foreach ($bendahara as $ttd)
                                <option value="{{ $ttd->nip }}" data-nama="{{ $ttd->nama }}">
                                    {{ $ttd->nip }} | {{ $ttd->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="pa_kpa" class="form-label">PA/KPA</label>
                            <select name="pa_kpa" class="form-control" id="pa_kpa">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($pa_kpa as $ttd)
                                    <option value="{{ $ttd->nip }}" data-nama="{{ $ttd->nama }}">
                                        {{ $ttd->nip }} | {{ $ttd->nama }}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                
             
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="spasi" class="form-label">Spasi</label>
                        <input type="number" value="1" min="1" class="form-control" id="spasi"
                            name="spasi">
                    </div>
                </div>
               
               
                <div class="mb-3 row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                            name="bku_pdf"> PDF</button>
                        <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                            name="bku_layar">Layar</button>
                        <button type="button" class="btn btn-md btn-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#bendahara').select2({
            dropdownParent: $('#modal_cetak'),
            theme: 'bootstrap-5'
        });

        $('#bulan').select2({
            dropdownParent: $('#modal_cetak'),
            theme: 'bootstrap-5'
        });

        $('#pa_kpa').select2({
            dropdownParent: $('#modal_cetak'),
            theme: 'bootstrap-5'
        });

    });

    $('#lapbku').on('click', function() {
            $('#modal_cetak').modal('show');
    });
            // CETAK BKU
            $('.bku_layar').on('click', function() {
                let spasi       = document.getElementById('spasi').value;
                let bulan       = document.getElementById('bulan').value;
                let bendahara   = document.getElementById('bendahara').value;
                let pa_kpa      = document.getElementById('pa_kpa').value;
                let kd_skpd     = document.getElementById('kd_skpd').value;
                let jenis_print = $(this).data("jenis");
                
                if (!bendahara) {
                    alert('Bendahara Pengeluaran tidak boleh kosong!');
                    return;
                }
                if (!pa_kpa) {
                    alert("PA/KPA tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('skpd.laporan_bendahara.cetak_bku') }}");
                let searchParams = url.searchParams;
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });        

</script>
@endsection
