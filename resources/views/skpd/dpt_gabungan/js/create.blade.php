<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let detail = $('#rincian_pengeluaran').DataTable({
            responsive: true,
            processing: true,
            ordering: false,
            lengthMenu: [
                [-1],
                ["All"]
            ],
            columns: [{
                    data: 'no_dpt',
                    name: 'no_dpt',
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                },
                {
                    data: 'no_dpt_unit',
                    name: 'no_dpt_unit',
                },
                {
                    data: 'nilai',
                    name: 'nilai',
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ],
        });

        $('.select-modal').select2({
            dropdownParent: $('#modal_tambah .modal-content'),
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('#tambah_rincian').on('click', function() {
            $('#pilih_no_dpt').empty();
            $('#unit').val(null);
            $('#nm_unit').val(null);
            $('#nilai').val(null);
            load_lpj();
            $('#modal_tambah').modal('show');
        });

        $('#pilih_no_dpt').on('select2:select', function() {
            let kd_skpd = $(this).find(':selected').data('kd_skpd');
            let nm_skpd = $(this).find(':selected').data('nm_skpd');
            let nilai = $(this).find(':selected').data('nilai');

            $("#unit").val(kd_skpd);
            $("#nm_unit").val(nm_skpd);
            $("#nilai").val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(nilai));
        });

        $('#pilih').on('click', function() {
            let pilih_no_dpt = document.getElementById('pilih_no_dpt').value;
            let no_dpt = document.getElementById('no_dpt').value;
            let unit = document.getElementById('unit').value;
            let nm_unit = document.getElementById('nm_unit').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let tahun_anggaran = "{{ tahun_anggaran() }}";
            let nilai = rupiah(document.getElementById('nilai').value);
            let total = rupiah(document.getElementById('total').value);

            if (!pilih_no_dpt) {
                alert('Pilih Nomor DPT Dahulu...!!!');
                return;
            }

            if (!no_dpt) {
                alert('Isi Nomor DPT Global Dahulu...!!!');
                return;
            }

            let dpt = no_dpt +
                "/DPT/GLOBAL/" + kd_skpd +
                "/" + tahun_anggaran;

            detail.row.add({
                'no_dpt': dpt,
                'kd_skpd': unit,
                'nm_skpd': nm_unit,
                'no_dpt_unit': pilih_no_dpt,
                'nilai': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai),
                'aksi': `<a href="javascript:void(0);" onclick="hapus('${pilih_no_dpt}','${unit}','${nilai}')" class="btn btn-danger btn-sm"><i class="uil-trash"></i></a>`,
            }).draw();
            $('#total').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total + nilai));
            $('#modal_tambah').modal('hide');
        });

        $('#simpan').on('click', function() {
            let total = rupiah(document.getElementById('total').value);
            // let sisa_kas = rupiah(document.getElementById('sisa_kas').value);

            let kd_skpd = document.getElementById('kd_skpd').value;

            let no_dpt = document.getElementById('no_dpt').value;
            if (no_dpt < 0) {
                alert("No DPT harus diisi dengan benar!");
                return;
            }

            let tgl_dpt = document.getElementById('tgl_dpt').value;
            let tahun_anggaran = "{{ tahun_anggaran() }}";
            let keterangan = document.getElementById('keterangan').value;
            let tahun_input = tgl_dpt.substr(0, 4);

            let detail_dpt = detail.rows().data().toArray().map((value) => {
                let data = {
                    no_dpt_unit: value.no_dpt_unit
                };
                return data;
            });

            let rincian_input1 = detail.rows().data().toArray().map((value) => {
                let data = {
                    no_dpt: value.no_dpt,
                    kd_skpd: value.kd_skpd,
                    no_dpt_unit: value.no_dpt_unit,
                    nm_sub_kegiatan: value.nm_sub_kegiatan,
                    nilai: rupiah(value.nilai),
                };
                return data;
            });

            let rincian_input = JSON.stringify(rincian_input1);

            if (!no_dpt) {
                alert('Nomor tidak boleh kosong');
                return;
            }

            if (!tgl_dpt) {
                alert('Tanggal tidak boleh kosong!');
                return;
            }

            if (tahun_anggaran != tahun_input) {
                alert('Tahun input tidak sesuai dengan tahun anggaran');
                return;
            }

            if (total == 0) {
                alert('Rincian tidak boleh kosong!');
                return;
            }

            if (!keterangan) {
                alert('Keterangan harus diisi!');
                return;
            }

            if (keterangan.length > 1000) {
                alert('Keterangan hanya boleh diisi hingga 1000 karakter');
                return;
            }

            // if (total > sisa_kas) {
            //     alert('Total DPT melebihi Sisa Kas!');
            //     return
            // }

            if (detail_dpt.length == 0) {
                alert('Rincian tidak boleh kosong!');
                return;
            }

            let data = {
                no_dpt,
                tgl_dpt,
                kd_skpd,
                keterangan,
                detail_dpt,
                rincian_input
            };

            $('#simpan').prop('disabled', true);
            $.ajax({
                url: "{{ route('dpt_gabungan.simpan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(response) {
                    if (response.message == '1') {
                        alert('Data berhasil ditambahkan!');
                        window.location.href =
                            "{{ route('dpt_gabungan.index') }}";
                    } else if (response.message == '2') {
                        alert('Nomor Telah Dipakai!');
                        $('#simpan').prop('disabled', false);
                        return;
                    } else {
                        alert('Data tidak berhasil ditambahkan!');
                        $('#simpan').prop('disabled', false);
                        return;
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        });

        function load_lpj() {
            let detail_lpj = detail.rows().data().toArray().map((value) => {
                let data = {
                    no_dpt_unit: value.no_dpt_unit,
                };
                return data;
            });

            $.ajax({
                url: "{{ route('dpt_gabungan.load_dpt') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    kd_skpd: document.getElementById('kd_skpd').value,
                    no_dpt_unit: detail_lpj.length == 0 ? '0' : detail_lpj,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#pilih_no_dpt').empty();
                    $('#pilih_no_dpt').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#pilih_no_dpt').append(
                            `<option value="${data.no_dpt}" data-kd_skpd="${data.kd_skpd}" data-nm_skpd="${data.nm_skpd}" data-nilai="${data.nilai}">${data.no_dpt} | ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(data.nilai)}</option>`
                        );
                    })
                }
            })
        }
    });

    function rupiah(n) {
        let n1 = n.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }

    function hapus(no_dpt_unit, unit, nilai) {
        let hapus = confirm('Yakin Ingin Menghapus Data, No DPT Unit : ' + no_dpt_unit + ' ?');
        let total = rupiah(document.getElementById('total').value);
        let tabel = $('#rincian_pengeluaran').DataTable();

        if (hapus == true) {
            tabel.rows(function(idx, data, node) {
                return data.no_dpt_unit == no_dpt_unit && data.kd_skpd == unit
            }).remove().draw();
            $('#total').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total - parseFloat(nilai)));
        }
    }
</script>
