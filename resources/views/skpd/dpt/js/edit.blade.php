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
            serverSide: true,
            ordering: false,
            ajax: {
                "url": "{{ route('dpt.detail_dpr') }}",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "data": function(d) {
                    d.no_dpr = document.getElementById('no_dpt').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                    d.jenis = "edit"
                },
                "dataSrc": function(data) {
                    recordsTotal = data.data;
                    return recordsTotal;
                },
            },
            lengthMenu: [
                [-1],
                ["All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                }, {
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
                    data: null,
                    name: 'nilai',
                    className: 'text-right',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai)
                    }
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
                {
                    data: 'bukti',
                    name: 'bukti',
                    visible: false
                },
                {
                    data: null,
                    name: 'nm_bukti',
                    render: function(data, type, row, meta) {
                        return data.bukti == '1' ? 'YA' : 'TIDAK'
                    }
                },
                {
                    data: 'uraian',
                    name: 'uraian',
                },
                {
                    data: 'pembayaran',
                    name: 'pembayaran',
                    visible: false
                },
                {
                    data: null,
                    name: 'nm_pembayaran',
                    render: function(data, type, row, meta) {
                        switch (data.pembayaran) {
                            case '1':
                                return 'KATALOG';
                                break;
                            case '2':
                                return 'TOKO DARING';
                                break;
                            case '3':
                                return 'LPSE';
                                break;
                            case '4':
                                return 'LAIN-LAIN';
                                break;
                            default:
                                return '';
                                break;
                        }
                    }
                },
            ],
            drawCallback: function(select) {
                let total = recordsTotal.reduce((previousValue,
                    currentValue) => (previousValue += parseFloat(currentValue.nilai)), 0);
                $('#total_belanja').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(total));
            }
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('#simpan').on('click', function() {
            let no_dpt = document.getElementById('no_dpt').value;
            let tgl_dpt = document.getElementById('tgl_dpt').value;
            let no_dpr = document.getElementById('no_dpr').value;
            let tgl_dpr = document.getElementById('tgl_dpr').value;
            let no_urut = document.getElementById('no_urut').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = document.getElementById('nm_skpd').value;
            let tahun_anggaran = "{{ tahun_anggaran() }}";

            let total_belanja = rupiah(document.getElementById('total_belanja').value);
            // let sisa_kas = rupiah(document.getElementById('sisa_kas').value);

            let rincian_rekening1 = tabel_rekening.rows().data().toArray().map((value) => {
                let data = {
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    nm_sub_kegiatan: value.nm_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    nm_rek6: value.nm_rek6,
                    nilai: rupiah(value.nilai),
                    sumber: value.sumber,
                    bukti: value.bukti,
                    uraian: value.uraian,
                    pembayaran: value.pembayaran,
                };
                return data;
            });

            let rincian_rekening = JSON.stringify(rincian_rekening1);

            if (rincian_rekening1.length == 0) {
                alert('Rincian Rekening tidak boleh kosong!');
                return;
            }

            let tahun_input = tgl_dpt.substr(0, 4);

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            // if (total_belanja > sisa_kas) {
            //     alert('Nilai Melebihi sisa Kas KKPD');
            //     return;
            // }

            if (!no_dpt) {
                alert('Nomor DPT Tidak Boleh Kosong');
                return;
            }

            if (!tgl_dpt) {
                alert('Tanggal DPT Tidak Boleh Kosong');
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
                no_dpt,
                tgl_dpt,
                no_dpr,
                tgl_dpr,
                no_urut,
                kd_skpd,
                nm_skpd,
                total_belanja,
                rincian_rekening,
            };

            $('#simpan').prop('disabled', true);

            $.ajax({
                url: "{{ route('dpt.update') }}",
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
                        window.location.href = "{{ route('dpt.index') }}";
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
