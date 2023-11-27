@extends('template.app')
@section('title', 'CALK | SIMAKDA')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'CALK' }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ 'App' }}</a></li>
                        <li class="breadcrumb-item active">{{ 'calk' }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    CALK
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="kd_skpd" class="form-label">Pilihan SKPD / UNIT</label><br>
                            <div class=" form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pilihan_skpd" id="pilihan1_skpd"
                                    value="skpd">
                                <label class="form-check-label" for="pilihan">SKPD</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pilihan_skpd" id="pilihan2_skpd"
                                    value="unit">
                                <label class="form-check-label" for="pilihan">Unit</label>
                            </div>
                        </div>
                        <div class="col-md-6" >
                            <div id="baris_skpd">
                                <label for="kd_skpd" class="form-label">Kode SKPD</label>
                                <select class="form-control select2-multiple  @error('kd_skpd') is-invalid @enderror"
                                    style=" width: 100%;" id="kd_skpd" name="kd_skpd">
                                    <option value="" disabled selected>Silahkan Pilih</option>
                                </select>
                                @error('kd_skpd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="lampiran" class="col-md-2 col-form-label">Lampiran</label>
                            <div class="col-md-12">
                                <select class="form-control select2-multiple" name="lampiran" id="lampiran" style="width: 100%">
                                    <option value="" disabled selected>Silahkan Pilih Lampiran</option>
                                    <option value="1">KATA PENGANTAR</option>
                                    <option value="2">DAFTAR ISI</option>
                                    <option value="3">PERNYATAAN TANGGUNG JAWAB</option>
                                    <option value="4">RINGKASAN LAPORAN KEUANGAN</option>
                                    <option value="5">I. LRA</option>
                                    <option value="6">II. LO</option>
                                    <option value="7">III. NERACA</option>
                                    <option value="8">IV. LPE</option>
                                    <option value="11">BAB I PENDAHULUAN</option>
                                    <option value="16">BAB II IKHTISAR PENCAPAIAN KINERJA KEUANGAN</option>
                                    <option value="9">BAB III LRA (PENDAPATAN)</option>
                                    <option value="13">BAB III LRA (BELANJA)</option>
                                    <option value="14">BAB III LO (PENDAPATAN)</option>
                                    <option value="15">BAB III LO (BEBAN)</option>
                                    <option value="17">BAB III NERACA</option>
                                    <option value="18">BAB III LPE</option>
                                    <option value="12">BAB IV. PENJELASAN ATAS INFORMASI-INFORMASI NON KEUANGAN</option>
                                    <option value="10">BAB V PENUTUP</option>
                                    <option value="19">LAMP.I ANALISIS</option>
                                    <option value="20">LAMP.II PENJELASAN PENYAJIAN DATA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="judul" class="col-md-2 col-form-label">Pilih</label>
                            <div class="col-md-12">
                                <select class="form-control select2-multiple" style="width: 100%" id="judul"
                                    name="judul">
                                    <option value="1">Tanpa Judul</option>
                                    <option value="2">Dengan Judul</option>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="ttd_pakpa" class="form-label">PA/KPA</label>
                            <select class="form-control select2-multiple @error('ttd_pakpa') is-invalid @enderror"
                                style=" width: 100%;" id="ttd_pakpa" name="ttd_pakpa">
                                <option value="" disabled selected>Silahkan Pilihh</option>
                                <option value="-">SUTARMIDJI</option>
                            </select>
                            @error('ttd_pakpa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jenis" class="form-label">Jenis Cetakan</label>
                            <div class="col-md-12">
                                <select class="form-control select2-multiple" style="width: 100%" id="jenis"
                                    name="jenis">
                                    <option value="1">Preview</option>
                                    <option value="2">Cetak</option>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-md bku_pdf" data-jenis="pdf"
                                name="bku_pdf"> PDF</button>
                            <button type="button" class="btn btn-dark btn-md bku_layar" data-jenis="layar"
                                name="bku_layar">Layar</button>
                            <button type="button" class="btn btn-success btn-md bku_excel" data-jenis="excel"
                                name="bku_excel">Excel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    

   
    {{-- modal cetak SPJ  --}}
{{-- @include('akuntansi.modal.lrasap') --}}
@include('akuntansi.modal.lraperda')
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
            $('.select2-multiple').select2({
                placeholder: "Silahkan Pilih",
                theme: 'bootstrap-5'
            });
            document.getElementById('baris_skpd').hidden = true; // Hide
        });

        
        $('input:radio[name="pilihan_skpd"]').change(function() {

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
        function cari_skpd(jenis) {
            $.ajax({
                url: "{{ route('calk.skpd') }}",
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
        $('#kd_skpd').on('select2:select', function() {
            let kd_skpd = this.value;
            ttd(kd_skpd);
        });
        $('#kd_skpd').on('change', function() {
            let selected = $(this).find('option:selected');
            let kd_skpd = this.value;
            
            let nama = $(this).find(':selected').data('nama');
            $('#nm_skpd').val(nama);
        });

        function ttd(kd_skpd) {
            $.ajax({
                url: "{{ route('calk.ttd_pakpa') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    // console.log(data);
                    $('#ttd_pakpa').empty();
                    $('#ttd_pakpa').append(
                        `<option value="" disabled selected>Pilih Penandatanganan</option>`);
                    $.each(data, function(index, data) {
                        $('#ttd_pakpa').append(
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
            let kd_skpd                    = document.getElementById('kd_skpd').value;
            let ttd                  = document.getElementById('ttd_pakpa').value;
            let lampiran             = document.getElementById('lampiran').value;
            let judul                    = document.getElementById('judul').value;
            let jenis                    = document.getElementById('jenis').value;
            let skpdunit                 = $('input:radio[name="pilihan_skpd"]:checked').val();
            // PERINGATAN
                if (!kd_skpd) {
                    alert('SKPD tidak boleh kosong!');
                    return;
                }
                if (!lampiran) {
                    alert('Lampiran tidak boleh kosong!');
                    return;
                }
                if (!ttd) {
                    alert('Tanda Tangan tidak boleh kosong!');
                    return;
                }

            // SET CETAKAN
                let url ='';

                if (lampiran == 1 || lampiran == 2 || lampiran == 3 ) {
                    url             = new URL("{{ route('calk.cetakan') }}");
                }else if(lampiran == 4){
                    url             = new URL("{{ route('calk.cetakan5') }}");
                }else if(lampiran == 5){
                    url             = new URL("{{ route('calk.cetakan5') }}");
                }else if(lampiran == 6){
                    url             = new URL("{{ route('calk.cetakan6') }}");
                }else if(lampiran == 7){
                    url             = new URL("{{ route('calk.cetakan7') }}");
                }else{
                    alert('Cetakan Tidak Tersedia');
                    return;
                }
                let searchParams    = url.searchParams;
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("ttd", ttd);
                searchParams.append("lampiran", lampiran);
                searchParams.append("jenis", jenis);
                searchParams.append("judul", judul);
                searchParams.append("skpdunit", skpdunit);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            
        }
    </script>
@endsection
