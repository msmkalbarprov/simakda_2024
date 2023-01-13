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

    {{-- modal cetak SPJ --}}
    <div id="modal_cetak" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><label for="labelcetak" id="labelcetak"></label></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- SKPD --}}
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="kd_skpd" class="form-label">Kode SKPD</label>
                            {{-- <input type="text"  class="form-control" id="kd_skpd" name="kd_skpd" value="{{ $data_skpd->kd_skpd }}" readonly> --}}
                            <select class="form-control select2-modal" style=" width: 100%;" id="kd_skpd" name="kd_skpd">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="periode1">
                            <label for="tanggal1" class="form-label">Periode</label>
                            <input type="date" id="tanggal1" name="tanggal1" class="form-control">
                        </div>
                        <div class="col-md-3" id="periode2">
                            <label for="tanggal2" class="form-label">&nbsp;</label>
                            <input type="date" id="tanggal2" name="tanggal2" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div id="bendahara1">
                                <label for="bendahara" class="form-label">Bendahara</label>
                                <select class="form-control select2-modal" style=" width: 100%;" id="bendahara"
                                    name="bendahara">
                                    <option value="" disabled selected>Silahkan Pilih</option>
                                </select>
                            </div>
                            <div id="rekening1">
                                <label for="rekening" class="form-label">Rekening</label>
                                <select class="form-control select2-modal" style=" width: 100%;" id="rekening"
                                    name="rekening">
                                    <option value="" disabled selected>Silahkan Pilih</option>
                                    @foreach ($daftar_rekening as $rekening)
                                        <option value="{{ $rekening->kd_rek6 }}">
                                            {{ $rekening->kd_rek6 }} | {{ $rekening->nm_rek6 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- PA/KPA --}}
                        <div class="col-md-6">
                            <div id="tgl_ttd1">
                                <label for="pa_kpa" class="form-label">Tanggal TTD</label>
                                <input type="date" id="tgl_ttd" name="tgl_ttd" class="form-control">
                            </div>
                            <div id="tipe1">
                                <label for="tipe" class="form-label">Data</label>
                                <select class="form-control select2-modal" style=" width: 100%;" id="tipe"
                                    name="tipe">
                                    <option value="1" selected>Setoran</option>
                                    <option value="2">Penerimaan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Bendahara --}}
                    <div class="mb-3 row">
                        <div class="col-md-6" id="pa_kpa1">
                            <label for="pa_kpa" class="form-label">PA/KPA</label>
                            <select class="form-control select2-modal" style=" width: 100%;" id="pa_kpa"
                                name="pa_kpa">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>

                        <div class="col-md-4" id="jenis1">
                            <label for="jenis_cetak" class="form-label">Jenis</label>
                            <select name="jenis_cetak" class="form-control select2-modal" id="jenis_cetak">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                <option value="org">Organisasi</option>
                                <option value="skpd">SKPD</option>
                            </select>
                        </div>

                        <div class="col-md-2" id="spasi1">
                            <label for="spasi" class="form-label">Spasi</label>
                            <input type="number" value="1" min="1" class="form-control" id="spasi"
                                name="spasi">
                        </div>

                        <div class="col-md-6" id="rekening2">
                            <label for="rekening3" class="form-label">Rekening</label>
                            <select class="form-control select2-modal" style=" width: 100%;" id="rekening3"
                                name="rekening3">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row" id="jenisanggaran">
                        <div class="col-md-6">
                            <label for="jns_anggaran" class="form-label">Jenis Anggaran</label>
                            <select name="jns_anggaran" class="form-control select2-modal" id="jns_anggaran">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                @foreach ($jns_anggaran as $anggaran)
                                    <option value="{{ $anggaran->kode }}" data-nama="{{ $anggaran->nama }}">
                                        {{ $anggaran->kode }} | {{ $anggaran->nama }}
                                    </option>
                                @endforeach
                            </select>
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
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- modal cetak SPJ  --}}

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

            $('.select2-modal').select2({
                dropdownParent: $('#modal_cetak'),
                theme: 'bootstrap-5'
            });

            // $('#bendahara').select2({
            //     dropdownParent: $('#modal_cetak'),
            //     theme: 'bootstrap-5'
            // });

            // $('#kd_skpd').select2({
            //     dropdownParent: $('#modal_cetak'),
            //     theme: 'bootstrap-5'
            // });

            // $('#pa_kpa').select2({
            //     dropdownParent: $('#modal_cetak'),
            //     theme: 'bootstrap-5'
            // });
            // $('#jenis_cetak').select2({
            //     dropdownParent: $('#modal_cetak'),
            //     theme: 'bootstrap-5'
            // });
            // $('#rekening').select2({
            //     dropdownParent: $('#modal_cetak'),
            //     theme: 'bootstrap-5'
            // });
            // $('#jns_anggaran').select2({
            //     dropdownParent: $('#modal_cetak'),
            //     theme: 'bootstrap-5'
            // });
        });

        // onclick card
        $('#lapbku').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            $("#labelcetak").html("Buku Penerimaan dan Pengeluaran");
            document.getElementById('jenisanggaran').hidden = true; // Hide
            document.getElementById('jenis1').hidden = false; // Hide
            document.getElementById('spasi1').hidden = false; // Hide
            document.getElementById('tgl_ttd1').hidden = false; // Hide
            document.getElementById('tipe1').hidden = true; // Hide
            document.getElementById('rekening1').hidden = true; // Hide
            document.getElementById('bendahara1').hidden = false; // Hide
            document.getElementById('pa_kpa1').hidden = false; // Hide
            document.getElementById('rekening2').hidden = true; // Hide
            cari_skpd(kd_skpd, 'unit');
        });

        $('#lapspj').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            $("#labelcetak").html("SPJ Pendapatan");
            cari_skpd(kd_skpd, 'unit');
            document.getElementById('jenisanggaran').hidden = false; // Hide
            document.getElementById('jenis1').hidden = false; // Hide
            document.getElementById('spasi1').hidden = false; // Hide
            document.getElementById('tgl_ttd1').hidden = false; // Hide
            document.getElementById('tipe1').hidden = true; // Hide
            document.getElementById('rekening1').hidden = true; // Hide
            document.getElementById('bendahara1').hidden = false; // Hide
            document.getElementById('pa_kpa1').hidden = false; // Hide
            document.getElementById('rekening2').hidden = true; // Hide
        });
        // cari skpd/org
        $('#lapsetoran').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            $("#labelcetak").html("Cek Buku Setoran");
            cari_skpd(kd_skpd, 'unit');
            document.getElementById('jenisanggaran').hidden = true; // Hide
            document.getElementById('jenis1').hidden = true; // Hide
            document.getElementById('spasi1').hidden = true; // Hide
            document.getElementById('tgl_ttd1').hidden = true; // Hide
            document.getElementById('tipe1').hidden = false; // Hide
            document.getElementById('rekening1').hidden = false; // Hide
            document.getElementById('bendahara1').hidden = true; // Hide
            document.getElementById('pa_kpa1').hidden = true; // Hide
            document.getElementById('rekening2').hidden = true; // Hide
        });

        $('#laprincian').on('click', function() {
            let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            $('#modal_cetak').modal('show');
            $("#labelcetak").html("BP Sub Rincian Objek");
            cari_skpd(kd_skpd, 'unit');
            document.getElementById('jenisanggaran').hidden = false; // Hide
            document.getElementById('jenis1').hidden = true; // Hide
            document.getElementById('spasi1').hidden = true; // Hide
            document.getElementById('tgl_ttd1').hidden = false; // Hide
            document.getElementById('tipe1').hidden = true; // Hide
            document.getElementById('rekening1').hidden = true; // Hide
            document.getElementById('bendahara1').hidden = false; // Hide
            document.getElementById('pa_kpa1').hidden = false; // Hide
            document.getElementById('rekening2').hidden = false; // Hide
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
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara.skpd') }}",
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
                    $('#kd_skpd2').empty();
                    $('#kd_skpd2').append(
                        `<option value="" disabled selected>Pilih SKPD</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_skpd2').append(
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

        function cari_rekening(kd_skpd) {
            $.ajax({
                url: "{{ route('skpd.laporan_bendahara_penerimaan.rekening') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    $('#rekening3').empty();
                    $('#rekening3').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#rekening3').append(
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
            let spasi = document.getElementById('spasi').value;
            let tgl_ttd = document.getElementById('tgl_ttd').value;
            let bendahara = document.getElementById('bendahara').value;
            let pa_kpa = document.getElementById('pa_kpa').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let jenis_print = $(this).data("jenis");
            let jenis_cetakan = document.getElementById('jenis_cetak').value;
            let jenis_cetak = document.getElementById('labelcetak').textContent
            let tanggal1 = document.getElementById('tanggal1').value;
            let tanggal2 = document.getElementById('tanggal2').value;
            let jns_anggaran = document.getElementById('jns_anggaran').value;
            let rekening = document.getElementById('rekening').value;
            let tipe = document.getElementById('tipe').value;

            // subrincian objek
            if (jenis_cetak == 'Cek Buku Setoran') {
                if (!rekening) {
                    alert("Rekening tidak boleh kosong!");
                    return;
                }
            } else {
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
            }

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
                let url = new URL("{{ route('skpd.laporan_bendahara_penerimaan.cetak_spj_pendapatan') }}");
                let searchParams = url.searchParams;
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanggal1", tanggal1);
                searchParams.append("tanggal2", tanggal2);
                searchParams.append("rekening", rekening);
                searchParams.append("tipe", tipe);
                searchParams.append("cetak", jns_cetak);

                window.open(url.toString(), "_blank");

            } else {
                alert('-' + jenis_cetak + '- Tidak ada url');

            }
        }
    </script>
@endsection
