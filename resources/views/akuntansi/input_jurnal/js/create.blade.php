<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('.select2-modal').select2({
            dropdownParent: $('#modal_rincian .modal-content'),
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        let detail = $('#detail_rincian').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    data: 'no_voucher',
                    name: 'no_voucher',
                    visible: false
                },
                {
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan'
                },
                {
                    data: 'nm_sub_kegiatan',
                    name: 'nm_sub_kegiatan',
                    visible: false
                },
                {
                    data: 'kd_rek_ang',
                    name: 'kd_rek_ang',
                },
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6'
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6'
                },
                {
                    data: 'debet',
                    name: 'debet'
                },
                {
                    data: 'kredit',
                    name: 'kredit'
                },
                {
                    data: 'rk',
                    name: 'rk'
                },
                {
                    data: 'jns',
                    name: 'jns',
                    visible: false
                },
                {
                    data: 'post',
                    name: 'post',
                },
                {
                    data: 'hibah',
                    name: 'hibah',
                    visible: false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                }
            ]
        });

        let detail1 = $('#input_rekening').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    data: 'no_voucher',
                    name: 'no_voucher',
                    visible: false
                },
                {
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan'
                },
                {
                    data: 'nm_sub_kegiatan',
                    name: 'nm_sub_kegiatan',
                    visible: false
                },
                {
                    data: 'kd_rek_ang',
                    name: 'kd_rek_ang',
                },
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6'
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6'
                },
                {
                    data: 'debet',
                    name: 'debet'
                },
                {
                    data: 'kredit',
                    name: 'kredit'
                },
                {
                    data: 'rk',
                    name: 'rk'
                },
                {
                    data: 'jns',
                    name: 'jns',
                    visible: false
                },
                {
                    data: 'post',
                    name: 'post',
                },
                {
                    data: 'hibah',
                    name: 'hibah',
                    visible: false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                }
            ]
        });

        $('#pilihan_umum').hide();
        $('#pilihan_lain').hide();
        $('#pilihan_hibah').hide();
        $('#pilihan_mutasi').hide();

        $('#jenis_jurnal').on('select2:select', function() {
            let jenis_jurnal = this.value;
            let lain_lain = $('#lain_lain').val();
            let umum = $('#umum').val();

            if (jenis_jurnal == '0') {
                $('#pilihan_umum').show();
                $('#pilihan_lain').hide();
                $('#pilihan_mutasi').hide();

                if (umum == '69') {
                    $('#pilihan_hibah').show();
                }
            } else if (jenis_jurnal == '1') {
                $('#pilihan_umum').hide();
                $('#pilihan_lain').hide();
                $('#pilihan_hibah').hide();
                $('#pilihan_mutasi').hide();
            } else if (jenis_jurnal == '2') {
                $('#pilihan_umum').hide();
                $('#pilihan_lain').hide();
                $('#pilihan_hibah').hide();
                $('#pilihan_mutasi').hide();
            } else if (jenis_jurnal == '3') {
                $('#pilihan_umum').hide();
                $('#pilihan_lain').show();
                $('#pilihan_hibah').hide();

                if (lain_lain == '4' || lain_lain == '5') {
                    $('#pilihan_mutasi').show();
                }
            }
        });

        $('#umum').on('select2:select', function() {
            let umum = this.value;

            if (umum == '69') {
                $('#pilihan_hibah').show();
            } else {
                $('#pilihan_hibah').hide();
            }
        });

        $('#lain_lain').on('select2:select', function() {
            let lain_lain = this.value;

            if (lain_lain == '4' || lain_lain == '5') {
                $('#pilihan_mutasi').show();
            } else {
                $('#pilihan_mutasi').hide();
            }
        });

        $('#jenis').on('select2:select', function() {
            let jenis = this.value;

            $('#kd_sub_kegiatan').empty();
            $('#kd_rek6').empty();

            $.ajax({
                url: "{{ route('input_jurnal.kegiatan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    jenis: jenis,
                    kd_skpd: $('#kd_skpd').val(),
                },
                success: function(data) {
                    $('#kd_sub_kegiatan').empty();
                    $('#kd_sub_kegiatan').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`
                    );
                    $.each(data, function(index, data) {
                        $('#kd_sub_kegiatan').append(
                            `<option value="${data.kd_sub_kegiatan}" data-nama="${data.nm_sub_kegiatan}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                        );
                    })
                }
            })
        });

        $('#kd_sub_kegiatan').on('select2:select', function() {
            $('#kd_rek6').empty();

            let detail_rincian = detail.rows().data().toArray().map((value) => {
                let data = {
                    kd_rek6: value.kd_rek6,
                };
                return data;
            });

            $.ajax({
                url: "{{ route('input_jurnal.rekening') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    jenis: $('#jenis').val(),
                    kd_sub_kegiatan: $('#kd_sub_kegiatan').val(),
                    kd_skpd: $('#kd_skpd').val(),
                    kd_rek6: detail_rincian.length == 0 ? '0' : detail_rincian
                },
                success: function(data) {
                    $('#kd_rek6').empty();
                    $('#kd_rek6').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`
                    );
                    $.each(data, function(index, data) {
                        $('#kd_rek6').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}">${data.kd_rek6} | ${data.kd_rek6} | ${data.nm_rek6}</option>`
                        );
                    })
                }
            })
        });

        $('#tambah_rincian').on('click', function() {
            let no_voucher = document.getElementById('no_voucher').value;
            let tgl_voucher = document.getElementById('tgl_voucher').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let jenis_jurnal = document.getElementById('jenis_jurnal').value;
            let umum = document.getElementById('umum').value;
            let lain_lain = document.getElementById('lain_lain').value;
            let hibah = document.getElementById('hibah').value;
            let mutasi = document.getElementById('mutasi').value;

            if (jenis_jurnal == 0 && !umum) {
                alert('Silahkan pilih jenis jurnal UMUM!');
                return;
            }
            if (jenis_jurnal == 0 && umum == 69 && !hibah) {
                alert('Silahkan pilih rekening hibah!');
                return;
            }
            if (jenis_jurnal == 3 && !lain_lain) {
                alert('Silahkan pilih jenis Lain-Lain!');
                return;
            }
            if (jenis_jurnal == 3 && (lain_lain == 4 || lain_lain == 5) && !mutasi) {
                alert('Silahkan pilih SKPD mutasi!');
                return;
            }

            if (no_voucher != '' && tgl_voucher != '' && kd_skpd != '') {
                $('#modal_rincian').modal('show');
            } else {
                Swal.fire({
                    title: 'Harap Isi Kode SKPD, Tanggal Transaksi & Nomor Transaksi',
                    confirmButtonColor: '#5b73e8',
                })
            }
        });

        $('#simpan_rincian').on('click', function() {
            let unposting = document.getElementById('unposting').checked;

            let no_voucher = document.getElementById('no_voucher').value;
            let jenis = document.getElementById('jenis').value;
            let hibah = document.getElementById('hibah').value;
            let debet_kredit = document.getElementById('debet_kredit').value;

            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let kegiatan = $('#kd_sub_kegiatan').find('option:selected');
            let nm_sub_kegiatan = kegiatan.data('nama');

            let kd_rek6 = document.getElementById('kd_rek6').value;
            let rekening = $('#kd_rek6').find('option:selected');
            let nm_rek6 = rekening.data('nama');

            let nilai = angka(document.getElementById('nilai').value);
            let total_debet = rupiah(document.getElementById('total_debet').value);
            let total_kredit = rupiah(document.getElementById('total_kredit').value);

            let pos = '';
            let nilai_debet = '';
            let nilai_kredit = '';

            if ((jenis == '' || kd_rek6 == '' || debet_kredit == '')) {
                alert('Jenis Rekening, Kode Rekening dan Nilai tidak boleh kosong');
                return
            }

            if (unposting == true) {
                pos = '0';
            } else {
                pos = '1';
            }

            if (debet_kredit == 'D') {
                nilai_debet = nilai;
                nilai_kredit = 0;
                total_debet += nilai;
                $('#total_debet').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total_debet));
                $('#total_debet1').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total_debet));
            } else {
                nilai_debet = 0;
                nilai_kredit = nilai;
                total_kredit += nilai;
                nm_rek6 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + nm_rek6;
                $('#total_kredit').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total_kredit));
                $('#total_kredit1').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total_kredit));
            }

            detail.row.add({
                'no_voucher': no_voucher,
                'kd_sub_kegiatan': kd_sub_kegiatan,
                'nm_sub_kegiatan': nm_sub_kegiatan,
                'kd_rek_ang': kd_rek6,
                'kd_rek6': kd_rek6,
                'nm_rek6': nm_rek6,
                'debet': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai_debet),
                'kredit': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai_kredit),
                'rk': debet_kredit,
                'jns': jenis,
                'post': pos,
                'hibah': hibah,
                'aksi': `<a href="javascript:void(0);" onclick="hapus('${no_voucher}','${kd_sub_kegiatan}','${kd_rek6}','${nilai_debet}','${nilai_kredit}','${debet_kredit}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>`,
            }).draw();

            detail1.row.add({
                'no_voucher': no_voucher,
                'kd_sub_kegiatan': kd_sub_kegiatan,
                'nm_sub_kegiatan': nm_sub_kegiatan,
                'kd_rek_ang': kd_rek6,
                'kd_rek6': kd_rek6,
                'nm_rek6': nm_rek6,
                'debet': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai_debet),
                'kredit': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai_kredit),
                'rk': debet_kredit,
                'jns': jenis,
                'post': pos,
                'hibah': hibah,
                'aksi': `<a href="javascript:void(0);" onclick="hapus('${no_voucher}','${kd_sub_kegiatan}','${kd_rek6}','${nilai_debet}','${nilai_kredit}','${debet_kredit}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>`,
            }).draw();

            $('#jenis').val(null).change();
            $('#debet_kredit').val(null).change();
            $('#kd_sub_kegiatan').val(null).change();
            $('#kd_rek6').empty();
            $('#nilai').val(null);
        });

        $('#pilih').on('click', function() {
            let no_sp2h = document.getElementById('no_sp2h').value;
            let nilai = angka(document.getElementById('nilai').value);

            let sp2h = $('#no_sp2h').find('option:selected');
            let satdik = sp2h.data('satdik');
            let nm_satdik = sp2h.data('nm_satdik');
            let tanggal = sp2h.data('tanggal');

            let total = rupiah(document.getElementById('total').value);

            if (nilai == 0) {
                alert('Silahkan Isi Nilai!!');
                return;
            }

            detail.row.add({
                'tgl_sp2h': tanggal,
                'no_sp2h': no_sp2h,
                'kd_satdik': satdik,
                'nm_satdik': nm_satdik,
                'nilai': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai),
                'aksi': `<a href="javascript:void(0);" onclick="hapus('${no_sp2h}','${satdik}','${nilai}')" class="btn btn-danger btn-sm"><i class="uil-trash"></i></a>`,
            }).draw();

            $('#total').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total + nilai));

            $('#no_sp2h').val(null).change();
            $('#nilai').val(null);
        });

        $('#simpan').on('click', function() {
            let no_tersimpan = document.getElementById('no_tersimpan').value;

            let no_voucher = document.getElementById('no_voucher').value;
            let tgl_voucher = document.getElementById('tgl_voucher').value;

            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = document.getElementById('nm_skpd').value;

            let tahun_anggaran = "{{ tahun_anggaran() }}";
            let keterangan = document.getElementById('keterangan').value;
            let jenis_jurnal = document.getElementById('jenis_jurnal').value;
            let lain_lain = document.getElementById('lain_lain').value;
            let umum = document.getElementById('umum').value;
            let hibah = document.getElementById('hibah').value;
            let mutasi = document.getElementById('mutasi').value;

            let skpd_mutasi = $('#mutasi').find('option:selected');
            let nama_mutasi = skpd_mutasi.data('nama');

            let total_debet = rupiah(document.getElementById('total_debet').value);
            let total_kredit = rupiah(document.getElementById('total_kredit').value);

            let tahun_input = tgl_voucher.substr(0, 4);

            let cj_d = '';

            if (jenis_jurnal == '3') {
                cj_d = lain_lain;
            } else {
                cj_d = umum;
            }

            let detail_rincian1 = detail.rows().data().toArray().map((value) => {
                let data = {
                    no_voucher: value.no_voucher,
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    nm_sub_kegiatan: value.nm_sub_kegiatan,
                    kd_rek_ang: value.kd_rek_ang,
                    kd_rek6: value.kd_rek6,
                    nm_rek6: value.nm_rek6.split('&nbsp;').join(''),
                    debet: rupiah(value.debet),
                    kredit: rupiah(value.kredit),
                    rk: value.rk,
                    jns: value.jns,
                    post: value.post,
                    hibah: value.hibah,
                };
                return data;
            });

            let detail_rincian = JSON.stringify(detail_rincian1);

            if (!no_voucher) {
                alert('Nomor tidak boleh kosong');
                return;
            }

            if (!tgl_voucher) {
                alert('Tanggal tidak boleh kosong!');
                return;
            }

            if (!kd_skpd) {
                alert('KODE SKPD tidak boleh kosong!');
                return;
            }

            if (tahun_anggaran != tahun_input) {
                alert('Tahun input tidak sesuai dengan tahun anggaran');
                return;
            }

            if ((total_debet < 0) || (total_kredit < 0)) {
                alert('Rincian tidak boleh Minus');
                return
            }

            if (total_debet != total_kredit) {
                alert('Kredit dan Debet harus sama!');
                return
            }

            if (jenis_jurnal == '3' && cj_d == '') {
                alert('Harap Dipilih Kategori dari Jenis Lain-Lain');
                return
            }

            if (jenis_jurnal == '0' && cj_d == '') {
                alert('Harap Dipilih Kategori dari Jenis Umum');
                return
            }

            let skpd_mutasi_ = '';
            let nmskpd_mutasi_ = '';
            let ket_mutasi1 = '';
            let ket_mutasi2 = '';

            if (lain_lain == '4') {
                if (mutasi == '') {
                    alert('SKPD Mutasi Tidak boleh Kosong');
                    return;
                }

                skpd_mutasi_ = mutasi;
                nmskpd_mutasi_ = nama_mutasi;
                ket_mutasi1 = 'Mutasi Masuk dari ';
                ket_mutasi2 = ' Berdasarkan BAST ';
            } else if (lain_lain == '5') {
                if (mutasi == '') {
                    alert('SKPD Mutasi Tidak boleh Kosong');
                    return;
                }

                skpd_mutasi_ = mutasi;
                nmskpd_mutasi_ = nama_mutasi;
                ket_mutasi1 = 'Mutasi Keluar ke ';
                ket_mutasi2 = ' Berdasarkan BAST ';
            } else {
                skpd_mutasi_ = '';
                nmskpd_mutasi_ = '';
                ket_mutasi1 = '';
                ket_mutasi2 = '';
            }

            if (detail_rincian1.length == 0) {
                alert('Rincian tidak boleh kosong!');
                return;
            }

            let data = {
                no_tersimpan,
                no_voucher,
                tgl_voucher,
                kd_skpd,
                nm_skpd,
                keterangan,
                total_debet,
                total_kredit,
                jenis_jurnal,
                cj_d,
                ket_mutasi1,
                ket_mutasi2,
                skpd_mutasi_,
                nmskpd_mutasi_,
                detail_rincian
            };

            $('#simpan').prop('disabled', true);
            $.ajax({
                url: "{{ route('input_jurnal.simpan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('Data berhasil ditambahkan!');
                        window.location.href =
                            "{{ route('input_jurnal.index') }}";
                    } else if (response.message == '2') {
                        alert('Nomor Telah Digunakan!');
                        $('#simpan').prop('disabled', false);
                        return;
                    } else {
                        alert('Data tidak berhasil ditambahkan!');
                        $('#simpan').prop('disabled', false);
                        return;
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
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

    function hapus(no_voucher, kd_sub_kegiatan, kd_rek6, nilai_debet, nilai_kredit, debet_kredit) {
        let nilai = '';
        if (debet_kredit == 'K') {
            nilai = nilai_kredit;
        } else {
            nilai = nilai_debet;
        }

        let hapus = confirm('Yakin Ingin Menghapus Data, Kegiatan : ' + kd_sub_kegiatan + ' Rekening : ' + kd_rek6 +
            ' Nilai : ' +
            nilai);

        let total_debet = rupiah(document.getElementById('total_debet').value);
        let total_kredit = rupiah(document.getElementById('total_kredit').value);

        let tabel = $('#detail_rincian').DataTable();
        let tabel1 = $('#input_rekening').DataTable();

        if (hapus == true) {
            if (debet_kredit == 'D') {
                tabel.rows(function(idx, data, node) {
                    return data.kd_sub_kegiatan == kd_sub_kegiatan && data.kd_rek6 == kd_rek6 && rupiah(data
                        .debet) == parseFloat(nilai)
                }).remove().draw();
                tabel1.rows(function(idx, data, node) {
                    return data.kd_sub_kegiatan == kd_sub_kegiatan && data.kd_rek6 == kd_rek6 && rupiah(data
                        .debet) == parseFloat(nilai)
                }).remove().draw();

                $('#total_debet').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total_debet - parseFloat(nilai)));

                $('#total_debet1').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total_debet - parseFloat(nilai)));
            } else {
                tabel.rows(function(idx, data, node) {
                    return data.kd_sub_kegiatan == kd_sub_kegiatan && data.kd_rek6 == kd_rek6 && rupiah(data
                        .kredit) == parseFloat(nilai)
                }).remove().draw();
                tabel1.rows(function(idx, data, node) {
                    return data.kd_sub_kegiatan == kd_sub_kegiatan && data.kd_rek6 == kd_rek6 && rupiah(data
                        .kredit) == parseFloat(nilai)
                }).remove().draw();

                $('#total_kredit').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total_kredit - parseFloat(nilai)));

                $('#total_kredit1').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total_kredit - parseFloat(nilai)));
            }
        }
    }
</script>
