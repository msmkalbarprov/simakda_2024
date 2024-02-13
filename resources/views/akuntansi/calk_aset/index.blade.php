@extends('template.app')
@section('title', 'CALK & ASET | SIMAKDA')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'Lampiran CALK & ASET' }}</h4>

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
            <div class="card card-info collapsed-card card-outline" id="lap_aset">
                <div class="card-body">
                    {{ 'Cetak LAP. Aset' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_penyu_aset">
                <div class="card-body">
                    {{ 'Cetak LAP. Penyusutan Aset' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_amortisasi">
                <div class="card-body">
                    {{ 'Cetak LAP. Amortisasi' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_peng_aset">
                <div class="card-body">
                    {{ 'Cetak LAP. Pengadaan Aset' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_pen_lralo">
                <div class="card-body">
                    {{ 'Cetak LAP. Penjelasan LRA LO' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_pen_komulatif">
                <div class="card-body">
                    {{ 'Cetak LAP. Penjelasan Komulatif' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_pen_lo">
                <div class="card-body">
                    {{ 'Cetak LAP. Penjelasan LO' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="hambatan_calk">
                <div class="card-body">
                    {{ 'Cetak Hambatan CALK' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="rekap_bel_peg_brg">
                <div class="card-body">
                    {{ 'Cetak Rekap Belanja Pegawai dan Barang' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="rekap_pendapatan">
                <div class="card-body">
                    {{ 'Cetak Rekap Pendapatan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="rekap_beban">
                <div class="card-body">
                    {{ 'Cetak Rekap Beban' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="penjelasan_pendapatan">
                <div class="card-body">
                    {{ 'Cetak Penjelasan Pendapatan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_calk_lo_beban">
                <div class="card-body">
                    {{ 'Cetak Lap. CALK LO Beban' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_calk_aset">
                <div class="card-body">
                    {{ 'Cetak Lap. CALK Aset' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_calk_penyajian_data">
                <div class="card-body">
                    {{ 'Cetak Lap. CALK Penyajian Data ' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_calk_kewajiban">
                <div class="card-body">
                    {{ 'Cetak Lap. CALK Kewajiban ' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_calk_lap_lpe_lain_lain">
                <div class="card-body">
                    {{ 'Cetak Lap. CALK LPE lain-lain ' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="penjelasan_calk">
                <div class="card-body">
                    {{ 'Cetak Penjelasan CALK' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_beban_penyusutan">
                <div class="card-body">
                    {{ 'Cetak Lap. Beban Penyusutan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_jaminan">
                <div class="card-body">
                    {{ 'Cetak Laporan Jaminan Pemeliharaan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lap_akumulasi_penyusutan">
                <div class="card-body">
                    {{ 'Cetak Lap. Akumulasi Penyusutan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    

@include('akuntansi.calk_aset.modal_calk_aset')
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

            $(".select_lap_aset").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_aset .modal-content'),
                
            });

            $(".select_lap_penyu_aset").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_penyu_aset .modal-content'),
                
            });

            $(".select_lap_pen_lralo").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_pen_lralo .modal-content'),
                
            });

            $(".select_lap_calk_lo_beban").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_calk_lo_beban .modal-content'),
                
            });

            $(".select_lap_calk_aset").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_calk_aset .modal-content'),
                
            });

            $(".select_lap_calk_penyajian_data").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_calk_penyajian_data .modal-content'),
                
            });

            $(".select_lap_calk_kewajiban").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_calk_kewajiban .modal-content'),
                
            });

            $(".select_penjelasan_calk").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_penjelasan_calk .modal-content'),
                
            });

            $(".select_lap_jaminan").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_jaminan .modal-content'),
                
            });

            $(".select_lap_akumulasi_penyusutan").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lap_akumulasi_penyusutan .modal-content'),
                
            });
        });


    // onclick card start
        $('#lap_aset').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_aset').modal('show');
            $("#labelcetak_semester").html("Cetak lap Aset");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_penyu_aset').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_penyu_aset').modal('show');
            $("#labelcetak_semester").html("Cetak lap Penyusutan Aset");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_amortisasi').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_amortisasi').modal('show');
            $("#labelcetak_semester").html("Cetak lap Amortisasi");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_peng_aset').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_peng_aset').modal('show');
            $("#labelcetak_semester").html("Cetak lap Pengadaan Aset");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_pen_lralo').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_pen_lralo').modal('show');
            $("#labelcetak_semester").html("Cetak lap Penjelasan LRA LO");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_pen_komulatif').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_pen_komulatif').modal('show');
            $("#labelcetak_semester").html("Cetak lap Penjelasan Komulatif");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_pen_lo').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_pen_lo').modal('show');
            $("#labelcetak_semester").html("Cetak lap Penjelasan LO");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#hambatan_calk').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_hambatan_calk').modal('show');
            $("#labelcetak_semester").html("Cetak Hambatan CALK");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#rekap_bel_peg_brg').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_rekap_bel_peg_brg').modal('show');
            $("#labelcetak_semester").html("Cetak Rekap Belanja Pegawai dan Barang");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#rekap_pendapatan').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_rekap_pendapatan').modal('show');
            $("#labelcetak_semester").html("Cetak Rekap Pendapatan");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#rekap_beban').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_rekap_beban').modal('show');
            $("#labelcetak_semester").html("Cetak Rekap Beban");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#penjelasan_pendapatan').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_penjelasan_pendapatan').modal('show');
            $("#labelcetak_semester").html("Cetak Penjelasan Pendapatan");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_calk_lo_beban').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_calk_lo_beban').modal('show');
            $("#labelcetak_semester").html("Cetak Lap. CALK LO Beban");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_calk_aset').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_calk_aset').modal('show');
            $("#labelcetak_semester").html("Cetak Lap. CALK Aset");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_calk_penyajian_data').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_calk_penyajian_data').modal('show');
            $("#labelcetak_semester").html("Cetak Lap. CALK Penyajian Data");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_calk_kewajiban').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_calk_kewajiban').modal('show');
            $("#labelcetak_semester").html("Cetak Lap. CALK Kewajiban");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_calk_lap_lpe_lain_lain').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_calk_lpe_lain_lain').modal('show');
            $("#labelcetak_semester").html("Cetak Lap. CALK LPE Lain-lain");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#penjelasan_calk').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_penjelasan_calk').modal('show');
            $("#labelcetak_semester").html("Cetak Penjelasan CALK");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_beban_penyusutan').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_beban_penyusutan').modal('show');
            $("#labelcetak_semester").html("Cetak Lap. Beban Penyusutan");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lap_jaminan').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_jaminan').modal('show');
            $("#labelcetak_semester").html("Cetak Lap. Jaminan Pemeliharaan");
            document.getElementById('baris_skpd_jaminan').hidden = true; // Hide
        });

        $('#lap_akumulasi_penyusutan').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lap_akumulasi_penyusutan').modal('show');
            $("#labelcetak_semester").html("Cetak Lap. Akumulasi Penyusutan");
        });
    //

        $('input:radio[name="pilihan_jaminan"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_jaminan').hidden = true; // Hide
            }else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_jaminan').hidden = false; // show
            }
        });

        

        function cari_rek_objek() {
            $.ajax({
                url: "{{ route('calkaset.rekobjek') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#rekobjek_lap_aset').empty();
                    $('#rekobjek_lap_aset').append(
                        `<option value="" disabled selected>Pilih Rekening</option>`);
                    $.each(data, function(index, data) {
                        $('#rekobjek_lap_aset').append(
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
                    $('#kd_skpd_jaminan').empty();
                    $('#kd_skpd_jaminan').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_jaminan').append(
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
            if (labelcetak_semester == 'Cetak lap Aset') {
                let rek3                  = document.getElementById('rekobjek_lap_aset').value;
                let jenis                  = document.getElementById('jenis_lap_aset').value;
                let format                  = document.getElementById('format_lap_aset').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_aset') }}");
                let searchParams    = url.searchParams;
                searchParams.append("jenis", jenis);
                searchParams.append("rek3", rek3);
                searchParams.append("format", format);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak lap Penyusutan Aset') {
                let rek3                  = document.getElementById('rek_lap_penyu_aset').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_penyu_aset') }}");
                let searchParams    = url.searchParams;
                searchParams.append("rek3", rek3);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak lap Amortisasi') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_amortisasi') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak lap Pengadaan Aset') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_peng_aset') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak lap Penjelasan LRA LO') {
                let format                  = document.getElementById('format_lap_pen_lralo').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_pen_lralo') }}");
                let searchParams    = url.searchParams;
                searchParams.append("format", format);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak lap Penjelasan Komulatif') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_pen_komulatif') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak lap Penjelasan LO') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_pen_lo') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Hambatan CALK') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_hambatan_calk') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Rekap Belanja Pegawai dan Barang') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_rekap_bel_peg_brg') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Rekap Pendapatan') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_rekap_pendapatan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Rekap Beban') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_rekap_beban') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Penjelasan Pendapatan') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_penjelasan_pendapatan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lap. CALK LO Beban') {
                let jenis                  = document.getElementById('jenis_lap_calk_lo_beban').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_calk_lo_beban') }}");
                let searchParams    = url.searchParams;
                searchParams.append("jenis", jenis);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lap. CALK Aset') {
                let rek                  = document.getElementById('rek_lap_calk_aset').value;
                let jenis                  = document.getElementById('jenis_lap_calk_aset').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_calk_aset') }}");
                let searchParams    = url.searchParams;
                searchParams.append("rek", rek);
                searchParams.append("jenis", jenis);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lap. CALK Penyajian Data') {
                let rek                  = document.getElementById('rek_lap_calk_penyajian_data').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_calk_penyajian_data') }}");
                let searchParams    = url.searchParams;
                searchParams.append("rek", rek);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lap. CALK Kewajiban') {
                let rek                  = document.getElementById('rek_lap_calk_kewajiban').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_calk_kewajiban') }}");
                let searchParams    = url.searchParams;
                searchParams.append("rek", rek);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lap. CALK LPE Lain-lain') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_calk_lpe_lain_lain') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Penjelasan CALK') {
                let rek                  = document.getElementById('rek_penjelasan_calk').value;
                let format                  = document.getElementById('format_penjelasan_calk').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_penjelasan_calk') }}");
                let searchParams    = url.searchParams;
                searchParams.append("rek", rek);
                searchParams.append("format", format);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lap. Beban Penyusutan') {
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_beban_penyusutan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lap. Jaminan Pemeliharaan') {
                let kd_skpd           = document.getElementById('kd_skpd_jaminan').value;
                let skpdunit                 = $('input:radio[name="pilihan_jaminan"]:checked').val();
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_jaminan_pemeliharaan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Lap. Akumulasi Penyusutan') {
                let rek           = document.getElementById('rek_lap_akumulasi_penyusutan').value;
                let url             = new URL("{{ route('calkaset.cetak_calkaset_lap_akumulasi_penyusutan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("rek", rek);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else{
                alert('-' + jns_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
