@extends('template.app')
@section('title', 'SPM | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('spm.create') }}" id="tambah_spp_ls" class="btn btn-primary"
                        style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="spm" class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 100px;text-align:center">Nomor SPM</th>
                                        <th style="width: 50px;text-align:center">Tanggal</th>
                                        <th style="width: 150px;text-align:center">Keterangan</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_spm as $spm)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $spm->no_spm }}</td>
                                            <td>{{ \Carbon\Carbon::parse($spm->tgl_spm)->locale('id')->isoFormat('DD MMMM Y') }}
                                            </td>
                                            <td style="text-align: justify">{{ Str::limit($spm->keperluan, '20') }}</td>
                                            <td>
                                                <a href="{{ route('spm.tambah_potongan', $spm->no_spm) }}"
                                                    id="tambah_potongan" class="btn btn-secondary btn-sm"><i
                                                        class="uil-percentage"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    {{-- modal batal spp --}}
    <div id="batal_spp" class="modal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">KETERANGAN PEMBATALAN SPP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- No SPP --}}
                    <div class="mb-3 row">
                        <label for="no_spp_batal" class="col-md-2 col-form-label">No SPP</label>
                        <div class="col-md-12">
                            <input type="text" readonly class="form-control" id="no_spp_batal" name="no_spp_batal">
                            <input type="text" readonly class="form-control" id="beban_batal" name="beban_batal" hidden>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="keterangan_batal" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-12">
                            <textarea type="text" class="form-control" id="keterangan_batal" name="keterangan_batal"></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-md btn-danger" id="batal_sppls"><i
                                    class="uil-ban"></i>Batal SPP</button>
                            <button type="button" class="btn btn-md btn-warning" data-bs-dismiss="modal"><i
                                    class="fa fa-undo"></i>Keluar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#spm').DataTable();

            $('#bendahara').select2({
                dropdownParent: $('#modal_cetak'),
                theme: 'bootstrap-5'
            });

            $('#pptk').select2({
                dropdownParent: $('#modal_cetak'),
                theme: 'bootstrap-5'
            });

            $('#pa_kpa').select2({
                dropdownParent: $('#modal_cetak'),
                theme: 'bootstrap-5'
            });

            $('#ppkd').select2({
                dropdownParent: $('#modal_cetak'),
                theme: 'bootstrap-5'
            });

            $('#bendahara').on('select2:select', function() {
                let nama = $(this).find(':selected').data('nama');
                $('#nama_bendahara').val(nama);
            });

            $('#pptk').on('select2:select', function() {
                let nama = $(this).find(':selected').data('nama');
                $('#nama_pptk').val(nama);
            });

            $('#pa_kpa').on('select2:select', function() {
                let nama = $(this).find(':selected').data('nama');
                $('#nama_pa_kpa').val(nama);
            });

            $('#ppkd').on('select2:select', function() {
                let nama = $(this).find(':selected').data('nama');
                $('#nama_ppkd').val(nama);
            });

            // cetak pengantar layar
            $('.pengantar_layar').on('click', function() {
                let spasi = document.getElementById('spasi').value;
                let no_spp = document.getElementById('no_spp').value;
                let beban = document.getElementById('beban').value;
                let bendahara = document.getElementById('bendahara').value;
                let pptk = document.getElementById('pptk').value;
                let pa_kpa = document.getElementById('pa_kpa').value;
                let ppkd = document.getElementById('ppkd').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let tanpa_tanggal = document.getElementById('tanpa_tanggal').checked;
                let jenis_print = $(this).data("jenis");
                let tanpa;
                if (tanpa_tanggal == false) {
                    tanpa = 0;
                } else {
                    tanpa = 1;
                }
                if (!bendahara) {
                    alert('Bendahara Penghasilan tidak boleh kosong!');
                    return;
                }
                if (!pptk) {
                    alert("PPTK tidak boleh kosong!");
                    return;
                }
                if (!ppkd) {
                    alert("PPKD tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('sppls.cetak_pengantar_layar') }}");
                let searchParams = url.searchParams;
                searchParams.append("no_spp", no_spp);
                searchParams.append("beban", beban);
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pptk", pptk);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("ppkd", ppkd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanpa", tanpa);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });

            // cetak rincian layar
            $('.rincian_layar').on('click', function() {
                let spasi = document.getElementById('spasi').value;
                let no_spp = document.getElementById('no_spp').value;
                let beban = document.getElementById('beban').value;
                let bendahara = document.getElementById('bendahara').value;
                let pptk = document.getElementById('pptk').value;
                let pa_kpa = document.getElementById('pa_kpa').value;
                let ppkd = document.getElementById('ppkd').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let tanpa_tanggal = document.getElementById('tanpa_tanggal').checked;
                let jenis_print = $(this).data("jenis");
                let tanpa;
                if (tanpa_tanggal == false) {
                    tanpa = 0;
                } else {
                    tanpa = 1;
                }
                if (!bendahara) {
                    alert('Bendahara Penghasilan tidak boleh kosong!');
                    return;
                }
                if (!pptk) {
                    alert("PPTK tidak boleh kosong!");
                    return;
                }
                if (!ppkd) {
                    alert("PPKD tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('sppls.cetak_rincian_layar') }}");
                let searchParams = url.searchParams;
                searchParams.append("no_spp", no_spp);
                searchParams.append("beban", beban);
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pptk", pptk);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("ppkd", ppkd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanpa", tanpa);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });

            // cetak permintaan layar
            $('.permintaan_layar').on('click', function() {
                let spasi = document.getElementById('spasi').value;
                let no_spp = document.getElementById('no_spp').value;
                let beban = document.getElementById('beban').value;
                let bendahara = document.getElementById('bendahara').value;
                let pptk = document.getElementById('pptk').value;
                let pa_kpa = document.getElementById('pa_kpa').value;
                let ppkd = document.getElementById('ppkd').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let tanpa_tanggal = document.getElementById('tanpa_tanggal').checked;
                let jenis_print = $(this).data("jenis");
                let tanpa;
                if (tanpa_tanggal == false) {
                    tanpa = 0;
                } else {
                    tanpa = 1;
                }
                if (!bendahara) {
                    alert('Bendahara Penghasilan tidak boleh kosong!');
                    return;
                }
                if (!pptk) {
                    alert("PPTK tidak boleh kosong!");
                    return;
                }
                if (!ppkd) {
                    alert("PPKD tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('sppls.cetak_permintaan_layar') }}");
                let searchParams = url.searchParams;
                searchParams.append("no_spp", no_spp);
                searchParams.append("beban", beban);
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pptk", pptk);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("ppkd", ppkd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanpa", tanpa);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });

            $('.ringkasan_layar').on('click', function() {
                let spasi = document.getElementById('spasi').value;
                let no_spp = document.getElementById('no_spp').value;
                let beban = document.getElementById('beban').value;
                let bendahara = document.getElementById('bendahara').value;
                let pptk = document.getElementById('pptk').value;
                let pa_kpa = document.getElementById('pa_kpa').value;
                let ppkd = document.getElementById('ppkd').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let tanpa_tanggal = document.getElementById('tanpa_tanggal').checked;
                let jenis_print = $(this).data("jenis");
                let tanpa;
                if (tanpa_tanggal == false) {
                    tanpa = 0;
                } else {
                    tanpa = 1;
                }
                if (!bendahara) {
                    alert('Bendahara Penghasilan tidak boleh kosong!');
                    return;
                }
                if (!pptk) {
                    alert("PPTK tidak boleh kosong!");
                    return;
                }
                if (!ppkd) {
                    alert("PPKD tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('sppls.cetak_ringkasan_layar') }}");
                let searchParams = url.searchParams;
                searchParams.append("no_spp", no_spp);
                searchParams.append("beban", beban);
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pptk", pptk);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("ppkd", ppkd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanpa", tanpa);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });

            $('.pernyataan_layar').on('click', function() {
                let spasi = document.getElementById('spasi').value;
                let no_spp = document.getElementById('no_spp').value;
                let beban = document.getElementById('beban').value;
                let bendahara = document.getElementById('bendahara').value;
                let pptk = document.getElementById('pptk').value;
                let pa_kpa = document.getElementById('pa_kpa').value;
                let ppkd = document.getElementById('ppkd').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let tanpa_tanggal = document.getElementById('tanpa_tanggal').checked;
                let jenis_print = $(this).data("jenis");
                let tanpa;
                if (tanpa_tanggal == false) {
                    tanpa = 0;
                } else {
                    tanpa = 1;
                }
                if (!bendahara) {
                    alert('Bendahara Penghasilan tidak boleh kosong!');
                    return;
                }
                if (!pptk) {
                    alert("PPTK tidak boleh kosong!");
                    return;
                }
                if (!ppkd) {
                    alert("PPKD tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('sppls.cetak_pernyataan_layar') }}");
                let searchParams = url.searchParams;
                searchParams.append("no_spp", no_spp);
                searchParams.append("beban", beban);
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pptk", pptk);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("ppkd", ppkd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanpa", tanpa);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });

            $('.sptb_layar').on('click', function() {
                let spasi = document.getElementById('spasi').value;
                let no_spp = document.getElementById('no_spp').value;
                let beban = document.getElementById('beban').value;
                let bendahara = document.getElementById('bendahara').value;
                let pptk = document.getElementById('pptk').value;
                let pa_kpa = document.getElementById('pa_kpa').value;
                let ppkd = document.getElementById('ppkd').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let tanpa_tanggal = document.getElementById('tanpa_tanggal').checked;
                let jenis_print = $(this).data("jenis");
                let tanpa;
                if (tanpa_tanggal == false) {
                    tanpa = 0;
                } else {
                    tanpa = 1;
                }
                if (!bendahara) {
                    alert('Bendahara Penghasilan tidak boleh kosong!');
                    return;
                }
                if (!pptk) {
                    alert("PPTK tidak boleh kosong!");
                    return;
                }
                if (!ppkd) {
                    alert("PPKD tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('sppls.cetak_sptb_layar') }}");
                let searchParams = url.searchParams;
                searchParams.append("no_spp", no_spp);
                searchParams.append("beban", beban);
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pptk", pptk);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("ppkd", ppkd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanpa", tanpa);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });

            $('.spp_layar').on('click', function() {
                let spasi = document.getElementById('spasi').value;
                let no_spp = document.getElementById('no_spp').value;
                let beban = document.getElementById('beban').value;
                let bendahara = document.getElementById('bendahara').value;
                let pptk = document.getElementById('pptk').value;
                let pa_kpa = document.getElementById('pa_kpa').value;
                let ppkd = document.getElementById('ppkd').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let tanpa_tanggal = document.getElementById('tanpa_tanggal').checked;
                let jenis_print = $(this).data("jenis");
                let tanpa;
                if (tanpa_tanggal == false) {
                    tanpa = 0;
                } else {
                    tanpa = 1;
                }
                if (!bendahara) {
                    alert('Bendahara Penghasilan tidak boleh kosong!');
                    return;
                }
                if (!pptk) {
                    alert("PPTK tidak boleh kosong!");
                    return;
                }
                if (!ppkd) {
                    alert("PPKD tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('sppls.cetak_spp77_layar') }}");
                let searchParams = url.searchParams;
                searchParams.append("no_spp", no_spp);
                searchParams.append("beban", beban);
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pptk", pptk);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("ppkd", ppkd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanpa", tanpa);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });

            $('.rincian77_layar').on('click', function() {
                let spasi = document.getElementById('spasi').value;
                let no_spp = document.getElementById('no_spp').value;
                let beban = document.getElementById('beban').value;
                let bendahara = document.getElementById('bendahara').value;
                let pptk = document.getElementById('pptk').value;
                let pa_kpa = document.getElementById('pa_kpa').value;
                let ppkd = document.getElementById('ppkd').value;
                let kd_skpd = document.getElementById('kd_skpd').value;
                let tanpa_tanggal = document.getElementById('tanpa_tanggal').checked;
                let jenis_print = $(this).data("jenis");
                let tanpa;
                if (tanpa_tanggal == false) {
                    tanpa = 0;
                } else {
                    tanpa = 1;
                }
                if (!bendahara) {
                    alert('Bendahara Penghasilan tidak boleh kosong!');
                    return;
                }
                if (!pptk) {
                    alert("PPTK tidak boleh kosong!");
                    return;
                }
                if (!ppkd) {
                    alert("PPKD tidak boleh kosong!");
                    return;
                }
                let url = new URL("{{ route('sppls.cetak_rincian77_layar') }}");
                let searchParams = url.searchParams;
                searchParams.append("no_spp", no_spp);
                searchParams.append("beban", beban);
                searchParams.append("spasi", spasi);
                searchParams.append("bendahara", bendahara);
                searchParams.append("pptk", pptk);
                searchParams.append("pa_kpa", pa_kpa);
                searchParams.append("ppkd", ppkd);
                searchParams.append("kd_skpd", kd_skpd);
                searchParams.append("tanpa", tanpa);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            });

            $('#batal_sppls').on('click', function() {
                let no_spp = document.getElementById('no_spp_batal').value;
                let keterangan = document.getElementById('keterangan_batal').value;
                let beban = document.getElementById('beban_batal').value;
                let tanya = confirm('Anda yakin akan Membatalkan SPP: ' + no_spp + '  ?');
                if (tanya == true) {
                    if (!keterangan) {
                        alert('Keterangan harus diisi!');
                        return;
                    }
                    $.ajax({
                        url: "{{ route('sppls.batal_sppls') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            no_spp: no_spp,
                            keterangan: keterangan,
                            beban: beban
                        },
                        success: function(data) {
                            if (data.message == '1') {
                                alert('SPP Berhasil Dibatalkan');
                                window.location.href = "{{ route('sppls.index') }}";
                            } else {
                                alert('SPP Berhasil Dibatalkan');
                                return;
                            }
                        }
                    })
                }
            });
        });

        function cetak(no_spp, beban, kd_skpd) {
            $('#no_spp').val(no_spp);
            $('#beban').val(beban);
            $('#kd_skpd').val(kd_skpd);
            $('#modal_cetak').modal('show');
        }

        function batal_spp(no_spp, beban, kd_skpd) {
            $('#no_spp_batal').val(no_spp);
            $('#beban_batal').val(beban);
            $('#batal_spp').modal('show');
        }

        function deleteData(no_spp) {
            let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor SPP : ' + no_spp)
            if (tanya == true) {
                $.ajax({
                    url: "{{ route('sppls.hapus_sppls') }}",
                    type: "DELETE",
                    dataType: 'json',
                    data: {
                        no_spp: no_spp
                    },
                    success: function(data) {
                        if (data.message == '1') {
                            alert('Data berhasil dihapus!');
                            location.reload();
                        } else {
                            alert('Data gagal dihapus!');
                            location.reload();
                        }
                    }
                })
            } else {
                return false;
            }
        }
    </script>
@endsection
