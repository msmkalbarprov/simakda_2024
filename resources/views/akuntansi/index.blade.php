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
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lkonsol">
                <div class="card-body">
                    {{ 'Laporan Konsolidasi' }}
                    <a class="card-block stretched-link" href="{{ route('laporan_akuntansi.konsolidasi.konsolidasi') }}">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lkeu">
                <div class="card-body">
                    {{ 'Laporan Keuangan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
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
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lrba">
                <div class="card-body">
                    {{ 'Rekon BA' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
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
    </div>

@include('akuntansi.modal.bukubesar')
@include('akuntansi.modal.neraca_saldo')
@include('akuntansi.modal.ped')
@include('akuntansi.modal.inflasi')
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            cari_rek6();
            cari_skpdbb();
            cari_rek1();
            ttd_bud();

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
            // hidden
            document.getElementById('baris_skpd').hidden = true; // Hide
            document.getElementById('baris_periode1').hidden = true; // Hide
            document.getElementById('baris_periode2').hidden = true; // Hide
            document.getElementById('baris_bulan').hidden = true; // Hide

            

        });

        let jenis_skpd = "{{ substr(Auth::user()->kd_skpd, 18, 4) }}";
        let jenis
        if (jenis_skpd == '0000') {
            jenis = 'skpd';
        } else {
            jenis = 'unit';
        }

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

        // onclick card end

        // cari skpd/org
        $('input:radio[name="inlineRadioOptions"]').change(function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpdbb('skpd')
                document.getElementById('baris_skpd').hidden = false; // show
            } else {
                cari_skpdbb('unit')
                document.getElementById('baris_skpd').hidden = false; // show
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
            

            // SET CETAKAN
            if (labelcetak_semester == 'Cetak Buku Besar') {
                let tanggal1                    = document.getElementById('tanggal1').value;
                let tanggal2                    = document.getElementById('tanggal2').value;
                let kd_skpd             = document.getElementById('kd_skpd').value;
                let rek6                    = document.getElementById('rek6').value;

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

                let url             = new URL("{{ route('laporan_akuntansi.cbb') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("rek6", rek6);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak Neraca Saldo') {
                let tanggal1_ns                    = document.getElementById('tanggal1_ns').value;
                let tanggal2_ns                    = document.getElementById('tanggal2_ns').value;
                let kd_skpd_ns             = document.getElementById('kd_skpd_ns').value;
                let bulan_ns             = document.getElementById('bulan_ns').value;
                let rek1                    = document.getElementById('rek1').value;

                if (!rek1) {
                    alert('Rekening tidak boleh kosong!');
                    return;
                }

                let url             = new URL("{{ route('laporan_akuntansi.cns') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tanggal1_ns", tanggal1_ns);
                searchParams.append("tanggal2_ns", tanggal2_ns);
                searchParams.append("bulan_ns", bulan_ns);
                searchParams.append("kd_skpd_ns", kd_skpd_ns);
                searchParams.append("rek1", rek1);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak PED') {
                let tanggal1_ped                    = document.getElementById('tanggal1_ped').value;
                let tanggal2_ped                    = document.getElementById('tanggal2_ped').value;
                let ttd_bud             = document.getElementById('ttd_bud').value;
                let jns_ang             = document.getElementById('jns_anggaran').value;

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

                let url             = new URL("{{ route('laporan_akuntansi.cped') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tanggal1_ped", tanggal1_ped);
                searchParams.append("tanggal2_ped", tanggal2_ped);
                searchParams.append("jns_ang", jns_ang);
                searchParams.append("ttd_bud", ttd_bud);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak INFLASI') {
                let tanggal1_inflasi                    = document.getElementById('tanggal1_inflasi').value;
                let tanggal2_inflasi                    = document.getElementById('tanggal2_inflasi').value;
                let ttd_bud             = document.getElementById('ttd_bud_inflasi').value;
                let jns_ang             = document.getElementById('jns_anggaran_inflasi').value;

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

                let url             = new URL("{{ route('laporan_akuntansi.cinflasi') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tanggal1_inflasi", tanggal1_inflasi);
                searchParams.append("tanggal2_inflasi", tanggal2_inflasi);
                searchParams.append("jns_ang", jns_ang);
                searchParams.append("ttd_bud", ttd_bud);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else{
                alert('-' + jns_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
