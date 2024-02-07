<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2-modal').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#dpr').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('dpr.load_data') }}",
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
                // {
                //     data: 'kd_skpd',
                //     name: 'kd_skpd',
                // },
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

        $('.cetak').on('click', function() {
            let no_dpr = document.getElementById('no_dpr').value;
            let jenis_belanja = document.getElementById('jenis_belanja').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let pptk = document.getElementById('pptk').value;
            let margin_kiri = document.getElementById('margin_kiri').value;
            let margin_kanan = document.getElementById('margin_kanan').value;
            let margin_atas = document.getElementById('margin_atas').value;
            let margin_bawah = document.getElementById('margin_bawah').value;
            let jenis_print = $(this).data("jenis");

            if (!pptk) {
                alert("PPTK tidak boleh kosong!");
                return;
            }

            let url = new URL("{{ route('dpr.cetak_list') }}");
            let searchParams = url.searchParams;
            searchParams.append("no_dpr", no_dpr);
            searchParams.append("jenis_belanja", jenis_belanja);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("pptk", pptk);
            searchParams.append("jenis_print", jenis_print);
            searchParams.append("margin_kiri", margin_kiri);
            searchParams.append("margin_kanan", margin_kanan);
            searchParams.append("margin_atas", margin_atas);
            searchParams.append("margin_bawah", margin_bawah);
            window.open(url.toString(), "_blank");
        });
    });

    function hapus(no_dpr, kd_skpd) {
        let tabel = $("#dpr").DataTable();

        let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor DPR : ' + no_dpr);

        if (tanya == true) {
            $.ajax({
                url: "{{ route('dpr.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_dpr: no_dpr,
                    "_token": "{{ csrf_token() }}",
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

    function cetak(no_dpr, jenis_belanja, kd_skpd) {
        $('#no_dpr').val(no_dpr);
        $('#jenis_belanja').val(jenis_belanja);
        $('#kd_skpd').val(kd_skpd);
        $('#modal_cetak').modal('show');
    }
</script>
