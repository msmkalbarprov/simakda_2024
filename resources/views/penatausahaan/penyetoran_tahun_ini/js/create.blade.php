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

        let detail = $('#detail_sts').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    visible: false,
                    data: 'no_sts',
                    name: 'no_sts'
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
                    data: 'nilai',
                    name: 'nilai'
                },
                {
                    data: 'sumber',
                    name: 'sumber',
                    visible: false
                },
                {
                    data: 'kanal',
                    name: 'kanal',
                    visible: false
                },
                {
                    data: 'nama_kanal',
                    name: 'nama_kanal',
                },
                {
                    data: 'nama_pengirim',
                    name: 'nama_pengirim',
                },
                {
                    data: 'aksi',
                    name: 'aksi'
                }
            ]
        });

        $('#tgl_terima').on('change', function() {
            let tgl_terima = this.value;
            $('#no_terima').empty();

            $.ajax({
                url: "{{ route('penyetoran_ini.no_terima') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    tgl_terima: tgl_terima
                },
                success: function(data) {
                    $('#no_terima').empty();
                    $('#no_terima').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#no_terima').append(
                            `<option value="${data.no_terima}" data-tgl_terima="${data.tgl_terima}" data-kd_rek6="${data.kd_rek6}" data-nm_rek6="${data.nm_rek6}" data-nilai="${data.nilai}" data-sumber="${data.sumber}" data-kanal="${data.kanal}" data-nama_kanal="${data.nama}" data-nm_pengirim="${data.nm_pengirim}">${data.no_terima} | ${data.kd_rek6} | ${new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai)}</option>`
                        );
                    })
                }
            })
        });

        $('#no_terima').on('select2:select', function() {
            let tgl_terima = $(this).find(':selected').data('tgl_terima');
            let kd_rek6 = $(this).find(':selected').data('kd_rek6');
            let nm_rek6 = $(this).find(':selected').data('nm_rek6');
            let nilai = $(this).find(':selected').data('nilai');
            let sumber = $(this).find(':selected').data('sumber');
            let kanal = $(this).find(':selected').data('kanal');
            let nama_kanal = $(this).find(':selected').data('nama_kanal');
            let nm_pengirim = $(this).find(':selected').data('nm_pengirim');

            detail.row.add({
                'no_sts': this.value,
                'kd_rek6': kd_rek6,
                'nm_rek6': nm_rek6,
                'nilai': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai),
                'sumber': sumber,
                'kanal': kanal,
                'nama_kanal': nama_kanal,
                'nama_pengirim': nm_pengirim,
                'aksi': `<a href="javascript:void(0);" onclick="deleteData('${this.value}','${kd_rek6}','${nm_rek6}','${nilai}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>`,
            }).draw();
        });

        $('#kd_sub_kegiatan').on('select2:select', function() {
            let nama = $(this).find(':selected').data('nama');
            $('#nm_sub_kegiatan').val(nama);
        });

        $('#simpan').on('click', function() {
            let no_sts = document.getElementById('no_sts').value;
            let tgl_sts = document.getElementById('tgl_sts').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = document.getElementById('nm_skpd').value;
            let pengirim = document.getElementById('pengirim').value;
            let nm_pengirim = document.getElementById('nm_pengirim').value;
            let kd_sub_kegiatan = document.getElementById('kd_sub_kegiatan').value;
            let tahun_anggaran = document.getElementById('tahun_anggaran').value;
            let keterangan = document.getElementById('keterangan').value;
            let total = rupiah(document.getElementById('total').value);
            let tahun_input = tgl_sts.substr(0, 4);

            if (!tgl_sts) {
                alert('Tanggal Tidak Boleh Kosong');
                return;
            }

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (!no_sts) {
                alert('No STS Tidak Boleh Kosong');
                return;
            }

            if (!kd_skpd) {
                alert('Kode SKPD Tidak Boleh Kosong');
                return;
            }

            if (!pengirim) {
                alert('Pengirim Tidak Boleh Kosong');
                return;
            }

            let detail_sts = detail.rows().data().toArray().map((value) => {
                let data = {
                    no_sts: value.no_sts,
                    kd_rek6: value.kd_rek6,
                    nm_rek6: value.nm_rek6,
                    nilai: rupiah(value.nilai),
                };
                return data;
            });

            if (detail_sts.length == 0) {
                alert('Detail STS tidak boleh kosong!');
                return;
            }

            let data = {
                no_sts,
                tgl_sts,
                kd_skpd,
                nm_skpd,
                pengirim,
                kd_sub_kegiatan,
                keterangan,
                total,
                detail_sts
            };

            $('#simpan').prop('disabled', true);
            $.ajax({
                url: "{{ route('penyetoran_lalu.simpan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('Data berhasil disimpan!');
                        window.location.href =
                            "{{ route('penyetoran_lalu.index') }}";
                    } else if (response.message == '2') {
                        alert(
                            "Tanggal sudah dikunci KASDA. Silahkan Hubungi operator/staff di bagian KASDA."
                        );
                        $('#simpan').prop('disabled', false);
                    } else if (response.message == '3') {
                        alert(
                            "Tanggal sudah dikunci Akuntansi. Silahkan Hubungi operator/staff di bagian Akuntansi."
                        );
                        $('#simpan').prop('disabled', false);
                    } else if (response.message == '4') {
                        alert("Nomor Telah Dipakai!");
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
