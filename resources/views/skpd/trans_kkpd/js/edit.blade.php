<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let tabel_rekening = $('#rincian_pengeluaran').DataTable({
            responsive: true,
            processing: true,
            // serverSide: true,
            ordering: false,
            lengthMenu: [
                [-1],
                ["All"]
            ],
            columns: [{
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan',
                },
                {
                    data: 'nm_sub_kegiatan',
                    name: 'nm_sub_kegiatan',
                    visible: false
                },
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6',
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6',
                },
                {
                    data: 'nilai',
                    name: 'nilai'
                },
                {
                    data: 'sumber',
                    name: 'sumber',
                    visible: false
                },
                {
                    data: 'nm_sumber',
                    name: 'nm_sumber',
                },
            ],
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('#simpan').on('click', function() {
            let no_voucher = document.getElementById('no_voucher').value;
            let tgl_voucher = document.getElementById('tgl_voucher').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = document.getElementById('nm_skpd').value;
            let no_sp2d = document.getElementById('no_sp2d').value;
            let keterangan = document.getElementById('keterangan').value;
            let tahun_anggaran = "{{ tahun_anggaran() }}";

            let total_belanja = rupiah(document.getElementById('total_belanja').value);

            let rincian_rekening1 = tabel_rekening.rows().data().toArray().map((value) => {
                let data = {
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    nm_sub_kegiatan: value.nm_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    nm_rek6: value.nm_rek6,
                    nilai: rupiah(value.nilai),
                    sumber: value.sumber,
                };
                return data;
            });

            let rincian_rekening = JSON.stringify(rincian_rekening1);

            if (rincian_rekening1.length == 0) {
                alert('Rincian Rekening tidak boleh kosong!');
                return;
            }

            let tahun_input = tgl_voucher.substr(0, 4);

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (!no_voucher) {
                alert('Nomor Voucher Tidak Boleh Kosong');
                return;
            }

            if (!tgl_voucher) {
                alert('Tanggal Voucher Tidak Boleh Kosong');
                return;
            }

            if (!kd_skpd) {
                alert('Kode SKPD Tidak Boleh Kosong');
                return;
            }

            if (total_belanja == 0) {
                alert('Rincian Tidak ada rekening!');
                return;
            }

            let response = {
                no_voucher,
                tgl_voucher,
                no_sp2d,
                kd_skpd,
                nm_skpd,
                total_belanja,
                rincian_rekening,
                keterangan
            };

            $('#simpan').prop('disabled', true);

            $.ajax({
                url: "{{ route('trans_kkpd.update') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: response,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $("#overlay").fadeIn(100);
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Data Berhasil Diupdate...!!!');
                        window.location.href = "{{ route('trans_kkpd.index') }}";
                    } else if (data.message == '2') {
                        alert('Nomor Telah Dipakai!');
                        $('#simpan').prop('disabled', false);
                    } else {
                        alert('Data gagal Diupdate...!');
                        $('#simpan').prop('disabled', false);
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        });
    });

    function rupiah(n) {
        let n1 = n.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }
</script>
