<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#jenis').prop('disabled', true);
        $('#pengirim').prop('disabled', true);
        $('#rkud').prop('disabled', true);

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        no_bukti();

        $('#no_sts').on('select2:select', function() {
            let kd_rek6 = $(this).find(':selected').data('kd_rek6');
            let sumber = $(this).find(':selected').data('sumber');
            $('#jenis').val(kd_rek6).change();
            $('#pengirim').val(sumber).change();
        });

        $('#simpan').on('click', function() {
            let no_kas = document.getElementById('no_kas').value;
            let tgl_kas = document.getElementById('tgl_kas').value;
            let no_sts = document.getElementById('no_sts').value;
            let jenis = document.getElementById('jenis').value;
            let pengirim = document.getElementById('pengirim').value;
            let tahun_anggaran = document.getElementById('tahun_anggaran').value;
            let rkud = document.getElementById('rkud').value;
            let keterangan = document.getElementById('keterangan').value;
            let nilai = angka(document.getElementById('nilai').value);
            let tahun_input = tgl_kas.substr(0, 4);

            let sts = $('#no_sts').find('option:selected');
            let total = rupiah(sts.data('total'));
            let tgl_sts = sts.data('tgl');

            if (!tgl_kas) {
                alert('Tanggal Tidak Boleh Kosong');
                return;
            }

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (tgl_kas < tgl_sts) {
                alert('Tanggal Kas tidak boleh lebih kecil dari Tanggal STS!');
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

            if (nilai > total) {
                alert('Nilai potongan tidak boleh lebih besar dari nilai penerimaan!');
                return;
            }

            if (nilai == '0') {
                alert('Nilai 0!Cek Lagi!!!');
                return;
            }

            let data = {
                no_kas,
                tgl_kas,
                no_sts,
                tgl_sts,
                jenis,
                pengirim,
                rkud,
                keterangan,
                nilai,
            };

            $.ajax({
                url: "{{ route('potongan_ppkd.simpan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $('#simpan').prop('disabled', true);
                    $("#overlay").fadeIn(100);
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('Data berhasil ditambahkan, Nomor Baru yang tersimpan adalah: ' +
                            response.nomor);
                        $('#no_kas').val(null);
                        $('#no_sts').empty();
                        $('#jenis').val(null).change();
                        $('#pengirim').val(null).change();
                        $('#nilai').val(null);
                        $('#keterangan').val(null);
                        $('#simpan').prop('disabled', false);
                        no_bukti();
                    } else if (response.message == '2') {
                        alert('Nomor telah digunakan!');
                        $('#simpan').prop('disabled', false);
                    } else {
                        alert('Data gagal disimpan!');
                        $('#simpan').prop('disabled', false);
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
    });

    function no_bukti() {
        $.ajax({
            url: "{{ route('potongan_ppkd.no_bukti') }}",
            type: "POST",
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            beforeSend: function() {
                $("#overlay").fadeIn(100);
            },
            success: function(data) {
                let data_sts = data.data_sts;

                $('#no_sts').empty();
                $('#no_sts').append(
                    `<option value="" disabled selected>Silahkan Pilih</option>`);
                $.each(data_sts, function(index, data_sts) {
                    $('#no_sts').append(
                        `<option value="${data_sts.no_sts}" data-tgl="${data_sts.tgl_sts}" data-kd_rek6="${data_sts.kd_rek6.trim()}" data-sumber="${data_sts.sumber}" data-total="${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(data_sts.total)}">${data_sts.no_sts} | ${data_sts.tgl_sts} | ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(data_sts.total)}</option>`
                    );
                });
                $('#no_kas').val(data.no_urut);
            },
            complete: function(data) {
                $("#overlay").fadeOut(100);
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
