<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select-modal').select2({
            dropdownParent: $('#modal_tambah .modal-content'),
            theme: 'bootstrap-5'
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        let detail = $('#detail_lpj').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    data: 'no_lpj',
                    name: 'no_lpj'
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd'
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd'
                },
                {
                    data: 'no_lpj_unit',
                    name: 'no_lpj_unit'
                },
                {
                    data: 'nilai',
                    name: 'nilai',
                },
                {
                    data: 'aksi',
                    name: 'aksi'
                }
            ]
        });

        $('#pilih_no_lpj').on('select2:select', function() {
            let kd_skpd = $(this).find(':selected').data('kd_skpd');
            let nm_skpd = $(this).find(':selected').data('nm_skpd');
            let nilai = $(this).find(':selected').data('nilai');

            $("#unit").val(kd_skpd);
            $("#nm_unit").val(nm_skpd);
            $("#nilai").val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(nilai));
        });

        $('#tambah_rincian').on('click', function() {
            $('#modal_tambah').modal('show');
        });

        $('#pilih').on('click', function() {
            let pilih_no_lpj = document.getElementById('pilih_no_lpj').value;
            let no_lpj = document.getElementById('no_lpj').value;
            let unit = document.getElementById('unit').value;
            let nm_unit = document.getElementById('nm_unit').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nilai = rupiah(document.getElementById('nilai').value);
            let total = rupiah(document.getElementById('total').value);

            if (!pilih_no_lpj) {
                alert('Pilih Nomor LPJ Dahulu...!!!');
                return;
            }

            if (!no_lpj) {
                alert('Isi Nomor LPJ Global Dahulu...!!!');
                return;
            }

            detail.row.add({
                'no_lpj': no_lpj,
                'kd_skpd': unit,
                'nm_skpd': nm_unit,
                'no_lpj_unit': pilih_no_lpj,
                'nilai': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai),
                'aksi': `<a href="javascript:void(0);" onclick="hapus('${pilih_no_lpj}','${unit}','${nilai}')" class="btn btn-danger btn-sm"><i class="uil-trash"></i></a>`,
            }).draw();
        });

        $('#simpan').on('click', function() {
            let jumlah_spd = rupiah(document.getElementById('jumlah_spd').value);
            let realisasi_spd_spp = rupiah(document.getElementById('realisasi_spd_spp').value);
            let total = rupiah(document.getElementById('total').value);
            if (jumlah_spd < realisasi_spd_spp + total) {
                alert('Total SPD tidak mencukupi...!!!');
                return;
            }

            let no_lpj = document.getElementById('no_lpj').value;
            if (no_lpj < 0) {
                alert("No LPJ harus diisi dengan benar!");
                return;
            }
            let no_lpj_simpan = document.getElementById('no_lpj_simpan').value;
            let tgl_lpj = document.getElementById('tgl_lpj').value;
            let tgl_awal = document.getElementById('tgl_awal').value;
            let tgl_akhir = document.getElementById('tgl_akhir').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let tahun_anggaran = document.getElementById('tahun_anggaran').value;
            let keterangan = document.getElementById('keterangan').value;
            let nilai_up = rupiah(document.getElementById('nilai_up').value);
            let nilai_min_gu = rupiah(document.getElementById('nilai_min_gu').value);
            let sisa_spd = rupiah(document.getElementById('sisa_spd').value);
            let tahun_input = tgl_lpj.substr(0, 4);

            let detail_lpj = detail.rows().data().toArray().map((value) => {
                let data = {
                    kd_skpd: value.kd_skpd,
                    no_bukti: value.no_bukti,
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    kdrek6: value.kdrek6,
                    nmrek6: value.nmrek6,
                    nilai: rupiah(value.nilai),
                };
                return data;
            });

            if (!no_lpj) {
                alert('Nomor tidak boleh kosong');
                return;
            }

            if (!tgl_lpj) {
                alert('Tanggal tidak boleh kosong!');
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

            if (detail_lpj.length == 0) {
                alert('Rincian tidak boleh kosong!');
                return;
            }

            let data = {
                no_lpj,
                tgl_lpj,
                tgl_awal,
                tgl_akhir,
                kd_skpd,
                keterangan,
                total,
                detail_lpj
            };

            $('#simpan').prop('disabled', true);
            $.ajax({
                url: "{{ route('lpj.skpd_tanpa_unit.simpan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('Data berhasil ditambahkan!');
                        window.location.href =
                            "{{ route('lpj.skpd_tanpa_unit.index') }}";
                    } else {
                        alert('Data tidak berhasil ditambahkan!');
                        $('#simpan').prop('disabled', false);
                        return;
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

        function load_nomor() {
            // $.ajax({
            //     url: "{{ route('sppls.cari_rekening') }}",
            //     type: "POST",
            //     dataType: 'json',
            //     data: {
            //         kd_sub_kegiatan: kd_sub_kegiatan,
            //     },
            //     success: function(data) {
            //         $('#kode_rekening').empty();
            //         $('#kode_rekening').append(`<option value="0">Silahkan Pilih</option>`);
            //         $.each(data, function(index, data) {
            //             $('#kode_rekening').append(
            //                 `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}">${data.kd_rek6} | ${data.nm_rek6}</option>`
            //             );
            //         })
            //     }
            // })
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

    function hapus(no_bukti, kd_rek6, nilai) {
        let hapus = confirm('Yakin Ingin Menghapus Data, Rekening : ' + kd_rek6 + '  Nilai :  ' + nilai +
            ' ?');
        let total = rupiah(document.getElementById('total').value);
        let tabel = $('#detail_lpj').DataTable();

        if (hapus == true) {
            tabel.rows(function(idx, data, node) {
                return data.no_bukti == no_bukti && data.kdrek6 == kd_rek6
            }).remove().draw();
            $('#total').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total - parseFloat(nilai)));
        }
    }
</script>
