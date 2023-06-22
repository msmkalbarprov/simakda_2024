<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#kunci_jurnal').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [
                [-1],
                ["All"]
            ],
            ajax: {
                "url": "{{ route('kunci_jurnal.load') }}",
                "type": "POST",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                },
                {
                    data: null,
                    name: 'jurnal',
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        if (data.kd_skpd == '-') {
                            return `<a href="javascript:void(0);" onclick="kunci('${data.kd_skpd}','${data.kunci_jurnal}')" class="btn btn-dark btn-md"><i class="fa fa-lock"></i> / <i class="fas fa-lock-open"></i></a>`
                        } else {
                            if (data.kunci_jurnal == 1) {
                                return `<a href="javascript:void(0);" onclick="kunci('${data.kd_skpd}','${data.kunci_jurnal}')" class="btn btn-info btn-md"><i class="fa fa-lock" aria-hidden="true"></i></a>`
                            } else {
                                return `<a href="javascript:void(0);" onclick="kunci('${data.kd_skpd}','${data.kunci_jurnal}')" class="btn btn-danger btn-md"><i class="fas fa-lock-open"></i></a>`
                            }
                        }
                    }
                }
            ],
        });

        $('.kunci').on('click', function() {
            let tabel = $('#kunci_jurnal').DataTable();
            let nilai = $(this).data("nilai");
            let kd_skpd = $('#kd_skpd').val();
            let jenis = $('#jenis').val();


            $.ajax({
                url: "{{ route('kunci_jurnal.kunci') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd,
                    jenis: jenis,
                    kunci: nilai
                },
                success: function(data) {
                    if (data.message == 1) {
                        tabel.ajax.reload();
                        $('#kunci_semua').modal('hide');
                    }
                }
            });
        });
    });

    function kunci(kd_skpd, kunci) {
        if (kd_skpd == '-') {
            $('#kd_skpd').val(kd_skpd);
            $('#kunci_semua').modal('show');
        } else {
            let tabel = $('#kunci_jurnal').DataTable();
            $.ajax({
                url: "{{ route('kunci_jurnal.kunci') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: kd_skpd,
                    kunci: kunci,
                },
                success: function(data) {
                    if (data.message == 1) {
                        tabel.ajax.reload();
                    }
                }
            });
        }

    }
</script>
