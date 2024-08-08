<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let nilai_kunci = 0;

        $('#jenis_belanja').prop('disabled', true);

        let tabel_rekening = $('#input_rekening').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ordering: false,
            ajax: {
                "url": "{{ route('dpr.detail_edit') }}",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "data": function(d) {
                    d.no_dpr = document.getElementById('no_dpr').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                },
                "dataSrc": function(data) {
                    recordsTotal = data.data;
                    return recordsTotal;
                },
            },
            lengthMenu: [
                [-1],
                ["All"]
            ],
            columns: [{
                    data: 'no_dpr',
                    name: 'no_dpr',
                    visible: false
                },
                {
                    data: 'tgl_transaksi',
                    name: 'tgl_transaksi',
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
                    data: null,
                    name: 'nilai',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai)
                    }
                },
                {
                    data: 'sumber',
                    name: 'sumber',
                    visible: false
                },
                {
                    data: 'nm_sumber',
                    name: 'nm_sumber',
                },
                {
                    data: 'bukti',
                    name: 'bukti',
                    visible: false
                },
                {
                    data: null,
                    name: 'nm_bukti',
                    visible: false,
                    render: function(data, type, row, meta) {
                        return data.bukti == '1' ? 'YA' : 'TIDAK'
                    }
                },
                {
                    data: 'uraian',
                    name: 'uraian',
                    visible: false
                },
                {
                    data: 'pembayaran',
                    name: 'pembayaran',
                    visible: false
                },
                {
                    data: null,
                    name: 'nm_pembayaran',
                    visible: false,
                    render: function(data, type, row, meta) {
                        switch (data.pembayaran) {
                            case '1':
                                return 'KATALOG';
                                break;
                            case '2':
                                return 'TOKO DARING';
                                break;
                            case '3':
                                return 'LPSE';
                                break;
                            case '4':
                                return 'LAIN-LAIN';
                                break;
                            default:
                                return '';
                                break;
                        }
                    }

                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ],
            drawCallback: function(select) {
                let total = recordsTotal.reduce((previousValue,
                    currentValue) => (previousValue += parseFloat(currentValue.nilai)), 0);
                $('#total_input_rekening').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total));
                $('#total_belanja').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total));
            }
        });

        let tabel_rekening1 = $('#rincian_rekening').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                "url": "{{ route('dpr.detail_edit') }}",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "data": function(d) {
                    d.no_dpr = document.getElementById('no_dpr').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                },
                "dataSrc": function(data) {
                    recordsTotal = data.data;
                    return recordsTotal;
                },
            },
            lengthMenu: [
                [-1],
                ["All"]
            ],
            columns: [{
                    data: 'no_dpr',
                    name: 'no_dpr',
                    visible: false
                },
                {
                    data: 'tgl_transaksi',
                    name: 'tgl_transaksi',
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
                    data: null,
                    name: 'nilai',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai)
                    }
                },
                {
                    data: 'sumber',
                    name: 'sumber',
                    visible: false
                },
                {
                    data: 'nm_sumber',
                    name: 'nm_sumber',
                },
                {
                    data: 'bukti',
                    name: 'bukti',
                    visible: false
                },
                {
                    data: null,
                    name: 'nm_bukti',
                    render: function(data, type, row, meta) {
                        return data.bukti == '1' ? 'YA' : 'TIDAK'
                    }
                },
                {
                    data: 'uraian',
                    name: 'uraian',
                },
                {
                    data: 'pembayaran',
                    name: 'pembayaran',
                    visible: false
                },
                {
                    data: null,
                    name: 'nm_pembayaran',
                    render: function(data, type, row, meta) {
                        switch (data.pembayaran) {
                            case '1':
                                return 'KATALOG';
                                break;
                            case '2':
                                return 'TOKO DARING';
                                break;
                            case '3':
                                return 'LPSE';
                                break;
                            case '4':
                                return 'LAIN-LAIN';
                                break;
                            default:
                                return '';
                                break;
                        }
                    }
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ],
            drawCallback: function(select) {
                let total = recordsTotal.reduce((previousValue,
                    currentValue) => (previousValue += parseFloat(currentValue.nilai)), 0);
                $('#total_input_rekening').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total));
                $('#total_belanja').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total));
            }
        });

        cari_kegiatan("{{ $dpr->jenis_belanja }}", "{{ $dpr->kd_skpd }}")

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

        $('#jenis_belanja').on('select2:select', function() {
            let jenis_belanja = this.value;
            let kd_skpd = document.getElementById('kd_skpd').value;

            if (!kd_skpd) {
                alert('Isi terlebih dahulu Kode SKPD!');
                $("#jenis_belanja").val(null).change();
                return;
            }

            $('#kd_sub_kegiatan').empty();
            $('#kd_rekening').empty();
            $('#sumber').empty();
            $('#nm_sub_kegiatan').val(null);
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
            cari_kegiatan(jenis_belanja, kd_skpd);
        });

        $('#tambah_rek').on('click', function() {
            let no_dpr = document.getElementById('no_dpr').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let jenis_belanja = document.getElementById('jenis_belanja').value;
            let tgl_dpr = document.getElementById('tgl_dpr').value;

            if (kd_skpd != '' && tgl_dpr != '' && jenis_belanja != '' && no_dpr != '') {
                status_anggaran();
                status_angkas();

                $('#modal_kegiatan').modal('show');
            } else {
                Swal.fire({
                    title: 'Harap Isi Kode SKPD, Tanggal Transaksi & Jenis Belanja',
                    confirmButtonColor: '#5b73e8',
                })
            }
        });

        $('#no_kkpd').on('select2:select', function() {
            let nm_kkpd = $(this).find(':selected').data('nama');
            $("#nm_kkpd").val(nm_kkpd);
        });

        $('#kd_sub_kegiatan').on('select2:select', function() {
            let nm_sub_kegiatan = $(this).find(':selected').data('nama');
            let kd_sub_kegiatan = this.value;

            let tgl_transaksi = document.getElementById('tgl_transaksi').value;

            if (!tgl_transaksi) {
                alert('Tanggal transaksi wajib dipilih!');
                $('#kd_sub_kegiatan').val(null).change()
                return;
            }

            $("#nm_sub_kegiatan").val(nm_sub_kegiatan);

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
            cari_rekening(kd_sub_kegiatan);
        });

        $('#kd_rekening').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            let anggaran = $(this).find(':selected').data('anggaran');
            let lalu = $(this).find(':selected').data('lalu');

            let tgl_transaksi = document.getElementById('tgl_transaksi').value;

            if (!tgl_transaksi) {
                alert('Tanggal transaksi wajib dipilih!');
                $('#kd_rekening').val(null).change()
                return;
            }

            $('#nm_rekening').val(nama);
            let kd_rek6 = this.value;
            let jenis_belanja = document.getElementById('jenis_belanja').value;

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
            cari_sumber(kd_rek6);

            $('#total_anggaran').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(anggaran));
            $('#realisasi_anggaran').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(lalu));
            $('#sisa_anggaran').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(anggaran - lalu));
        })

        $('#sumber').on('select2:select', function() {
            let sumber = this.value;
            if (sumber == 'null') {
                alert('Sumber dana tidak dapat digunakan!');
                $('#sumber').val(null).change();
                return;
            }

            let anggaran = $(this).find(':selected').data('anggaran');
            let nama = $(this).find(':selected').data('nama');

            $('#total_sumber').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(anggaran));

            $('#nm_sumber').val(nama);
            $('#realisasi_sumber').val(null);
            $('#sisa_sumber').val(null);
            load_dana(sumber);
        })

        $('#simpan_rekening').on('click', function() {
            let tgl_transaksi = document.getElementById('tgl_transaksi').value;
            let no_dpr = document.getElementById('no_dpr').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let nm_sub_kegiatan = document.getElementById('nm_sub_kegiatan').value;
            let kd_rekening = document.getElementById('kd_rekening').value;
            let sumber = document.getElementById('sumber').value;
            let nm_sumber = document.getElementById('nm_sumber').value;
            let jenis_belanja = document.getElementById('jenis_belanja').value;
            let nm_rekening = document.getElementById('nm_rekening').value;
            let status_anggaran = document.getElementById('status_anggaran').value;
            let uraian = document.getElementById('uraian').value;
            let bukti = document.getElementById('bukti').value;
            let nm_bukti = $('#bukti').find('option:selected').data('nama');
            let pembayaran = document.getElementById('pembayaran').value;
            let nm_pembayaran = $('#pembayaran').find('option:selected').data('nama');

            let sisa_anggaran = rupiah(document.getElementById('sisa_anggaran').value);
            let nilai = angka(document.getElementById('nilai').value);
            let sisa_kas = rupiah(document.getElementById('sisa_kas').value);
            let sisa_spd = rupiah(document.getElementById('sisa_spd').value);
            let total_input_rekening = rupiah(document.getElementById('total_input_rekening').value);
            let sisa_sumber = rupiah(document.getElementById('sisa_sumber').value);
            let sisa_angkas = rupiah(document.getElementById('sisa_angkas').value);
            let realisasi_anggaran = rupiah(document.getElementById('realisasi_anggaran').value);
            let kd_rekening1 = $('#kd_rekening').find('option:selected');
            let anggaran = kd_rekening1.data('anggaran');
            let lalu = kd_rekening1.data('lalu');
            let sp2d = kd_rekening1.data('sp2d');

            let akumulasi = nilai + total_input_rekening;

            let cek_perjalanan_dinas = '';
            let cek_belanja_modal = '';
            let cek_belanja_barang = '';

            let tahun_input = tgl_transaksi.substr(0, 4);
            let tahun_anggaran = "{{ tahun_anggaran() }}";

            $.ajax({
                url: "{{ route('dpr.cek_modal') }}",
                type: "POST",
                dataType: 'json',
                async: false,
                data: {
                    kd_skpd: kd_skpd,
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    kd_rekening: kd_rekening,
                    sumber: sumber,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    cek_perjalanan_dinas = data.cek_perjalanan_dinas;
                    cek_belanja_modal = data.cek_belanja_modal;
                    cek_belanja_barang = data.cek_belanja_barang;
                },
            });

            if (!tgl_transaksi) {
                alert('Silahkan pilih tanggal transaksi');
                return;
            }

            if (tahun_anggaran != tahun_input) {
                alert('Tahun tidak sama dengan tahun anggaran');
                return;
            }


            if (sumber == '221020101') {
                alert(
                    'Silahkan konfirmasi ke perbendaharaan jika ingin transaksi sumber dana DID, jika tidak maka transaksi tidak bisa di approve oleh perbendahaaraan, terima kasih'
                );
            }

            if (kd_rekening.substr(0, 2) == '52' && nilai > 15000000) {
                alert('Akun Belanja ini tidak boleh melebihi 15 juta');
                return;
            }

            if (kd_sub_kegiatan.substr(0, 17) == '5.06.01.1.09.0002' &&
                kd_rekening
                .substr(0, 12) == '510203020035' && nilai > 50000000) {
                alert('Akun Belanja ini tidak boleh melebihi 50 juta');
                return;
            }

            if (kd_rekening.substr(0, 6) == '510203' && nilai > 15000000 && kd_sub_kegiatan.substr(0,
                    17) == '5.06.01.1.09.0002') {
                alert('Akun Belanja ini tidak boleh melebihi 15 juta');
                return;
            }

            if (jenis_belanja == '1' && cek_perjalanan_dinas == '0' && nilai > 40000000) {
                alert('Transaksi pertama perjalanan dinas tidak boleh melebihi 40 juta');
                return;
            }

            if (jenis_belanja == '2' && cek_belanja_modal == '0' && nilai > 50000000) {
                alert('Transaksi pertama belanja modal tidak boleh melebihi 50 juta');
                return;
            }

            if (jenis_belanja == '3' && cek_belanja_barang == '0' && nilai > 50000000) {
                alert('Transaksi pertama belanja barang dan jasa tidak boleh melebihi 50 juta');
                return;
            }

            if (!pembayaran) {
                alert('Silahkan pilih pembayaran!');
                return;
            }

            if (pembayaran == '4' && nilai > 50000000) {
                alert('Jenis Pembayaran Lain-Lain tidak boleh melebihi 50 juta');
                return;
            }

            if (!kd_sub_kegiatan) {
                alert('Pilih Kegiatan Dahulu');
                return;
            }

            if (!nm_sub_kegiatan) {
                alert('Pilih Kegiatan Dahulu');
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

            let tampungan = tabel_rekening.rows().data().toArray().map((value) => {
                let result = {
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    sumber: value.sumber,
                };
                return result;
            });

            let kondisi = tampungan.map(function(data) {
                // if (data.kd_rek6 == kd_rekening && data.sumber == sumber) {
                //     return '2';
                // } else if (data.kd_sub_kegiatan != kd_sub_kegiatan) {
                //     return '3';

                // } else if (data.kd_rek6 != kd_rekening) {
                //     return '4';
                // } else {
                //     return '1';
                // }
                // if (data.kd_sub_kegiatan == kd_sub_kegiatan && data.kd_rek6 == kd_rekening &&
                //     data.sumber == sumber) {
                //     return '2';
                // } else {
                //     return '1';
                // }
            });

            // if (kondisi.includes("3")) {
            //     alert('Tdk boleh memilih kegiatan berbeda dlm 1 no bku');
            //     return;
            // }

            // if (kondisi.includes("4")) {
            //     alert('Tdk boleh memilih rekening berbeda dlm 1 no bku');
            //     return;
            // }

            // if (kondisi.includes("2")) {
            //     alert('Tdk boleh memilih rekening dgn sumber dana yg sama dlm 1 no bku');
            //     return;
            // }

            if (kondisi.includes("2")) {
                alert('Sub kegiatan, rekening, sumber tidak boleh sama dalam satu list transaksi!!!');
                return;
            }

            if (nilai > sisa_kas) {
                alert('Total Transaksi melebihi Sisa Kas KKPD');
                return;
            }

            if (nilai > sisa_kas) {
                alert('Total Transaksi melebihi Sisa Kas KKPD');
                return;
            }

            if (nilai == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
                return;
            }

            if (nilai > 200000000) {
                alert('Nilai Transaksi Melebihi 200 Juta!!!');
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

            if (nilai > sisa_spd) {
                alert('Nilai Transaksi melebihi Sisa SPD');
                return;
            }

            if (!bukti) {
                alert('Silahkan pilih bukti!');
                return;
            }

            if (bukti == 1 && !uraian) {
                alert('Silahkan isi uraian ketika ada bukti!');
                return;
            }

            if (bukti == 1 && uraian.length > 1000) {
                alert('Uraian tidak boleh melebihi 1000 karakter');
                return;
            }

            if (nilai + nilai_kunci > sisa_anggaran) {
                alert('Nilai melebihi pagu terkait automatic adjustment sebesar ' + new Intl
                    .NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(nilai_kunci) + ' , Silahkan hubungi bidang anggaran/perbendaharaan!');
                return;
            }

            $.ajax({
                url: "{{ route('dpr.simpan_detail') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    tgl_transaksi: tgl_transaksi,
                    no_dpr: no_dpr,
                    kd_skpd: kd_skpd,
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    nm_sub_kegiatan: nm_sub_kegiatan,
                    kd_rekening: kd_rekening,
                    nm_rekening: nm_rekening,
                    uraian: uraian,
                    bukti: bukti,
                    nilai: nilai,
                    sumber: sumber,
                    pembayaran: pembayaran,
                    "_token": "{{ csrf_token() }}",
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
                        tabel_rekening.ajax.reload();
                        tabel_rekening1.ajax.reload();

                        $('#kd_sub_kegiatan').val(null).change();
                        $('#nm_sub_kegiatan').val(null);
                        $('#tgl_transaksi').val(null);
                        $('#kd_rekening').val(null).change();
                        $('#nm_rekening').val(null);
                        $('#sumber').val(null).change();
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
                        $('#nilai').val(null);
                        $('#uraian').val(null);
                        $('#bukti').val(null).change();
                        $('#pembayaran').val(null).change();

                        $('#jenis_belanja').prop('disabled', true);
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            });
        });

        $('#simpan').on('click', function() {
            let no_dpr = document.getElementById('no_dpr').value;
            let tgl_dpr = document.getElementById('tgl_dpr').value;
            let no_urut = document.getElementById('no_urut').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = document.getElementById('nm_skpd').value;
            let no_kkpd = document.getElementById('no_kkpd').value;
            let nm_kkpd = document.getElementById('nm_kkpd').value;
            let jenis_belanja = document.getElementById('jenis_belanja').value;
            let tahun_anggaran = "{{ tahun_anggaran() }}";

            let total_belanja = rupiah(document.getElementById('total_belanja').value);
            let sisa_kas = rupiah(document.getElementById('sisa_kas').value);

            let rincian_rekening1 = tabel_rekening1.rows().data().toArray().map((value) => {
                let data = {
                    no_dpr: value.no_dpr,
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    nm_sub_kegiatan: value.nm_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    nm_rek6: value.nm_rek6,
                    nilai: rupiah(value.nilai),
                    sumber: value.sumber,
                    bukti: value.bukti,
                    uraian: value.uraian,
                    pembayaran: value.pembayaran,
                };
                return data;
            });

            let rincian_rekening = JSON.stringify(rincian_rekening1);

            if (rincian_rekening1.length == 0) {
                alert('Rincian Rekening tidak boleh kosong!');
                return;
            }

            let tahun_input = tgl_dpr.substr(0, 4);

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (!no_dpr) {
                alert('Nomor DPR Tidak Boleh Kosong');
                return;
            }

            if (!tgl_dpr) {
                alert('Tanggal DPR Tidak Boleh Kosong');
                return;
            }

            if (!kd_skpd) {
                alert('Kode SKPD Tidak Boleh Kosong');
                return;
            }

            if (!jenis_belanja) {
                alert('Jenis Belanja Tidak Boleh Kosong');
                return;
            }

            if (!no_kkpd) {
                alert('Nomor KKPD tidak boleh kosong');
                return;
            }

            if (total_belanja == 0) {
                alert('Rincian Tidak ada rekening!');
                return;
            }

            let response = {
                no_dpr,
                tgl_dpr,
                no_urut,
                kd_skpd,
                nm_skpd,
                jenis_belanja,
                no_kkpd,
                nm_kkpd,
                total_belanja,
                rincian_rekening,
            };

            $('#simpan').prop('disabled', true);

            $.ajax({
                url: "{{ route('dpr.update') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: response,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Data Berhasil Terupdate...!!!');
                        window.location.href = "{{ route('dpr.index') }}";
                    } else if (data.message == '2') {
                        alert('Nomor Telah Dipakai!');
                        $('#simpan').prop('disabled', false);
                    } else {
                        alert('Data gagal Terupdate...!');
                        $('#simpan').prop('disabled', false);
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        });

        function cari_kegiatan(jenis_belanja, kd_skpd) {
            let kartu_kkpd = "{{ $dpr->no_kkpd }}";
            $.ajax({
                url: "{{ route('dpr.kegiatan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    jenis_belanja: jenis_belanja,
                    kd_skpd: kd_skpd,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    $('#no_kkpd').empty();
                    $('#no_kkpd').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`);
                    $.each(data.kartu, function(index, kartu) {
                        if (kartu.no_kkpd == kartu_kkpd) {
                            $('#no_kkpd').append(
                                `<option value="${kartu.no_kkpd}" data-nama="${kartu.nm_kkpd}" selected>${kartu.no_kkpd} | ${kartu.nm_kkpd}</option>`
                            );
                        } else {
                            $('#no_kkpd').append(
                                `<option value="${kartu.no_kkpd}" data-nama="${kartu.nm_kkpd}">${kartu.no_kkpd} | ${kartu.nm_kkpd}</option>`
                            );
                        }

                    })

                    $('#kd_sub_kegiatan').empty();
                    $('#kd_sub_kegiatan').append(
                        `<option value="" disabled selected>Pilih Sub Kegiatan</option>`);
                    $.each(data.kegiatan, function(index, kegiatan) {
                        $('#kd_sub_kegiatan').append(
                            `<option value="${kegiatan.kd_sub_kegiatan}" data-nama="${kegiatan.nm_sub_kegiatan}" data-kdprogram="${kegiatan.kd_program}" data-nmprogram="${kegiatan.nm_program}">${kegiatan.kd_sub_kegiatan} | ${kegiatan.nm_sub_kegiatan}</option>`
                        );
                    })
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        }

        function cari_rekening(kd_sub_kegiatan) {
            $.ajax({
                url: "{{ route('dpr.rekening') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    jenis_belanja: document.getElementById('jenis_belanja').value,
                    kd_sub_kegiatan: document.getElementById('kd_sub_kegiatan').value,
                    kd_skpd: document.getElementById('kd_skpd').value,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    $('#kd_rekening').empty();
                    $('#kd_rekening').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`);
                    $.each(data.rekening, function(index, rekening) {
                        $('#kd_rekening').append(
                            `<option value="${rekening.kd_rek6}" data-nama="${rekening.nm_rek6}" data-sp2d="${rekening.sp2d}" data-anggaran="${rekening.anggaran}" data-lalu="${rekening.lalu}">${rekening.kd_rek6} | ${rekening.nm_rek6} | ${new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(rekening.lalu)}</option>`
                        );
                    });

                    // SISA KKPD
                    let nilai = parseFloat(data.sisa_kkpd) || 0;

                    $('#sisa_kas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(nilai));
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        }

        function cari_sumber(kd_rek6) {
            $.ajax({
                url: "{{ route('dpr.sumber') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_rek6: kd_rek6,
                    kd_skpd: document.getElementById('kd_skpd').value,
                    kd_sub_kegiatan: document.getElementById('kd_sub_kegiatan').value,
                    jenis_belanja: document.getElementById('jenis_belanja').value,
                    tgl_dpr: document.getElementById('tgl_transaksi').value,
                    status_angkas: document.getElementById('status_angkas').value,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    $('#sumber').empty();
                    $('#sumber').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`);
                    $.each(data.sumber, function(index, sumber) {
                        $('#sumber').append(
                            `<option value="${sumber.sumber}" data-anggaran="${sumber.nilai}" data-nama="${sumber.nm_sumber}">${sumber.sumber}</option>`
                        );
                    });

                    // LOAD ANGKAS
                    $('#total_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas));

                    // LOAD ANGKAS LALU
                    $('#realisasi_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas_lalu));
                    $('#realisasi_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas_lalu));
                    $('#sisa_angkas').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.angkas - data.angkas_lalu));

                    // LOAD SPD
                    $('#total_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.spd));
                    $('#sisa_spd').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(data.spd - data.angkas_lalu));

                    nilai_kunci = parseFloat(data.nilai_kunci)
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        }

        function load_dana(sumber) {
            $.ajax({
                url: "{{ route('penagihan.realisasi_sumber_dana') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_sub_kegiatan: document.getElementById('kd_sub_kegiatan').value,
                    kd_skpd: document.getElementById('kd_skpd').value,
                    sumber: sumber,
                    kd_rek6: document.getElementById('kd_rekening').value,
                    "_token": "{{ csrf_token() }}",
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
                url: "{{ route('dpr.status_ang') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#status_anggaran').val(data.nama);
                }
            });
        }

        function status_angkas() {
            let tanggal = document.getElementById('tgl_dpr').value;
            $.ajax({
                url: "{{ route('penagihan.cek_status_ang') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#status_angkas').val(data.status);
                }
            });
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

    function angka(n) {
        let nilai = n.split(',').join('');
        return parseFloat(nilai) || 0;
    }

    function rupiah(n) {
        let n1 = n.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }

    function deleteData(no_bukti, kd_sub_kegiatan, kd_rek, sumber, nilai, id) {
        let tabel = $('#input_rekening').DataTable();
        let tabel1 = $('#rincian_rekening').DataTable();


        let hapus = confirm('Yakin Ingin Menghapus Data, Rekening : ' + kd_rek + '  Nilai :  ' + nilai +
            ' ?');
        if (hapus == true) {
            $.ajax({
                url: "{{ route('dpr.hapus_detail') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_bukti: no_bukti,
                    kd_sub_kegiatan: kd_sub_kegiatan,
                    kd_rek: kd_rek,
                    sumber: sumber,
                    id: id,
                    "_token": "{{ csrf_token() }}",
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
                        tabel.ajax.reload();
                        tabel1.ajax.reload();
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
