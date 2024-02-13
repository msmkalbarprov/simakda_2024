@extends('template.app')
@section('title', 'Laporan Keuangan | SIMAKDA')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'Laporan Keuangan' }}</h4>

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
    @if(Auth::user()->role == '1027')
        <div class="row">
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="semester">
                    <div class="card-body">
                        {{ 'Laporan Triwulan / Semester' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="semester_bpkp">
                    <div class="card-body">
                        {{ 'Laporan Rinci' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
        </div>
    @elseif(Auth::user()->role == '1032')
        <div class="row">
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="lra">
                    <div class="card-body">
                        {{ 'Laporan LRA' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="semester">
                    <div class="card-body">
                        {{ 'Laporan Triwulan / Semester' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="semesterrinci">
                    <div class="card-body">
                        {{ 'Laporan Triwulan / Semester Rinci' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="lo">
                    <div class="card-body">
                        {{ 'Laporan Operasional' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="lra">
                    <div class="card-body">
                        {{ 'Laporan LRA' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="lpe">
                    <div class="card-body">
                        {{ 'LPE' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="neraca">
                    <div class="card-body">
                        {{ 'NERACA' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
        </div>
    @endif

   
    {{-- modal cetak SPJ  --}}
@include('akuntansi.modal.lapkeu')
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

            $(".select2").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_lralapkeu .modal-content'),
                
            });
            $(".select_neraca").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_neraca .modal-content'),
                
            });
            $(".select_lo").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lo .modal-content'),
                
            });
            $(".select_lpe").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lpe .modal-content'),
                
            });
            $(".select_lak").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lak .modal-content'),
                
            });
            $(".select_semester").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_semester .modal-content'),
                
            });
            $(".select_semesterrinci").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_semesterrinci .modal-content'),
                
            });
            $(".select_semester_bpkp").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_semester_bpkp .modal-content'),
                
            });
            // hidden
            //LRA
            document.getElementById('baris_skpd').hidden = true; // Hide
            document.getElementById('baris_periode1').hidden = true; // Hide
            document.getElementById('baris_periode2').hidden = true; // Hide
            document.getElementById('baris_bulan').hidden = true; // Hide
            let typeu = "{{Auth::user()->is_admin}}";
            if (typeu == '1') {
                 document.getElementById('pilihan0').hidden = false;
            }else{
                document.getElementById('pilihan0').hidden = true;
            }
            //ENDLRA
            // tambahclass()

            // hidden
            document.getElementById('baris_skpd_neraca').hidden = true; // Hide
            // tambahclass()
            // hidden
            document.getElementById('baris_skpd_lo').hidden = true; // Hide
            // tambahclass()
            // hidden
            document.getElementById('baris_skpd_lpe').hidden = true; // Hide
            // tambahclass()

            //LRA SEMESTERR
            document.getElementById('baris_skpd_semester').hidden = true; // Hide
            document.getElementById('baris_periode1_semester').hidden = true; // Hide
            document.getElementById('baris_periode2_semester').hidden = true; // Hide
            document.getElementById('baris_bulan_semester').hidden = true; // Hide
            let typeus = "{{Auth::user()->is_admin}}";
            if (typeus == '1') {
                 document.getElementById('pilihan0_semester').hidden = false;
            }else{
                document.getElementById('pilihan0_semester').hidden = true;
            }
            //ENDLRA SEMESTER

            //LRA SEMESTERR rinci
            document.getElementById('baris_skpd_semesterrinci').hidden = true; // Hide
            document.getElementById('baris_periode1_semesterrinci').hidden = true; // Hide
            document.getElementById('baris_periode2_semesterrinci').hidden = true; // Hide
            document.getElementById('baris_bulan_semesterrinci').hidden = true; // Hide
            let typeusrinci = "{{Auth::user()->is_admin}}";
            if (typeusrinci == '1') {
                 document.getElementById('pilihan0_semesterrinci').hidden = false;
            }else{
                document.getElementById('pilihan0_semesterrinci').hidden = true;
            }
            //ENDLRA SEMESTER rinci

            //LRA rinci
            document.getElementById('baris_skpd_semester_bpkp').hidden = true; // Hide
            document.getElementById('baris_periode1_semester_bpkp').hidden = true; // Hide
            document.getElementById('baris_periode2_semester_bpkp').hidden = true; // Hide
            document.getElementById('baris_bulan_semester_bpkp').hidden = true; // Hide
            let typeus_bpkp = "{{Auth::user()->is_admin}}";
            if (typeus_bpkp == '1') {
                 document.getElementById('pilihan0_semester_bpkp').hidden = false;
            }else{
                document.getElementById('pilihan0_semester_bpkp').hidden = true;
            }
            //ENDLRA rinci
        });

    // function tambahclass(){
    //     $(".select2").select2({
    //             containerCssClass: "pink",
    //             theme: 'bootstrap-5',
    //             templateResult: function (data, container) {
    //                 if (data.id==1) {
    //                     $(container).addClass($(data.element).attr("class"));
    //                 }
    //                 return data.text;
    //             }
    //             });
    // }
        

    // onclick card start
        $('#lra').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_lralapkeu').modal('show');
            $("#labelcetak_semester").html("Cetak LRA");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#neraca').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_neraca').modal('show');
            $("#labelcetak_semester").html("Cetak NERACA");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lo').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lo').modal('show');
            $("#labelcetak_semester").html("Cetak LO");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lpe').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lpe').modal('show');
            $("#labelcetak_semester").html("Cetak LPE");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
        $('#lak').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lak').modal('show');
            $("#labelcetak_semester").html("Cetak LAK");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
        $('#semester').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_semester').modal('show');
            $("#labelcetak_semester").html("Cetak Semester");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
        $('#semesterrinci').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_semesterrinci').modal('show');
            $("#labelcetak_semester").html("Cetak Semester Rinci");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#semester_bpkp').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_semester_bpkp').modal('show');
            $("#labelcetak_semester").html("Cetak Semester_bpkp");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });


    // onclick card end

        // cari skpd/org
        $('input:radio[name="inlineRadioOptions"]').change(function() {
            cari_ttd();
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                cari_ttd(kd_skpd.substring(0,16))
                document.getElementById('baris_skpd').hidden = false; // show
            } else {
                cari_skpd('unit')
                cari_ttd(kd_skpd)
                document.getElementById('baris_skpd').hidden = false; // show
            }
        });

        $('input:radio[name="pilihan_neraca"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_neraca').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_neraca').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_neraca').hidden = false; // show
            }
        });

        $('input:radio[name="pilihan_lo"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_lo').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_lo').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_lo').hidden = false; // show
            }
        });

        $('input:radio[name="pilihan_lpe"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_lpe').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_lpe').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_lpe').hidden = false; // show
            }
        });

        $('input:radio[name="pilihan_semester"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_semester').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_semester').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_semester').hidden = false; // show
            }
        });

        $('input:radio[name="pilihan_semesterrinci"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_semesterrinci').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_semesterrinci').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_semesterrinci').hidden = false; // show
            }
        });

        $('input:radio[name="pilihan_semester_bpkp"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_semester_bpkp').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_semester_bpkp').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_semester_bpkp').hidden = false; // show
            }
        });

        // cari periode
        $('input:radio[name="pilihanperiode"]').change(function() {
            if ($(this).val() == 'tahun') {
                document.getElementById('baris_periode1').hidden = true; // Hide
                document.getElementById('baris_periode2').hidden = true; // Hide
                document.getElementById('baris_bulan').hidden = true; // Hide
            }else if ($(this).val() == 'periode') {
                document.getElementById('baris_periode1').hidden = false; // show
                document.getElementById('baris_periode2').hidden = false; // Hide
                document.getElementById('baris_bulan').hidden = true; // Hide
            } else {
                document.getElementById('baris_periode1').hidden = true; // show
                document.getElementById('baris_periode2').hidden = true; // Hide
                document.getElementById('baris_bulan').hidden = false; // Hide
            }
        });

        $('input:radio[name="pilihanperiode_semester"]').change(function() {
            if ($(this).val() == 'tahun') {
                document.getElementById('baris_periode1_semester').hidden = true; // Hide
                document.getElementById('baris_periode2_semester').hidden = true; // Hide
                document.getElementById('baris_bulan_semester').hidden = true; // Hide
            }else if ($(this).val() == 'periode') {
                document.getElementById('baris_periode1_semester').hidden = false; // show
                document.getElementById('baris_periode2_semester').hidden = false; // Hide
                document.getElementById('baris_bulan_semester').hidden = true; // Hide
            } else {
                document.getElementById('baris_periode1_semester').hidden = true; // show
                document.getElementById('baris_periode2_semester').hidden = true; // Hide
                document.getElementById('baris_bulan_semester').hidden = false; // Hide
            }
        });

        $('input:radio[name="pilihanperiode_semesterrinci"]').change(function() {
            if ($(this).val() == 'tahun') {
                document.getElementById('baris_periode1_semesterrinci').hidden = true; // Hide
                document.getElementById('baris_periode2_semesterrinci').hidden = true; // Hide
                document.getElementById('baris_bulan_semesterrinci').hidden = true; // Hide
            }else if ($(this).val() == 'periode') {
                document.getElementById('baris_periode1_semesterrinci').hidden = false; // show
                document.getElementById('baris_periode2_semesterrinci').hidden = false; // Hide
                document.getElementById('baris_bulan_semesterrinci').hidden = true; // Hide
            } else {
                document.getElementById('baris_periode1_semesterrinci').hidden = true; // show
                document.getElementById('baris_periode2_semesterrinci').hidden = true; // Hide
                document.getElementById('baris_bulan_semesterrinci').hidden = false; // Hide
            }
        });

        $('input:radio[name="pilihanperiode_semester_bpkp"]').change(function() {
            if ($(this).val() == 'tahun') {
                document.getElementById('baris_periode1_semester_bpkp').hidden = true; // Hide
                document.getElementById('baris_periode2_semester_bpkp').hidden = true; // Hide
                document.getElementById('baris_bulan_semester_bpkp').hidden = true; // Hide
            }else if ($(this).val() == 'periode') {
                document.getElementById('baris_periode1_semester_bpkp').hidden = false; // show
                document.getElementById('baris_periode2_semester_bpkp').hidden = false; // Hide
                document.getElementById('baris_bulan_semester_bpkp').hidden = true; // Hide
            } else {
                document.getElementById('baris_periode1_semester_bpkp').hidden = true; // show
                document.getElementById('baris_periode2_semester_bpkp').hidden = true; // Hide
                document.getElementById('baris_bulan_semester_bpkp').hidden = false; // Hide
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
                    $('#kd_skpd_neraca').empty();
                    $('#kd_skpd_neraca').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_neraca').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                    $('#kd_skpd_lo').empty();
                    $('#kd_skpd_lo').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_lo').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })

                    $('#kd_skpd_lpe').empty();
                    $('#kd_skpd_lpe').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_lpe').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })

                    $('#kd_skpd_semester').empty();
                    $('#kd_skpd_semester').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_semester').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })

                    $('#kd_skpd_semesterrinci').empty();
                    $('#kd_skpd_semesterrinci').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_semesterrinci').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })

                    $('#kd_skpd_semester_bpkp').empty();
                    $('#kd_skpd_semester_bpkp').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_semester_bpkp').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                }
            })
        }

        $('#kd_skpd').on('select2:select', function() {
            let kd_skpd = this.value;
            cari_pakpa(kd_skpd);
        });

        $('#kd_skpd_semester').on('select2:select', function() {
            let kd_skpd = this.value;
            cari_pakpa(kd_skpd);
        });

        $('#kd_skpd_semesterrinci').on('select2:select', function() {
            let kd_skpd = this.value;
            cari_pakpa(kd_skpd);
        });        


        function cari_ttd(kd_skpd) {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $.ajax({
                url: "{{ route('laporan_akuntansi.pakpa') }}",
                type: "POST",
                data: {
                    kd_skpd: kd_skpd
                }, 
                dataType: 'json',
                success: function(data) {
                    $('#ttd').empty();
                    $('#ttd').append(
                        `<option value="" disabled selected>Pilih Tanda Tangan</option>
                        <option value="0">Tanpa Tanda Tangan</option>`);
                    $.each(data, function(index, data) {
                        $('#ttd').append(
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
                        `<option value="" disabled selected>Pilih PA/KPA</option>
                        <option value="0">Tanpa Tanda Tangan</option>`);
                    $.each(data, function(index, data) {
                        $('#pa_kpa').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                    $('#pa_kpa_rinci').empty();
                    $('#pa_kpa_rinci').append(
                        `<option value="" disabled selected>Pilih PA/KPA</option>
                        <option value="0">Tanpa Tanda Tangan</option>`);
                    $.each(data, function(index, data) {
                        $('#pa_kpa_rinci').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                    $('#pa_kpa_lra').empty();
                    $('#pa_kpa_lra').append(
                        `<option value="" disabled selected>Pilih PA/KPA</option>
                        <option value="0">Tanpa Tanda Tangan</option>`);
                    $.each(data, function(index, data) {
                        $('#pa_kpa_lra').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
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
            if (labelcetak_semester == 'Cetak LRA') {
                let kd_skpd                  = document.getElementById('kd_skpd').value;
                let bulan                    = document.getElementById('bulan').value;
                let tanggal1                 = document.getElementById('tanggal1').value;
                let tanggal2                 = document.getElementById('tanggal2').value;
                let tgl_ttd                  = document.getElementById('tgl_ttd').value;
                let ttd                      = document.getElementById('pa_kpa_lra').value;
                let jns_anggaran             = document.getElementById('jns_anggaran').value;
                let periodebulan             = $('input:radio[name="pilihanperiode"]:checked').val();
                let skpdunit                 = $('input:radio[name="inlineRadioOptions"]:checked').val();

                // PERINGATAN
                if (!ttd) {
                    alert('Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                // alert(periodebulan);


                let url             = new URL("{{ route('laporan_akuntansi.lapkeu.cetak_lra') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("ttd", ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("periodebulan", periodebulan);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak NERACA') {
                let kd_skpd           = document.getElementById('kd_skpd_neraca').value;
                let bulan             = document.getElementById('bulan_neraca').value;
                let format             = document.getElementById('cetakan').value;
                let skpdunit                 = $('input:radio[name="pilihan_neraca"]:checked').val();

                if (!format) {
                    alert('Jenis Cetakan tidak boleh kosong!');
                    return;
                }

                let url             = new URL("{{ route('laporan_akuntansi.konsolidasi.cetak_neraca') }}");
                let searchParams    = url.searchParams;
                searchParams.append("format", format);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak LO') {
                let kd_skpd           = document.getElementById('kd_skpd_lo').value;
                let bulan             = document.getElementById('bulan_lo').value;
                let format             = document.getElementById('cetakan_lo').value;
                let skpdunit                 = $('input:radio[name="pilihan_lo"]:checked').val();

                if (!format) {
                    alert('Jenis Cetakan tidak boleh kosong!');
                    return;
                }
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }

                let url             = new URL("{{ route('laporan_akuntansi.konsolidasi.cetak_lo') }}");
                let searchParams    = url.searchParams;
                searchParams.append("format", format);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak LPE') {
                let kd_skpd           = document.getElementById('kd_skpd_lpe').value;
                let bulan             = document.getElementById('bulan_lpe').value;
                let skpdunit                 = $('input:radio[name="pilihan_lpe"]:checked').val();

                
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }

                let url             = new URL("{{ route('laporan_akuntansi.konsolidasi.cetak_lpe') }}");
                let searchParams    = url.searchParams;
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak LAK') {
                let bulan             = document.getElementById('bulan_lak').value;

                
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }

                let url             = new URL("{{ route('laporan_akuntansi.konsolidasi.cetak_lak') }}");
                let searchParams    = url.searchParams;
                searchParams.append("bulan", bulan);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Semester') {
                let kd_skpd                  = document.getElementById('kd_skpd_semester').value;
                let bulan                    = document.getElementById('bulan_semester').value;
                let tanggal1                 = document.getElementById('tanggal1_semester').value;
                let tanggal2                 = document.getElementById('tanggal2_semester').value;
                let tgl_ttd                  = document.getElementById('tgl_ttd_semester').value;
                let ttd                      = document.getElementById('pa_kpa').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_semester').value;
                let jenis_data               = document.getElementById('jenis_data_semester').value;
                let periodebulan             = $('input:radio[name="pilihanperiode_semester"]:checked').val();
                let skpdunit                 = $('input:radio[name="pilihan_semester"]:checked').val();

                // PERINGATAN
                
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!ttd) {
                    alert('PA/KPA tidak boleh kosong!');
                    return;
                }
                if (!jenis_data) {
                    alert('jenis Data tidak boleh kosong!');
                    return;
                }

                // alert(jenis_data);
                let url ='';

                if (jenis_data == 5 || jenis_data == 4 ) {
                    url             = new URL("{{ route('laporan_akuntansi.lapkeu.semester_jurnal') }}");
                }else{
                    url             = new URL("{{ route('laporan_akuntansi.lapkeu.semester') }}");
                }
                let searchParams    = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("ttd", ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_data", jenis_data);
                searchParams.append("panjang_data", 4);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("periodebulan", periodebulan);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Semester Rinci') {
                let kd_skpd                  = document.getElementById('kd_skpd_semesterrinci').value;
                let bulan                    = document.getElementById('bulan_semesterrinci').value;
                let tanggal1                 = document.getElementById('tanggal1_semesterrinci').value;
                let tanggal2                 = document.getElementById('tanggal2_semesterrinci').value;
                let tgl_ttd                  = document.getElementById('tgl_ttd_semesterrinci').value;
                let ttd                      = document.getElementById('pa_kpa_rinci').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_semesterrinci').value;
                let jenis_data               = document.getElementById('jenis_data_semesterrinci').value;
                let panjang_data             = document.getElementById('panjang_data_semesterrinci').value;
                let periodebulan             = $('input:radio[name="pilihanperiode_semesterrinci"]:checked').val();
                let skpdunit                 = $('input:radio[name="pilihan_semesterrinci"]:checked').val();

                // PERINGATAN
                
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!jenis_data) {
                    alert('jenis Data tidak boleh kosong!');
                    return;
                }
                if (!panjang_data) {
                    alert('Rincian tidak boleh kosong!');
                    return;
                }

                let url             = new URL("{{ route('laporan_akuntansi.lapkeu.semesterrinci') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("ttd", ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_data", jenis_data);
                searchParams.append("panjang_data", panjang_data);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("periodebulan", periodebulan);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Semester_bpkp') {
                let kd_skpd                  = document.getElementById('kd_skpd_semester_bpkp').value;
                let bulan                    = document.getElementById('bulan_semester_bpkp').value;
                let tanggal1                 = document.getElementById('tanggal1_semester_bpkp').value;
                let tanggal2                 = document.getElementById('tanggal2_semester_bpkp').value;
                let panjang_data             = document.getElementById('panjang_data_semester_bpkp').value;
                let periodebulan             = $('input:radio[name="pilihanperiode_semester_bpkp"]:checked').val();
                let skpdunit                 = $('input:radio[name="pilihan_semester_bpkp"]:checked').val();

                // PERINGATAN
                
                if (!skpdunit) {
                    alert('Pilih Pilihan 1 tidak boleh kosong!');
                    return;
                }
                if (!periodebulan) {
                    alert('Pilih Pilihan 2 tidak boleh kosong!');
                    return;
                }
                if (!panjang_data) {
                    alert('Rincian tidak boleh kosong!');
                    return;
                }
                
                url             = new URL("{{ route('laporan_akuntansi.lapkeu.semester_bpkp') }}");
                
                let searchParams    = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("panjang_data", panjang_data);
                searchParams.append("jenis_anggaran", "P3");
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("periodebulan", periodebulan);
                window.open(url.toString(), "_blank");
            
            }else{
                alert('-' + jenis_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
