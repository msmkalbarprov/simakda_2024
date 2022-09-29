<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#spp_ls').DataTable();
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
