<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2-realisasi_pendapatan').select2({
            dropdownParent: $('#modal_realisasi_pendapatan .modal-content'),
            theme: 'bootstrap-5'
        });

        $('.select2-pembantu_penerimaan').select2({
            dropdownParent: $('#modal_pembantu_penerimaan .modal-content'),
            theme: 'bootstrap-5'
        });

        $('.select2-bku').select2({
            dropdownParent: $('#modal_bku .modal-content'),
            theme: 'bootstrap-5'
        });

        $('.select2-pajak_daerah').select2({
            dropdownParent: $('#modal_pajak_daerah .modal-content'),
            theme: 'bootstrap-5'
        });

        $('.select2-rekap_gaji').select2({
            dropdownParent: $('#modal_rekap_gaji .modal-content'),
            theme: 'bootstrap-5'
        });

        // CETAKAN REALISASI PENDAPATAN
        $('#pilih_skpd_realisasi_pendapatan').hide();
        $('#pilih_unit_realisasi_pendapatan').hide();

        $('#pilihan_keseluruhan_realisasi_pendapatan').on('click', function() {
            $('#kd_skpd_realisasi_pendapatan').val(null).change();
            $('#nm_skpd_realisasi_pendapatan').val(null);
            $('#kd_unit_realisasi_pendapatan').val(null).change();
            $('#nm_unit_realisasi_pendapatan').val(null);
            $('#pilih_unit_realisasi_pendapatan').hide();
            $('#pilih_skpd_realisasi_pendapatan').hide();
        });

        $('#pilihan_skpd_realisasi_pendapatan').on('click', function() {
            $('#kd_skpd_realisasi_pendapatan').val(null).change();
            $('#nm_skpd_realisasi_pendapatan').val(null);
            $('#pilih_unit_realisasi_pendapatan').hide();
            $('#pilih_skpd_realisasi_pendapatan').show();
        });

        $('#pilihan_unit_realisasi_pendapatan').on('click', function() {
            $('#kd_unit_realisasi_pendapatan').val(null).change();
            $('#nm_unit_realisasi_pendapatan').val(null);
            $('#pilih_skpd_realisasi_pendapatan').hide();
            $('#pilih_unit_realisasi_pendapatan').show();
        });

        $('#kd_skpd_realisasi_pendapatan').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            $('#nm_skpd_realisasi_pendapatan').val(nama);
        });

        $('#kd_unit_realisasi_pendapatan').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            $('#nm_unit_realisasi_pendapatan').val(nama);
        });

        $('#realisasi_pendapatan').on('click', function() {
            $('#modal_realisasi_pendapatan').modal('show');
        });

        $('.cetak_realisasi_pendapatan').on('click', function() {
            let keseluruhan = document.getElementById('pilihan_keseluruhan_realisasi_pendapatan')
                .checked;
            let skpd = document.getElementById('pilihan_skpd_realisasi_pendapatan').checked;
            let unit = document.getElementById('pilihan_unit_realisasi_pendapatan').checked;

            if (keseluruhan == false) {
                if (skpd == false) {
                    if (unit == false) {
                        alert('Silahkan Pilih Keseluruhan, SKPD atau Unit!');
                        return;
                    }
                }
            }

            let kd_skpd = document.getElementById('kd_skpd_realisasi_pendapatan').value;
            let kd_unit = document.getElementById('kd_unit_realisasi_pendapatan').value;
            let periode = document.getElementById('periode_realisasi_pendapatan').value;
            let anggaran = document.getElementById('anggaran_realisasi_pendapatan').value;
            let jenis = document.getElementById('jenis_realisasi_pendapatan').value;
            let ttd = document.getElementById('ttd_realisasi_pendapatan').value;
            let tgl_ttd = document.getElementById('tgl_ttd_realisasi_pendapatan').value;
            let jenis_print = $(this).data("jenis");

            if (skpd) {
                if (!kd_skpd) {
                    alert('Silahkan Pilih SKPD!');
                    return;
                }
            }
            if (unit) {
                if (!kd_unit) {
                    alert('Silahkan Pilih Unit!');
                    return;
                }
            }
            let pilihan = '';
            if (keseluruhan) {
                pilihan = '1';
            } else if (skpd) {
                pilihan = '2';
            } else if (unit) {
                pilihan = '3';
            }

            if (!periode) {
                alert('Periode tidak boleh kosong!');
                return;
            }
            if (!anggaran) {
                alert("Anggaran tidak boleh kosong!");
                return;
            }
            if (!jenis) {
                alert("Jenis tidak boleh kosong!");
                return;
            }
            let url = new URL("{{ route('laporan_bendahara_umum.realisasi_pendapatan') }}");
            let searchParams = url.searchParams;
            searchParams.append("pilihan", pilihan);
            searchParams.append("periode", periode);
            searchParams.append("anggaran", anggaran);
            searchParams.append("jenis", jenis);
            searchParams.append("ttd", ttd);
            searchParams.append("tgl_ttd", tgl_ttd);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("kd_unit", kd_unit);
            searchParams.append("jenis_print", jenis_print);
            window.open(url.toString(), "_blank");
        });
        // CETAKAN REALISASI PENDAPATAN

        // CETAKAN BUKU KAS PEMBANTU PENERIMAAN
        $('#pilih_tgl_pembantu_penerimaan').hide();
        $('#pilih_periode_pembantu_penerimaan').hide();

        $('#pilihan_tgl_pembantu_penerimaan').on('click', function() {
            $('#tgl_pembantu_penerimaan').val(null);
            $('#pilih_periode_pembantu_penerimaan').hide();
            $('#pilih_tgl_pembantu_penerimaan').show();
        });

        $('#pilihan_periode_pembantu_penerimaan').on('click', function() {
            $('#periode1_pembantu_penerimaan').val(null);
            $('#periode2_pembantu_penerimaan').val(null);
            $('#pilih_tgl_pembantu_penerimaan').hide();
            $('#pilih_periode_pembantu_penerimaan').show();
        });

        $('#pembantu_penerimaan').on('click', function() {
            $('#modal_pembantu_penerimaan').modal('show');
        });

        $('.cetak_pembantu_penerimaan').on('click', function() {
            let pilih_tgl = document.getElementById('pilihan_tgl_pembantu_penerimaan').checked;
            let pilih_periode = document.getElementById('pilihan_periode_pembantu_penerimaan').checked;

            if (pilih_tgl == false) {
                if (pilih_periode == false) {
                    alert('Silahkan Pilih Tanggal atau Periode!');
                    return;
                }
            }

            let tgl = document.getElementById('tgl_pembantu_penerimaan').value;
            let periode1 = document.getElementById('periode1_pembantu_penerimaan').value;
            let periode2 = document.getElementById('periode2_pembantu_penerimaan').value;
            let halaman = document.getElementById('halaman_pembantu_penerimaan').value;
            let spasi = document.getElementById('spasi_pembantu_penerimaan').value;
            let ttd = document.getElementById('ttd_pembantu_penerimaan').value;
            let jenis_print = $(this).data("jenis");

            if (pilih_tgl) {
                if (!tgl) {
                    alert('Silahkan Pilih Tanggal!');
                    return;
                }
            }
            if (pilih_periode) {
                if (!periode1 || !periode2) {
                    alert('Silahkan Pilih Periode!');
                    return;
                }
            }

            let pilihan = '';
            if (pilih_tgl) {
                pilihan = '1';
            } else if (pilih_periode) {
                pilihan = '2';
            }

            let url = new URL("{{ route('laporan_bendahara_umum.buku_kas_pembantu_penerimaan') }}");
            let searchParams = url.searchParams;
            searchParams.append("pilihan", pilihan);
            searchParams.append("periode1", periode1);
            searchParams.append("periode2", periode2);
            searchParams.append("tgl", tgl);
            searchParams.append("halaman", halaman);
            searchParams.append("spasi", spasi);
            searchParams.append("ttd", ttd);
            searchParams.append("jenis_print", jenis_print);
            window.open(url.toString(), "_blank");
        });
        // CETAKAN BUKU KAS PEMBANTU PENERIMAAN

        // CETAKAN BKU IX
        $('#pilih_tgl_bku').hide();
        $('#pilih_periode_bku').hide();

        $('#pilihan_tgl_bku').on('click', function() {
            $('#tgl_bku').val(null);
            $('#pilih_periode_bku').hide();
            $('#pilih_tgl_bku').show();
        });

        $('#pilihan_periode_bku').on('click', function() {
            $('#periode1_bku').val(null);
            $('#periode2_bku').val(null);
            $('#pilih_tgl_bku').hide();
            $('#pilih_periode_bku').show();
        });

        $('#bku').on('click', function() {
            $('#modal_bku').modal('show');
        });

        $('.cetak_bku').on('click', function() {
            let pilih_tgl = document.getElementById('pilihan_tgl_bku').checked;
            let pilih_periode = document.getElementById('pilihan_periode_bku').checked;

            if (pilih_tgl == false) {
                if (pilih_periode == false) {
                    alert('Silahkan Pilih Tanggal atau Periode!');
                    return;
                }
            }

            let tgl = document.getElementById('tgl_bku').value;
            let periode1 = document.getElementById('periode1_bku').value;
            let periode2 = document.getElementById('periode2_bku').value;
            let halaman = document.getElementById('halaman_bku').value;
            let no_urut = document.getElementById('no_urut_bku').value;
            let jenis = document.getElementById('jenis_bku').value;
            let ttd = document.getElementById('ttd_bku').value;
            let jenis_print = $(this).data("jenis");

            if (pilih_tgl) {
                if (!tgl) {
                    alert('Silahkan Pilih Tanggal!');
                    return;
                }
            }
            if (pilih_periode) {
                if (!periode1 || !periode2) {
                    alert('Silahkan Pilih Periode!');
                    return;
                }
            }

            if (!jenis) {
                alert('Silahkan Pilih Jenis!');
                return;
            }

            let pilihan = '';
            if (pilih_tgl) {
                pilihan = '1';
            } else if (pilih_periode) {
                pilihan = '2';
            }

            if (pilihan == '1') {
                if (jenis == '2' || jenis == '3') {
                    alert("Maaf Untuk Jenis Tersebut Tidak Ada");
                    return;
                }
            }

            if (pilihan == '2') {
                if (jenis == '4') {
                    alert("Maaf Untuk Jenis Tersebut Tidak Ada");
                    return;
                }
            }

            if (jenis == '1') {
                let url = new URL("{{ route('laporan_bendahara_umum.bku_tanpa_tanggal') }}");
                let searchParams = url.searchParams;
                searchParams.append("pilihan", pilihan);
                searchParams.append("periode1", periode1);
                searchParams.append("periode2", periode2);
                searchParams.append("tgl", tgl);
                searchParams.append("halaman", halaman);
                searchParams.append("no_urut", no_urut);
                searchParams.append("ttd", ttd);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            }
            if (jenis == '2') {
                let url = new URL("{{ route('laporan_bendahara_umum.bku_dengan_tanggal') }}");
                let searchParams = url.searchParams;
                searchParams.append("pilihan", pilihan);
                searchParams.append("periode1", periode1);
                searchParams.append("periode2", periode2);
                searchParams.append("halaman", halaman);
                searchParams.append("no_urut", no_urut);
                searchParams.append("ttd", ttd);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            }
            if (jenis == '3') {
                let url = new URL("{{ route('laporan_bendahara_umum.bku_tanpa_blud') }}");
                let searchParams = url.searchParams;
                searchParams.append("pilihan", pilihan);
                searchParams.append("periode1", periode1);
                searchParams.append("periode2", periode2);
                searchParams.append("halaman", halaman);
                searchParams.append("no_urut", no_urut);
                searchParams.append("ttd", ttd);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            }
            if (jenis == '4') {
                let url = new URL("{{ route('laporan_bendahara_umum.bku_rincian') }}");
                let searchParams = url.searchParams;
                searchParams.append("pilihan", pilihan);
                searchParams.append("tgl", tgl);
                searchParams.append("halaman", halaman);
                searchParams.append("no_urut", no_urut);
                searchParams.append("ttd", ttd);
                searchParams.append("jenis_print", jenis_print);
                window.open(url.toString(), "_blank");
            }
        });
        // CETAKAN BKU IX

        // CETAKAN PENERIMAAN PAJAK DAERAH
        $('#pilih_bulan_pajak_daerah').hide();
        $('#pilih_tanggal_pajak_daerah').hide();
        $('#pilih_pengirim_pajak_daerah').hide();
        $('#pilih_wilayah_pajak_daerah').hide();
        $('#pilih_rekap_pajak_daerah').hide();

        $('#pilih_tgl_pengirim_pajak_daerah').hide();
        $('#pilih_bulan_pengirim_pajak_daerah').hide();
        $('#pilih_tgl_wilayah_pajak_daerah').hide();
        $('#pilih_bulan_wilayah_pajak_daerah').hide();

        // PILIH PER BULAN
        $('#pilihan_bulan_pajak_daerah').on('click', function() {
            $('#bulan_pajak_daerah').val(null).change();

            $('#pilih_tanggal_pajak_daerah').hide();
            $('#pilih_pengirim_pajak_daerah').hide();
            $('#pilih_wilayah_pajak_daerah').hide();
            $('#pilih_rekap_pajak_daerah').hide();

            $('#pilih_tgl_pengirim_pajak_daerah').hide();
            $('#pilih_bulan_pengirim_pajak_daerah').hide();

            $('#pilih_tgl_wilayah_pajak_daerah').hide();
            $('#pilih_bulan_wilayah_pajak_daerah').hide();

            $('#pilih_bulan_pajak_daerah').show();
        });

        // PILIH PER TANGGAL
        $('#pilihan_tanggal_pajak_daerah').on('click', function() {
            $('#tgl_kas_pajak_daerah').val(null);
            $('#tgl_kas_sbl_pajak_daerah').val(null);

            $('#pilih_bulan_pajak_daerah').hide();
            $('#pilih_pengirim_pajak_daerah').hide();
            $('#pilih_wilayah_pajak_daerah').hide();
            $('#pilih_rekap_pajak_daerah').hide();

            $('#pilih_tgl_pengirim_pajak_daerah').hide();
            $('#pilih_bulan_pengirim_pajak_daerah').hide();

            $('#pilih_tgl_wilayah_pajak_daerah').hide();
            $('#pilih_bulan_wilayah_pajak_daerah').hide();

            $('#pilih_tanggal_pajak_daerah').show();
        });

        // PILIH PENGIRIM
        $('#pilihan_pengirim_pajak_daerah').on('click', function() {
            $('#pengirim_pajak_daerah').val(null).change();
            $('#nm_pengirim_pajak_daerah').val(null);

            $('#pilih_bulan_pajak_daerah').hide();
            $('#pilih_tanggal_pajak_daerah').hide();
            $('#pilih_wilayah_pajak_daerah').hide();
            $('#pilih_rekap_pajak_daerah').hide();

            $('#pilih_tgl_pengirim_pajak_daerah').hide();
            $('#pilih_bulan_pengirim_pajak_daerah').hide();

            $('#pilih_tgl_wilayah_pajak_daerah').hide();
            $('#pilih_bulan_wilayah_pajak_daerah').hide();

            $('#pilih_pengirim_pajak_daerah').show();
        });

        // PILIH PENGIRIM PER TANGGAL
        $('#pilihan_tgl_pengirim_pajak_daerah').on('click', function() {
            $('#tgl_kas_pengirim_pajak_daerah').val(null);
            $('#tgl_kas_sbl_pengirim_pajak_daerah').val(null);

            $('#pilih_bulan_pengirim_pajak_daerah').hide();

            $('#pilih_tgl_pengirim_pajak_daerah').show();
        });

        // PILIH PENGIRIM PER TANGGAL
        $('#pilihan_bulan_pengirim_pajak_daerah').on('click', function() {
            $('#bulan_pengirim1_pajak_daerah').val(null).change();
            $('#bulan_pengirim2_pajak_daerah').val(null).change();

            $('#pilih_tgl_pengirim_pajak_daerah').hide();

            $('#pilih_bulan_pengirim_pajak_daerah').show();
        });

        // PILIH WILAYAH
        $('#pilihan_wilayah_pajak_daerah').on('click', function() {
            $('#wilayah_pajak_daerah').val(null).change();
            $('#nm_wilayah_pajak_daerah').val(null);

            $('#pilih_bulan_pajak_daerah').hide();
            $('#pilih_tanggal_pajak_daerah').hide();
            $('#pilih_pengirim_pajak_daerah').hide();
            $('#pilih_rekap_pajak_daerah').hide();

            $('#pilih_tgl_pengirim_pajak_daerah').hide();
            $('#pilih_bulan_pengirim_pajak_daerah').hide();

            $('#pilih_tgl_wilayah_pajak_daerah').hide();
            $('#pilih_bulan_wilayah_pajak_daerah').hide();

            $('#pilih_wilayah_pajak_daerah').show();
        });

        // PILIH WILAYAH PER TANGGAL
        $('#pilihan_tgl_wilayah_pajak_daerah').on('click', function() {
            $('#tgl_kas_wilayah_pajak_daerah').val(null);
            $('#tgl_kas_sbl_wilayah_pajak_daerah').val(null);

            $('#pilih_bulan_wilayah_pajak_daerah').hide();

            $('#pilih_tgl_wilayah_pajak_daerah').show();
        });

        // PILIH WILAYAH PER TANGGAL
        $('#pilihan_bulan_wilayah_pajak_daerah').on('click', function() {
            $('#bulan_wilayah1_pajak_daerah').val(null).change();
            $('#bulan_wilayah2_pajak_daerah').val(null).change();

            $('#pilih_tgl_wilayah_pajak_daerah').hide();

            $('#pilih_bulan_wilayah_pajak_daerah').show();
        });

        // PILIH REKAP
        $('#pilihan_rekap_pajak_daerah').on('click', function() {
            $('#bulan_rekap1_pajak_daerah').val(null).change();
            $('#bulan_rekap2_pajak_daerah').val(null).change();

            $('#pilih_bulan_pajak_daerah').hide();
            $('#pilih_tanggal_pajak_daerah').hide();
            $('#pilih_pengirim_pajak_daerah').hide();
            $('#pilih_wilayah_pajak_daerah').hide();

            $('#pilih_tgl_pengirim_pajak_daerah').hide();
            $('#pilih_bulan_pengirim_pajak_daerah').hide();

            $('#pilih_tgl_wilayah_pajak_daerah').hide();
            $('#pilih_bulan_wilayah_pajak_daerah').hide();

            $('#pilih_rekap_pajak_daerah').show();
        });

        $('#pajak_daerah').on('click', function() {
            $('#modal_pajak_daerah').modal('show');
        });

        $('#pengirim_pajak_daerah').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            $('#nm_pengirim_pajak_daerah').val(nama);
        });

        $('#wilayah_pajak_daerah').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            $('#nm_wilayah_pajak_daerah').val(nama);
        });

        $('.cetak_pajak_daerah').on('click', function() {
            let pilih_bulan = document.getElementById('pilihan_bulan_pajak_daerah').checked;
            let pilih_tanggal = document.getElementById('pilihan_tanggal_pajak_daerah').checked;
            let pilih_pengirim = document.getElementById('pilihan_pengirim_pajak_daerah').checked;
            let pilih_wilayah = document.getElementById('pilihan_wilayah_pajak_daerah').checked;
            let pilih_rekap = document.getElementById('pilihan_rekap_pajak_daerah').checked;

            let pilih_tgl_pengirim = document.getElementById('pilihan_tgl_pengirim_pajak_daerah')
                .checked;
            let pilih_bulan_pengirim = document.getElementById('pilihan_bulan_pengirim_pajak_daerah')
                .checked;

            let pilih_tgl_wilayah = document.getElementById('pilihan_tgl_wilayah_pajak_daerah')
                .checked;
            let pilih_bulan_wilayah = document.getElementById('pilihan_bulan_wilayah_pajak_daerah')
                .checked;

            if (pilih_bulan == false) {
                if (pilih_tanggal == false) {
                    if (pilih_pengirim == false) {
                        if (pilih_wilayah == false) {
                            if (pilih_rekap == false) {
                                alert('Silahkan Pilih!!!');
                                return;
                            }
                        }
                    }
                }
            }

            let bulan = document.getElementById('bulan_pajak_daerah').value;
            let tgl_kas = document.getElementById('tgl_kas_pajak_daerah').value;
            let tgl_kas_sebelumnya = document.getElementById('tgl_kas_sbl_pajak_daerah').value;
            let pengirim = document.getElementById('pengirim_pajak_daerah').value;
            let wilayah = document.getElementById('wilayah_pajak_daerah').value;
            let halaman = document.getElementById('halaman_pajak_daerah').value;

            let tgl_kas_pengirim = document.getElementById('tgl_kas_pengirim_pajak_daerah').value;
            let tgl_kas_sbl_pengirim = document.getElementById('tgl_kas_sbl_pengirim_pajak_daerah')
                .value;
            let bulan_pengirim1 = document.getElementById('bulan_pengirim1_pajak_daerah').value;
            let bulan_pengirim2 = document.getElementById('bulan_pengirim2_pajak_daerah').value;

            let tgl_kas_wilayah = document.getElementById('tgl_kas_wilayah_pajak_daerah').value;
            let tgl_kas_sbl_wilayah = document.getElementById('tgl_kas_sbl_wilayah_pajak_daerah').value;
            let bulan_wilayah1 = document.getElementById('bulan_wilayah1_pajak_daerah').value;
            let bulan_wilayah2 = document.getElementById('bulan_wilayah2_pajak_daerah').value;

            let bulan_rekap1 = document.getElementById('bulan_rekap1_pajak_daerah').value;
            let bulan_rekap2 = document.getElementById('bulan_rekap2_pajak_daerah').value;

            let jenis_print = $(this).data("jenis");

            if (pilih_bulan) {
                if (!bulan) {
                    alert('Silahkan Pilih Bulan!');
                    return;
                }
            }

            if (pilih_tanggal) {
                if (!tgl_kas || !tgl_kas_sebelumnya) {
                    alert('Silahkan Pilih Tanggal Kas atau Tanggal Kas Sebelumnya!');
                    return;
                }
            }

            if (pilih_pengirim) {
                if (!pengirim) {
                    alert('Silahkan Pilih Nama Pengirim!');
                    return;
                }
                if (pilih_tgl_pengirim == false) {
                    if (pilih_bulan_pengirim == false) {
                        alert('Silahkan Pilih Per Tanggal atau Per Bulan');
                        return;
                    }
                }
                if (pilih_tgl_pengirim == true) {
                    if (!tgl_kas_pengirim || !tgl_kas_sbl_pengirim) {
                        alert('Silahkan isi Tanggal Kas!');
                        return;
                    }
                }
                if (pilih_bulan_pengirim == true) {
                    if (!bulan_pengirim1 || !bulan_pengirim2) {
                        alert('Silahkan isi Bulan!');
                        return;
                    }
                }
            }

            if (pilih_wilayah) {
                if (!wilayah) {
                    alert('Silahkan Pilih Nama Wilayah!');
                    return;
                }
                if (pilih_tgl_wilayah == false) {
                    if (pilih_bulan_wilayah == false) {
                        alert('Silahkan Pilih Per Tanggal atau Per Bulan');
                        return;
                    }
                }
                if (pilih_tgl_wilayah == true) {
                    if (!tgl_kas_wilayah || !tgl_kas_sbl_wilayah) {
                        alert('Silahkan isi Tanggal Kas!');
                        return;
                    }
                }
                if (pilih_bulan_wilayah == true) {
                    if (!bulan_wilayah1 || !bulan_wilayah2) {
                        alert('Silahkan isi Bulan!');
                        return;
                    }
                }
            }

            if (pilih_rekap) {
                if (!bulan_rekap1 || !bulan_rekap2) {
                    alert('Silahkan isi Bulan!');
                    return;
                }
            }

            if (!halaman) {
                alert('Halaman tidak boleh kosong!');
                return;
            }

            let pilihan = '';
            if (pilih_bulan) {
                pilihan = '1';
            } else if (pilih_tanggal) {
                pilihan = '2';
            } else if (pilih_pengirim) {
                if (pilih_tgl_pengirim) {
                    pilihan = '31';
                }
                if (pilih_bulan_pengirim) {
                    pilihan = '32';
                }
            } else if (pilih_wilayah) {
                if (pilih_tgl_wilayah) {
                    pilihan = '41';
                }
                if (pilih_bulan_wilayah) {
                    pilihan = '42';
                }
            } else if (pilih_rekap) {
                pilihan = '5';
            }

            let url = new URL("{{ route('laporan_bendahara_umum.penerimaan_pajak_daerah') }}");
            let searchParams = url.searchParams;
            searchParams.append("pilihan", pilihan);
            searchParams.append("bulan_perbulan", bulan);
            searchParams.append("tgl_kas_pertanggal", tgl_kas);
            searchParams.append("tgl_kas_sbl_pertanggal", tgl_kas_sebelumnya);
            searchParams.append("pengirim", pengirim);
            searchParams.append("tgl_kas_pengirim", tgl_kas_pengirim);
            searchParams.append("tgl_kas_sbl_pengirim", tgl_kas_sbl_pengirim);
            searchParams.append("bulan1_pengirim", bulan_pengirim1);
            searchParams.append("bulan2_pengirim", bulan_pengirim2);
            searchParams.append("wilayah", wilayah);
            searchParams.append("tgl_kas_wilayah", tgl_kas_wilayah);
            searchParams.append("tgl_kas_sbl_wilayah", tgl_kas_sbl_wilayah);
            searchParams.append("bulan1_wilayah", bulan_wilayah1);
            searchParams.append("bulan2_wilayah", bulan_wilayah2);
            searchParams.append("bulan_rekap1", bulan_rekap1);
            searchParams.append("bulan_rekap2", bulan_rekap2);
            searchParams.append("jenis_print", jenis_print);
            window.open(url.toString(), "_blank");
        });
        // CETAKAN PENERIMAAN PAJAK DAERAH

        // CETAKAN REKAP GAJI
        $('#pilih_skpd_rekap_gaji').hide();

        $('#pilihan_keseluruhan_rekap_gaji').on('click', function() {
            $('#kd_skpd_rekap_gaji').val(null).change();
            $('#nm_skpd_rekap_gaji').val(null);

            $('#pilih_skpd_rekap_gaji').hide();
        });

        $('#pilihan_skpd_rekap_gaji').on('click', function() {
            $('#kd_skpd_rekap_gaji').val(null).change();
            $('#nm_skpd_rekap_gaji').val(null);

            $('#pilih_skpd_rekap_gaji').show();
        });

        $('#kd_skpd_rekap_gaji').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            $('#nm_skpd_rekap_gaji').val(nama);
        });

        $('#rekap_gaji').on('click', function() {
            $('#modal_rekap_gaji').modal('show');
        });

        $('.cetak_rekap_gaji').on('click', function() {
            let keseluruhan = document.getElementById('pilihan_keseluruhan_rekap_gaji')
                .checked;
            let skpd = document.getElementById('pilihan_skpd_rekap_gaji').checked;

            let keseluruhan1 = document.getElementById('pilihan_keseluruhan_rekap_gaji1')
                .checked;
            let bulan = document.getElementById('pilihan_bulan_rekap_gaji')
                .checked;
            let periode = document.getElementById('pilihan_periode_rekap_gaji')
                .checked;

            if (keseluruhan == false) {
                if (skpd == false) {
                    alert('Silahkan Pilih Keseluruhan atau SKPD!');
                    return;
                }
            }

            if (keseluruhan) {
                if (keseluruhan1 == false) {
                    if (bulan == false) {
                        if (periode == false) {
                            alert('Silahkan Pilih Keseluruhan, Bulan atau Periode!');
                            return;
                        }
                    }
                }
            }

            if (skpd) {
                if (keseluruhan1 == false) {
                    if (bulan == false) {
                        if (periode == false) {
                            alert('Silahkan Pilih Keseluruhan, Bulan atau Periode!');
                            return;
                        }
                    }
                }
            }

            let kd_skpd = document.getElementById('kd_skpd_rekap_gaji').value;
            let bulan1 = document.getElementById('bulan_rekap_gaji').value;
            let periode1 = document.getElementById('periode1_rekap_gaji').value;
            let periode2 = document.getElementById('periode2_rekap_gaji').value;
            // let ttd = document.getElementById('ttd_rekap_gaji').value;
            let jenis_print = $(this).data("jenis");

            if (keseluruhan || skpd) {
                if (bulan) {
                    if (!bulan1) {
                        alert('Silahkan Pilih Bulan!');
                        return;
                    }
                }
            }

            if (keseluruhan || skpd) {
                if (periode) {
                    if (!periode1 || !periode2) {
                        alert('Silahkan Pilih Periode!');
                        return;
                    }
                }
            }

            if (skpd) {
                if (!kd_skpd) {
                    alert('Silahkan Pilih SKPD!');
                    return;
                }
            }

            let pilihan = '';
            if (keseluruhan) {
                if (keseluruhan1) {
                    pilihan = '11';
                }
                if (bulan) {
                    pilihan = '12';
                }
                if (periode) {
                    pilihan = '13';
                }
            }
            if (skpd) {
                if (keseluruhan1) {
                    pilihan = '21';
                }
                if (bulan) {
                    pilihan = '22';
                }
                if (periode) {
                    pilihan = '23';
                }
            }

            let url = new URL("{{ route('laporan_bendahara_umum.rekap_gaji') }}");
            let searchParams = url.searchParams;
            searchParams.append("pilihan", pilihan);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("bulan", bulan1);
            searchParams.append("periode1", periode1);
            searchParams.append("periode2", periode2);
            // searchParams.append("ttd", ttd);
            searchParams.append("jenis_print", jenis_print);
            window.open(url.toString(), "_blank");
        });
        // CETAKAN REKAP GAJI

    });
</script>
