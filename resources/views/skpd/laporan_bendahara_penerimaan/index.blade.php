@extends('template.app')
@section('title', 'Laporan Bendahara Penerimaan | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ 'Laporan Bendahara Penerimaan' }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ 'App' }}</a></li>
                        <li class="breadcrumb-item">{{ 'Laporan Bendahara' }}</li>
                        <li class="breadcrumb-item active">{{ 'Penerimaan ' }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lapbku">
                <div class="card-body">
                    {{ 'Buku Penerimaan dan Penyetoran' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lapspj">
                <div class="card-body">
                    {{ 'SPJ Pendapatan' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="lapsetoran">
                <div class="card-body">
                    {{ 'Cek Buku Setoran' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info collapsed-card card-outline" id="laprincian">
                <div class="card-body">
                    {{ 'BP Sub Rincian Objek' }}
                    <a class="card-block stretched-link" href="#">

                    </a>
                    <i class="fa fa-chevron-right float-end mt-2"></i>

                </div>
            </div>
        </div>
    </div>


    @include('skpd.laporan_bendahara_penerimaan.modal1')
    @include('skpd.laporan_bendahara_penerimaan.modal2')
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2-modal').select2({
                dropdownParent: $('#modal_cetak'),
                theme: 'bootstrap-5'
            });

            $('.select2-modal2').select2({
                dropdownParent: $('#modal_cetak2'),
                theme: 'bootstrap-5'
            });
        

        });
        let jenis_skpd = "{{ substr(Auth::user()->kd_skpd, 18, 4) }}";
        let jenis
        if (jenis_skpd == '0000') {
            jenis = 'skpd';
        } else {
            jenis = 'unit';
        }

        let modal

        $('#lapbku').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            $('#modal_cetak2').modal('hide');
            $("#labelcetak").html("Buku Penerimaan dan Pengeluaran");
            document.getElementById('jenisanggaran').hidden = true; // Hide
            document.getElementById('jenis1').hidden = false; // Hide
            document.getElementById('spasi1').hidden = false; // Hide
            document.getElementById('tgl_ttd1').hidden = false; // Hide
            document.getElementById('bendahara1').hidden = false; // Hide
            document.getElementById('pa_kpa1').hidden = false; // Hide
            cari_skpd(kd_skpd, jenis);
            modal = 1;
        });

        $('#lapspj').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            $('#modal_cetak2').modal('hide');
            $("#labelcetak").html("SPJ Pendapatan");
            cari_skpd(kd_skpd, jenis);
            document.getElementById('jenisanggaran').hidden = false; // Hide
            document.getElementById('jenis1').hidden = false; // Hide
            document.getElementById('spasi1').hidden = false; // Hide
            document.getElementById('tgl_ttd1').hidden = false; // Hide
            document.getElementById('bendahara1').hidden = false; // Hide
            document.getElementById('pa_kpa1').hidden = false; // Hide
            modal = 1;
        });
        // cari skpd/org
        $('#lapsetoran').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak2').modal('show');
            $('#modal_cetak').modal('hide');
            $("#labelcetak2").html("Cek Buku Setoran");
            cari_skpd2(kd_skpd, jenis);
            document.getElementById('jenisanggaran2').hidden = true; // Hide
            document.getElementById('jenis1').hidden = true; // Hide
            document.getElementById('spasi1').hidden = true; // Hide
            document.getElementById('tgl_ttd1').hidden = true; // Hide
            document.getElementById('tipe1').hidden = false; // Hide
            document.getElementById('rekening1').hidden = false; // Hide
            document.getElementById('bendahara1').hidden = true; // Hide
            document.getElementById('pa_kpa1').hidden = true; // Hide
            modal = 2;
        
        });

        $('#laprincian').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak2').modal('show');
            $('#modal_cetak').modal('hide');
            $("#labelcetak2").html("BP Sub Rincian Objek");
            cari_skpd2(kd_skpd, jenis);

            document.getElementById('jenisanggaran2').hidden = false; // Hide
            document.getElementById('jenis1').hidden = true; // Hide
            document.getElementById('spasi1').hidden = true; // Hide
            document.getElementById('tgl_ttd1').hidden = false; // Hide
            document.getElementById('tipe1').hidden = true; // Hide
            document.getElementById('rekening1').hidden = true; // Hide
            document.getElementById('bendahara1').hidden = false; // Hide
            document.getElementById('pa_kpa1').hidden = false; // Hide
            modal = 2;
    
        });

        $('input:radio[name="inlineRadioOptions"]').change(function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            if ($(this).val() == 'skpd') {
                cari_skpd(kd_skpd, 'skpd')
            } else {
                cari_skpd(kd_skpd, 'unit')
            }
        });

        function cari_skpd(kd_skpd, jenis) {
            alert(kd_skpd)
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara_penerimaan.skpd') }}",
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

        function cari_skpd2(kd_skpd, jenis) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara_penerimaan.skpd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd,
                    jenis: jenis
                },
                success: function(data) {
                    $('#kd_skpd_2').empty();
                    $('#kd_skpd_2').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd_2').append(
                            `<option value="${data.kd_skpd}" data-nama="${data.nm_skpd}">${data.kd_skpd} | ${data.nm_skpd}</option>`
                        );
                    })
                }
            })
        }

        // action skpd
        $('#kd_skpd').on('select2:select', function() {
            let kd_skpd = this.value;
            cari_bendahara(kd_skpd);
            cari_pakpa(kd_skpd);
            cari_rekening(kd_skpd);
        });

        $('#kd_skpd_2').on('select2:select', function() {
            let kd_skpd = this.value;
            cari_bendahara2(kd_skpd);
            cari_pakpa2(kd_skpd);
            cari_rekening(kd_skpd);
        });

        function cari_bendahara(kd_skpd) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara_penerimaan.bendahara') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    $('#bendahara').empty();
                    $('#bendahara').append(
                        `<option value="" disabled selected>Pilih Bendahara Penerimaan</option>`);
                    $.each(data, function(index, data) {
                        $('#bendahara').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                }
            })
        }

        function cari_bendahara2(kd_skpd) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara_penerimaan.bendahara') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    $('#bendahara_2').empty();
                    $('#bendahara_2').append(
                        `<option value="" disabled selected>Pilih Bendahara Penerimaan</option>`);
                    $.each(data, function(index, data) {
                        $('#bendahara_2').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                }
            })
        }

        function cari_pakpa(kd_skpd) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara_penerimaan.pakpa') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    $('#pa_kpa').empty();
                    $('#pa_kpa').append(
                        `<option value="" disabled selected>Pilih PA/KPA</option>`);
                    $.each(data, function(index, data) {
                        $('#pa_kpa').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                }
            })
        }

        function cari_pakpa2(kd_skpd) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara_penerimaan.pakpa') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    $('#pa_kpa_2').empty();
                    $('#pa_kpa_2').append(
                        `<option value="" disabled selected>Pilih PA/KPA</option>`);
                    $.each(data, function(index, data) {
                        $('#pa_kpa_2').append(
                            `<option value="${data.nip}" data-nama="${data.nama}">${data.nip} | ${data.nama}</option>`
                        );
                    })
                }
            })
        }

        function cari_rekening(kd_skpd) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara_penerimaan.rekening') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    $('#rekening').empty();
                    $('#rekening').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#rekening').append(
                            `<option value="${data.kd_rek6}">${data.kd_rek6} | ${data.nm_rek6}</option>`
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
            let jenis_cetak
            let jenis_print
            let spasi
            let tgl_ttd
            let bendahara
            let pa_kpa
            let kd_skpd
            let jenis_cetakan
            let tanggal1
            let tanggal2
            let jns_anggaran
            let rekening
            let tipe
            if(modal==1){
                spasi           = document.getElementById('spasi').value;
                tgl_ttd         = document.getElementById('tgl_ttd').value;
                bendahara       = document.getElementById('bendahara').value;
                pa_kpa          = document.getElementById('pa_kpa').value;
                kd_skpd         = document.getElementById('kd_skpd').value;
                jenis_print     = $(this).data("jenis");
                jenis_cetakan   = document.getElementById('jenis_cetak').value;
                jenis_cetak         = document.getElementById('labelcetak').textContent
                tanggal1        = document.getElementById('tanggal1').value;
                tanggal2        = document.getElementById('tanggal2').value;
                jns_anggaran    = document.getElementById('jns_anggaran').value;

                // alert validasi data
                if (!kd_skpd) {
                    alert('SKPD tidak boleh kosong!');
                    return;
                }
                if (!bendahara) {
                    alert('Bendahara Pengeluaran tidak boleh kosong!');
                    return;
                }
                if (!pa_kpa) {
                    alert("PA/KPA tidak boleh kosong!");
                    return;
                }
                if (!tanggal1) {
                    alert("Periode 1 tidak boleh kosong!");
                    return;
                }
                if (!tanggal2) {
                    alert("Periode 2 tidak boleh kosong!");
                    return;
                }
                if (!tgl_ttd) {
                    alert("Tanggal Penandatangan tidak boleh kosong!");
                    return;
                }
                if (jenis_cetak == 'SPJ Pendapatan') {
                    if (!jns_anggaran) {
                        alert("Jenis Anggaran tidak boleh kosong!");
                        return;
                    }
                }
            }else{
                spasi           = document.getElementById('spasi_2').value;
                tgl_ttd         = document.getElementById('tgl_ttd_2').value;
                bendahara       = document.getElementById('bendahara_2').value;
                pa_kpa          = document.getElementById('pa_kpa_2').value;
                kd_skpd         = document.getElementById('kd_skpd_2').value;
                jenis_print     = $(this).data("jenis_2");
                jenis_cetakan   = document.getElementById('jenis_cetak_2').value;
                jenis_cetak     = document.getElementById('labelcetak2').textContent;
                tanggal1        = document.getElementById('tanggal1_2').value;
                tanggal2        = document.getElementById('tanggal2_2').value;
                jns_anggaran    = document.getElementById('jns_anggaran_2').value;
                rekening    = document.getElementById('rekening').value;
                tipe        = document.getElementById('tipe').value;

                
                // alert validasi data
                    if (!kd_skpd) {
                        alert('SKPD tidak boleh kosong!');
                        return;
                    }
                    if (!bendahara) {
                        alert('Bendahara Pengeluaran tidak boleh kosong!');
                        return;
                    }
                    if (!pa_kpa) {
                        alert("PA/KPA tidak boleh kosong!");
                        return;
                    }
                    if (!tanggal1) {
                        alert("Periode 1 tidak boleh kosong!");
                        return;
                    }
                    if (!tanggal2) {
                        alert("Periode 2 tidak boleh kosong!");
                        return;
                    }
                    if (!tgl_ttd) {
                        alert("Tanggal Penandatangan tidak boleh kosong!");
                        return;
                    }

                    if (!rekening) {
                        alert("Rekening tidak boleh kosong!");
                        return;
                    }

                    if (!tipe) {
                        alert("Tipe tidak boleh kosong!");
                        return;
                    }

                    
                    
            }

            // subrincian objek
 

            if (jenis_cetak == 'Buku Penerimaan dan Pengeluaran') {
                let url = new URL("{{ route('skpd.laporan_bendahara_penerimaan.cetak_buku_penerimaan_penyetoran') }}");
                let searchParams = url.searchParams;
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_print", jenis_print);
                searchParams.append("jenis_cetakan", jenis_cetakan);
                searchParams.append("cetak", jns_cetak);

                window.open(url.toString(), "_blank");

            } else if (jenis_cetak == 'SPJ Pendapatan') {
                let url = new URL("{{ route('skpd.laporan_bendahara_penerimaan.cetak_spj_pendapatan') }}");
                let searchParams = url.searchParams;
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tgl_ttd", tgl_ttd);
                searchParams.append("jenis_print", jenis_print);
                searchParams.append("jenis_cetakan", jenis_cetakan);
                searchParams.append("jns_anggaran", jns_anggaran);
                searchParams.append("cetak", jns_cetak);

                window.open(url.toString(), "_blank");

            } else if (jenis_cetak == 'Cek Buku Setoran') {
                let url = new URL("{{ route('skpd.laporan_bendahara_penerimaan.cetak_buku_setoran') }}");
                let searchParams = url.searchParams;
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("rekening", rekening);
                searchParams.append("tipe", tipe);
                searchParams.append("jenis_print", jenis_print);
                searchParams.append("cetak", jns_cetak);

                window.open(url.toString(), "_blank");

            } else {
                alert('-' + jenis_cetak + '- Tidak ada url');

            }
        }
    </script>
@endsection
