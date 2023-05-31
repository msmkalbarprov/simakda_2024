@extends('template.app')
@section('title', 'Pengesahan SPJ | SIMAKDA')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'Lampiran Neraca' }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ 'App' }}</a></li>
                        <li class="breadcrumb-item active">{{ 'LK' }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lamp_neraca">
                <div class="card-body">
                    {{ 'Cetak Lamp Neraca' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="umur_piutang">
                <div class="card-body">
                    {{ 'Cetak Analisis Umur Piutang' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="ikhtisar">
                <div class="card-body">
                    {{ 'Ikhtisar Pencapaian Kinerja' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="penyisihan_piutang">
                <div class="card-body">
                    {{ 'Rincian Penyisihan Piutang' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        
    </div>

    

   
    {{-- modal cetak SPJ  --}}
@include('akuntansi.modal.lamp_neraca.lamp_neraca')
    {{-- modal cetak Sub Rincian Objek  --}}
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            cari_rek_objek();
            cari_skpd();

            $(".select_lamp_neraca").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lamp_neraca .modal-content'),
                
            });
            $(".select_umur_piutang").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_umur_piutang .modal-content'),
                
            });
            $(".select_penyisihan_piutang").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_penyisihan_piutang .modal-content'),
                
            });
            
        });


    // onclick card start
        $('#lamp_neraca').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lamp_neraca').modal('show');
            $("#labelcetak_semester").html("Cetak Lampiran Neraca");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
        $('#umur_piutang').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_umur_piutang').modal('show');
            $("#labelcetak_semester").html("Cetak Umur Piutang");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
        $('#penyisihan_piutang').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_penyisihan_piutang').modal('show');
            $("#labelcetak_semester").html("Cetak Penyisihan Piutang");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        

        function cari_rek_objek() {
            $.ajax({
                url: "{{ route('lamp_neraca.rekobjek') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#rekobjek').empty();
                    $('#rekobjek').append(
                        `<option value="" disabled selected>Pilih Rekening</option>`);
                    $.each(data, function(index, data) {
                        $('#rekobjek').append(
                            `<option value="${data.kd_rek3}" data-nama="${data.nm_rek3}">${data.kd_rek3} | ${data.nm_rek3}</option>`
                        );
                    })
                }
            })
        }

        function cari_skpd(kd_skpd, jenis) {
            $.ajax({
                url: "{{ route('lamp_neraca.skpd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd,
                    jenis: jenis
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
                    $('#kd_skpd_up').empty();
                    $('#kd_skpd_up').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_up').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                    $('#kd_skpd_piu').empty();
                    $('#kd_skpd_piu').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_piu').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                    
                }
            })
        }

        


        


        $('.bku_layar').on('click', function() {
            Cetak(1)
        });
        $('.bku_pdf').on('click', function() {
            Cetak(2)
        });
        $('.bku_excel').on('click', function() {
            Cetak(3)
        });

        function Cetak(jns_cetak) {

            // GET DATA
            let labelcetak_semester      = document.getElementById('labelcetak_semester').textContent;

            // SET CETAKAN
            if (labelcetak_semester == 'Cetak Lampiran Neraca') {

                let kd_skpd                    = document.getElementById('kd_skpd').value;
                let rek3                  = document.getElementById('rekobjek').value;
                let cetakan                  = document.getElementById('cetakan').value;
                let url             = new URL("{{ route('lamp_neraca.cetak_lamp_neraca') }}");
                let searchParams    = url.searchParams;
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("rek3", rek3);
                searchParams.append("cetakan", cetakan);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Umur Piutang') {

                let kd_skpd                    = document.getElementById('kd_skpd_up').value;
                let tahun                  = document.getElementById('tahun').value;
                let url             = new URL("{{ route('lamp_neraca.cetak_umur_piutang') }}");
                let searchParams    = url.searchParams;
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tahun", tahun);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Penyisihan Piutang') {

                let kd_skpd                    = document.getElementById('kd_skpd_piu').value;
                let tahun                  = document.getElementById('tahun_piu').value;
                let url             = new URL("{{ route('lamp_neraca.cetak_penyisihan_piutang') }}");
                let searchParams    = url.searchParams;
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tahun", tahun);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else{
                alert('-' + jns_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
