<script>
    $(document).ready(function() {
        $('.select2-multiple').select2();
        $('#kd_sub_kegiatan').select2({
            dropdownParent: $('#tambah-penagihan')
        });
        $('#kode_rekening').select2({
            dropdownParent: $('#tambah-penagihan')
        });
        $('#sumber_dana').select2({
            dropdownParent: $('#tambah-penagihan')
        });
    });
</script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let tabel = $('#input_penagihan').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    visible: false,
                    data: 'no_bukti'
                },
                {
                    visible: false,
                    data: 'no_sp2d'
                },
                {
                    data: 'kd_sub_kegiatan'
                },
                {
                    visible: false,
                    data: 'nm_sub_kegiatan'
                },
                {
                    data: 'kd_rek6'
                },
                {
                    data: 'kd_rek'
                },
                {
                    visible: false,
                    data: 'nm_rek6'
                },
                {
                    data: 'nilai'
                },
                {
                    visible: false,
                    data: 'lalu'
                },
                {
                    visible: false,
                    data: 'sp2d'
                },
                {
                    data: 'anggaran'
                },
                {
                    data: 'sumber'
                }
            ]
        });
        let tabel1 = $('#rincian_penagihan').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    visible: false,
                    data: 'no_bukti'
                },
                {
                    visible: false,
                    data: 'no_sp2d'
                },
                {
                    data: 'kd_sub_kegiatan'
                },
                {
                    visible: false,
                    data: 'nm_sub_kegiatan'
                },
                {
                    data: 'kd_rek6'
                },
                {
                    visible: false,
                    data: 'kd_rek'
                },
                {
                    data: 'nm_rek6'
                },
                {
                    data: 'nilai'
                },
                {
                    visible: false,
                    data: 'lalu'
                },
                {
                    visible: false,
                    data: 'sp2d'
                },
                {
                    visible: false,
                    data: 'anggaran'
                },
                {
                    data: 'sumber'
                }
            ]
        });
        $('#tambah_rincian').on("click", function() {
            let no_bukti = document.getElementById('no_bukti').value;
            let tgl_bukti = document.getElementById('tgl_bukti').value;
            let skpd = document.getElementById('kd_skpd').value;
            let kontrak = document.getElementById('no_kontrak').value;
            if (no_bukti != '' && tgl_bukti != '' && skpd != '' && kontrak != '') {
                $('#tambah-penagihan').modal('show');
            } else {
                Swal.fire({
                    title: 'Harap isi kode, tanggal, nomor penagihan dan nomor kontrak',
                    confirmButtonColor: '#5b73e8',
                })
            }
        });
        $('#tgl_bukti').on("change", function() {
            let tgl_bukti = this.value;
            $.ajax({
                url: "{{ route('penagihan.cek_status_ang_new') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    tgl_bukti: tgl_bukti,
                },
                success: function(data) {
                    $('#status_anggaran').val(data.nama);
                }
            })
        });
        $('#kd_sub_kegiatan').on("change", function() {
            let nm_sub_kegiatan = $(this).find(':selected').data('nama');
            let kd_sub_kegiatan = this.value;
            $("#nm_sub_kegiatan").val(nm_sub_kegiatan);
            $("#nm_rekening").val("");
            $('#sumber_dana').empty();
            $("#nm_sumber").val("");
            $("#total_spd").val("");
            $("#realisasi_spd").val("");
            $("#sisa_spd").val("");
            $("#total_angkas").val("");
            $("#realisasi_angkas").val("");
            $("#sisa_angkas").val("");
            $("#total_pagu").val("");
            $("#realisasi_pagu").val("");
            $("#sisa_pagu").val("");
            $("#nilai_sumber_dana").val("");
            $("#realisasi_sumber_dana").val("");
            $("#sisa_sumber_dana").val("");
            let tgl_bukti = document.getElementById('tgl_bukti').value;
            $.ajax({
                url: "{{ route('penagihan.cek_status_ang') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    tgl_bukti: tgl_bukti,
                },
                success: function(data) {
                    $('#status_angkas').val(data.status);
                }
            })
            $.ajax({
                url: "{{ route('penagihan.cari_rekening') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: kd_sub_kegiatan,
                },
                success: function(data) {
                    $('#kode_rekening').empty();
                    $('#kode_rekening').append(`<option value="0">Pilih Rekening</option>`);
                    $.each(data, function(index, data) {
                        $('#kode_rekening').append(
                            `<option value="${data.kd_rek6}" data-lalu="${data.lalu}" data-anggaran="${data.anggaran}" data-nama="${data.nm_rek6}" data-map="${data.map_lo}">${data.kd_rek6} | ${data.map_lo} | ${data.nm_rek6} | ${data.lalu} | ${data.sp2d} | ${data.anggaran}</option>`
                        );
                    })
                }
            })
        });
        $('#kode_rekening').on('change', function() {
            let selected = $(this).find('option:selected');
            let nm_rekening = selected.data('nama');
            let anggaran1 = selected.data('anggaran');
            let anggaran = parseInt(selected.data('anggaran')) || 0;
            let lalu = parseInt(selected.data('lalu')) || 0;
            let sisa = parseInt(anggaran - lalu) || 0;
            $("#nm_rekening").val(nm_rekening);
            $("#nm_sumber").val("");
            $("#nilai_sumber_dana").val("");
            $("#realisasi_sumber_dana").val("");
            $("#sisa_sumber_dana").val("");
            $("#total_spd").val(anggaran.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#total_angkas").val(anggaran.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#total_pagu").val(anggaran.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#realisasi_spd").val(lalu.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#realisasi_angkas").val(lalu.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#realisasi_pagu").val(lalu.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#sisa_spd").val(sisa.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#sisa_angkas").val(sisa.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#sisa_pagu").val(sisa.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            // load_sumber_dn
            let skpd = document.getElementById('kd_skpd').value;
            let kdgiat = document.getElementById('kd_sub_kegiatan').value;
            let kdrek = document.getElementById('kode_rekening').value;
            let status_ang = document.getElementById('status_anggaran').value;
            $.ajax({
                url: "{{ route('penagihan.cari_sumber_dana') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    skpd: skpd,
                    kdgiat: kdgiat,
                    kdrek: kdrek,
                    status_ang: status_ang,
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
            $("#nilai_sumber_dana").val(dana.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#realisasi_sumber_dana").val(dana_lalu.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
            $("#sisa_sumber_dana").val(sisa_dana.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));
        });
        $('#no_kontrak').on('change', function() {
            let selected = $(this).find('option:selected');
            let no_kontrak = this.value;
            let skpd = document.getElementById('kd_skpd').value;
            let dana = parseFloat(selected.data('nilai')) || 0;
            let dana_lalu = parseFloat(selected.data('lalu')) || 0;
            let sisa_dana = parseFloat(dana - dana_lalu) || 0;
            // $("#nilai_lalu").val(dana_lalu.toLocaleString('id-ID', {
            //     minimumFractionDigits: 2
            // }));
            $('#nilai_lalu').val(dana_lalu);
            // $("#sisa_kontrak").val(sisa_dana.toLocaleString('id-ID', {
            //     minimumFractionDigits: 2
            // }));
            $('#sisa_kontrak').val(sisa_dana);
            $.ajax({
                url: "{{ route('penagihan.cari_total_kontrak') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_kontrak: no_kontrak,
                    skpd: skpd,
                },
                success: function(data) {
                    let total_kontrak = parseFloat(data.total_kontrak) || 0;
                    // $("#nilai_kontrak").val(total_kontrak.toLocaleString('id-ID', {
                    //     minimumFractionDigits: 2
                    // }));
                    $('#nilai_kontrak').val(total_kontrak);
                }
            })
        });
        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });
        $('#simpan-btn').on('click', function() {
            // perhitungan nilai
            let nilai_penagihan = document.getElementById('nilai_penagihan').value;
            let nilai_tagih = nilai_penagihan.replace(/,/g, ''); //nilai input rincian penagihan
            let kode_rekening = $('#kode_rekening').find('option:selected');
            let anggaran = kode_rekening.data('anggaran'); //nilai anggaran angkas, spd, pagu
            let lalu = kode_rekening.data('lalu'); // realisasi angkas, spd, pagu
            let sisa_angkas = anggaran - lalu; //sisa angkas
            let sisa_spd = anggaran - lalu; //sisa spd
            let sisa_pagu = anggaran - lalu; //sisa pagu
            let sumber_dana = $('#sumber_dana').find('option:selected');
            let nilai_sumber = sumber_dana.data('nilai'); //nilai sumber dana
            let lalu_sumber = sumber_dana.data('lalu'); //realisasi sumber dana
            let sisa_sumber = nilai_sumber - lalu_sumber; //sisa nilai sumber dana
            let no_simpan = document.getElementById('no_tersimpan').value; //no tersimpan
            let nomor = document.getElementById('no_bukti').value; //no bast penagihan
            let sisa_spd_rekening = lalu + sisa_spd;
            // kondisi
            let kdgiat = document.getElementById('kd_sub_kegiatan').value; //sub kegiatan
            let nmgiat = document.getElementById('nm_sub_kegiatan').value; //nama sub kegiatan
            let kdrek = document.getElementById('kode_rekening').value; //rekening
            let map_lo = kode_rekening.data('map'); // map lo
            let nmrek = document.getElementById('nm_rekening').value; //nama rekening
            let sumber = document.getElementById('sumber_dana').value; //sumber
            let status_ang = document.getElementById('status_anggaran').value; //status anggaran
            let sisa_kontrak = document.getElementById('sisa_kontrak').value; //sisa kontrak
            let nosp2d = ''; //no_sp2d
            let csp2d = 0;
            let clalu = 0;
            let nilai_total_penagihan = parseFloat(document.getElementById('total_input_penagihan')
                .value) || 0;
            if (!kdgiat) {
                alert('Silahkan pilih sub kegiatan');
                return;
            };
            if (!kdrek) {
                alert('Silahkan pilih rekening');
                return;
            };
            if (!sumber) {
                alert('Silahkan pilih sumber');
                return;
            };
            if (!nilai_tagih) {
                alert('Silahkan isi nilai');
                return;
            };
            if (!status_ang) {
                alert('Silahkan pilih tanggal');
                return;
            };
            if (sumber == "221020101") {
                alert(
                    'Silahkan konfirmasi ke perbendaharaan jika ingin transaksi sumber dana DID, jika tidak maka transaksi tidak bisa di approve oleh perbendahaaraan, terima kasih'
                );
            };
            if (nilai_tagih == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
                return;
            }
            // Angkas
            if (nilai_tagih > sisa_angkas) {
                alert('Nilai Transaksi melebihi Sisa Anggaran Kas');
                return;
            }
            // sumber dana
            if (nilai_tagih > sisa_sumber) {
                alert('Nilai Melebihi Sisa Sumber Dana...!!!, Cek Lagi...!!!');
                return;
            }
            // pagu
            if (nilai_tagih > sisa_pagu) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!');
                return;
            }
            // cek jika no tersimpan tersedia
            if (no_simpan == no_bukti) {
                if (nilai_tagih > sisa_spd_rekening) {
                    alert('Nilai Total Rekeing Melebihi Sisa SPD...!!!, Cek Lagi...!!!');
                    return;
                }
            } else {
                if (nilai_tagih > sisa_spd) {
                    alert('Nilai Total Rekeing Melebihi Sisa SPD...!!!, Cek Lagi...!!!');
                    return;
                }
            }
            // spd
            if (nilai_tagih > sisa_spd) {
                alert('Nilai Melebihi Sisa Dana SPD...!!!, Cek Lagi...!!!');
                return;
            }
            // kondisi ketika kode sub kegiatan dan rekening tidak sama dengan tampungan
            let tampungan_penagihan = tabel.rows().data().toArray();
            tampungan_penagihan.map(function(data) {
                if (data.kd_sub_kegiatan == kdgiat && data.kd_rek == kdrek) {
                    if (data.kd_sub_kegiatan == kdgiat && data.kd_rek == kdrek && data.sumber ==
                        sumber) {
                        alert(
                            'Sumber tidak boleh sama dengan rincian penagihan!'
                        );
                        exit();
                    }
                } else {
                    alert(
                        'Kode sub kegiatan dan rekening tidak boleh beda dengan rincian penagihan!'
                    );
                    exit();
                }
            })
            // tampungan
            $.ajax({
                url: "{{ route('penagihan.simpan_tampungan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    nomor: nomor,
                    kdgiat: kdgiat,
                    kdrek: kdrek,
                    nilai_tagih: nilai_tagih,
                    sumber: sumber,
                },
                success: function(data) {
                    if (data.message == '0') {
                        alert('Data Detail Gagal Tersimpan');
                        return;
                    } else {
                        alert('Data Detail Tersimpan di tampungan');
                        tabel.row.add({
                            'no_bukti': nomor,
                            'no_sp2d': nosp2d,
                            'kd_sub_kegiatan': kdgiat,
                            'nm_sub_kegiatan': nmgiat,
                            'kd_rek6': map_lo,
                            'kd_rek': kdrek,
                            'nm_rek6': nmrek,
                            'nilai': nilai_tagih,
                            'lalu': clalu,
                            'sp2d': csp2d,
                            'anggaran': nilai_sumber,
                            'sumber': sumber,
                        }).draw();
                        tabel1.row.add({
                            'no_bukti': nomor,
                            'no_sp2d': nosp2d,
                            'kd_sub_kegiatan': kdgiat,
                            'nm_sub_kegiatan': nmgiat,
                            'kd_rek6': map_lo,
                            'kd_rek': kdrek,
                            'nm_rek6': nmrek,
                            'nilai': nilai_tagih,
                            'lalu': clalu,
                            'sp2d': csp2d,
                            'anggaran': nilai_sumber,
                            'sumber': sumber,
                        }).draw();
                        nilai_total_penagihan += parseFloat(nilai_tagih);
                        $('#total_input_penagihan').val(nilai_total_penagihan);
                        $('#total_nilai').val(nilai_total_penagihan);
                        $('#nm_sub_kegiatan').val('');
                        $('#nm_rekening').val('');
                        $('#nm_sumber').val('');
                        $('#total_spd').val('');
                        $('#realisasi_spd').val('');
                        $('#sisa_spd').val('');
                        $('#total_angkas').val('');
                        $('#realisasi_angkas').val('');
                        $('#sisa_angkas').val('');
                        $('#total_pagu').val('');
                        $('#realisasi_pagu').val('');
                        $('#sisa_pagu').val('');
                        $('#nilai_sumber_dana').val('');
                        $('#realisasi_sumber_dana').val('');
                        $('#sisa_sumber_dana').val('');
                        $('#nilai_penagihan').val('');
                        $('#kd_sub_kegiatan').val("");
                        $('#kode_rekening').empty();
                        $('#sumber_dana').empty();
                    }
                }
            })
        });
        $('#simpan_penagihan').on('click', function() {
            let no_bukti = document.getElementById('no_bukti').value;
            let no_kontrak = document.getElementById('no_kontrak').value;
            let ket = document.getElementById('ket').value;
            let ket_bast = document.getElementById('ket_bast').value;
            let total_nilai = parseFloat(document.getElementById('total_nilai').value);
            let sisa_kontrak = parseFloat(document.getElementById('sisa_kontrak').value);
            let tgl_bukti = document.getElementById('tgl_bukti').value;
            let no_tersimpan = document.getElementById('no_tersimpan').value;
            let status_bayar = document.getElementById('status_bayar').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = document.getElementById('nm_skpd').value;
            let jenis = document.getElementById('jenis').value;
            let rekanan = document.getElementById('rekanan').value;
            let cjenis = '6';
            let cstatus = '';
            let rincian_penagihan = tabel1.rows().data().toArray().map((value) => {
                let data = {
                    no_bukti: value.no_bukti,
                    no_sp2d: value.no_sp2d,
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    nm_sub_kegiatan: value.nm_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    kd_rek: value.kd_rek,
                    nm_rek6: value.nm_rek6,
                    nilai: value.nilai,
                    kd_skpd: kd_skpd,
                    sumber: value.sumber,
                };
                return data;
            });
            let tanggal = new Date(tgl_bukti);
            let tahun_anggaran = new Date().getFullYear();
            let tahun = tanggal.getFullYear();
            let ctagih = '';
            let ctgltagih = '2016-12-1';
            let jns_trs = '1';
            if (!no_bukti) {
                alert('No. BAST/Penagihan harus diisi!');
                return;
            }
            if (!tgl_bukti) {
                alert('Tanggal Bukti Tidak Boleh Kosong');
                return;
            }
            if (!kd_skpd) {
                alert('Kode SKPD Tidak Boleh Kosong');
                return;
            }
            if (!nm_skpd) {
                alert('Nama SKPD Tidak Boleh Kosong');
                return;
            }
            if (!rekanan) {
                alert('Rekanan Tidak Boleh Kosong');
                return;
            }
            if (total_nilai > sisa_kontrak) {
                alert("Nilai penagihan melebihi sisa sisa nilai kontrak", "error");
                return;
            }
            if (!ket) {
                alert("Keterangan Tidak Boleh Kosong", "error");
                return;
            }
            if (ket.length > 1000) {
                alert('Keterangan Tidak boleh lebih dari 1000 karakter');
                return;
            }
            if (!ket_bast) {
                alert("Keterangan (BA) Tidak Boleh Kosong", "error");
                return;
            }
            if (!no_kontrak) {
                alert("Nomor Kontrak Tidak Boleh Kosong", "error");
                return;
            }
            let cek = 0;
            cek_nilai_kontrak(no_bukti, no_kontrak, ket, ket_bast, total_nilai, sisa_kontrak, tgl_bukti,
                no_tersimpan, status_bayar, kd_skpd, nm_skpd, jenis, rekanan, cjenis, cstatus,
                rincian_penagihan, tahun_anggaran, tahun, ctagih, ctgltagih, jns_trs, cek);
        });

        function cek_nilai_kontrak(no_bukti, no_kontrak, ket, ket_bast, total_nilai, sisa_kontrak, tgl_bukti,
            no_tersimpan, status_bayar, kd_skpd, nm_skpd, jenis, rekanan, cjenis, cstatus, rincian_penagihan,
            tahun_anggaran, tahun, ctagih, ctgltagih, jns_trs, cek) {
            $.ajax({
                url: "{{ route('penagihan.cek_nilai_kontrak') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_kontrak: no_kontrak,
                    tgl_bukti: tgl_bukti,
                },
                success: function(data) {
                    let nilai = parseFloat(data.total) || 0;
                    cek = nilai + total_nilai;
                    if (cek > 0) {
                        cek_nilai_kontrak2(no_bukti, no_kontrak, ket, ket_bast, total_nilai,
                            sisa_kontrak, tgl_bukti, no_tersimpan, status_bayar, kd_skpd,
                            nm_skpd, jenis, rekanan, cjenis, cstatus, rincian_penagihan,
                            tahun_anggaran, tahun, ctagih, ctgltagih, jns_trs, cek);
                    }
                }
            })
        }

        function cek_nilai_kontrak2(no_bukti, no_kontrak, ket, ket_bast, total_nilai, sisa_kontrak, tgl_bukti,
            no_tersimpan, status_bayar, kd_skpd, nm_skpd, jenis, rekanan, cjenis, cstatus, rincian_penagihan,
            tahun_anggaran, tahun, ctagih, ctgltagih, jns_trs, cek) {
            $.ajax({
                url: "{{ route('penagihan.cek_nilai_kontrak2') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_kontrak: no_kontrak,
                    tgl_bukti: tgl_bukti,
                },
                success: function(data) {
                    if (cek > data.nilai) {
                        alert("Nilai Penagihan Melebihi Inputan Master Kontrak.!!");
                        exit();
                    } else {
                        if (tahun != tahun_anggaran) {
                            alert('Tahun tidak sama dengan tahun Anggaran');
                            exit();
                        }
                        if (cstatus == false) {
                            cstatus = 0;
                        } else {
                            cstatus = 1;
                        }
                        $.ajax({
                            url: "{{ route('penagihan.cek_simpan_penagihan') }}",
                            type: "POST",
                            dataType: 'json',
                            data: {
                                no_bukti: no_bukti,
                            },
                            success: function(data) {
                                if (data.jumlah == 1) {
                                    alert("Nomor Telah Dipakai!");
                                    document.getElementById("no_bukti").focus();
                                    exit();
                                } else {
                                    alert("Nomor Bisa dipakai");
                                    simpan_penagihan(no_bukti, no_kontrak, ket,
                                        ket_bast, total_nilai, sisa_kontrak,
                                        tgl_bukti, no_tersimpan, status_bayar,
                                        kd_skpd, nm_skpd, jenis, rekanan, cjenis,
                                        cstatus, rincian_penagihan, tahun_anggaran,
                                        tahun, ctagih, ctgltagih, jns_trs, cek);
                                }
                            }
                        })
                    }
                }
            })
        }

        function simpan_penagihan(no_bukti, no_kontrak, ket, ket_bast, total_nilai, sisa_kontrak, tgl_bukti,
            no_tersimpan, status_bayar, kd_skpd, nm_skpd, jenis, rekanan, cjenis, cstatus, rincian_penagihan,
            tahun_anggaran, tahun, ctagih, ctgltagih, jns_trs, cek) {
            $.ajax({
                url: "{{ route('penagihan.simpan_penagihan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_bukti: no_bukti,
                    tgl_bukti: tgl_bukti,
                    ket: ket,
                    kd_skpd: kd_skpd,
                    nm_skpd: nm_skpd,
                    total_nilai: total_nilai,
                    ctagih: ctagih,
                    cstatus: cstatus,
                    status_bayar: status_bayar,
                    ctgltagih: ctgltagih,
                    cjenis: cjenis,
                    jenis: jenis,
                    no_kontrak: no_kontrak,
                    jns_trs: jns_trs,
                    ket_bast: ket_bast,
                    rekanan: rekanan,
                },
                success: function(data) {
                    if (data.message == '0') {
                        alert('Gagal Simpan..!!');
                        exit();
                    } else if (data.message == '1') {
                        alert('Data Sudah Ada..!!');
                        exit();
                    } else {
                        simpan_detail_penagihan(no_bukti, no_kontrak, ket, ket_bast, total_nilai,
                            sisa_kontrak, tgl_bukti,
                            no_tersimpan, status_bayar, kd_skpd, nm_skpd, jenis, rekanan,
                            cjenis, cstatus, rincian_penagihan,
                            tahun_anggaran, tahun, ctagih, ctgltagih, jns_trs, cek);
                    }
                }
            })
        }

        function simpan_detail_penagihan(no_bukti, no_kontrak, ket, ket_bast, total_nilai, sisa_kontrak,
            tgl_bukti,
            no_tersimpan, status_bayar, kd_skpd, nm_skpd, jenis, rekanan, cjenis, cstatus, rincian_penagihan,
            tahun_anggaran, tahun, ctagih, ctgltagih, jns_trs, cek) {
            $.ajax({
                url: "{{ route('penagihan.simpan_detail_penagihan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_bukti: no_bukti,
                    status_bayar: status_bayar,
                    rincian_penagihan: rincian_penagihan,
                    kd_skpd: kd_skpd,
                },
                success: function(data) {
                    if (data.message == '5') {
                        alert('Data Detail Gagal Tersimpan');
                    } else {
                        alert('Data Tersimpan..!!');
                        location.reload();
                    }
                }
            })
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
