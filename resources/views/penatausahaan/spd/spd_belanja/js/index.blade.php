<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let data_awal = $('#spd_belanja').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('spd.spd_belanja.load_data') }}",
                "type": "POST",
            },
            columns: [
                // {
                //     data: 'DT_RowIndex',
                //     name: 'DT_RowIndex',
                //     className: "text-center",
                // }, 
                {
                    data: 'no_spd',
                    name: 'no_spd',
                    className: "text-center",
                },
                {
                    data: 'tgl_spd',
                    name: 'tgl_spd',
                    className: "text-center",
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                    visible: false
                },
                {
                    data: 'nm_beban',
                    name: 'nm_beban',
                    className: "text-center",
                },
                {
                    data: 'jns_ang',
                    name: 'jns_ang',
                    className: "text-center",
                },
                {
                    data: 'revisi_ke',
                    name: 'revisi_ke',
                    className: "text-center",
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: 100,
                    className: "text-center",
                },
            ],
        });

        $('#nip').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });
        $('#jenis').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#cetak-lampiran, #cetak-otorisasi').click(function() {
            var url = new URL($(this).data('url'))
            let nospd = document.getElementById('no_spd').value;
            let nip = $("#nip").val();
            let jenis = $("#jenis").val();

            if (document.getElementById("tambahan").checked == true) {
                tambahan = '1';
            } else {
                tambahan = '0';
            }

            if (!nip) {
                return alert('Bendahara PPKD Belum Dipilih')
            }

            if (!jenis) {
                return alert('Jenis Cetakkan Belum Dipilih')
            }

            let searchParams = url.searchParams;
            searchParams.append('no_spd', nospd);
            searchParams.append('nip', nip);
            searchParams.append('tambahan', tambahan);
            searchParams.append('jenis', jenis);
            window.open(url.toString(), "_blank");
        })
    });

    function cetak(no_spd) {
        $('#no_spd').val(no_spd);
        $('#modal_cetak').modal('show');
    }

    function hapusSPD(no_spd) {
            let tanya = confirm('Apakah anda yakin untuk menghapus data ini');
            if (tanya == true) {
                $.ajax({
                    url: "{{ route('spd.spd_belanja.hapus_data_spd') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        no_spd: no_spd,
                    },
                    success: function(data) {
                        if (data.message == '1') {
                            alert('Proses Hapus Berhasil');
                            window.location.reload();
                        } else {
                            alert('Proses Hapus Gagal...!!!');
                        }
                    }
                })
            } else {
                return false;
            }
        }
</script>