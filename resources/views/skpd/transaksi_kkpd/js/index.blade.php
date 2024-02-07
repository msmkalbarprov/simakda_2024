<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#transaksi_kkpd').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('skpd.transaksi_kkpd.load_data') }}",
                "type": "POST",
            },
            createdRow: function(row, data, index) {
                if (data.status_validasi == "1") {
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
                    className: "text-center",
                },
                {
                    data: 'tgl_voucher',
                    name: 'tgl_voucher',
                    className: "text-center",
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                    className: "text-center",
                },
                {
                    data: null,
                    name: 'ket',
                    render: function(data, type, row, meta) {
                        return data.ket.substr(0, 10) + '.....';
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
                    data: null,
                    name: 'status_validasi',
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (data.status_validasi == '1') {
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

    function hapus(no_voucher) {
        let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor Bukti : ' + no_voucher)
        if (tanya == true) {
            $.ajax({
                url: "{{ route('skpd.transaksi_kkpd.hapus_cms') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_voucher: no_voucher,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Data berhasil dihapus!');
                        location.reload();
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
