<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#ppk').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#pa_kpa').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#koreksi_rekening').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('koreksi_rekening.load_data') }}",
                "type": "POST",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                }, {
                    data: 'no_bukti',
                    name: 'no_bukti',
                    className: "text-center",
                },
                {
                    data: 'tgl_bukti',
                    name: 'tgl_bukti',
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                },
                {
                    data: null,
                    name: 'ket',
                    render: function(data, type, row, meta) {
                        return data.ket.substr(0, 10) + '.....';
                    }
                },
                {
                    data: 'ketlpj',
                    name: 'ketlpj',
                    className: "text-center",
                },
                {
                    data: 'ketspj',
                    name: 'ketspj',
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

        $('.cetak').on('click', function() {
            let kd_skpd = document.getElementById('kd_skpd').value;
            let ppk = document.getElementById('ppk').value;
            let pa_kpa = document.getElementById('pa_kpa').value;
            let periode1 = document.getElementById('periode1').value;
            let periode2 = document.getElementById('periode2').value;
            let tgl_ttd = document.getElementById('tgl_ttd').value;

            let jenis_print = $(this).data("jenis");

            if (!pa_kpa) {
                alert("Pilih Pengguna Anggaran Terlebih Dahulu!");
                return;
            }
            if (!ppk) {
                alert("Pilih NIP PPK Terlebih Dahulu!");
                return;
            }

            let url = new URL("{{ route('koreksi_rekening.cetak') }}");
            let searchParams = url.searchParams;
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("pa_kpa", pa_kpa);
            searchParams.append("ppk", ppk);
            searchParams.append("periode1", periode1);
            searchParams.append("periode2", periode2);
            searchParams.append("jenis_print", jenis_print);
            searchParams.append("tgl_ttd", tgl_ttd);
            window.open(url.toString(), "_blank");
        });
    });

    function cetak() {
        $('#modal_cetak').modal('show');
    }

    function hapusRekening(no_bukti, kd_skpd) {
        let tanya = confirm('Apakah anda yakin untuk menghapus data dengan Nomor : ' + no_bukti);
        if (tanya == true) {
            $.ajax({
                url: "{{ route('koreksi_rekening.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_bukti: no_bukti,
                    kd_skpd: kd_skpd,
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
