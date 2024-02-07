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

        let detail = $('#detail_lpj').DataTable({
            responsive: true,
            ordering: false,
            columns: [{
                    data: 'kd_skpd',
                    name: 'kd_skpd'
                },
                {
                    data: 'no_bukti',
                    name: 'no_bukti'
                },
                {
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan'
                },
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6'
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6',
                },
                {
                    data: 'nilai',
                    name: 'nilai',
                }
            ]
        });

        $('#no_sp2d').on('change', function() {
            detail.clear().draw();
            let no_sp2d = this.value
            let tgl = $(this).find(':selected').data('tgl');
            $("#tgl_sp2d").val(tgl);

            $.ajax({
                url: "{{ route('lpj_tu.detail') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_sp2d: no_sp2d,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    let total = rupiah(document.getElementById('total').value);
                    $.each(data, function(index, data) {
                        detail.row.add({
                            'kd_skpd': data.kd_bp_skpd,
                            'no_bukti': data.no_bukti,
                            'kd_sub_kegiatan': data.kd_sub_kegiatan,
                            'kd_rek6': data.kd_rek6,
                            'nm_rek6': data.nm_rek6,
                            'nilai': new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(data.nilai)
                        }).draw();
                        total += parseFloat(data.nilai);
                    })
                    $('#total').val(new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2
                    }).format(total));
                }
            });
        });

        $('#simpan').on('click', function() {
            let no_lpj = document.getElementById('no_lpj').value;
            if (no_lpj < 0) {
                alert("No LPJ harus diisi dengan benar!");
                return;
            }
            let tgl_lpj = document.getElementById('tgl_lpj').value;
            let no_sp2d = document.getElementById('no_sp2d').value;
            let tgl_sp2d = document.getElementById('tgl_sp2d').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let tahun_anggaran = document.getElementById('tahun_anggaran').value;
            let keterangan = document.getElementById('keterangan').value;
            let total = rupiah(document.getElementById('total').value);
            let tahun_input = tgl_lpj.substr(0, 4);

            let detail_lpj1 = detail.rows().data().toArray().map((value) => {
                let data = {
                    kd_skpd: value.kd_skpd,
                    no_bukti: value.no_bukti,
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    nm_rek6: value.nm_rek6,
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

            if (tgl_lpj < tgl_sp2d) {
                alert("Tanggal LPJ Harus Lebih besar dari tanggal Terbit SP2D");
                return;
            }

            if (!keterangan) {
                alert('Keterangan tidak boleh kosong!');
                return;
            }

            if (total == 0) {
                alert('Rincian tidak boleh kosong!');
                return;
            }

            let detail_lpj = JSON.stringify(detail_lpj1);

            if (detail_lpj1.length == 0) {
                alert('Rincian tidak boleh kosong!');
                return;
            }

            let data = {
                no_lpj,
                tgl_lpj,
                tgl_sp2d,
                no_sp2d,
                kd_skpd,
                keterangan,
                total,
                detail_lpj
            };

            $('#simpan').prop('disabled', true);
            $.ajax({
                url: "{{ route('lpj_tu.simpan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('Data berhasil ditambahkan!');
                        window.location.href = "{{ route('lpj_tu.index') }}";
                    } else if (response.message == '2') {
                        alert('Nomor Telah Dipakai!');
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
