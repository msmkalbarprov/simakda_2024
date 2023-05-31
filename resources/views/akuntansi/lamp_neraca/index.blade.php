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

            $(".select_lamp_aset").select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modal_cetak_lamp_aset .modal-content'),
                
            });
            
        });


    // onclick card start
        $('#lamp_aset').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_lamp_aset').modal('show');
            $("#labelcetak_semester").html("Cetak Lampiran Aset");
            // document.getElementById('row-hidden').hidden = true; // Hide
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

        function cari_skpdbb(kd_skpd, jenis) {
            $.ajax({
                url: "{{ route('laporan_akuntansi.skpd') }}",
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
            let bulan                    = document.getElementById('bulan').value;
            let tgl_ttd                  = document.getElementById('tgl_ttd').value;
            let jns_anggaran             = document.getElementById('jns_anggaran').value;
            let jenis                    = document.getElementById('jenis').value;
            let labelcetak_semester      = document.getElementById('labelcetak_semester').textContent;
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

            // SET CETAKAN
            if (labelcetak_semester == 'Cetak I.4 URUSAN') {
                let url             = new URL("{{ route('laporan_akuntansi.perda.cetak_i4_urusan') }}");
                let searchParams    = url.searchParams;
                searchParams.append("bulan", bulan);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis", jenis);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
            }else{
                alert('-' + jns_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
