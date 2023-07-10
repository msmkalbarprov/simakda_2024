<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let rincian_rekening = $('#rincian_rekening').DataTable({
            responsive: true,
            processing: true,
            ordering: false,
            lengthMenu: [5, 10],
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
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });

        let input_rekening = $('#input_rekening').DataTable({
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
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('.select2-modal').select2({
            dropdownParent: $('#modal_rekening .modal-content'),
            theme: 'bootstrap-5'
        });

        $('.select2-modal1').select2({
            dropdownParent: $('#modal_rekening_tujuan .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#tambah_rekening').on('click', function() {
            let beban = document.getElementById('beban').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let tgl_bukti = document.getElementById('tgl_bukti').value;
            let pembayaran = document.getElementById('pembayaran').value;
            if (beban && kd_skpd && tgl_bukti && pembayaran) {
                status_anggaran();
                status_angkas();
                $('#modal_rekening').modal('show');
            } else {
                Swal.fire({
                    title: 'Harap Isi Kode SKPD, Tanggal Transaksi, Pembayaran & Jenis Beban SP2D',
                    confirmButtonColor: '#5b73e8',
                })
            }
        });

        $('#tambah_rekening_tujuan').on('click', function() {
            $('#modal_rekening_tujuan').modal('show');
        });

        $('#kd_sub_kegiatan').on('select2:select', function() {
            let kd_sub_kegiatan = this.value;
            let nm_sub_kegiatan = $(this).find(':selected').data('nama');
            $('#nm_sub_kegiatan').val(nm_sub_kegiatan);

            $('#no_sp2d').empty();
            $('#kd_rekening').empty();
            $('#sumber').empty();
            $('#nm_rekening').val(null);
            $('#nm_sumber').val(null);
            $('#total_spd').val(null);
            $('#realisasi_spd').val(null);
            $('#sisa_spd').val(null);
            $('#total_angkas').val(null);
            $('#realisasi_angkas').val(null);
            $('#sisa_angkas').val(null);
            $('#total_anggaran').val(null);
            $('#realisasi_anggaran').val(null);
            $('#sisa_anggaran').val(null);
            $('#total_sumber').val(null);
            $('#realisasi_sumber').val(null);
            $('#sisa_sumber').val(null);
            $('#sisa_kas').val(null);
            $('#potongan_ls').val(null);
            $('#total_sisa').val(null);
            cari_nomor(kd_sub_kegiatan);

            let kd_rekening = document.getElementById('kd_rekening').value;
            let sumber = document.getElementById('sumber').value;

            let kode = kd_sub_kegiatan + '.' + kd_rekening + '.' + sumber;

            $('#kode_transaksi').val(kode);
        });

        $('#no_sp2d').on('select2:select', function() {
            let no_sp2d = this.value;
            let tgl_sp2d = $(this).find(':selected').data('tgl');
            let tgl_bukti = document.getElementById('tgl_bukti').value;
            let beban = document.getElementById('beban').value;
            if (tgl_sp2d > tgl_bukti) {
                alert('Kesalahan, Tanggal Sp2d lebih kecil Dari Tanggal Bukti');
                $("#no_sp2d").val(null).change();
                return;
            }

            $('#kd_rekening').empty();
            $('#sumber').empty();
            $('#nm_rekening').val(null);
            $('#nm_sumber').val(null);
            $('#total_spd').val(null);
            $('#realisasi_spd').val(null);
            $('#sisa_spd').val(null);
            $('#total_angkas').val(null);
            $('#realisasi_angkas').val(null);
            $('#sisa_angkas').val(null);
            $('#total_anggaran').val(null);
            $('#realisasi_anggaran').val(null);
            $('#sisa_anggaran').val(null);
            $('#total_sumber').val(null);
            $('#realisasi_sumber').val(null);
            $('#sisa_sumber').val(null);
            $('#sisa_kas').val(null);
            $('#potongan_ls').val(null);
            $('#total_sisa').val(null);
            cari_rekening(no_sp2d);
            // load_sisa_bank();
            // load_sisa_tunai(no_sp2d);

            // load_potongan_ls(no_sp2d); LAMA
        });

        $('#kd_rekening').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            let sp2d = $(this).find(':selected').data('sp2d');
            let anggaran = $(this).find(':selected').data('anggaran');
            let lalu = $(this).find(':selected').data('lalu');
            $('#nm_rekening').val(nama);
            let kd_rek6 = this.value;
            let beban = document.getElementById('beban').value;
            $('#sumber').empty();
            $('#nm_sumber').val(null);
            $('#total_spd').val(null);
            $('#realisasi_spd').val(null);
            $('#sisa_spd').val(null);
            $('#total_angkas').val(null);
            $('#realisasi_angkas').val(null);
            $('#sisa_angkas').val(null);
            $('#total_anggaran').val(null);
            $('#realisasi_anggaran').val(null);
            $('#sisa_anggaran').val(null);
            $('#total_sumber').val(null);
            $('#realisasi_sumber').val(null);
            $('#sisa_sumber').val(null);
            // load_angkas();
            cari_sumber(kd_rek6);
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

            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let sumber = document.getElementById('sumber').value;

            let kode = kd_sub_kegiatan + '.' + kd_rek6 + '.' + sumber;

            $('#kode_transaksi').val(kode);
        })

        $('#sumber').on('select2:select', function() {
            let sumber = this.value;
            if (sumber == 'null') {
                alert('Sumber dana tidak dapat digunakan!');
                $('#sumber').val(null).change();
                return;
            }
            let beban = document.getElementById('beban').value;
            let realisasi_sumber = rupiah(document.getElementById('realisasi_sumber').value);
            let nilai = $(this).find(':selected').data('nilai');
            let sisa = 0;
            if (beban == '1') {
                sisa = nilai - realisasi_sumber;
                $('#total_sumber').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai));
            } else {
                let sp2d = rupiah(document.getElementById('total_anggaran').value);
                sisa = sp2d - realisasi_sumber;
                $('#total_sumber').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(sp2d));
            }
            $('#sisa_sumber').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(sisa));

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
            // load_dana(sumber);

            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let kd_rekening = document.getElementById('kd_rekening').value;

            let kode = kd_sub_kegiatan + '.' + kd_rekening + '.' + sumber;

            $('#kode_transaksi').val(kode);
        })

        $('#simpan_rekening').on('click', function() {
            let no_bukti = document.getElementById('no_bukti').value;
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let nm_sub_kegiatan = document.getElementById('nm_sub_kegiatan').value;
            let no_sp2d = document.getElementById('no_sp2d').value;
            let kd_rekening = document.getElementById('kd_rekening').value;
            let sumber = document.getElementById('sumber').value;
            let beban = document.getElementById('beban').value;
            let nm_rekening = document.getElementById('nm_rekening').value;
            let pembayaran = document.getElementById('pembayaran').value;
            let status_anggaran = document.getElementById('status_anggaran').value;

            let sisa_anggaran = rupiah(document.getElementById('sisa_anggaran').value);
            let nilai = angka(document.getElementById('nilai').value);
            let total_sisa = rupiah(document.getElementById('total_sisa').value);
            let sisa_spd = rupiah(document.getElementById('sisa_spd').value);
            let total_input_rekening = rupiah(document.getElementById('total_input_rekening').value);
            let potongan_ls = rupiah(document.getElementById('potongan_ls').value);
            let sisa_sumber = rupiah(document.getElementById('sisa_sumber').value);
            let sisa_angkas = rupiah(document.getElementById('sisa_angkas').value);
            let realisasi_anggaran = rupiah(document.getElementById('realisasi_anggaran').value);
            let kd_rekening1 = $('#kd_rekening').find('option:selected');
            let anggaran = kd_rekening1.data('anggaran');
            let lalu = kd_rekening1.data('lalu');
            let sp2d = kd_rekening1.data('sp2d');

            let kode_transaksi = document.getElementById('kode_transaksi').value;

            let kode = kd_sub_kegiatan + '.' + kd_rekening + '.' + sumber;

            let sp2d_1 = no_sp2d.split('/');
            let sp2d_2 = no_bukti + "." + sp2d_1[0] + "/" + sp2d_1[2] + "." + kd_rekening;
            $('#ketcms').val(sp2d_2);

            let akumulasi = nilai + total_input_rekening;

            if (!kd_sub_kegiatan) {
                alert('Pilih Kegiatan Dahulu');
                return;
            }

            if (!nm_sub_kegiatan) {
                alert('Pilih Kegiatan Dahulu');
                return;
            }

            if (!no_sp2d) {
                alert('Pilih SP2D Dahulu');
                return;
            }

            if (!kd_rekening) {
                alert('Pilih Rekening Dahulu');
                return;
            }

            if (!nm_rekening) {
                alert('Pilih Rekening Dahulu');
                return;
            }

            if (!sumber) {
                alert('Pilih Sumber Dana Dahulu');
                return;
            }

            if (!status_anggaran) {
                alert('Pilih Tanggal Dahulu');
                return;
            }

            let tampungan = rincian_rekening.rows().data().toArray().map((value) => {
                let result = {
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    sumber: value.sumber,
                };
                return result;
            });

            let kondisi = tampungan.map(function(data) {
                if (data.kd_rek6 == kd_rekening && data.sumber == sumber) {
                    return '2';
                } else if (data.kd_sub_kegiatan != kd_sub_kegiatan) {
                    return '3';
                } else {
                    return '1';
                }
            });

            if (kode != kode_transaksi) {
                alert(
                    'Kegiatan,rekening,sumber tidak sesuai dengan rincian realisasi dan sisa, silahkan refresh!'
                );
                return;
            }

            if (kondisi.includes("2")) {
                alert('Tdk boleh memilih rekening dgn sumber dana yg sama dlm 1 no bku');
                return;
            }

            if (kondisi.includes("3")) {
                alert('Tdk boleh memilih kegiatan berbeda dlm 1 no bku');
                return;
            }

            if (!pembayaran) {
                alert('Jenis Pembayaran Tidak Boleh Kosong');
                return;
            }

            if (pembayaran == 'TUNAI' && (nilai > total_sisa)) {
                alert('Total Transaksi melebihi Sisa Kas Tunai');
                return;
            }

            if (nilai == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
                return;
            }

            if (nilai > sisa_angkas) {
                alert('Nilai Transaksi melebihi Sisa Anggaran Kas');
                return;
            }

            if (nilai > sisa_anggaran) {
                alert('Nilai Melebihi Sisa Anggaran...!!!, Cek Lagi...!!!');
                return;
            }

            if (nilai > sisa_sumber) {
                alert('Nilai Melebihi Sisa Anggaran Sumber Dana...!!!, Cek Lagi...!!!');
                return;
            }

            if (beban == '1' && (akumulasi > sisa_spd)) {
                alert('Total Transaksi melebihi Sisa SPD');
                return;
            }

            $.ajax({
                url: "{{ route('penagihan.simpan_tampungan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    nomor: no_bukti,
                    kdgiat: kd_sub_kegiatan,
                    kdrek: kd_rekening,
                    nilai_tagih: nilai,
                    sumber: sumber,
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(response) {
                    if (response.message == '0') {
                        alert('Data Detail Gagal Tersimpan');
                        return;
                    } else if (response.message == '2') {
                        alert(
                            'SKPD, Kegiatan, Rekening, Sumber telah ada di tampungan! Silahkan refresh!'
                        );
                        return;
                    } else {
                        // proses input ke tabel input detail spp
                        alert('Data Detail Tersimpan');
                        rincian_rekening.row.add({
                            'no_bukti': no_bukti,
                            'no_sp2d': no_sp2d,
                            'kd_sub_kegiatan': kd_sub_kegiatan,
                            'nm_sub_kegiatan': nm_sub_kegiatan,
                            'kd_rek6': kd_rekening,
                            'nm_rek6': nm_rekening,
                            'nilai': new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(nilai),
                            'sumber': sumber,
                            'lalu': new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(lalu),
                            'sp2d': new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(sp2d),
                            'anggaran': new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(anggaran),
                            'aksi': `<a href="javascript:void(0);" onclick="deleteData('${no_bukti}','${kd_sub_kegiatan}','${kd_rekening}','${sumber}','${nilai}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>`,
                        }).draw();
                        input_rekening.row.add({
                            'no_bukti': no_bukti,
                            'no_sp2d': no_sp2d,
                            'kd_sub_kegiatan': kd_sub_kegiatan,
                            'nm_sub_kegiatan': nm_sub_kegiatan,
                            'kd_rek6': kd_rekening,
                            'nm_rek6': nm_rekening,
                            'nilai': new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(nilai),
                            'sumber': sumber,
                            'lalu': lalu,
                            'sp2d': sp2d,
                            'anggaran': anggaran,
                            'aksi': `<a href="javascript:void(0);" onclick="deleteData('${no_bukti}','${kd_sub_kegiatan}','${kd_rekening}','${sumber}','${nilai}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>`,
                        }).draw();

                        $('#total_input_rekening').val(new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(total_input_rekening + nilai));
                        $('#total').val(new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(total_input_rekening + nilai));

                        kosong_input_detail();
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            });
        });

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });

        $('#beban').on('select2:select', function() {
            rincian_rekening.clear().draw();
            input_rekening.clear().draw();
            $('#total').val(null);
            $('#sisa_tunai').val(null);
            $('#sisa_kas').val(null);
            $('#potongan_ls').val(null);
            $('#total_sisa').val(null);
            $('#total_input_rekening').val(null);
            $('#kd_sub_kegiatan').val(null).change();
            $('#nm_sub_kegiatan').val(null);
            $('#no_sp2d').empty();
            $('#kd_rekening').empty();
            $('#nm_rekening').val(null);
            $('#sumber').empty();
            $('#nm_sumber').val(null);

            $('#total_spd').val(null);
            $('#realisasi_spd').val(null);
            $('#sisa_spd').val(null);

            $('#total_angkas').val(null);
            $('#realisasi_angkas').val(null);
            $('#sisa_angkas').val(null);

            $('#total_anggaran').val(null);
            $('#realisasi_anggaran').val(null);
            $('#sisa_anggaran').val(null);

            $('#total_sumber').val(null);
            $('#realisasi_sumber').val(null);
            $('#sisa_sumber').val(null);
        });

        $('#simpan_transaksi').on('click', function() {
            let no_bukti = document.getElementById('no_bukti').value;
            let tgl_bukti = document.getElementById('tgl_bukti').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = document.getElementById('nm_skpd').value;
            let keterangan = document.getElementById('keterangan').value;
            let beban = document.getElementById('beban').value;
            let pembayaran = document.getElementById('pembayaran').value;
            let tahun_anggaran = document.getElementById('tahun_anggaran').value;
            let ketcms = document.getElementById('ketcms').value;

            let total = rupiah(document.getElementById('total').value);
            let total_sisa = rupiah(document.getElementById('total_sisa').value);

            let tabel_rincian = rincian_rekening.rows().data().toArray().map((value) => {
                let data = {
                    no_bukti: value.no_bukti,
                    no_sp2d: value.no_sp2d,
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    nm_sub_kegiatan: value.nm_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    nm_rek6: value.nm_rek6,
                    nilai: rupiah(value.nilai),
                    sumber: value.sumber,
                    lalu: value.lalu,
                    sp2d: value.sp2d,
                    anggaran: value.anggaran,
                    volume: value.volume,
                    satuan: value.satuan,
                };
                return data;
            });

            if (beban == '1') {
                alert(
                    'Pada transaski UP/GU hanya boleh 1 Rekening Belanja, Info lebih lanjut silahkan hubungi bidang Perbendaharaan'
                );
            }

            if (tabel_rincian.length == 0) {
                alert('Rincian Rekening tidak boleh kosong!');
                return;
            }

            let no_sp2d = rincian_rekening.rows().data().toArray().map((value) => {
                let data = {
                    no_sp2d: value.no_sp2d,
                };
                return data;
            });
            let sp2d = no_sp2d[0]['no_sp2d'];

            let tahun_input = tgl_bukti.substr(0, 4);

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (!pembayaran) {
                alert('Jenis Pembayaran Tidak Boleh Kosong');
                return;
            }

            if (pembayaran == 'TUNAI' && total > total_sisa) {
                alert('Nilai Melebihi sisa KAS Tunai');
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

            if (!beban) {
                alert('Jenis beban Tidak Boleh Kosong');
                return;
            }

            if (!keterangan) {
                alert('Keterangan Tidak Boleh Kosong');
                return;
            }

            if (total == '0') {
                alert('Rincian Tidak ada rekening!');
                return;
            }

            let response = {
                no_bukti,
                tgl_bukti,
                kd_skpd,
                nm_skpd,
                beban,
                keterangan,
                total,
                pembayaran,
                ketcms,
                sp2d,
                tabel_rincian,
            };

            $('#simpan_transaksi').prop('disabled', true);
            $.ajax({
                url: "{{ route('skpd.transaksi_tunai.simpan_transaksi') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: response
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Data berhasil ditambahkan, dengan Nomor Bukti : ' + data
                            .no_bukti);
                        window.location.href =
                            "{{ route('skpd.transaksi_tunai.index') }}";
                    } else {
                        alert('Data tidak berhasil ditambahkan!');
                        $('#simpan_transaksi').prop('disabled', false);
                        return;
                    }
                }
            })
        });

        function kosong_input_detail() {
            $('#kd_rekening').val(null).change();
            $('#nm_rekening').val(null);
            $('#sumber').empty();
            $('#nm_sumber').val(null);

            $('#total_spd').val(null);
            $('#realisasi_spd').val(null);
            $('#sisa_spd').val(null);
            // total anggaran kas
            $('#total_angkas').val(null);
            $('#realisasi_angkas').val(null);
            $('#sisa_angkas').val(null);
            // anggaran penyusunan
            $('#total_anggaran').val(null);
            $('#realisasi_anggaran').val(null);
            $('#sisa_anggaran').val(null);
            // sumber dana penyusunan
            $('#total_sumber').val(null);
            $('#realisasi_sumber').val(null);
            $('#sisa_sumber').val(null);

            $('#nilai').val(null);
        }

        function cari_nomor(kd_sub_kegiatan) {
            $.ajax({
                url: "{{ route('skpd.transaksi_tunai.nomor_sp2d') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    beban: document.getElementById('beban').value,
                    kd_skpd: document.getElementById('kd_skpd').value,
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
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
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        }

        function cari_rekening(no_sp2d) {
            $.ajax({
                url: "{{ route('skpd.transaksi_tunai.cari_rekening') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_sp2d: no_sp2d,
                    no_bukti: document.getElementById('no_bukti').value,
                    beban: document.getElementById('beban').value,
                    kd_sub_kegiatan: document.getElementById('kd_sub_kegiatan').value,
                    kd_skpd: document.getElementById('kd_skpd').value,
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    let rekening = data.rekening;
                    $('#kd_rekening').empty();
                    $('#kd_rekening').append(
                        `<option value="" disabled selected>Pilih Rekening</option>`);
                    $.each(rekening, function(index, rekening) {
                        $('#kd_rekening').append(
                            `<option value="${rekening.kd_rek6}" data-nama="${rekening.nm_rek6}" data-sp2d="${rekening.sp2d}" data-anggaran="${rekening.anggaran}" data-lalu="${rekening.lalu}">${rekening.kd_rek6} | ${rekening.nm_rek6} | ${rekening.lalu}</option>`
                        );
                    });

                    let sisa_bank = parseFloat(data.sisa_bank) || 0;
                    let persen_kkpd = document.getElementById('persen_kkpd').value;
                    let persen_tunai = document.getElementById('persen_tunai').value;
                    let beban = document.getElementById('beban').value;
                    let sisa_kas;
                    if (beban == 1) {
                        sisa_kas = (persen_kkpd / 100) * sisa_bank;
                    } else {
                        sisa_kas = (persen_tunai / 100) * sisa_bank;
                    }
                    $('#sisa_kas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(sisa_kas));

                    let sisa_tunai = parseFloat(data.sisa_tunai) ||
                        0;
                    $('#sisa_tunai').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(sisa_tunai));

                    let potongan_ls = parseFloat(data.potongan_ls) || 0;
                    $('#potongan_ls').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(potongan_ls));
                    $('#total_sisa').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(sisa_tunai + potongan_ls));
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        }

        function cari_sumber(kd_rek6) {
            $.ajax({
                url: "{{ route('penagihan.cari_sumber_dana_tunai') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kdrek: kd_rek6,
                    skpd: document.getElementById('kd_skpd').value,
                    kdgiat: document.getElementById('kd_sub_kegiatan').value,
                    tgl_voucher: document.getElementById('tgl_bukti').value,
                    beban: document.getElementById('beban').value,
                    status_angkas: document.getElementById('status_angkas').value,
                    no_sp2d: document.getElementById('no_sp2d').value,
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    let sumber = data.sumber;
                    $('#sumber').empty();
                    $('#sumber').append(
                        `<option value="" disabled selected>Pilih Sumber Dana</option>`);
                    $.each(sumber, function(index, sumber) {
                        $('#sumber').append(
                            `<option value="${sumber.sumber}" data-nilai="${sumber.nilai}">${sumber.sumber}</option>`
                        );
                    });

                    $('#total_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas));

                    $('#realisasi_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas_lalu));

                    $('#realisasi_sumber').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas_lalu));

                    $('#realisasi_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas_lalu));

                    $('#sisa_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas - data.angkas_lalu));

                    $('#total_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.spd));
                    $('#sisa_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.spd - data.angkas_lalu));

                    $('#realisasi_anggaran').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas_lalu));
                    let total_anggaran = rupiah(document.getElementById('total_anggaran').value)
                    $('#sisa_anggaran').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(total_anggaran - data.angkas_lalu));
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
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
                    let persen_kkpd = document.getElementById('persen_kkpd').value;
                    let persen_tunai = document.getElementById('persen_tunai').value;
                    let beban = document.getElementById('beban').value;
                    let sisa_kas;
                    if (beban == 1) {
                        sisa_kas = (persen_kkpd / 100) * nilai;
                    } else {
                        sisa_kas = (persen_tunai / 100) * nilai;
                    }
                    $('#sisa_kas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(sisa_kas));
                }
            })
        }

        function load_sisa_tunai(no_sp2d) {
            $.ajax({
                url: "{{ route('skpd.transaksi_tunai.sisa_tunai') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    let nilai = parseFloat(data.terima - data.keluar) || 0;
                    $('#sisa_tunai').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(nilai));
                    load_potongan_ls(no_sp2d);
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
                    let sisa_tunai = rupiah(document.getElementById('sisa_tunai').value);
                    $('#total_sisa').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(sisa_tunai + nilai));
                }
            })
        }

        function load_angkas() {
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let kd_rekening = document.getElementById('kd_rekening').value;
            let tgl_voucher = document.getElementById('tgl_bukti').value;
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
            let tgl_voucher = document.getElementById('tgl_bukti').value;
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
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    $('#realisasi_sumber').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data));
                    let total_sumber = rupiah(document.getElementById('total_sumber').value);
                    $('#sisa_sumber').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(total_sumber - data));
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
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
            let tanggal = document.getElementById('tgl_bukti').value;
            $.ajax({
                url: "{{ route('penagihan.cek_status_ang') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#status_angkas').val(data.status);
                }
            })
        }
    });

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

    function angka(n) {
        let nilai = n.split(',').join('');
        return parseFloat(nilai) || 0;
    }

    function rupiah(n) {
        let n1 = n.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }

    function deleteData(no_bukti, kd_sub_kegiatan, kd_rek, sumber, nilai) {
        let tabel = $('#input_rekening').DataTable();
        let tabel1 = $('#rincian_rekening').DataTable();
        let nilai_rekening = parseFloat(nilai);
        let nilai_sementara = rupiah(document.getElementById('total_input_rekening').value);
        let hapus = confirm('Yakin Ingin Menghapus Data, Rekening : ' + kd_rek + '  Nilai :  ' + nilai +
            ' ?');
        if (hapus == true) {
            $.ajax({
                url: "{{ route('penagihan.hapus_detail_tampungan_penagihan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_bukti: no_bukti,
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    kd_rek: kd_rek,
                    sumber: sumber,
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(response) {
                    if (response.message == '0') {
                        alert('Data detail gagal dihapus');
                        return;
                    } else {
                        alert('Data detail berhasil dihapus');
                        tabel.rows(function(idx, data, node) {
                            return data.sumber == sumber && data.kd_sub_kegiatan ==
                                kd_sub_kegiatan &&
                                data.kd_rek6 == kd_rek
                        }).remove().draw();
                        tabel1.rows(function(idx, data, node) {
                            return data.sumber == sumber && data.kd_sub_kegiatan ==
                                kd_sub_kegiatan &&
                                data.kd_rek6 == kd_rek
                        }).remove().draw();
                        $('#total_input_rekening').val(new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(nilai_sementara - nilai_rekening));
                        $('#total').val(new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(nilai_sementara - nilai_rekening));
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            });
        } else {
            return false;
        }

    }
</script>
