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

        $('#sp2b').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('sp2b.load') }}",
                "type": "POST",
            },
            createdRow: function(row, data, index) {
                if (data.status == "1" || data.status == "2") {
                    $(row).css("background-color", "#e4b4bb");
                    $(row).css("color", "black");
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                }, {
                    data: 'no_sp2b',
                    name: 'no_sp2b',
                },
                {
                    data: 'tgl_sp2b',
                    name: 'tgl_sp2b',
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
            let no_sp2b = document.getElementById('no_sp2b').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let pa_kpa = document.getElementById('pa_kpa').value;
            let atas = document.getElementById('atas').value;
            let bawah = document.getElementById('bawah').value;
            let kiri = document.getElementById('kiri').value;
            let kanan = document.getElementById('kanan').value;
            let jenis_print = $(this).data("jenis");

            if (!pa_kpa) {
                alert("Pengguna Anggaran tidak boleh kosong!");
                return;
            }

            let url = new URL("{{ route('sp2b.cetak') }}");
            let searchParams = url.searchParams;
            searchParams.append("no_sp2b", no_sp2b);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("pa_kpa", pa_kpa);
            searchParams.append("jenis_print", jenis_print);
            searchParams.append("atas", atas);
            searchParams.append("bawah", bawah);
            searchParams.append("kiri", kiri);
            searchParams.append("kanan", kanan);
            window.open(url.toString(), "_blank");
        });
    });

    function cetak(no_sp2b, jenis, kd_skpd) {
        $('#no_sp2b').val(no_sp2b);
        $('#jenis').val(jenis);
        $('#kd_skpd').val(kd_skpd);
        $('#modal_cetak').modal('show');
    }

    function hapus(no_sp2b, kd_skpd) {
        let tanya = confirm('Apakah anda yakin untuk menghapus data dengan Nomor SP2B : ' + no_sp2b);
        if (tanya == true) {
            $.ajax({
                url: "{{ route('sp2b.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_sp2b: no_sp2b,
                    kd_skpd: kd_skpd,
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Proses Hapus Berhasil');
                        window.location.reload();
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