<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#verifikasi_dpr').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('dpr.load_verifikasi') }}",
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
                    data: 'no_dpr',
                    name: 'no_dpr',
                },
                {
                    data: 'tgl_dpr',
                    name: 'tgl_dpr',
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
    });

    function detail(no_dpr, kd_skpd) {
        $.ajax({
            url: "{{ route('dpr.detail_verifikasi') }}",
            type: "POST",
            dataType: 'json',
            data: {
                no_dpr: no_dpr,
                kd_skpd: kd_skpd
            },
            success: function(data) {
                console.log(data);
            }
        })
    }
</script>
