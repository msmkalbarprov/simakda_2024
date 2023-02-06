@extends('template.app')
@section('title', 'Laporan Keuangan | SIMAKDA')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'Laporan Konsolidasi' }}</h4>

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
            <div class="card card-info collapsed-card card-outline" id="lra77">
                <div class="card-body">
                    {{ 'LRA P77' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lrasemester">
                <div class="card-body">
                    {{ 'LRA Semester' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

   
    {{-- modal cetak SPJ  --}}
{{-- @include('akuntansi.modal.lrasap') --}}
@include('akuntansi.modal.lrasemester')
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

            $('.select2').select2({
                theme: 'bootstrap-5'
            });
            // hidden
            document.getElementById('baris_skpd').hidden = true; // Hide
            document.getElementById('baris_periode1').hidden = true; // Hide
            document.getElementById('baris_periode2').hidden = true; // Hide
            document.getElementById('baris_bulan').hidden = true; // Hide
            
        });

    // onclick card start
        $('#lra77').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_semester').modal('show');
            $("#labelcetak_semester").html("Cetak LRA 77");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

        $('#lrasemester').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak_semester').modal('show');
            $("#labelcetak_semester").html("Cetak LRA Semester");
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
                document.getElementById('baris_skpd').hidden = false; // show
            } else {
                cari_skpd('unit')
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
                }
            })
        }

        


        function cari_ttd() {
            $.ajax({
                url: "{{ route('laporan_akuntansi.ttd') }}",
                type: "POST",
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
            let kd_skpd                  = document.getElementById('kd_skpd').value;
            let bulan                    = document.getElementById('bulan').value;
            let tanggal1                 = document.getElementById('tanggal1').value;
            let tanggal2                 = document.getElementById('tanggal2').value;
            let tgl_ttd                  = document.getElementById('tgl_ttd').value;
            let ttd                      = document.getElementById('ttd').value;
            let jns_anggaran             = document.getElementById('jns_anggaran').value;
            let jenis_data               = document.getElementById('jenis_data').value;
            let jns_rincian              = document.getElementById('jns_rincian').value;
            let pilihkonversi            = document.getElementById('pilihkonversi').value;
            let pilihakumulsai           = document.getElementById('pilihakumulsai').value;
            let format                   = document.getElementById('format').value;
            let labelcetak_semester      = document.getElementById('labelcetak_semester').textContent;
            let periodebulan             = $('input:radio[name="pilihanperiode"]:checked').val();
            
            // PERINGATAN
                if (!ttd) {
                    alert('Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!tgl_ttd) {
                    alert('Tanggal Tanda Tangan tidak boleh kosong!');
                    return;
                }
                if (!pilihkonversi) {
                    alert('Pilihan Konversi tidak boleh kosong!');
                    return;
                }
                if (!pilihakumulsai) {
                    alert('Pilihan Akumulasi tidak boleh kosong!');
                    return;
                }
                if (!format) {
                    alert('Format tidak boleh kosong!');
                    return;
                }
                if (!jenis_data) {
                    alert('jenis Data tidak boleh kosong!');
                    return;
                }

            // SET CETAKAN
            if (labelcetak_semester == 'Cetak LRA 77') {
                let url             = new URL("{{ route('laporan_akuntansi.konsolidasi.cetak_lra_77') }}");
                let searchParams    = url.searchParams;
                searchParams.append("format", format);
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("ttd", ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_data", jenis_data);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("pilihkonversi", pilihkonversi);
                searchParams.append("pilihakumulsai", pilihakumulsai);
                searchParams.append("jns_rincian", jns_rincian);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("periodebulan", periodebulan);
                window.open(url.toString(), "_blank");
            }else if (labelcetak_semester == 'Cetak LRA Semester') {
                let url             = new URL("{{ route('laporan_akuntansi.konsolidasi.cetak_lra_semester') }}");
                let searchParams    = url.searchParams;
                searchParams.append("format", format);
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("ttd", ttd);
                searchParams.append("bulan", bulan);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_data", jenis_data);
                searchParams.append("jenis_anggaran", jns_anggaran);
                searchParams.append("pilihkonversi", pilihkonversi);
                searchParams.append("pilihakumulsai", pilihakumulsai);
                searchParams.append("jns_rincian", jns_rincian);
                searchParams.append("cetak", jns_cetak);
                searchParams.append("periodebulan", periodebulan);
                window.open(url.toString(), "_blank");
            }else{
                alert('-' + jenis_cetak + '- Tidak ada cetakan');
            }
        }
    </script>
@endsection
