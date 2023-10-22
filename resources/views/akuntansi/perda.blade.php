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
            <div class="card card-info collapsed-card card-outline" id="i1">
                <div class="card-body">
                    {{ 'PERDA LAMP I.1' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="i1_ringkasan">
                <div class="card-body">
                    {{ 'PERDA LAMP I.1 RINGKASAN' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="i2">
                <div class="card-body">
                    {{ 'PERDA LAMP 1.2' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="i3_rincian">
                <div class="card-body">
                    {{ 'PERDA LAMP 1.3 RINCIAN' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="i4_urusan">
                <div class="card-body">
                    {{ 'PERDA LAMP 1.4 URUSAN' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="i6_piutang">
                <div class="card-body">
                    {{ 'PERDA LAMP 1.6 PIUTANG' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="i8_aset_tetap">
                <div class="card-body">
                    {{ 'PERDA LAMP 1.8 ASET TETAP' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="d1">
                <div class="card-body">
                    {{ 'PERDA LAMP D 1 KESELARASAN' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="d3">
                <div class="card-body">
                    {{ 'PERDA LAMP D 3' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="c_3">
                <div class="card-body">
                    {{ 'PERDA LAMP C 3' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

@include('akuntansi.modal.lraperda')
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".select_i4_urusan").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_i4_urusan .modal-content'),
                
            });

            $(".select_i1").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_i1 .modal-content'),
                
            });

            $(".select_i1_ringkasan").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_i1_ringkasan .modal-content'),
                
            });

            $(".select_i2").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_i2 .modal-content'),
                
            });  

            $(".select_i3_rincian").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_i3_rincian .modal-content'),
                
            });  

            $(".select_i6_Piutang").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_i6_piutang .modal-content'),
                
            }); 

            $(".select_i8_aset_tetap").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_i8_aset_tetap .modal-content'),
                
            });    

            $(".select_d1").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_d1 .modal-content'),
                
            });   

            $(".select_d3").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_d3 .modal-content'),
                
            });    


            // hidden
            document.getElementById('baris_skpd_i2').hidden = true; // Hide 
            // hidden
            document.getElementById('baris_skpd_i3_rincian').hidden = true; // Hide        
        });


    // onclick card start
        $('#i4_urusan').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_i4_urusan').modal('show');
            $("#labelcetak_semester").html("Cetak I.4 URUSAN");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
        $('#i1').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_i1').modal('show');
            $("#labelcetak_semester").html("Cetak I.1");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
        $('#i1_ringkasan').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_i1_ringkasan').modal('show');
            $("#labelcetak_semester").html("Cetak I.1 Ringkasan");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
        $('#i2').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_i2').modal('show');
            $("#labelcetak_semester").html("Cetak I.2");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#i3_rincian').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_i3_rincian').modal('show');
            $("#labelcetak_semester").html("Cetak I.3 Rincian");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#i6_piutang').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_i6_piutang').modal('show');
            $("#labelcetak_semester").html("Cetak I.6 Piutang");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#i8_aset_tetap').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_i8_aset_tetap').modal('show');
            $("#labelcetak_semester").html("Cetak I.8 Aset Tetap");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#d1').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_d1').modal('show');
            $("#labelcetak_semester").html("Cetak D1 Keselarasan");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#d3').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_d3').modal('show');
            $("#labelcetak_semester").html("Cetak D3");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });
    //onclick card end

    //radio
        $('input:radio[name="pilihan_i2"]').change(function() {

            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'keseluruhan') {
                document.getElementById('baris_skpd_i2').hidden = true; // Hide
            }else if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_i2').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_i2').hidden = false; // show
            }
        });

        $('input:radio[name="pilihan_i3_rincian"]').change(function() {

            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'skpd') {
                cari_skpd('skpd')
                document.getElementById('baris_skpd_i3_rincian').hidden = false; // show
            } else {
                cari_skpd('unit')
                document.getElementById('baris_skpd_i3_rincian').hidden = false; // show
            }
        });
    //endradio

        

        function cari_skpd(jenis) {
            $.ajax({
                url: "{{ route('laporan_akuntansi.skpd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    jenis: jenis
                },
                success: function(data) {
                    $('#kd_skpd_i2').empty();
                    $('#kd_skpd_i2').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_i2').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })

                    $('#kd_skpd_i3_rincian').empty();
                    $('#kd_skpd_i3_rincian').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_i3_rincian').append(
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

            let labelcetak_semester      = document.getElementById('labelcetak_semester').textContent;

            // SET CETAKAN
            if (labelcetak_semester == 'Cetak I.4 URUSAN') {
                // GET DATA
                let bulan                    = document.getElementById('bulan').value;
                let tgl_ttd                  = document.getElementById('tgl_ttd').value;
                let jns_anggaran             = document.getElementById('jns_anggaran').value;
                let jenis                    = document.getElementById('jenis').value;
                // alert(labelcetak_semester)
                // PERINGATAN
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
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
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_i4_urusan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("bulan", bulan);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis", jenis);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak I.1') {
                // GET DATA
                let tgl_ttd                  = document.getElementById('tgl_ttd_i1').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_i1').value;
                // alert(labelcetak_semester)
                // PERINGATAN
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_i1') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak I.1 Ringkasan') {
                // GET DATA
                let tgl_ttd                  = document.getElementById('tgl_ttd_i1_ringkasan').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_i1_ringkasan').value;
                let bulan                    = document.getElementById('bulan_i1_ringkasan').value;
                // alert(labelcetak_semester)
                // PERINGATAN
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_i1_ringkasan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak I.2') {
                // GET DATA
                let kd_skpd                  = document.getElementById('kd_skpd_i2').value;
                let tgl_ttd                  = document.getElementById('tgl_ttd_i2').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_i2').value;
                let bulan                    = document.getElementById('bulan_i2').value;
                let skpdunit                 = $('input:radio[name="pilihan_i2"]:checked').val();
                // alert(labelcetak_semester)
                // PERINGATAN
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }
                if (!kd_skpd) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_i2') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("skpdunit", skpdunit); 
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak I.3 Rincian') {
                // GET DATA
                let kd_skpd                  = document.getElementById('kd_skpd_i3_rincian').value;
                let tgl_ttd                  = document.getElementById('tgl_ttd_i3_rincian').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_i3_rincian').value;
                let bulan                    = document.getElementById('bulan_i3_rincian').value;
                let panjang_data                    = document.getElementById('panjang_data_i3_rincian').value;
                let skpdunit                 = $('input:radio[name="pilihan_i3_rincian"]:checked').val();
                // alert(labelcetak_semester)
                // PERINGATAN
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }
                if (!kd_skpd) {
                    alert('SKPD tidak boleh kosong!');
                    return;
                }
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_i3_rincian') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("skpdunit", skpdunit); 
                searchParams.append("panjang_data", panjang_data); 
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak I.6 Piutang') {
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_i6_piutang') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak I.8 Aset Tetap') {
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_i8_aset_tetap') }}");
                let searchParams    = url.searchParams;
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak D1 Keselarasan') {
                // GET DATA
                let tgl_ttd                  = document.getElementById('tgl_ttd_d1').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_d1').value;
                let bulan                    = document.getElementById('bulan_d1').value;
                // alert(labelcetak_semester)
                // PERINGATAN
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_d1_keselarasan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else if (labelcetak_semester == 'Cetak D3') {
                // GET DATA
                let tgl_ttd                  = document.getElementById('tgl_ttd_d3').value;
                let jns_anggaran             = document.getElementById('jns_anggaran_d3').value;
                // alert(labelcetak_semester)
                // PERINGATAN
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!jns_anggaran) {
                    alert('Jenis Anggaran tidak boleh kosong!');
                    return;
                }
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_d3') }}");
                let searchParams    = url.searchParams;
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else{
                alert('-' + jns_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
