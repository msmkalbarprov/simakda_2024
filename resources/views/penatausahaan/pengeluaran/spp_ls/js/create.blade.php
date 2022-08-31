<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#card_penagihan').hide();

        let tabel = $('#rincian_sppls').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    visible: false,
                    data: 'no_bukti'
                },
                {
                    data: 'kd_sub_kegiatan'
                },
                {
                    data: 'kd_rek6'
                },
                {
                    data: 'nm_rek6'
                },
                {
                    data: 'nilai'
                },
                {
                    visible: false,
                    data: 'sumber'
                },
                {
                    data: 'nm_sumber'
                },
                {
                    data: 'volume_output'
                },
                {
                    data: 'satuan_output'
                },
                {
                    data: 'hapus'
                }
            ]
        });

        $('.select2-multiple').select2({
            theme: 'bootstrap-5'
        });

        $('#rekanan').on('change', function() {
            let pimpinan = $(this).find(':selected').data('pimpinan');
            let alamat = $(this).find(':selected').data('alamat');
            $("#pimpinan").val(pimpinan);
            $("#alamat").val(alamat);
        });

        $('#nm_penerima').on('change', function() {
            let rekening = $(this).find(':selected').data('rekening');
            let npwp = $(this).find(':selected').data('npwp');
            $("#rekening").val(rekening);
            $("#npwp").val(npwp);
        });

        $('#beban').on('change', function() {
            let beban = this.value;
            let tgl_spp = document.getElementById('tgl_spp').value;
            let jenis = document.getElementById('jenis').value;
            if (!tgl_spp) {
                alert('Pilih tanggal SPD terlebih dahulu!');
                document.getElementById('beban').selectedIndex = 0;
                return;
            }
            // cari jenis
            $.ajax({
                url: "{{ route('sppls.cari_jenis') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    beban: beban,
                },
                success: function(data) {
                    $('#jenis').empty();
                    $('#jenis').append(`<option value="0">Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#jenis').append(
                            `<option value="${data.id}" data-nama="${data.text}">${data.text}</option>`
                        );
                    })
                }
            })
            // cari nomor spd
            $.ajax({
                url: "{{ route('sppls.cari_nomor_spd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    beban: beban,
                    tgl_spp: tgl_spp,
                },
                success: function(data) {
                    $('#nomor_spd').empty();
                    $('#nomor_spd').append(`<option value="0">Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#nomor_spd').append(
                            `<option value="${data.no_spd}" data-tgl="${data.tgl_spd}" data-total="${data.total}">${data.no_spd} | ${data.tgl_spd} | ${data.total}</option>`
                        );
                    })
                }
            })

            // cek beban dan jenis
            if (beban == '6' || beban == '5') {
                document.getElementById('npwp').removeAttribute("disabled");
                document.getElementById('tgl_awal').removeAttribute("disabled");
                document.getElementById('tgl_akhir').removeAttribute("disabled");
                document.getElementById('rekanan').removeAttribute("disabled");
                document.getElementById('pimpinan').removeAttribute("disabled");
                document.getElementById('alamat').removeAttribute("disabled");
                document.getElementById('no_kontrak').removeAttribute("disabled");
                document.getElementById('bank').removeAttribute("disabled");
                document.getElementById('rekening').removeAttribute("disabled");
            } else {
                if (beban == '4' && jenis == '9') {
                    document.getElementById('npwp').removeAttribute("disabled");
                    document.getElementById('tgl_awal').setAttribute("disabled", "disabled");
                    document.getElementById('tgl_akhir').setAttribute("disabled", "disabled");
                    document.getElementById('rekanan').removeAttribute("disabled");
                    document.getElementById('pimpinan').removeAttribute("disabled");
                    document.getElementById('alamat').removeAttribute("disabled");
                    document.getElementById('no_kontrak').setAttribute("disabled", "disabled");
                    document.getElementById('bank').removeAttribute("disabled");
                    document.getElementById('rekening').removeAttribute("disabled");
                } else {
                    document.getElementById('npwp').removeAttribute("disabled");
                    document.getElementById('tgl_awal').setAttribute("disabled", "disabled");
                    document.getElementById('tgl_akhir').setAttribute("disabled", "disabled");
                    document.getElementById('rekanan').setAttribute("disabled", "disabled");
                    document.getElementById('pimpinan').setAttribute("disabled", "disabled");
                    document.getElementById('alamat').setAttribute("disabled", "disabled");
                    document.getElementById('no_kontrak').setAttribute("disabled", "disabled");
                    document.getElementById('bank').removeAttribute("disabled");
                    document.getElementById('rekening').removeAttribute("disabled");
                }
            }
        });

        $('#jenis').on('change', function() {
            let jenis = this.value;
            let beban = document.getElementById('beban').value;
            // cek beban dan jenis
            if (beban == '6' && jenis == '6') {
                document.getElementById('npwp').removeAttribute("disabled");
                document.getElementById('tgl_awal').removeAttribute("disabled");
                document.getElementById('tgl_akhir').removeAttribute("disabled");
                document.getElementById('rekanan').removeAttribute("disabled");
                document.getElementById('pimpinan').removeAttribute("disabled");
                document.getElementById('alamat').removeAttribute("disabled");
                document.getElementById('no_kontrak').removeAttribute("disabled");
                document.getElementById('bank').removeAttribute("disabled");
                document.getElementById('rekening').removeAttribute("disabled");
            } else if (beban == '5') {
                document.getElementById('npwp').removeAttribute("disabled");
                document.getElementById('tgl_awal').setAttribute("disabled", "disabled");
                document.getElementById('tgl_akhir').setAttribute("disabled", "disabled");
                document.getElementById('rekanan').removeAttribute("disabled");
                document.getElementById('pimpinan').removeAttribute("disabled");
                document.getElementById('alamat').removeAttribute("disabled");
                document.getElementById('no_kontrak').removeAttribute("disabled");
                document.getElementById('bank').removeAttribute("disabled");
                document.getElementById('rekening').removeAttribute("disabled");
            } else if (beban == '6' && jenis == '4') {
                document.getElementById('npwp').removeAttribute("disabled");
                document.getElementById('tgl_awal').setAttribute("disabled", "disabled");
                document.getElementById('tgl_akhir').setAttribute("disabled", "disabled");
                document.getElementById('rekanan').removeAttribute("disabled");
                document.getElementById('pimpinan').removeAttribute("disabled");
                document.getElementById('alamat').removeAttribute("disabled");
                document.getElementById('no_kontrak').removeAttribute("disabled");
                document.getElementById('bank').removeAttribute("disabled");
                document.getElementById('rekening').removeAttribute("disabled");
            } else if (beban == '4' && jenis == '9') {
                document.getElementById('npwp').removeAttribute("disabled");
                document.getElementById('tgl_awal').setAttribute("disabled", "disabled");
                document.getElementById('tgl_akhir').setAttribute("disabled", "disabled");
                document.getElementById('rekanan').removeAttribute("disabled");
                document.getElementById('pimpinan').removeAttribute("disabled");
                document.getElementById('alamat').removeAttribute("disabled");
                document.getElementById('no_kontrak').setAttribute("disabled", "disabled");
                document.getElementById('bank').removeAttribute("disabled");
                document.getElementById('rekening').removeAttribute("disabled");
            } else {
                document.getElementById('npwp').removeAttribute("disabled");
                document.getElementById('tgl_awal').setAttribute("disabled", "disabled");
                document.getElementById('tgl_akhir').setAttribute("disabled", "disabled");
                document.getElementById('rekanan').setAttribute("disabled", "disabled");
                document.getElementById('pimpinan').setAttribute("disabled", "disabled");
                document.getElementById('alamat').setAttribute("disabled", "disabled");
                document.getElementById('no_kontrak').setAttribute("disabled", "disabled");
                document.getElementById('bank').removeAttribute("disabled");
                document.getElementById('rekening').removeAttribute("disabled");
            }
        });

        $('#nomor_spd').on('change', function() {
            let spd = this.value;
            let tgl = $(this).find(':selected').data('tgl');
            let total = $(this).find(':selected').data('total');
            $("#tgl_spd").val(tgl);
            // cari kode sub kegiatan
            $.ajax({
                url: "{{ route('sppls.cari_sub_kegiatan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    spd: spd,
                },
                success: function(data) {
                    $('#kd_sub_kegiatan').empty();
                    $('#kd_sub_kegiatan').append(
                        `<option value="0">Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_sub_kegiatan').append(
                            `<option value="${data.kd_sub_kegiatan}" data-nama="${data.nm_sub_kegiatan}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                        );
                    })
                }
            })
        });

        $('#kd_sub_kegiatan').on('change', function() {
            let kd_sub_kegiatan = this.value;
            let nama = $(this).find(':selected').data('nama');
            $("#nm_sub_kegiatan").val(nama);
            $('#nmsub_kegiatan').val(nama);
            $('#sub_kegiatan').val(kd_sub_kegiatan);
            // cari rekening
            $.ajax({
                url: "{{ route('sppls.cari_rekening') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: kd_sub_kegiatan,
                },
                success: function(data) {
                    $('#kode_rekening').empty();
                    $('#kode_rekening').append(`<option value="0">Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#kode_rekening').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}">${data.kd_rek6} | ${data.nm_rek6}</option>`
                        );
                    })
                }
            })
        });

        $('#kode_rekening').on('change', function() {
            let kode_rekening = this.value;
            let kd_sub_kegiatan = document.getElementById('sub_kegiatan').value;
            let skpd = document.getElementById('opd_unit').value;
            let status_ang = document.getElementById('status_anggaran').value;
            let tgl_spd = document.getElementById('tgl_spd').value;
            let tgl_spp = document.getElementById('tgl_spp').value;
            let no_spp = document.getElementById('no_spp').value;
            let beban = document.getElementById('beban').value;
            let nama = $(this).find(':selected').data('nama');
            $("#nm_rekening").val(nama);
            // cari sumber dana
            $.ajax({
                url: "{{ route('penagihan.cari_sumber_dana') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    skpd: skpd,
                    kdgiat: kd_sub_kegiatan,
                    kdrek: kode_rekening,
                    status_ang: status_ang
                },
                success: function(data) {
                    $('#sumber_dana').empty();
                    $('#sumber_dana').append(
                        `<option value="0">Pilih Sumber Dana</option>`);
                    $.each(data, function(index, data) {
                        $('#sumber_dana').append(
                            `<option value="${data.sumber_dana}" data-lalu="${data.lalu}" data-nilai="${data.nilai}">${data.sumber_dana}</option>`
                        );
                    })
                }
            })
            // kondisi untuk kode rekening tertentu
            if (kode_rekening.substr(0, 2) == '52') {
                document.getElementById('volume_output').removeAttribute("disabled");
                document.getElementById('satuan_output').removeAttribute("disabled");
            } else if (kode_rekening.substr(0, 4) == '5105') {
                document.getElementById('volume_output').removeAttribute("disabled");
                document.getElementById('satuan_output').removeAttribute("disabled");
            } else if (kode_rekening.substr(0, 4) == '5106') {
                document.getElementById('volume_output').removeAttribute("disabled");
                document.getElementById('satuan_output').removeAttribute("disabled");
            } else if (kode_rekening.substr(0, 4) == '5402') {
                document.getElementById('volume_output').removeAttribute("disabled");
                document.getElementById('satuan_output').removeAttribute("disabled");
            } else if (kode_rekening.substr(0, 6) == '510203') {
                document.getElementById('volume_output').removeAttribute("disabled");
                document.getElementById('satuan_output').removeAttribute("disabled");
            } else if (kode_rekening == '510101080001' && kode_rekening == '5110105') {
                document.getElementById('minus').removeAttribute("disabled");
            } else {
                document.getElementById('minus').setAttribute("disabled", "disabled");
                document.getElementById('minus').checked = false;
            }
            // anggaran penyusunan
            $.ajax({
                url: "{{ route('sppls.jumlah_anggaran_penyusunan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    skpd: skpd,
                    kdgiat: kd_sub_kegiatan,
                    kdrek: kode_rekening,
                    no_spp: no_spp
                },
                success: function(data) {
                    let rektotal = parseFloat(data.rektotal) || 0;
                    let rektotal_lalu = parseFloat(data.rektotal_lalu) || 0;
                    let sisa_dana = parseFloat(rektotal - rektotal_lalu) || 0;
                    $("#total_penyusunan").val(rektotal.toLocaleString('id-ID', {
                        minimumFractionDigits: 2
                    }));
                    $("#realisasi_penyusunan").val(rektotal_lalu.toLocaleString('id-ID', {
                        minimumFractionDigits: 2
                    }));
                    $("#sisa_penyusunan").val(sisa_dana.toLocaleString('id-ID', {
                        minimumFractionDigits: 2
                    }));
                }
            })

            // total spd
            $.ajax({
                url: "{{ route('sppls.total_spd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    skpd: skpd,
                    kdgiat: kd_sub_kegiatan,
                    kdrek: kode_rekening,
                    no_spp: no_spp,
                    tgl_spd: tgl_spd,
                    tgl_spp: tgl_spp,
                    beban: beban
                },
                success: function(data) {
                    let total_spd = parseFloat(data.total_spd) || 0;
                    $("#total_spd").val(total_spd.toLocaleString('id-ID', {
                        minimumFractionDigits: 2
                    }));
                }
            })
        });

        $('#sumber_dana').on('change', function() {
            let selected = $(this).find('option:selected');
            let sumber_dana = this.value;
            $.ajax({
                url: "{{ route('penagihan.cari_nama_sumber') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    sumber_dana: sumber_dana,
                },
                success: function(data) {
                    $('#nm_sumber').val(data.nm_sumber_dana1);
                }
            })
            let dana = parseInt(selected.data('nilai')) || 0;
            let dana_lalu = parseInt(selected.data('lalu')) || 0;
            let sisa_dana = parseInt(dana - dana_lalu) || 0;
            $("#total_sumber").val(dana.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#realisasi_sumber").val(dana_lalu.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#sisa_sumber").val(sisa_dana.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
        });

        $('#no_penagihan').on('change', function() {
            let tgl = $(this).find(':selected').data('tgl');
            let total = $(this).find(':selected').data('total');
            $("#tgl_penagihan").val(tgl);
            $("#nilai_penagihan").val(total);
        });

        let data = false;
        $('#dengan_penagihan').on('change', function() {
            if ($(this).is(':checked')) {
                data = $(this).is(':checked');
                if (data == true) {
                    document.getElementById('tambah_rincian').setAttribute("disabled", "disabled");
                    document.getElementById('tgl_penagihan').value = '';
                    document.getElementById('nilai_penagihan').value = '';
                    $('#card_penagihan').show();
                }
            } else {
                data = $(this).is(':checked');
                if (data == false) {
                    document.getElementById('tambah_rincian').removeAttribute("disabled");
                    document.getElementById('tgl_penagihan').value = '';
                    document.getElementById('nilai_penagihan').value = '';
                    $('#card_penagihan').hide();
                }
            }
        });

        // tambah_rincian
        $('#tambah_rincian').on("click", function() {
            // cek sub kegiatan sudah dipilih atau belum
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            // if (!kd_sub_kegiatan) {
            //     alert('Isi kode kegiatan terlebih dahulu!');
            //     return;
            // }
            // cari status angkas
            let tgl_spp = document.getElementById('tgl_spp').value;
            // cek status anggaran
            $.ajax({
                url: "{{ route('penagihan.cek_status_ang_new') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    tgl_bukti: tgl_spp,
                },
                success: function(data) {
                    $('#status_anggaran').val(data.nama);
                }
            })
            // cek status angkas
            $.ajax({
                url: "{{ route('penagihan.cek_status_ang') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    tgl_bukti: tgl_spp,
                },
                success: function(data) {
                    $('#status_angkas').val(data.status);
                }
            })

            // cek tanggal spp dengan tanggal spp lalu
            if (!tgl_spp) {
                alert('Tanggal SPP harus dipilih!');
                return;
            }
            let tgl_spp_lalu = document.getElementById('tgl_spp_lalu').value;
            if (tgl_spp < tgl_spp_lalu) {
                alert("tanggal SPP tidak boleh kurang dari SPP Lalu...!!!");
                return;
            }

            let no_penagihan = document.getElementById('no_penagihan').value;
            let beban = document.getElementById('beban').value;
            let jenis = document.getElementById('jenis').value;
            if (beban == '6' && jenis == '6' && no_penagihan == '') {
                alert('Nomor Penagihan Masih Kosong....!!!');
                return;
            }

            $('#tambah_rincianspp').modal('show');
        });
    });
</script>
