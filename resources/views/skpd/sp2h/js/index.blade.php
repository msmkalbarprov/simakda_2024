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

        $('#sp2h').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('sp2h.load') }}",
                "type": "POST",
            },
            createdRow: function(row, data, index) {
                if (data.status == 1 || data.status == 2) {
                    $(row).css("background-color", "#e4b4bb");
                    $(row).css("color", "black");
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                }, {
                    data: 'no_sp2h',
                    name: 'no_sp2h',
                },
                {
                    data: 'tgl_sp2h',
                    name: 'tgl_sp2h',
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
            let no_sp2h = document.getElementById('no_sp2h').value;
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

            let url = new URL("{{ route('sp2h.cetak') }}");
            let searchParams = url.searchParams;
            searchParams.append("no_sp2h", no_sp2h);
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

    function cetak(no_sp2h, jenis, kd_skpd) {
        $('#no_sp2h').val(no_sp2h);
        $('#jenis').val(jenis);
        $('#kd_skpd').val(kd_skpd);
        $('#modal_cetak').modal('show');
    }

    function hapus(no_sp2h, kd_skpd) {
        let tanya = confirm('Apakah anda yakin untuk menghapus data dengan Nomor SP2H : ' + no_sp2h);
        if (tanya == true) {
            $.ajax({
                url: "{{ route('sp2h.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_sp2h: no_sp2h,
                    kd_skpd: kd_skpd,
                    "_token": "{{ csrf_token() }}",
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
