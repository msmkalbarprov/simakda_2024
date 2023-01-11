<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#pengesahan_lpj').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('pengesahan_lpj_tu.load') }}",
                "type": "POST",
            },
            createdRow: function(row, data, index) {
                if (data.status == 1 || data.status == 2) {
                    $(row).css("background-color", "#4bbe68");
                    $(row).css("color", "white");
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                },
                {
                    data: 'no_lpj',
                    name: 'no_lpj',
                    className: "text-center",
                },
                {
                    data: 'tgl_lpj',
                    name: 'tgl_lpj',
                    className: "text-center",
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                    className: "text-center",
                },
                {
                    data: null,
                    name: 'keterangan',
                    render: function(data, type, row, meta) {
                        return data.keterangan.substr(0, 10) + '.....';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    className: "text-center",
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: 100,
                    className: "text-center",
                },
            ],
        });
    });

    function hapus(no_kas, no_sts, kd_skpd, tgl_kas) {
        let tanya = confirm('Apakah anda yakin untuk menghapus data dengan Nomor Kas : ' + no_kas);
        if (tanya == true) {
            $.ajax({
                url: "{{ route('penerimaan_kas.kunci_kasda') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd,
                },
                success: function(data) {
                    if (tgl_kas < data) {
                        alert('Tanggal kas lebih kecil dari tanggal kuncian!');
                        return;
                    } else {
                        $.ajax({
                            url: "{{ route('penerimaan_kas.hapus') }}",
                            type: "POST",
                            dataType: 'json',
                            data: {
                                no_kas: no_kas,
                                no_sts: no_sts,
                                kd_skpd: kd_skpd,
                            },
                            success: function(data) {
                                if (data.message == '1') {
                                    alert('Proses Hapus Berhasil');
                                    window.location.reload();
                                } else {
                                    alert('Proses Hapus Gagal...!!!');
                                }
                            }
                        })
                    }
                }
            })
        } else {
            return false;
        }
    }

    function cetak(no_kas, no_sts, kd_skpd) {
        let url = new URL("{{ route('penerimaan_kas.cetak') }}");
        let searchParams = url.searchParams;
        searchParams.append("no_kas", no_kas);
        searchParams.append("no_sts", no_sts);
        searchParams.append("kd_skpd", kd_skpd);
        window.open(url.toString(), "_blank");
    }
</script>
