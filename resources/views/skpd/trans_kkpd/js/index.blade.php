<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#trans_kkpd').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('trans_kkpd.load_data') }}",
                "type": "POST",
            },
            createdRow: function(row, data, index) {
                if (data.status_upload == "1") {
                    $(row).css("background-color", "#B0E0E6");
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                }, {
                    data: 'no_voucher',
                    name: 'no_voucher',
                },
                {
                    data: 'tgl_voucher',
                    name: 'tgl_voucher',
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
                    name: 'status_upload',
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (data.status_upload == '1') {
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
    });

    function hapus(no_dpt, no_dpr, kd_skpd) {
        let tabel = $("#dpt").DataTable();

        let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor DPT : ' + no_dpt);

        if (tanya == true) {
            $.ajax({
                url: "{{ route('dpt.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_dpt: no_dpt,
                    no_dpr: no_dpr,
                    kd_skpd: kd_skpd
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Data berhasil dihapus!');
                        tabel.ajax.reload();
                    } else {
                        alert('Data gagal dihapus!');
                        return;
                    }
                }
            })
        } else {
            return false;
        }
    }
</script>
