<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let tabel = $('#rincian_spm').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
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
            ]
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('#no_spp').on('select2:select', function() {
            let no_spp = this.value;
            let tgl_spp = $(this).find(':selected').data('tgl_spp');
            let no_spd = $(this).find(':selected').data('no_spd');
            let bulan = $(this).find(':selected').data('bulan');
            let bank = $(this).find(':selected').data('bank');
            let kd_skpd = $(this).find(':selected').data('kd_skpd');
            let nm_skpd = $(this).find(':selected').data('nm_skpd');
            let keperluan = $(this).find(':selected').data('keperluan');
            let beban = $(this).find(':selected').data('beban');
            let rekanan = $(this).find(':selected').data('rekanan');
            let jenis = $(this).find(':selected').data('jenis');
            let npwp = $(this).find(':selected').data('npwp');
            let rekening = $(this).find(':selected').data('rekening');
            let nm_bulan = cari_bulan(bulan);
            let nm_beban = cari_beban(beban);
            let nm_jenis = cari_jenis(beban, jenis);
            let nm_bank = cari_bank(bank);
            $("#tgl_spp").val(tgl_spp);
            $("#no_spd").val(no_spd);
            $("#bulan").val(bulan);
            $('#nm_bulan').val(nm_bulan);
            $("#bank").val(bank);
            $("#nm_bank").val(nm_bank);
            $("#kd_skpd").val(kd_skpd);
            $("#nm_skpd").val(nm_skpd);
            $("#keperluan").val(keperluan);
            $("#beban").val(beban);
            $("#nm_beban").val(nm_beban);
            $("#rekanan").val(rekanan);
            $("#jenis").val(jenis);
            $("#nm_jenis").val(nm_jenis);
            $("#npwp").val(npwp);
            $("#rekening").val(rekening);

            $.ajax({
                url: "{{ route('spm.detail_spm') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_spp: no_spp
                },
                success: function(data) {
                    let total = 0;
                    $.each(data, function(index, data) {
                        tabel.row.add({
                            'kd_sub_kegiatan': data.kd_sub_kegiatan,
                            'kd_rek6': data.kd_rek6,
                            'nm_rek6': data.nm_rek6,
                            'nilai': new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(data.nilai),
                        }).draw();
                        total += parseFloat(data.nilai);
                    })
                    $('#total').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(total))
                }
            });
        });

        function cari_bulan(bulan) {
            switch (bulan) {
                case 1:
                    return 'Januari';
                    break;
                case 2:
                    return 'Februari';
                    break;
                case 3:
                    return 'Maret';
                    break;
                case 4:
                    return 'April';
                    break;
                case 5:
                    return 'Mei';
                    break;
                case 6:
                    return 'Juni';
                    break;
                case 7:
                    return 'Juli';
                    break;
                case 8:
                    return 'Agustus';
                    break;
                case 9:
                    return 'September';
                    break;
                case 10:
                    return 'Oktober';
                    break;
                case 11:
                    return 'November';
                    break;
                case 12:
                    return 'Desember';
                    break;
                default:
                    break;
            }
        }

        function cari_beban(beban) {
            switch (beban) {
                case 1:
                    return 'UP'
                    break;
                case 2:
                    return 'GU'
                    break;
                case 3:
                    return 'TU'
                    break;
                case 4:
                    return 'LS GAJI'
                    break;
                case 5:
                    return 'LS Pihak Ketiga Lainnya'
                    break;
                case 6:
                    return 'LS Barang Jasa'
                    break;
                default:
                    break;
            }
        }

        function cari_jenis(beban, jenis) {
            $.ajax({
                url: "{{ route('spm.cari_jenis') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    beban: beban,
                    jenis: jenis,
                },
                success: function(data) {
                    $('#nm_jenis').val(data.nama);
                }
            })
        }

        function cari_bank(bank) {
            $.ajax({
                url: "{{ route('spm.cari_bank') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    bank: bank,
                },
                success: function(data) {
                    $('#nm_bank').val(data.nama);
                }
            })
        }

    });

    function deleteData(kd_sub_kegiatan, kode_rekening, nm_rekening, sumber_dana, nilai_rincian) {
        let hapus = confirm('Yakin Ingin Menghapus Data, Rekening : ' + kode_rekening + '  Nilai :  ' + nilai_rincian +
            ' ?');
        let total = rupiah(document.getElementById('total').value);
        let tabel = $('#rincian_sppls').DataTable();
        if (hapus == true) {
            tabel.rows(function(idx, data, node) {
                return data.sumber == sumber_dana && data.kd_sub_kegiatan == kd_sub_kegiatan &&
                    data.kd_rek6 == kode_rekening
            }).remove().draw();
            $('#total').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total - nilai_rincian));
        }
    }
</script>
