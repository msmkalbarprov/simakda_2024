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

        $('#spp_gu').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('spp_gu.load') }}",
                "type": "POST",
            },
            createdRow: function(row, data, index) {
                if (data.status == 1 && (data.sts_batal == '0' || data.sts_batal == '' || data
                        .sts_batal == null)) {
                    $(row).css("background-color", "#03d3ff");
                    $(row).css("color", "white");
                } else if (data.status == 1 && data.sts_batal == '1') {
                    $(row).css("background-color", "#red");
                    $(row).css("color", "white");
                } else if (data.sts_batal == '1') {
                    $(row).css("background-color", "#red");
                    $(row).css("color", "white");
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                }, {
                    data: 'no_spp',
                    name: 'no_spp',
                    className: "text-center",
                },
                {
                    data: 'tgl_spp',
                    name: 'tgl_spp',
                    className: "text-center",
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                },
                {
                    data: null,
                    name: 'keperluan',
                    className: 'text-right',
                    render: function(data, type, row, meta) {
                        return data.keperluan.substr(0, 10) + '.....';
                    }
                },
                {
                    data: null,
                    name: 'nilai',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai)
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

        $('#pilihan').on('select2:select', function() {
            let pilihan = this.value;
            if (pilihan != '2') {
                $('#kd_sub_kegiatan').val(null).change();
                $('#kd_sub_kegiatan').prop('disabled', true);
            } else {
                $('#kd_sub_kegiatan').val(null).change();
                $('#kd_sub_kegiatan').prop('disabled', false);

                // CARI KODE SUB KEGIATAN
                $.ajax({
                    type: "POST",
                    url: "{{ route('lpj.skpd_tanpa_unit.sub_kegiatan') }}",
                    dataType: 'json',
                    data: {
                        no_lpj: document.getElementById('no_lpj').value
                    },
                    success: function(data) {
                        $('#kd_sub_kegiatan').empty();
                        $('#kd_sub_kegiatan').append(
                            `<option value="" disabled selected>Silahkan Pilih</option>`
                        );
                        $.each(data, function(index, data) {
                            $('#kd_sub_kegiatan').append(
                                `<option value="${data.kd_sub_kegiatan}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                            );
                        })
                    }
                });
            }
        });

        $('.sptb').on('click', function() {
            let no_lpj = document.getElementById('no_lpj').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let pa_kpa = document.getElementById('pa_kpa').value;
            let jenis_print = $(this).data("jenis");

            if (!pa_kpa) {
                alert("Pengguna Anggaran tidak boleh kosong!");
                return;
            }

            let url = new URL("{{ route('lpj.skpd_tanpa_unit.cetak_sptb') }}");
            let searchParams = url.searchParams;
            searchParams.append("no_lpj", no_lpj);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("pa_kpa", pa_kpa);
            searchParams.append("jenis_print", jenis_print);
            window.open(url.toString(), "_blank");
        });
    });

    function cetak(no_lpj, jenis, kd_skpd) {
        $('#no_lpj').val(no_lpj);
        $('#jenis').val(jenis);
        $('#kd_skpd').val(kd_skpd);
        $('#modal_cetak').modal('show');
    }

    function hapus(no_spp, no_lpj, kd_skpd) {
        let tanya = confirm('Apakah anda yakin untuk menghapus data dengan Nomor SPP : ' + no_spp);
        if (tanya == true) {
            $.ajax({
                url: "{{ route('spp_gu.hapus') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_spp: no_spp,
                    no_lpj: no_lpj,
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
