@extends('template.app')
@section('title', 'Kapitalisasi | SIMAKDA')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'Kapitalisasi' }}</h4>

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
            <div class="card card-info collapsed-card card-outline" id="cetak">
                <div class="card-body">
                    {{ 'Cetak Kapitalisasi' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="input_kapit">
                <div class="card-body">
                    {{ 'Input Kapitalisasi' }}
                    <a class="card-block stretched-link" href="{{ route('kapitalisasi.input_kapitalisasi.inputan') }}">
                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    

   
    {{-- modal cetak SPJ  --}}
@include('akuntansi.modal.kapit.kapit')
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
            cari_skpd();

            $(".select_kapit").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_kapit .modal-content'),
                
            });
        });


    // onclick card start
        $('#cetak').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_kapit').modal('show');
            $("#labelcetak_semester").html("Cetak Kapitalisasi");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        function cari_skpd(kd_skpd, jenis) {
            $.ajax({
                url: "{{ route('kapitalisasi.skpd') }}",
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
            if (labelcetak_semester == 'Cetak Kapitalisasi') {

                let kd_skpd                    = document.getElementById('kd_skpd').value;
                let cetakan                  = document.getElementById('cetakan').value;
                let url             = new URL("{{ route('kapitalisasi.cetak_kapitalisasi') }}");
                let searchParams    = url.searchParams;
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("cetakan", cetakan);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else{
                alert('-' + jns_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
