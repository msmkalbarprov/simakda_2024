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

        $('#sp2bp_blud').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('sp2bp_blud.load') }}",
                "type": "POST",
            },
            createdRow: function(row, data, index) {
                if (data.no_sp2bps != 0) {
                    $(row).css("background-color", "#e4b4bb");
                    $(row).css("color", "black");
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                },
                {
                    data: 'no_sp2bp',
                    name: 'no_sp2bp',
                }, {
                    data: 'tgl_sp2bp',
                    name: 'tgl_sp2bp',
                    className: "text-center",
                }, {
                    data: 'no_sp3b',
                    name: 'no_sp3b',
                },
                {
                    data: 'tgl_sp3b',
                    name: 'tgl_sp3b',
                    width: '150px',
                    className: "text-center",
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                    className: 'text-right'
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

            if (!bud) {
                alert("Kuasa BUD tidak boleh kosong!");
                return;
            }

            let url = new URL("{{ route('sp2bp_blud.cetak') }}");
            let searchParams = url.searchParams;
            searchParams.append("no_sp2bp", no_spb);
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

    function hapus(no_sp2bp, no_sp3b, kd_skpd) {
        let spb = $('#sp2bp_blud').DataTable();

        let tanya = confirm('Apakah anda yakin untuk menghapus data dengan Nomor SP2BP : ' + no_sp2bp);

        if (tanya == true) {
            $.ajax({
                url: "{{ route('sp2bp_blud.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_sp2bp: no_sp2bp,
                    no_sp3b: no_sp3b,
                    kd_skpd: kd_skpd,
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
