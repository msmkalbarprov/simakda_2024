<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#dpt_gabungan').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('dpt_gabungan.load_data') }}",
                "type": "POST",
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
                // {
                //     data: null,
                //     name: 'total',
                //     render: function(data, type, row, meta) {
                //         return new Intl.NumberFormat('id-ID', {
                //             minimumFractionDigits: 2
                //         }).format(data.total)
                //     }
                // },
                // {
                //     data: null,
                //     name: 'status_verifikasi',
                //     className: "text-center",
                //     render: function(data, type, row, meta) {
                //         if (data.status_verifikasi == '1') {
                //             return '&#10004';
                //         } else {
                //             return '&#10008';
                //         }
                //     }
                // },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: 100,
                    className: "text-center",
                },
            ],
        });
    });

    function hapus(no_dpt, kd_skpd) {
        let tabel = $("#dpt_gabungan").DataTable();

        let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor DPT : ' + no_dpt);

        if (tanya == true) {
            $.ajax({
                url: "{{ route('dpt_gabungan.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_dpt: no_dpt,
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
