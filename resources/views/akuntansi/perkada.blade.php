@extends('template.app')
@section('title', 'Laporan Keuangan | SIMAKDA')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'Laporan Perda' }}</h4>

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
            <div class="card card-info collapsed-card card-outline" id="lamp1">
                <div class="card-body">
                    {{ 'PERKADA LAMP I' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lamp2">
                <div class="card-body">
                    {{ 'PERKADA LAMP II' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    

   
    {{-- modal cetak SPJ  --}}
{{-- @include('akuntansi.modal.lrasap') --}}
@include('akuntansi.modal.lraperkada')
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

            $(".select_lamp1").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lamp1 .modal-content'),
                
            });
            $(".select_lamp2").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lamp2 .modal-content'),
                
            });
            // hidden lamp1
            document.getElementById('baris_skpd').hidden = true; // Hide
            let typeu = "{{Auth::user()->is_admin}}";
            if (typeu == '1') {
                 document.getElementById('pilihan0').hidden = false;
            }else{
                document.getElementById('pilihan0').hidden = true;
            }
            
            
        });


    // onclick card start
        $('#lamp1').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lamp1').modal('show');
            $("#labelcetak_semester").html("Cetak Lamp I");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('input:radio[name="inlineRadioOptions"]').change(function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd').hidden = true; // Hide
            }else {
                cari_skpd('unit')
                document.getElementById('baris_skpd').hidden = false; // show
            }
        });
        $('#lamp2').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lamp2').modal('show');
            $("#labelcetak_semester").html("Cetak Lamp II");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('input:radio[name="pilih_lamp2"]').change(function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_lamp2').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_lamp2').hidden = false; // show
            }
        });


        function cari_skpd(jenis) {
            $.ajax({
                url: "{{ route('laporan_akuntansi.skpd') }}",
                type: "POST",
                dataType: 'json',
                data: {
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
                    $('#kd_skpd_lamp2').empty();
                    $('#kd_skpd_lamp2').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_lamp2').append(
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
            // alert(labelcetak_semester)
            // PERINGATAN
                

            // SET CETAKAN
            if (labelcetak_semester == 'Cetak Lamp I') {
                // GET DATA
                let kd_skpd                  = document.getElementById('kd_skpd').value;
                let bulan                    = document.getElementById('bulan').value;
                let jns_anggaran             = document.getElementById('jns_anggaran').value;
                let jenis                    = document.getElementById('jenis').value;

                if (!jenis) {
                    alert('jenis Data tidak boleh kosong!');
                    return;
                }
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                let url             = new URL("{{ route('laporan_akuntansi.perkada.cetak_lamp1') }}");
                let searchParams    = url.searchParams;
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("jenis", jenis);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lamp II') {
                // GET DATA
                let kd_skpd                  = document.getElementById('kd_skpd_lamp2').value;
                let bulan                    = document.getElementById('bulan_lamp2').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_lamp2').value;
                let jenis                    = document.getElementById('jns_rincian').value;
                let tgl_ttd                    = document.getElementById('tgl_ttd').value;
                let skpdunit                 = $('input:radio[name="pilih_lamp2"]:checked').val();

                if (!jenis) {
                    alert('jenis Data tidak boleh kosong!');
                    return;
                }
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                let url             = new URL("{{ route('laporan_akuntansi.perkada.cetak_lamp2') }}");
                let searchParams    = url.searchParams;
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("jenis", jenis);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("skpdunit", skpdunit);
                window.open(url.toString(), "_blank");
            
            }else{
                alert('-' + jns_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
