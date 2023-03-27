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

        $('.select-modal').select2({
            dropdownParent: $('#modal_rincian .modal-content'),
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        let detail = $('#detail_spb').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    data: 'tgl_sp2h',
                    name: 'tgl_sp2h',
                    visible: false
                },
                {
                    data: 'no_sp2h',
                    name: 'no_sp2h'
                },
                {
                    data: 'kd_satdik',
                    name: 'kd_satdik'
                },
                {
                    data: 'nm_satdik',
                    name: 'nm_satdik',
                },
                {
                    data: 'nilai',
                    name: 'nilai'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                }
            ]
        });

        load_sp2h();

        let kd_skpd = document.getElementById('kd_skpd').value;
        let kd_sub_kegiatan = "{{ $spb->kd_sub_kegiatan }}";
        $.ajax({
            url: "{{ route('spb_hibah.kegiatan') }}",
            type: "POST",
            dataType: 'json',
            data: {
                kd_skpd: kd_skpd
            },
            success: function(data) {
                $('#kd_sub_kegiatan').empty();
                $('#kd_sub_kegiatan').append(
                    `<option value="" disabled selected>Silahkan Pilih</option>`
                );
                $.each(data, function(index, data) {
                    if (data.kd_sub_kegiatan == kd_sub_kegiatan) {
                        $('#kd_sub_kegiatan').append(
                            `<option value="${data.kd_sub_kegiatan}" selected data-nama="${data.nm_sub_kegiatan}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                        );
                    } else {
                        $('#kd_sub_kegiatan').append(
                            `<option value="${data.kd_sub_kegiatan}" data-nama="${data.nm_sub_kegiatan}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                        );
                    }
                })
            }
        })

        $('#kd_skpd').on('select2:select', function() {
            let kd_skpd = this.value;

            $.ajax({
                url: "{{ route('spb_hibah.kegiatan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd
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

        $('#tambah_rincian').on('click', function() {
            $('#modal_rincian').modal('show');
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
            let no_urut = document.getElementById('no_urut').value;

            let no_spb = document.getElementById('no_spb').value;
            let tgl_spb = document.getElementById('tgl_spb').value;

            let skpd = $('#kd_skpd').find('option:selected');
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = skpd.data('nama');

            let tahun_anggaran = document.getElementById('tahun_anggaran').value;
            let kategori = document.getElementById('kategori').value;
            let gelombang = document.getElementById('gelombang').value;
            let tahapan = document.getElementById('tahapan').value;

            let sub_kegiatan = $('#kd_sub_kegiatan').find('option:selected');
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let nm_sub_kegiatan = sub_kegiatan.data('nama');

            let rekening = $('#rekening').find('option:selected');
            let kd_rek6 = document.getElementById('rekening').value;
            let nm_rek6 = rekening.data('nama');

            let total = rupiah(document.getElementById('total').value);
            let tahun_input = tgl_spb.substr(0, 4);

            let detail_spb1 = detail.rows().data().toArray().map((value) => {
                let data = {
                    tgl_sp2h: value.tgl_sp2h,
                    no_sp2h: value.no_sp2h,
                    kd_satdik: value.kd_satdik,
                    nm_satdik: value.nm_satdik,
                    nilai: rupiah(value.nilai),
                };
                return data;
            });

            let detail_spb = JSON.stringify(detail_spb1);

            if (!no_spb) {
                alert('Nomor SPB tidak boleh kosong');
                return;
            }

            if (!tgl_spb) {
                alert('Tanggal SPB tidak boleh kosong!');
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

            if (total == 0) {
                alert('Rincian tidak boleh kosong!');
                return;
            }

            if (detail_spb1.length == 0) {
                alert('Rincian tidak boleh kosong!');
                return;
            }

            let data = {
                no_urut,
                no_spb,
                tgl_spb,
                kd_skpd,
                nm_skpd,
                kd_sub_kegiatan,
                nm_sub_kegiatan,
                kd_rek6,
                nm_rek6,
                gelombang,
                tahapan,
                kategori,
                total,
                detail_spb
            };


            $.ajax({
                url: "{{ route('spb_hibah.update') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                beforeSend: function() {
                    $('#simpan').prop('disabled', true);
                    $("#overlay").fadeIn(100);
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('Data berhasil ditambahkan!');
                        window.location.href =
                            "{{ route('spb_hibah.index') }}";
                    } else if (response.message == '2') {
                        alert('No SP2B Telah Digunakan!');
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
                    $('#simpan').prop('disabled', false);
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

        function load_sp2h() {
            let detail_spb = detail.rows().data().toArray().map((value) => {
                let data = {
                    no_sp2h: value.no_sp2h,
                };
                return data;
            });

            $.ajax({
                url: "{{ route('spb_hibah.nomor') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_sp2h: detail_spb.length == 0 ? '0' : detail_spb
                },
                success: function(data) {
                    $('#no_sp2h').empty();
                    $('#no_sp2h').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`
                    );
                    $.each(data, function(index, data) {
                        $('#no_sp2h').append(
                            `<option value="${data.no_sp2h}" data-satdik="${data.kd_satdik}" data-nm_satdik="${data.nm_satdik}" data-tanggal="${data.tgl_sp2h}">${data.no_sp2h} | ${data.tgl_sp2h} | ${data.keterangan}</option>`
                        );
                    })
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

    function hapus(no_sp2h, satdik, nilai) {
        let hapus = confirm('Yakin Ingin Menghapus Data, No SP2H : ' + no_sp2h + '  Nilai :  ' + nilai +
            ' ?');
        let total = rupiah(document.getElementById('total').value);
        let tabel = $('#detail_spb').DataTable();

        if (hapus == true) {
            tabel.rows(function(idx, data, node) {
                return data.no_sp2h == no_sp2h && rupiah(data.nilai) == parseFloat(nilai)
            }).remove().draw();

            $('#total').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total - parseFloat(nilai)));
        }
    }
</script>
