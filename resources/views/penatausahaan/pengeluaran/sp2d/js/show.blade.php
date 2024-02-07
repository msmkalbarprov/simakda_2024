<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let tabel_spm = $('#rincian_spm').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('sp2d.load_rincian_spm') }}",
                "type": "POST",
                "data": function(d) {
                    d.no_spp = document.getElementById('no_spp').value
                }
            },
            ordering: false,
            columns: [{
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
                    name: 'sisa',
                    className: 'text-right',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.sisa)
                    }
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
                },
            ]
        });

        let tabel_potongan = $('#rincian_potongan').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('sp2d.load_rincian_potongan') }}",
                "type": "POST",
                "data": function(d) {
                    d.no_spm = document.getElementById('no_spm').value
                }
            },
            ordering: false,
            columns: [{
                    data: 'kd_rek6',
                    name: 'kd_rek6',
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6',
                },
                {
                    data: 'idBilling',
                    name: 'idBilling',
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
                },
                {
                    data: 'pot',
                    name: 'pot',
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('.cetak_billing').on('click', function() {
            let id_billing = document.getElementById('id_billing_cetak').value;
            let jnsreport = $(this).data("cetak");

            $.ajax({
                type: "POST",
                url: "{{ route('spm.create_report') }}",
                dataType: 'json',
                data: {
                    id_billing: id_billing,
                    jnsreport: jnsreport,
                    "_token": "{{ csrf_token() }}",
                },
                dataType: "json",
                success: function(data) {
                    let data1 = $.parseJSON(data);
                    console.table(data1);
                    if (data1.data[0].response_code == '00') {
                        alert(data1.data[0].message);
                        $("#link1").attr("value", data1.data[0].data.linkDownload);
                        window.open(data1.data[0].data.linkDownload);
                    } else {
                        alert(data1.data[0].message);
                    }
                }
            })
        });
    });

    function cetakPajak(no_spm, kd_rek6, nm_rek6, nilai, idBilling) {
        $("#id_billing_cetak").val(idBilling);
        $('#modal_cetak').modal('show');
    }
</script>
