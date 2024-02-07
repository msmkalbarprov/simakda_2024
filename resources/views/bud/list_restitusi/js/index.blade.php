<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#list_restitusi').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('list_restitusi.load') }}",
                "type": "POST",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                }, {
                    data: 'no_kas',
                    name: 'no_kas',
                }, {
                    data: 'no_sts',
                    name: 'no_sts',
                },
                {
                    data: 'tgl_kas',
                    name: 'tgl_kas',
                    className: "text-center",
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                    className: "text-center",
                },
                {
                    data: null,
                    name: 'total',
                    className: 'text-right',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.total)
                    }
                }, {
                    data: 'keterangan',
                    name: 'keterangan',
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

    function angka(n) {
        let nilai = n.split(',').join('');
        return parseFloat(nilai) || 0;
    }

    function rupiah(n) {
        let n1 = n.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }

    function hapus(no_kas, no_sts, kd_skpd) {
        let tanya = confirm('Apakah anda yakin untuk menghapus data dengan Nomor Kas : ' + no_kas);
        if (tanya == true) {
            $.ajax({
                url: "{{ route('list_restitusi.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_kas: no_kas,
                    no_sts: no_sts,
                    kd_skpd: kd_skpd,
                    "_token": "{{ csrf_token() }}",
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
        } else {
            return false;
        }
    }

    function cetak(no_kas, no_sts, kd_skpd) {
        let url = new URL("{{ route('list_restitusi.cetak') }}");
        let searchParams = url.searchParams;
        searchParams.append("no_kas", no_kas);
        searchParams.append("no_sts", no_sts)
        searchParams.append("kd_skpd", kd_skpd);
        window.open(url.toString(), "_blank");
    }
</script>
