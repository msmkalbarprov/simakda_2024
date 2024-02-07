<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select-modal').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#spb').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('spb_hibah.load') }}",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            // createdRow: function(row, data, index) {
            //     if (data.no_spbs != 0) {
            //         $(row).css("background-color", "#e4b4bb");
            //         $(row).css("color", "black");
            //     }
            // },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                },
                {
                    data: 'no_spb_hibah',
                    name: 'no_spb_hibah',
                }, {
                    data: 'tgl_spb_hibah',
                    name: 'tgl_spb_hibah',
                    className: "text-center",
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                },
                {
                    data: null,
                    name: 'total',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.total)
                    }
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: '100px',
                    className: "text-center",
                },
            ],
        });

        $('.cetak').on('click', function() {
            let no_spb = document.getElementById('no_spb').value;
            let tgl_spb = document.getElementById('tgl_spb').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let bud = document.getElementById('bud').value;
            let atas = document.getElementById('atas').value;
            let bawah = document.getElementById('bawah').value;
            let kiri = document.getElementById('kiri').value;
            let kanan = document.getElementById('kanan').value;
            let jenis_print = $(this).data("jenis");

            if (!no_spb) {
                alert("No SPB tidak boleh kosong!");
                return;
            }

            if (!tgl_spb) {
                alert("Tanggal SPB tidak boleh kosong!");
                return;
            }

            if (!bud) {
                alert("Kuasa BUD tidak boleh kosong!");
                return;
            }

            let url = new URL("{{ route('spb_hibah.cetak') }}");
            let searchParams = url.searchParams;
            searchParams.append("no_spb", no_spb);
            searchParams.append("tgl_spb", tgl_spb);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("bud", bud);
            searchParams.append("jenis_print", jenis_print);
            searchParams.append("atas", atas);
            searchParams.append("bawah", bawah);
            searchParams.append("kiri", kiri);
            searchParams.append("kanan", kanan);
            window.open(url.toString(), "_blank");
        });
    });

    function cetak(no_spb, kd_skpd) {
        $('#no_spb').val(no_spb);
        $('#kd_skpd').val(kd_skpd);
        $('#modal_cetak').modal('show');
    }

    function hapus(no_spb, kd_skpd) {
        let spb = $('#spb').DataTable();

        let tanya = confirm('Apakah anda yakin untuk menghapus data dengan Nomor SPB : ' + no_spb);

        if (tanya == true) {
            $.ajax({
                url: "{{ route('spb_hibah.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_spb: no_spb,
                    kd_skpd: kd_skpd,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Proses Hapus Berhasil');
                        spb.ajax.reload();
                    } else {
                        alert('Proses Hapus Gagal...!!!');
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        } else {
            return false;
        }
    }
</script>
