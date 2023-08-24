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
    @if (Auth::user()->role == '1025')
    @else
        <div class="row">
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="lkonsol">
                    <div class="card-body">
                        {{ 'Laporan Konsolidasi' }}
                        <a class="card-block stretched-link"
                            href="{{ route('laporan_akuntansi.konsolidasi.konsolidasi') }}">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="lkeu">
                    <div class="card-body">
                        {{ 'Laporan Keuangan' }}
                        <a class="card-block stretched-link" href="{{ route('laporan_akuntansi.lapkeu') }}">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lbb">
                <div class="card-body">
                    {{ 'Buku Besar' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        @if(Auth::user()->role == '1015')
        @else
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="rekonba">
                <div class="card-body">
                    {{ 'Rekon BA' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="neraca_saldo">
                <div class="card-body">
                    {{ 'Neraca Saldo' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="ped">
                <div class="card-body">
                    {{ 'PED' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="inflasi">
                <div class="card-body">
                    {{ 'Inflasi Daerah' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="jumum">
                <div class="card-body">
                    {{ 'Cetak Jurnal Umum' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="mandatory">
                <div class="card-body">
                    {{ 'Mandatory' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lralo">
                <div class="card-body">
                    {{ 'Selisih LRA dan LO' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    @if (Auth::user()->role == '1006' || Auth::user()->role == '1022')
        <div class="row">
            <div class="col-md-6">
                <div class="card card-info collapsed-card card-outline" id="siskas">
                    <div class="card-body">
                        {{ 'Rekap Sisa Kas' }}
                        <a class="card-block stretched-link" href="#">

                        </a>
                        <i class="fa fa-chevron-right float-end mt-2"></i>

                    </div>
                </div>
            </div>

        </div>
    @else
    @endif

    @include('akuntansi.modal.bukubesar')
    @include('akuntansi.modal.neraca_saldo')
    @include('akuntansi.modal.ped')
    @include('akuntansi.modal.inflasi')
    @include('akuntansi.modal.mandatory')
    @include('akuntansi.modal.rekonba')
    @include('akuntansi.modal.jumum')
    @include('akuntansi.modal.rekap_sisa_kas')
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // cari_rek6();
            cari_skpdbb();
            cari_rek1();
            ttd_bud();
            ttd_kasubbid();

            $(".select_lbb").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lbb .modal-content'),

            });

            $(".select_ns").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_ns .modal-content'),

            });

            $(".select_ped").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_ped .modal-content'),

            });
            $(".select_inflasi").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_inflasi .modal-content'),

            });
            $(".select_rekonba").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_rekonba .modal-content'),

            });

            $(".select_ju").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_ju .modal-content'),

            });
            $(".select_rekap_sisa_kas").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_rekap_sisa_kas .modal-content'),
            });
            $(".select_mandatory").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_mandatory .modal-content'),
            });
            $(".select_lralo").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lralo .modal-content'),
            });
            // hidden
            document.getElementById('baris_skpd').hidden = true; // Hide
            document.getElementById('baris_skpd_rekonba').hidden = true; // Hide
            document.getElementById('baris_periode1').hidden = true; // Hide
            document.getElementById('baris_periode2').hidden = true; // Hide
            document.getElementById('baris_bulan').hidden = true; // Hide
            document.getElementById('baris_skpd_lralo').hidden = true; // Hide
            document.getElementById('baris_periode1_lralo').hidden = true; // Hide
            document.getElementById('baris_periode2_lralo').hidden = true; // Hide
            document.getElementById('baris_bulan_lralo').hidden = true; // Hide



        });

        let jenis_skpd = "{{ substr(Auth::user()->kd_skpd, 18, 4) }}";
        let jenis
        if (jenis_skpd == '0000') {
            jenis = 'skpd';
        } else {
            jenis = 'unit';
        }
        let role = "{{ Auth::user()->role }}";
        if (role == '1025') {
            $('#lbb').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_lbb').modal('show');
                $("#labelcetak_semester").html("Cetak Buku Besar");
                // document.getElementById('row-hidden').hidden = true; // Hide
                document.getElementById('lkonsol').hidden = true; // Hide
            });
        } else {
            // onclick card start

            $('#lbb').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_lbb').modal('show');
                $("#labelcetak_semester").html("Cetak Buku Besar");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });
            $('#neraca_saldo').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_ns').modal('show');
                $("#labelcetak_semester").html("Cetak Neraca Saldo");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });

            $('#ped').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_ped').modal('show');
                $("#labelcetak_semester").html("Cetak PED");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });

            $('#inflasi').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_inflasi').modal('show');
                $("#labelcetak_semester").html("Cetak INFLASI");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });
            $('#rekonba').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_rekonba').modal('show');
                $("#labelcetak_semester").html("Cetak RekonBA");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });
            $('#jumum').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_ju').modal('show');
                $("#labelcetak_semester").html("Cetak Jurnal Umum");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });
            $('#siskas').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_rekap_sisa_kas').modal('show');
                $("#labelcetak_semester").html("Cetak Rekap Sisa Kas");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });
            $('#mandatory').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_mandatory').modal('show');
                $("#labelcetak_semester").html("Cetak Mandatory");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });
            $('#lralo').on('click', function() {
                // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
                $('#modal_cetak_lralo').modal('show');
                $("#labelcetak_semester").html("Cetak LRALO");
                // document.getElementById('row-hidden').hidden = true; // Hide
            });
        }
        // onclick card end

        // cari skpd/org
        $('input:radio[name="inlineRadioOptions"]').change(function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd').hidden = true; // Hide
            } else if ($(this).val() == 'skpd') {
                cari_skpdbb('skpd')
                document.getElementById('baris_skpd').hidden = false; // show
            } else {
                cari_skpdbb('unit')
                document.getElementById('baris_skpd').hidden = false; // show
            }
        });
        //rekonba
        $('input:radio[name="pilihan_rekonba"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_rekonba').hidden = true; // Hide
            } else if ($(this).val() == 'skpd') {
                cari_skpdbb('skpd')
                document.getElementById('baris_skpd_rekonba').hidden = false; // show
            } else {
                cari_skpdbb('unit')
                document.getElementById('baris_skpd_rekonba').hidden = false; // show
            }
        });
        //lralo
        $('input:radio[name="pilihan_lralo"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_lralo').hidden = true; // Hide
            } else if ($(this).val() == 'skpd') {
                cari_skpdbb('skpd')
                document.getElementById('baris_skpd_lralo').hidden = false; // show
            } else {
                cari_skpdbb('unit')
                document.getElementById('baris_skpd_lralo').hidden = false; // show
            }
        });

        // cari periode neraca saldo
        $('input:radio[name="pilihanperiode"]').change(function() {
            if ($(this).val() == 'tahun') {
                document.getElementById('baris_periode1').hidden = true; // Hide
                document.getElementById('baris_periode2').hidden = true; // Hide
                document.getElementById('baris_bulan').hidden = true; // Hide
            } else if ($(this).val() == 'periode') {
                document.getElementById('baris_periode1').hidden = false; // show
                document.getElementById('baris_periode2').hidden = false; // Hide
                document.getElementById('baris_bulan').hidden = true; // Hide
            } else {
                document.getElementById('baris_periode1').hidden = true; // show
                document.getElementById('baris_periode2').hidden = true; // Hide
                document.getElementById('baris_bulan').hidden = false; // Hide
            }
        });
        // cari periode lralo
        $('input:radio[name="pilihanperiode_lralo"]').change(function() {
            if ($(this).val() == 'tahun') {
                document.getElementById('baris_periode1_lralo').hidden = true; // Hide
                document.getElementById('baris_periode2_lralo').hidden = true; // Hide
                document.getElementById('baris_bulan_lralo').hidden = true; // Hide
            } else if ($(this).val() == 'periode') {
                document.getElementById('baris_periode1_lralo').hidden = false; // show
                document.getElementById('baris_periode2_lralo').hidden = false; // Hide
                document.getElementById('baris_bulan_lralo').hidden = true; // Hide
            } else {
                document.getElementById('baris_periode1_lralo').hidden = true; // show
                document.getElementById('baris_periode2_lralo').hidden = true; // Hide
                document.getElementById('baris_bulan_lralo').hidden = false; // Hide
            }
        });

        

        function ttd_bud() {
            $.ajax({
                url: "{{ route('laporan_akuntansi.cari_ttd_bud') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#ttd_bud').empty();
                    $('#ttd_bud').append(
                        `<option value="" disabled selected>Pilih Penandatanganan</option>`);
                    $.each(data, function(index, data) {
                        $('#ttd_bud').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                    $('#ttd_bud_inflasi').empty();
                    $('#ttd_bud_inflasi').append(
                        `<option value="" disabled selected>Pilih Penandatanganan</option>`);
                    $.each(data, function(index, data) {
                        $('#ttd_bud_inflasi').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                }
            })
        }

        function ttd_kasubbid() {
            $.ajax({
                url: "{{ route('laporan_akuntansi.ttd_kasubbid') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#ttd_kasubbid').empty();
                    $('#ttd_kasubbid').append(
                        `<option value="" disabled selected>Pilih Penandatanganan</option>`);
                    $.each(data, function(index, data) {
                        $('#ttd_kasubbid').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })

                }
            })
        }

        function cari_rek1() {
            $.ajax({
                url: "{{ route('laporan_akuntansi.rek1') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#rek1').empty();
                    $('#rek1').append(
                        `<option value="" disabled selected>Pilih Rekening</option>`);
                    $.each(data, function(index, data) {
                        $('#rek1').append(
                            `<option value="${data.kd_rek1}" data-nama="${data.nm_rek1}">${data.kd_rek1} | ${data.nm_rek1}</option>`
                        );
                    })
                }
            })
        }

        function cari_skpdbb(kd_skpd, jenis) {
            $.ajax({
                url: "{{ route('laporan_akuntansi.skpd2') }}",
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
                    $('#kd_skpd_ns').empty();
                    $('#kd_skpd_ns').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_ns').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                    $('#kd_skpd_rekonba').empty();
                    $('#kd_skpd_rekonba').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_rekonba').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                    $('#kd_skpd_ju').empty();
                    $('#kd_skpd_ju').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_ju').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                    $('#kd_skpd_lralo').empty();
                    $('#kd_skpd_lralo').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_lralo').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                }
            })
        }
        $('#kd_skpd').on('select2:select', function() {
            let kd_skpd = this.value;
            cari_sub_kegiatan(kd_skpd);
        }); 

        function cari_sub_kegiatan(kd_skpd) {
            $.ajax({
                url: "{{ route('laporan_akuntansi.subkegiatan') }}",
                type: "POST",
                data: {
                    kd_skpd: kd_skpd
                }, 
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#kd_sub_kegiatan').empty();
                    $('#kd_sub_kegiatan').append(
                        `<option value="" disabled selected>Pilih Sub Kegiatan</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_sub_kegiatan').append(
                            `<option value="${data.kd_sub_kegiatan}" data-nama="${data.nm_sub_kegiatan}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                        );
                    })
                }
            })
        }
        $('#kd_sub_kegiatan').on('select2:select', function() {
            let kd_sub_kegiatan = this.value;
            cari_rek6bb(kd_sub_kegiatan);
        }); 

        function cari_rek6bb(kd_sub_kegiatan) {
            $.ajax({
                url: "{{ route('laporan_akuntansi.rek6bb') }}",
                type: "POST",
                data: {
                    kd_sub_kegiatan: kd_sub_kegiatan
                }, 
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#rek6bb').empty();
                    $('#rek6bb').append(
                        `<option value="" disabled selected>Pilih Rekening</option>`);
                    $.each(data, function(index, data) {
                        $('#rek6bb').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}">${data.kd_rek6} | ${data.nm_rek6}</option>`
                        );
                    })
                }
            })
        } 
        function cari_rek6() {
            $.ajax({
                url: "{{ route('laporan_akuntansi.rek6') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#rek6').empty();
                    $('#rek6').append(
                        `<option value="" disabled selected>Pilih Rekening</option>`);
                    $.each(data, function(index, data) {
                        $('#rek6').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}">${data.kd_rek6} | ${data.nm_rek6}</option>`
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

            let labelcetak_semester = document.getElementById('labelcetak_semester').textContent;


            // alert(labelcetak_semester)


            // SET CETAKAN
            if (labelcetak_semester == 'Cetak Buku Besar') {
                let tanggal1 = document.getElementById('tanggal1').value;
                let tanggal2 = document.getElementById('tanggal2').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
                let rek6 = document.getElementById('rek6bb').value;

                // PERINGATAN
                if (!tanggal1) {
                    alert('Tanggal Awal tidak boleh kosong!');
                    return;
                }
                if (!tanggal2) {
                    alert('Tanggal Akhir tidak boleh kosong!');
                    return;
                }
                if (!kd_skpd) {
                    alert('SKPD tidak boleh kosong!');
                    return;
                }
                if (!rek6) {
                    alert('Rekening tidak boleh kosong!');
                    return;
                }

                let url = new URL("{{ route('laporan_akuntansi.cbb') }}");
                let searchParams = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("kd_sub_kegiatan", kd_sub_kegiatan);
                searchParams.append("rek6", rek6);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");

            } else if (labelcetak_semester == 'Cetak Neraca Saldo') {
                let tanggal1_ns = document.getElementById('tanggal1_ns').value;
                let tanggal2_ns = document.getElementById('tanggal2_ns').value;
                let kd_skpd_ns = document.getElementById('kd_skpd_ns').value;
                let bulan_ns = document.getElementById('bulan_ns').value;
                let rek1 = document.getElementById('rek1').value;
                let skpdunit                 = $('input:radio[name="inlineRadioOptions"]:checked').val();
                let periodebulan             = $('input:radio[name="pilihanperiode"]:checked').val();

                if (!rek1) {
                    alert('Rekening tidak boleh kosong!');
                    return;
                }

                let url = new URL("{{ route('laporan_akuntansi.cns') }}");
                let searchParams = url.searchParams;
                searchParams.append("tanggal1_ns", tanggal1_ns);
                searchParams.append("tanggal2_ns", tanggal2_ns);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("bulan_ns", bulan_ns);
                searchParams.append("periodebulan", periodebulan);
                searchParams.append("kd_skpd_ns", kd_skpd_ns);
                searchParams.append("rek1", rek1);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");

            } else if (labelcetak_semester == 'Cetak PED') {
                let tanggal1_ped = document.getElementById('tanggal1_ped').value;
                let tanggal2_ped = document.getElementById('tanggal2_ped').value;
                let ttd_bud = document.getElementById('ttd_bud').value;
                let jns_ang = document.getElementById('jns_anggaran').value;

                if (!jns_ang) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!tanggal1_ped) {
                    alert('Tanggal Awal tidak boleh kosong!');
                    return;
                }
                if (!tanggal2_ped) {
                    alert('Tanggal Akhir tidak boleh kosong!');
                    return;
                }
                if (!ttd_bud) {
                    alert('Penandatangan tidak boleh kosong!');
                    return;
                }

                let url = new URL("{{ route('laporan_akuntansi.cped') }}");
                let searchParams = url.searchParams;
                searchParams.append("tanggal1_ped", tanggal1_ped);
                searchParams.append("tanggal2_ped", tanggal2_ped);
                searchParams.append("jns_ang", jns_ang);
                searchParams.append("ttd_bud", ttd_bud);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");

            } else if (labelcetak_semester == 'Cetak INFLASI') {
                let tanggal1_inflasi = document.getElementById('tanggal1_inflasi').value;
                let tanggal2_inflasi = document.getElementById('tanggal2_inflasi').value;
                let ttd_bud = document.getElementById('ttd_bud_inflasi').value;
                let jns_ang = document.getElementById('jns_anggaran_inflasi').value;

                if (!jns_ang) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!tanggal1_ped) {
                    alert('Tanggal Awal tidak boleh kosong!');
                    return;
                }
                if (!tanggal2_ped) {
                    alert('Tanggal Akhir tidak boleh kosong!');
                    return;
                }
                if (!ttd_bud) {
                    alert('Penandatangan tidak boleh kosong!');
                    return;
                }

                let url = new URL("{{ route('laporan_akuntansi.cinflasi') }}");
                let searchParams = url.searchParams;
                searchParams.append("tanggal1_inflasi", tanggal1_inflasi);
                searchParams.append("tanggal2_inflasi", tanggal2_inflasi);
                searchParams.append("jns_ang", jns_ang);
                searchParams.append("ttd_bud", ttd_bud);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");

            } else if (labelcetak_semester == 'Cetak RekonBA') {
                let kd_skpd = document.getElementById('kd_skpd_rekonba').value;
                let tanggal1 = document.getElementById('tanggal1_rekonba').value;
                let tanggal2 = document.getElementById('tanggal2_rekonba').value;
                let ttd = document.getElementById('ttd_kasubbid').value;
                let jns_ang = document.getElementById('jns_anggaran_rekonba').value;
                let skpdunit = $('input:radio[name="pilihan_rekonba"]:checked').val();
                let jenis_cetakan = document.getElementById('jenis_rekonba').value;


                if (!kd_skpd) {
                    alert('SKPD tidak boleh kosong!');
                    return;
                }
                if (!jns_ang) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!tanggal1) {
                    alert('Tanggal Awal tidak boleh kosong!');
                    return;
                }
                if (!tanggal2) {
                    alert('Tanggal Akhir tidak boleh kosong!');
                    return;
                }
                // if (!ttd) {
                //     alert('Penandatangan tidak boleh kosong!');
                //     return;
                // }
                if (!jenis_cetakan) {
                    alert('Jenis Cetakan tidak boleh kosong!');
                    return;
                }

                let url = new URL("{{ route('laporan_akuntansi.crekonba') }}");
                let searchParams = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("jns_ang", jns_ang);
                searchParams.append("ttd", ttd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("jenis_cetakan", jenis_cetakan);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");

            } else if (labelcetak_semester == 'Cetak Jurnal Umum') {
                let tanggal1 = document.getElementById('tanggal1_ju').value;
                let tanggal2 = document.getElementById('tanggal2_ju').value;
                let kd_skpd = document.getElementById('kd_skpd_ju').value;
                // PERINGATAN
                if (!tanggal1) {
                    alert('Tanggal Awal tidak boleh kosong!');
                    return;
                }
                if (!tanggal2) {
                    alert('Tanggal Akhir tidak boleh kosong!');
                    return;
                }
                if (!kd_skpd) {
                    alert('SKPD tidak boleh kosong!');
                    return;
                }

                let url = new URL("{{ route('laporan_akuntansi.cju') }}");
                let searchParams = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");

            } else if (labelcetak_semester == 'Cetak Rekap Sisa Kas') {
                let bulan = document.getElementById('bulan_kas').value;
                let anggaran = document.getElementById('jns_anggaran_kas').value;
                let jenis = document.getElementById('jenis_kas').value;
                // PERINGATAN
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }
                if (!anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!jenis) {
                    alert('SKPD tidak boleh kosong!');
                    return;
                }

                let url = new URL("{{ route('laporan_akuntansi.crekap_sisa_kas') }}");
                let searchParams = url.searchParams;
                searchParams.append("bulan", bulan);
                searchParams.append("anggaran", anggaran);
                searchParams.append("jenis", jenis);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");

            } else if (labelcetak_semester == 'Cetak Mandatory') {
                let bidang = document.getElementById('bidang_mandatory').value;
                let format = document.getElementById('format_mandatory').value;
                let anggaran = document.getElementById('anggaran_mandatory').value;

                let url = new URL("{{ route('laporan_akuntansi.mandatory') }}");
                let searchParams = url.searchParams;
                searchParams.append("bidang", bidang);
                searchParams.append("format", format);
                searchParams.append("anggaran", anggaran);
                window.open(url.toString(), "_blank");
            } else if (labelcetak_semester == 'Cetak LRALO') {
                let tanggal1 = document.getElementById('tanggal1_lralo').value;
                let tanggal2 = document.getElementById('tanggal2_lralo').value;
                let kd_skpd = document.getElementById('kd_skpd_lralo').value;
                let bulan = document.getElementById('bulan_lralo').value;
                let skpdunit                 = $('input:radio[name="pilihan_lralo"]:checked').val();
                let periodebulan             = $('input:radio[name="pilihanperiode_lralo"]:checked').val();


                let url = new URL("{{ route('laporan_akuntansi.clralo') }}");
                let searchParams = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("bulan", bulan);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("periodebulan", periodebulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");

            } else {
                alert('-' + jns_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
