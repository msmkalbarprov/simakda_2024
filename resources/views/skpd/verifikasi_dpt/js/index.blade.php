<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let tabel = $('#verifikasi_dpt').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('dpt.load_verifikasi') }}",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            createdRow: function(row, data, index) {
                if (data.status_verifikasi == "1") {
                    $(row).css("background-color", "#B0E0E6");
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                }, {
                    data: 'no_dpt',
                    name: 'no_dpt',
                },
                {
                    data: 'tgl_dpt',
                    name: 'tgl_dpt',
                    className: "text-center",
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                },
                {
                    data: null,
                    name: 'total',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.total)
                    }
                },
                {
                    data: null,
                    name: 'status_verifikasi',
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (data.status_verifikasi == '1') {
                            return '&#10004';
                        } else {
                            return '&#10008';
                        }
                    }
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: 100,
                    className: "text-center",
                },
            ],
        });

        let tabel1 = $('#detail_dpt').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [
                [-1],
                ["All"]
            ],
            ajax: {
                "url": "{{ route('dpt.detail_verifikasi') }}",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "data": function(d) {
                    d.no_dpt = document.getElementById('no_dpt').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                },
                "dataSrc": function(data) {
                    recordsTotal = data.data;
                    return recordsTotal;
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
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
                    data: 'nm_sumber',
                    name: 'nm_sumber',
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
            ],
            drawCallback: function(select) {
                let total = recordsTotal.reduce((previousValue,
                    currentValue) => (previousValue += parseFloat(currentValue.nilai)), 0);
                $('#total_belanja').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total));
            }
        });

        $('.simpan').on('click', function() {
            let no_dpt = document.getElementById('no_dpt').value;
            let tgl_dpt = document.getElementById('tgl_dpt').value;
            let no_dpr = document.getElementById('no_dpr').value;
            let tgl_dpr = document.getElementById('tgl_dpr').value;
            let tgl_verifikasi = document.getElementById('tgl_verifikasi').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let jenis = $(this).data("jenis");
            let tahun_anggaran = "{{ tahun_anggaran() }}";

            let nama = '';
            if (jenis == 'terima') {
                nama = "memverifikasi";
            } else {
                nama = "membatalkan";
            }

            if (!tgl_verifikasi) {
                alert('Silahkan pilih tanggal verifikasi!');
                return;
            }

            let tahun_input = tgl_verifikasi.substr(0, 4);

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Silahkan dicek terlebih dahulu sebelum ' + nama + '!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Tidak!',
            }).then((result) => {
                if (result.isConfirmed) {
                    simpan(no_dpt, no_dpr, kd_skpd, tgl_verifikasi, jenis);
                }
            })
        });

        function simpan(no_dpt, no_dpr, kd_skpd, tgl_verifikasi, jenis) {
            $('.simpan').prop('disabled', true);

            $.ajax({
                url: "{{ route('dpt.simpan_verifikasi') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_dpt: no_dpt,
                    no_dpr: no_dpr,
                    kd_skpd: kd_skpd,
                    tgl_verifikasi: tgl_verifikasi,
                    jenis: jenis,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    if (data.message == '1') {
                        if (jenis == 'terima') {
                            Swal.fire(
                                'Berhasil!',
                                'Data berhasil diverifikasi!',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Berhasil!',
                                'Data Berhasil Dibatalkan...!!!',
                                'warning'
                            )

                        }


                        $('#no_dpt').val(null);
                        $('#tgl_dpt').val(null);
                        $('#no_dpr').val(null);
                        $('#tgl_dpr').val(null);
                        $('#kd_skpd').val(null);
                        $('#nm_skpd').val(null);
                        $('#status').val(null);
                        $('#status_verifikasi').val(null);
                        $('#tgl_verifikasi').val(null);

                        tabel.ajax.reload();
                        tabel1.ajax.reload();

                        $('#modal_lihat').modal('hide');
                        $('.simpan').prop('disabled', false);
                    } else {
                        alert('Proses gagal...!');
                        $('.simpan').prop('disabled', false);
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        }
    });

    function detail(no_dpt, no_dpr, kd_skpd, tgl_dpt, tgl_dpr, nm_skpd, status, status_verifikasi, tgl_verifikasi) {
        $('#no_dpt').val(no_dpt);
        $('#tgl_dpt').val(tgl_dpt);
        $('#no_dpr').val(no_dpr);
        $('#tgl_dpr').val(tgl_dpr);
        $('#kd_skpd').val(kd_skpd);
        $('#nm_skpd').val(nm_skpd);
        $('#status').val(status);
        $('#status_verifikasi').val(status_verifikasi);
        $('#tgl_verifikasi').val(tgl_verifikasi);

        let tabel = $('#detail_dpt').DataTable();
        tabel.ajax.reload();

        if (status_verifikasi == '1' && status == '0') {
            $('.terima').hide();
            $('.batal').show();
        } else if (status_verifikasi == '1' && status == '1') {
            $('.terima').hide();
            $('.batal').hide();
        } else if (status_verifikasi == '0' && status == '0') {
            $('.terima').show();
            $('.batal').hide();
        }

        $('#modal_lihat').modal('show');
    }
</script>
