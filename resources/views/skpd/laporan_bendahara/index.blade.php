@extends('template.app')
@section('title', 'Laporan bendahara | SIMAKDA')
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
    </div>
    <div class="col-md-6">
        <div class="card card-info collapsed-card card-outline" id="lapspj">
          <div class="card-body">
              {{'SPJ Fungsional'}}
              <a class="card-block stretched-link" href="#">
              
              </a>
                  <i class="fa fa-chevron-right float-end mt-2"></i>
                  
          </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
      <div class="card card-info collapsed-card card-outline" id="lapbpbank">
        <div class="card-body">
            {{'Buku Pembantu Kas Bank'}}
            <a class="card-block stretched-link" href="#">
            
            </a>
                <i class="fa fa-chevron-right float-end mt-2"></i>
                
        </div>
      </div>
    </div>
    <div class="col-md-6">
        <div class="card card-info collapsed-card card-outline" id="lapbptunai">
          <div class="card-body">
              {{'Buku Pembantu Kas Tunai'}}
              <a class="card-block stretched-link" href="#">
              
              </a>
                  <i class="fa fa-chevron-right float-end mt-2"></i>
                  
          </div>
        </div>
    </div>
</div>

{{-- modal cetak SPJ --}}
<div id="modal_cetak" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><label for="labelcetak" id="labelcetak"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Pilihan SKPD/Unit --}}
                <div class="mb-3 row" id="row-hidden">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Pilih</label><br>
                        <div class=" form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="pilihan1" value="skpd">
                            <label class="form-check-label" for="pilihan">SKPD</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="pilihan2" value="unit">
                            <label class="form-check-label" for="pilihan">Unit</label>
                          </div>
                    </div>
                    {{-- Bulan --}}
                    <div class="col-md-6">
                        <label for="bulan" class="form-label">Jenis Anggaran</label>
                        <select name="jns_anggaran" class="form-control" id="jns_anggaran">
                            <option value="" selected disabled>Silahkan Pilih</option>
                            @foreach ($jns_anggaran as $anggaran)
                                <option value="{{ $anggaran->kode }}" data-nama="{{ $anggaran->nama }}">
                                    {{ $anggaran->kode }} | {{ $anggaran->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- SKPD --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="kd_skpd" class="form-label">Kode SKPD</label>
                        {{-- <input type="text"  class="form-control" id="kd_skpd" name="kd_skpd" value="{{ $data_skpd->kd_skpd }}" readonly> --}}
                        <select class="form-control select2-modal @error('kd_skpd') is-invalid @enderror"
                                style=" width: 100%;" id="kd_skpd" name="kd_skpd">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('kd_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nm_skpd" class="form-label">Bulan</label>
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
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="bendahara" class="form-label">Bendahara</label>
                        <select class="form-control select2-modal @error('bendahara') is-invalid @enderror"
                                style=" width: 100%;" id="bendahara" name="bendahara">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('bendahara')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        <label for="pa_kpa" class="form-label">PA/KPA</label>
                        <select class="form-control select2-modal @error('pa_kpa') is-invalid @enderror"
                                style=" width: 100%;" id="pa_kpa" name="pa_kpa">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('pa_kpa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>
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
{{-- modal cetak SPJ  --}}
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

        $('#kd_skpd').select2({
            dropdownParent: $('#modal_cetak'),
            theme: 'bootstrap-5'
        });

        $('#pa_kpa').select2({
            dropdownParent: $('#modal_cetak'),
            theme: 'bootstrap-5'
        });

        $('#jns_anggaran').select2({
            dropdownParent: $('#modal_cetak'),
            theme: 'bootstrap-5'
        });
        

    });

    // onclick card
    $('#lapbku').on('click', function() {
        let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            document.getElementById('jns_anggaran').disabled    = true;
            $("#labelcetak").html("Cetak BKU");
            document.getElementById('row-hidden').hidden        = true;      // Hide
            cari_skpd(kd_skpd,'unit');
    });
    $('#lapspj').on('click', function() {
            $('#modal_cetak').modal('show');
            $("#labelcetak").html("Cetak SPJ Fungsional");
            document.getElementById('jns_anggaran').disabled    = false;
            document.getElementById('row-hidden').hidden        = false; //show
    });

    $('#lapbpbank').on('click', function() {
        let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            document.getElementById('jns_anggaran').disabled    = true;
            $("#labelcetak").html("Cetak Buku Pembantu Kas Bank");
            document.getElementById('row-hidden').hidden        = true;      // Hide
            cari_skpd(kd_skpd,'unit');
    });

    $('#lapbptunai').on('click', function() {
        let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            document.getElementById('jns_anggaran').disabled    = true;
            $("#labelcetak").html("Cetak Buku Pembantu Kas Tunai");
            document.getElementById('row-hidden').hidden        = true;      // Hide
            cari_skpd(kd_skpd,'unit');
    });

    

    // cari skpd/org

    $('input:radio[name="inlineRadioOptions"]').change(function(){
        let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        if($(this).val() == 'skpd'){
            cari_skpd(kd_skpd,'skpd')
        }else{
            cari_skpd(kd_skpd,'unit')
        }
    });

    function cari_skpd(kd_skpd,jenis) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara.skpd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd,
                    jenis:jenis
                },
                success: function(data) {
                    $('#kd_skpd').empty();
                    $('#kd_skpd').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                }
            })
        }
    
        // action skpd
    $('#kd_skpd').on('select2:select', function() {
            let kd_skpd = this.value;
            cari_bendahara(kd_skpd);
            cari_pakpa(kd_skpd);
    });
    
    function cari_bendahara(kd_skpd) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara.bendahara') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    $('#bendahara').empty();
                    $('#bendahara').append(
                        `<option value="" disabled selected>Pilih Bendahara Pengeluaran</option>`);
                    $.each(data, function(index, data) {
                        $('#bendahara').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                }
            })
        }

    function cari_pakpa(kd_skpd) {
        $.ajax({
            url: "{{ route('skpd.laporan_bendahara.pakpa') }}",
            type: "POST",
            dataType: 'json',
            data: {
                kd_skpd: kd_skpd
            },
            success: function(data) {
                $('#pa_kpa').empty();
                $('#pa_kpa').append(
                    `<option value="" disabled selected>Pilih PA/KPA</option>`);
                $.each(data, function(index, data) {
                    $('#pa_kpa').append(
                        `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                    );
                })
            }
        })
    }


            // CETAK BKU
            $('.bku_layar').on('click', function() {
                let spasi       = document.getElementById('spasi').value;
                let bulan       = document.getElementById('bulan').value;
                let tgl_ttd     = document.getElementById('tgl_ttd').value;
                let bendahara   = document.getElementById('bendahara').value;
                let pa_kpa      = document.getElementById('pa_kpa').value;
                let kd_skpd     = document.getElementById('kd_skpd').value;
                let jns_anggaran= document.getElementById('jns_anggaran').value;
                let jenis_print = $(this).data("jenis");

                jenis_cetak     = document.getElementById('labelcetak').textContent

                if (!bendahara) {
                    alert('Bendahara Pengeluaran tidak boleh kosong!');
                    return;
                }
                if (!pa_kpa) {
                    alert("PA/KPA tidak boleh kosong!");
                    return;
                }
                if(jenis_cetak =='Cetak BKU'){
                    let url = new URL("{{ route('skpd.laporan_bendahara.cetak_bku') }}");
                    let searchParams = url.searchParams;
                    searchParams.append("spasi", spasi);
                    searchParams.append("bendahara", bendahara);
                    searchParams.append("pa_kpa", pa_kpa);
                    searchParams.append("bulan", bulan);
                    searchParams.append("kd_skpd", kd_skpd);
                    searchParams.append("tgl_ttd", tgl_ttd);
                    searchParams.append("jenis_print", jenis_print);
                    
                    window.open(url.toString(), "_blank");
                }else if(jenis_cetak =='Cetak SPJ Fungsional'){
                    let url = new URL("{{ route('skpd.laporan_bendahara.cetak_spj_fungsional') }}");
                    let searchParams = url.searchParams;
                    searchParams.append("spasi", spasi);
                    searchParams.append("bendahara", bendahara);
                    searchParams.append("pa_kpa", pa_kpa);
                    searchParams.append("bulan", bulan);
                    searchParams.append("jns_anggaran",jns_anggaran);
                    searchParams.append("kd_skpd", kd_skpd);
                    searchParams.append("tgl_ttd", tgl_ttd);
                    searchParams.append("jenis_print", jenis_print);
                    
                    window.open(url.toString(), "_blank");
                }else if(jenis_cetak =='Cetak Buku Pembantu Kas Bank'){
                    let url = new URL("{{ route('skpd.laporan_bendahara.cetak_bp_kasbank') }}");
                    let searchParams = url.searchParams;
                    searchParams.append("spasi", spasi);
                    searchParams.append("bendahara", bendahara);
                    searchParams.append("pa_kpa", pa_kpa);
                    searchParams.append("bulan", bulan);
                    searchParams.append("jns_anggaran",jns_anggaran);
                    searchParams.append("kd_skpd", kd_skpd);
                    searchParams.append("tgl_ttd", tgl_ttd);
                    searchParams.append("jenis_print", jenis_print);
                    
                    window.open(url.toString(), "_blank");
                }else{
                    alert('-'+jenis_cetak+'- Tidak dapat url');
                }
                


                
            });        

</script>
@endsection
