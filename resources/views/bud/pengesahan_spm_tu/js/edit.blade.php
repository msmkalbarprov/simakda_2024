<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let status = "{{ $spp->status }}";
        let sts_setuju = "{{ $spp->sts_setuju }}";

        if (status == 1 && sts_setuju == 1) {
            $('#setuju').prop('disabled', true);
            $('#batal_setuju').prop('disabled', true);
        } else if (status == 1 && (sts_setuju == 0 || sts_setuju == '')) {
            $('#setuju').prop('disabled', true);
            $('#batal_setuju').prop('disabled', true);
        } else if ((status == 0 || status == '') && sts_setuju == 1) {
            $('#setuju').prop('disabled', true);
            $('#batal_setuju').prop('disabled', false);
        } else {
            $('#setuju').prop('disabled', false);
            $('#batal_setuju').prop('disabled', true);
        }

        $('#detail_spp').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('pengesahan_spp_tu.detail') }}",
                "type": "POST",
                "data": function(d) {
                    d.no_spp = document.getElementById('no_spp').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                },
                "dataSrc": function(data) {
                    recordsTotal = data.data;
                    return recordsTotal;
                },
            },
            lengthMenu: [
                [-1],
                ["All"]
            ],
            ordering: false,
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
                    className: 'text-right',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai)
                    }
                }
            ],
            drawCallback: function(select) {
                let total = recordsTotal.reduce((previousValue,
                    currentValue) => (previousValue += parseFloat(currentValue.nilai)), 0);
                $('#total').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total));
            }
        });

        $('#setuju').on('click', function() {
            let no_spp = document.getElementById('no_spp').value;
            let kd_skpd = document.getElementById('kd_skpd').value;

            $('#setuju').prop('disabled', true);
            $.ajax({
                url: "{{ route('pengesahan_spp_tu.setuju') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_spp: no_spp,
                    kd_skpd: kd_skpd
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('SPP TU Sudah Disetujui');
                        window.location.reload();
                    } else {
                        alert('SPP TU Gagal Disetujui!');
                        $('#setuju').prop('disabled', false);
                    }
                }
            })
        });

        $('#batal_setuju').on('click', function() {
            let no_spp = document.getElementById('no_spp').value;
            let kd_skpd = document.getElementById('kd_skpd').value;

            $('#batal_setuju').prop('disabled', true);
            $.ajax({
                url: "{{ route('pengesahan_spp_tu.batal_setuju') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_spp: no_spp,
                    kd_skpd: kd_skpd
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('SPP TU telah dibatalkan');
                        window.location.reload();
                    } else {
                        alert('SPP TU Gagal Dibatalkan!');
                        $('#batal_setuju').prop('disabled', false);
                    }
                }
            })
        });
    });
</script>
