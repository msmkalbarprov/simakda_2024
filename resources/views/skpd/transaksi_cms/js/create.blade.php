<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#volume').prop('disabled', true);
        $('#satuan').prop('disabled', true);

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('.select2-modal').select2({
            dropdownParent: $('#modal_kegiatan .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#beban').on('select2:select', function() {
            let beban = this.value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            if (!kd_skpd) {
                alert('Isi terlebih dahulu Kode SKPD!');
                $("#beban").val(null).change();
                return;
            }
            cari_kegiatan(beban, kd_skpd);
        });

        // no bukti cms
        $.ajax({
            url: "{{ route('skpd.transaksi_cms.no_urut') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {
                $('#no_bukti').val(data);
            }
        })

        // kd skpd dan nm skpd
        $.ajax({
            url: "{{ route('skpd.transaksi_cms.skpd') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {
                $('#kd_skpd').val(data.kd_skpd);
                $('#nm_skpd').val(data.nm_skpd);
            }
        })

        let tabel_rekening = $('#input_rekening').DataTable({
            responsive: true,
            processing: true,
            ordering: false,
            columns: [{
                    data: 'no_bukti',
                    name: 'no_bukti',
                    visible: false
                },
                {
                    data: 'no_sp2d',
                    name: 'no_sp2d',
                },
                {
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan',
                },
                {
                    data: 'nm_sub_kegiatan',
                    name: 'nm_sub_kegiatan',
                    visible: false
                },
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6',
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6',
                },
                {
                    data: 'nilai',
                    name: 'nilai',
                },
                {
                    data: 'sumber',
                    name: 'sumber',
                },
                {
                    data: 'lalu',
                    name: 'lalu',
                },
                {
                    data: 'sp2d',
                    name: 'sp2d',
                },
                {
                    data: 'anggaran',
                    name: 'anggaran',
                },
                {
                    data: 'volume',
                    name: 'volume',
                },
                {
                    data: 'satuan',
                    name: 'satuan',
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });

        let tabel_rekening1 = $('#rincian_rekening').DataTable({
            responsive: true,
            processing: true,
            ordering: false,
            columns: [{
                    data: 'no_bukti',
                    name: 'no_bukti',
                    visible: false
                },
                {
                    data: 'no_sp2d',
                    name: 'no_sp2d',
                    visible: false
                },
                {
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan',
                },
                {
                    data: 'nm_sub_kegiatan',
                    name: 'nm_sub_kegiatan',
                    visible: false
                },
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6',
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6',
                },
                {
                    data: 'nilai',
                    name: 'nilai',
                },
                {
                    data: 'sumber',
                    name: 'sumber',
                },
                {
                    data: 'lalu',
                    name: 'lalu',
                    visible: false
                },
                {
                    data: 'sp2d',
                    name: 'sp2d',
                    visible: false
                },
                {
                    data: 'anggaran',
                    name: 'anggaran',
                    visible: false
                },
                {
                    data: 'volume',
                    name: 'volume',
                    visible: false
                },
                {
                    data: 'satuan',
                    name: 'satuan',
                    visible: false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });

        $('#tambah_rek').on('click', function() {
            let no_bukti = document.getElementById('no_bukti').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let beban = document.getElementById('beban').value;
            let tgl_voucher = document.getElementById('tgl_voucher').value;
            if (kd_skpd != '' && tgl_voucher != '' && beban != '' && no_bukti != '') {
                status_anggaran();
                status_angkas();
                $('#modal_kegiatan').modal('show');
            } else {
                Swal.fire({
                    title: 'Harap Isi Kode SKPD, Tanggal Transaksi & Jenis Beban SP2D',
                    confirmButtonColor: '#5b73e8',
                })
            }
        });

        $('#kd_sub_kegiatan').on('select2:select', function() {
            let nm_sub_kegiatan = $(this).find(':selected').data('nama');
            let kd_sub_kegiatan = this.value;
            $("#nm_sub_kegiatan").val(nm_sub_kegiatan);
            cari_nomor(kd_sub_kegiatan);
        })

        $('#no_sp2d').on('select2:select', function() {
            let tgl_sp2d = $(this).find(':selected').data('tgl');
            let no_sp2d = this.value;
            let tgl_voucher = document.getElementById('tgl_voucher').value;
            let beban = document.getElementById('beban').value;
            if (tgl_sp2d < tgl_voucher) {
                alert('Kesalahan, Tanggal Sp2d lebih kecil Dari Tanggal Bukti');
                $("#no_sp2d").val(null).change();
                return;
            }
            cari_rekening(no_sp2d);
            load_sisa_bank();
            load_potongan_ls(no_sp2d);
        })

        $('#kd_rekening').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            let sp2d = $(this).find(':selected').data('sp2d');
            let anggaran = $(this).find(':selected').data('anggaran');
            let lalu = $(this).find(':selected').data('lalu');
            $('#nm_rekening').val(nama);
            let kd_rek6 = this.value;
            let beban = document.getElementById('beban').value;
            cari_sumber(kd_rek6);
            if (kd_rek6.substr(0, 2) == '52') {
                $('#volume').prop('disabled', false);
                $('#satuan').prop('disabled', false);
            }
            if (kd_rek6.substr(0, 4) == '5105') {
                $('#volume').prop('disabled', false);
                $('#satuan').prop('disabled', false);
            }
            if (kd_rek6.substr(0, 4) == '5106') {
                $('#volume').prop('disabled', false);
                $('#satuan').prop('disabled', false);
            }
            if (kd_rek6.substr(0, 4) == '5402') {
                $('#volume').prop('disabled', false);
                $('#satuan').prop('disabled', false);
            }
            if (kd_rek6.substr(0, 6) == '510203') {
                $('#volume').prop('disabled', false);
                $('#satuan').prop('disabled', false);
            }
            let sisa = 0;
            if (beban == '1') {
                sisa = anggaran - lalu;
                $('#total_anggaran').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(anggaran));
            } else {
                sisa = sp2d - lalu;
                $('#total_anggaran').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(sp2d));
            }
            $('#realisasi_anggaran').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(lalu));
            $('#sisa_anggaran').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(sisa));
            load_angkas();
        })

        $('#sumber').on('select2:select', function() {
            let sumber = this.value;
            let anggaran = $(this).find(':selected').data('anggaran');
            $.ajax({
                url: "{{ route('penagihan.cari_nama_sumber') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    sumber_dana: sumber
                },
                success: function(data) {
                    $('#nm_sumber').val(data.nm_sumber_dana1);
                }
            })
            $('#total_sumber').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(anggaran));
            load_dana(sumber);
        })

        // $('#simpan_sp2d').on('click', function() {
        //     let beban = document.getElementById('beban').value;
        //     let no_sp2d = document.getElementById('no_sp2d').value;
        //     let no_spm = document.getElementById('no_spm').value;
        //     let no_spp = document.getElementById('no_spp').value;
        //     let tgl_sp2d = document.getElementById('tgl_sp2d').value;
        //     if (!beban) {
        //         alert('Silahkan pilih jenis beban!');
        //         return;
        //     }
        //     if (!no_spm) {
        //         alert('Silahkan pilih no spm!');
        //         return;
        //     }
        //     if (!no_spp) {
        //         alert('Silahkan pilih no spm!');
        //         return;
        //     }
        //     if (!tgl_sp2d) {
        //         alert('Silahkan pilih tanggal SP2D!');
        //         return;
        //     }
        //     if (beban == '4') {
        //         if (!no_sp2d) {
        //             alert('Nomor SP2D Tidak Boleh kosong');
        //             return;
        //         }
        //     }

        //     // simpan sp2d
        //     $.ajax({
        //         url: "{{ route('sp2d.simpan_sp2d') }}",
        //         type: "POST",
        //         dataType: 'json',
        //         data: {
        //             beban: beban,
        //             tgl_sp2d: tgl_sp2d,
        //             no_spm: no_spm,
        //             no_spp: no_spp,
        //         },
        //         success: function(data) {
        //             if (data.message == '1') {
        //                 alert('Data berhasil ditambahkan, No SP2D yang tersimpan adalah: ' +
        //                     data.no_sp2d);
        //                 window.location.href = "{{ route('spm.index') }}";
        //             } else {
        //                 alert('Data tidak berhasil ditambahkan!');
        //                 return;
        //             }
        //         }
        //     })
        // });

        function cari_kegiatan(beban, kd_skpd) {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.kegiatan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    beban: beban,
                    kd_skpd: kd_skpd,
                },
                success: function(data) {
                    $('#kd_sub_kegiatan').empty();
                    $('#kd_sub_kegiatan').append(
                        `<option value="" disabled selected>Pilih Sub Kegiatan</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_sub_kegiatan').append(
                            `<option value="${data.kd_sub_kegiatan}" data-nama="${data.nm_sub_kegiatan}" data-kdprogram="${data.kd_program}" data-nmprogram="${data.nm_program}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                        );
                    })
                }
            })
        }

        function cari_nomor(kd_sub_kegiatan) {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.nomor_sp2d') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    beban: document.getElementById('beban').value,
                    kd_skpd: document.getElementById('kd_skpd').value,
                    no_bukti: document.getElementById('no_bukti').value,
                },
                success: function(data) {
                    $('#no_sp2d').empty();
                    $('#no_sp2d').append(
                        `<option value="" disabled selected>Pilih Nomor SP2D</option>`);
                    $.each(data, function(index, data) {
                        $('#no_sp2d').append(
                            `<option value="${data.no_sp2d}" data-tgl="${data.tgl_sp2d}">${data.no_sp2d} | ${data.tgl_sp2d}</option>`
                        );
                    })
                }
            })
        }

        function cari_rekening(no_sp2d) {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.rekening') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_sp2d: no_sp2d,
                    no_bukti: document.getElementById('no_bukti').value,
                    beban: document.getElementById('beban').value,
                    kd_sub_kegiatan: document.getElementById('kd_sub_kegiatan').value,
                    kd_skpd: document.getElementById('kd_skpd').value,
                },
                success: function(data) {
                    $('#kd_rekening').empty();
                    $('#kd_rekening').append(
                        `<option value="" disabled selected>Pilih Rekening</option>`);
                    $.each(data, function(index, data) {
                        $('#kd_rekening').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}" data-sp2d="${data.sp2d}" data-anggaran="${data.anggaran}" data-lalu="${data.lalu}">${data.kd_rek6} | ${data.nm_rek6} | ${data.lalu}</option>`
                        );
                    })
                }
            })
        }

        function cari_sumber(kd_rek6) {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.sumber') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_rek6: kd_rek6,
                    kd_skpd: document.getElementById('kd_skpd').value,
                    kd_sub_kegiatan: document.getElementById('kd_sub_kegiatan').value,
                    no_sp2d: document.getElementById('no_sp2d').value,
                    beban: document.getElementById('beban').value,
                },
                success: function(data) {
                    $('#sumber').empty();
                    $('#sumber').append(
                        `<option value="" disabled selected>Pilih Sumber Dana</option>`);
                    $.each(data, function(index, data) {
                        $('#sumber').append(
                            `<option value="${data.sumber}" data-anggaran="${data.nilai}" data-kd_rek6="${data.kd_rek6}" data-kegiatan="${data.kegiatan}">${data.sumber}</option>`
                        );
                    })
                }
            })
        }

        function load_sisa_bank() {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.sisa_bank') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    let nilai = parseFloat(data) || 0;
                    $('#sisa_kas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(nilai))
                }
            })
        }

        function load_potongan_ls(no_sp2d) {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.potongan_ls') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_sp2d: no_sp2d
                },
                success: function(data) {
                    let nilai = parseFloat(data) || 0;
                    $('#potongan_ls').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(nilai));
                    let sisa_kas = rupiah(document.getElementById('sisa_kas').value);
                    $('#total_sisa').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(sisa_kas + nilai));
                }
            })
        }

        function load_dana(sumber) {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.load_dana') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: document.getElementById('kd_sub_kegiatan').value,
                    kd_skpd: document.getElementById('kd_skpd').value,
                    sumber: sumber,
                    kd_rekening: document.getElementById('kd_rekening').value,
                    no_sp2d: document.getElementById('no_sp2d').value,
                    beban: document.getElementById('beban').value,
                },
                success: function(data) {
                    $('#realisasi_sumber').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data));
                    let total_sumber = rupiah(document.getElementById('total_sumber').value);
                    $('#sisa_sumber').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(total_sumber - data));
                }
            })
        }

        function status_anggaran() {
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.status_ang') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#status_anggaran').val(data.nama);
                }
            })
        }

        function status_angkas() {
            let tanggal = document.getElementById('tgl_voucher').value;
            $.ajax({
                url: "{{ route('penagihan.cek_status_ang') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#status_angkas').val(data.status);
                }
            })
        }

        function load_angkas() {
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let kd_rekening = document.getElementById('kd_rekening').value;
            let tgl_voucher = document.getElementById('tgl_voucher').value;
            let beban = document.getElementById('beban').value;
            let status_angkas = document.getElementById('status_angkas').value;

            $.ajax({
                url: "{{ route('skpd.transaksi_cms.load_angkas') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    kd_skpd: kd_skpd,
                    kd_rekening: kd_rekening,
                    tgl_voucher: tgl_voucher,
                    beban: beban,
                    status_angkas: status_angkas,
                },
                success: function(data) {
                    $('#total_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.nilai));
                    load_angkas_lalu();
                }
            })
        }

        function load_angkas_lalu() {
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let kd_rekening = document.getElementById('kd_rekening').value;
            let no_sp2d = document.getElementById('no_sp2d').value;
            let tgl_voucher = document.getElementById('tgl_voucher').value;
            let beban = document.getElementById('beban').value;
            let total_angkas = rupiah(document.getElementById('total_angkas').value);
            $.ajax({
                url: "{{ route('skpd.transaksi_cms.load_angkas_lalu') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    kd_skpd: kd_skpd,
                    kd_rekening: kd_rekening,
                    no_sp2d: no_sp2d,
                    tgl_voucher: tgl_voucher,
                    beban: beban,
                },
                success: function(data) {
                    $('#realisasi_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.total));
                    $('#realisasi_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.total));
                    $('#sisa_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(total_angkas - data.total));
                    load_spd();
                }
            })
        }

        function load_spd() {
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let kd_rekening = document.getElementById('kd_rekening').value;
            let realisasi_spd = rupiah(document.getElementById('realisasi_spd').value);

            $.ajax({
                url: "{{ route('skpd.transaksi_cms.load_spd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    kd_skpd: kd_skpd,
                    kd_rekening: kd_rekening,
                },
                success: function(data) {
                    $('#total_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.total));
                    $('#sisa_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.total - realisasi_spd));
                }
            })
        }

        function rupiah(n) {
            let n1 = n.split('.').join('');
            let rupiah = n1.split(',').join('.');
            return parseFloat(rupiah) || 0;
        }

        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }

        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") {
                return;
            }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    });
</script>
