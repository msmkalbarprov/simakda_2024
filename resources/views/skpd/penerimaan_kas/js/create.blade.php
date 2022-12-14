<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#jenis').select2({
            disabled: true
        });
        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        let detail = $('#detail_sts').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('skpd.input_rak.rekening_rak') }}",
                "type": "POST",
                "data": function(d) {
                    d.kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                    d.jenis_rak = document.getElementById('jenis_rak').value;
                    d.jenis_anggaran = document.getElementById('jenis_anggaran').value;
                }
            },
            createdRow: function(row, data, index) {
                if ((data.nilai - data.nilai_rak) == 0) {
                    $(row).css("background-color", "#ffffff");
                } else {
                    $(row).css("background-color", "#ff471a");
                }
            },
            ordering: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
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
                    className: 'text-right',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai)
                    }
                },
                {
                    data: null,
                    name: 'nilai_rak',
                    className: 'text-right',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai_rak)
                    }
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    className: 'text-center'
                },
            ]
        });

        $('#kd_skpd').on('select2:select', function() {
            let tgl_kas = document.getElementById('tgl_kas').value;
            if (!tgl_kas) {
                alert('Silahkan pilih tanggal kas!');
                $('#kd_skpd').val(null).change();
                return;
            }
            let nama = $(this).find(':selected').data('nama');
            $('#nm_skpd').val(nama);
            let kd_skpd = this.value;

            $.ajax({
                url: "{{ route('penerimaan_kas.no_bukti') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd,
                    tgl_kas: tgl_kas,
                },
                success: function(data) {
                    $('#no_bukti').empty();
                    $('#no_bukti').append(`<option value="">Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#no_bukti').append(
                            `<option value="${data.no_sts}" data-tanggal="${data.tgl_sts}" data-keterangan="${data.keterangan}" data-kd_sub_kegiatan="${data.kd_sub_kegiatan}" data-jenis="${data.jns_trans}">${data.no_sts} | ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(data.total)} | ${data.keterangan}</option>`
                        );
                    })
                }
            })
        });

        $('#no_bukti').on('select2:select', function() {
            let tanggal = $(this).find(':selected').data('tanggal');
            let keterangan = $(this).find(':selected').data('keterangan');
            let kd_sub_kegiatan = $(this).find(':selected').data('kd_sub_kegiatan');
            let jenis = $(this).find(':selected').data('jenis');
            $('#tgl_bukti').val(tanggal);
            $('#keterangan').val(keterangan);
            $('#kd_sub_kegiatan').val(kd_sub_kegiatan);
            $("#jenis").select2("val", jenis);
        });

        $('#simpan').on('click', function() {
            let no_kas = document.getElementById('no_kas').value;
            let tgl_kas = document.getElementById('tgl_kas').value;
            let jenis = document.getElementById('jenis').value;
            let nama_jenis = document.getElementById('nama_jenis').value;
            let pengirim = document.getElementById('pengirim').value;
            let nama_pengirim = document.getElementById('nama_pengirim').value;
            let tahun_anggaran = document.getElementById('tahun_anggaran').value;
            let keterangan = document.getElementById('keterangan').value;
            let nilai = angka(document.getElementById('nilai').value);
            let tahun_input = tgl_kas.substr(0, 4);

            if (!tgl_kas) {
                alert('Tanggal Tidak Boleh Kosong');
                return;
            }

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (!jenis) {
                alert('Jenis Tidak Boleh Kosong');
                return;
            }

            if (!pengirim) {
                alert('Pengirim Tidak Boleh Kosong');
                return;
            }

            if (!keterangan) {
                alert('Keterangan Tidak Boleh Kosong');
                return;
            }

            if (nilai == '0') {
                alert('Nilai 0!Cek Lagi!!!');
                return;
            }

            let data = {
                no_kas,
                tgl_kas,
                jenis,
                pengirim,
                keterangan,
                nilai,
            };

            $('#simpan').prop('disabled', true);
            $.ajax({
                url: "{{ route('penerimaan_ppkd.simpan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('Data berhasil ditambahkan, Nomor Baru yang tersimpan adalah: ' +
                            response.nomor);
                        window.location.href =
                            "{{ route('penerimaan_ppkd.index') }}";
                    } else if (response.message == '2') {
                        alert('Nomor telah digunakan!');
                        $('#simpan').prop('disabled', false);
                    } else {
                        alert('Data gagal disimpan!');
                        $('#simpan').prop('disabled', false);
                    }
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
</script>
