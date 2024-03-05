<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // let tabel_rekening = $('#rincian_pengeluaran').DataTable({
        //     responsive: true,
        //     processing: true,
        //     serverSide: true,
        //     ordering: false,
        //     ajax: {
        //         "url": "{{ route('trans_kkpd.load_dpt') }}",
        //         "type": "POST",
        //         "data": function(d) {
        //             d.no_dpt = document.getElementById('no_dpt').value;
        //             d.kd_skpd = document.getElementById('kd_skpd').value;
        //             d.jenis = "create";
        //         },
        //         "dataSrc": function(data) {
        //             recordsTotal = data.data;
        //             return recordsTotal;
        //         },
        //     },
        //     lengthMenu: [
        //         [-1],
        //         ["All"]
        //     ],
        //     columns: [{
        //             data: 'DT_RowIndex',
        //             name: 'DT_RowIndex',
        //             className: "text-center",
        //         }, {
        //             data: 'kd_sub_kegiatan',
        //             name: 'kd_sub_kegiatan',
        //         },
        //         {
        //             data: 'nm_sub_kegiatan',
        //             name: 'nm_sub_kegiatan',
        //             visible: false
        //         },
        //         {
        //             data: 'kd_rek6',
        //             name: 'kd_rek6',
        //         },
        //         {
        //             data: 'nm_rek6',
        //             name: 'nm_rek6',
        //         },
        //         {
        //             data: null,
        //             name: 'nilai',
        //             className: 'text-right',
        //             render: function(data, type, row, meta) {
        //                 return new Intl.NumberFormat('id-ID', {
        //                     minimumFractionDigits: 2
        //                 }).format(data.nilai)
        //             }
        //         },
        //         {
        //             data: 'sumber',
        //             name: 'sumber',
        //             visible: false
        //         },
        //         {
        //             data: 'nm_sumber',
        //             name: 'nm_sumber',
        //         },
        //         {
        //             data: 'bukti',
        //             name: 'bukti',
        //             visible: false
        //         },
        //         {
        //             data: null,
        //             name: 'nm_bukti',
        //             render: function(data, type, row, meta) {
        //                 return data.bukti == '1' ? 'YA' : 'TIDAK'
        //             }
        //         },
        //         {
        //             data: 'uraian',
        //             name: 'uraian',
        //         },
        //         {
        //             data: 'pembayaran',
        //             name: 'pembayaran',
        //             visible: false
        //         },
        //         {
        //             data: null,
        //             name: 'nm_pembayaran',
        //             render: function(data, type, row, meta) {
        //                 switch (data.pembayaran) {
        //                     case '1':
        //                         return 'KATALOG';
        //                         break;
        //                     case '2':
        //                         return 'TOKO DARING';
        //                         break;
        //                     case '3':
        //                         return 'LPSE';
        //                         break;
        //                     case '4':
        //                         return 'LAIN-LAIN';
        //                         break;
        //                     default:
        //                         return '';
        //                         break;
        //                 }
        //             }
        //         },
        //     ],
        //     drawCallback: function(select) {
        //         let total = recordsTotal.reduce((previousValue,
        //             currentValue) => (previousValue += parseFloat(currentValue.nilai)), 0);
        //         $('#total_belanja').val(new Intl.NumberFormat('id-ID', {
        //             minimumFractionDigits: 2
        //         }).format(total));
        //     }
        // });

        let tabel_rekening = $('#rincian_pengeluaran').DataTable({
            responsive: true,
            processing: true,
            ordering: false,
            lengthMenu: [
                [-1],
                ["All"]
            ],
            columns: [{
                    data: 'id',
                    name: 'id',
                    // visible: false
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
                    data: 'nilai',
                    name: 'nilai',
                    className: 'text-right',

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
                    data: 'nm_bukti',
                    name: 'nm_bukti',
                    visible: false
                },
                {
                    data: 'uraian',
                    name: 'uraian',
                    visible: false
                },
                {
                    data: 'pembayaran',
                    name: 'pembayaran',
                    visible: false
                },
                {
                    data: 'nm_pembayaran',
                    name: 'nm_pembayaran',
                    visible: false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('.select-modal').select2({
            dropdownParent: $('#modal_tambah .modal-content'),
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        // $('#no_dpt').on('select2:select', function() {
        //     tabel_rekening.ajax.reload();
        // });

        $('#tambah_rincian').on('click', function() {
            let no_sp2d = document.getElementById('no_sp2d').value;
            $('#no_spp').val($('#no_sp2d').find(':selected').data('no_spp'));
            if (!no_sp2d) {
                alert('Silahkan pilih NO. SP2D terlebih dahulu!');
                return;
            }

            load_rincian();
            $('#modal_tambah').modal('show');
        });

        $('#no_sp2d').on('select2:select', function() {
            $('#no_sp2d').prop('disabled', true);
        });

        $('#pilih').on('click', function() {
            let id = $('#pilih_rincian_dpt').val();
            let kd_sub_kegiatan = $('#pilih_rincian_dpt').find(':selected').data('kd_sub_kegiatan');
            let nm_sub_kegiatan = $('#pilih_rincian_dpt').find(':selected').data('nm_sub_kegiatan');
            let kd_rek6 = $('#pilih_rincian_dpt').find(':selected').data('kd_rek6');
            let nm_rek6 = $('#pilih_rincian_dpt').find(':selected').data('nm_rek6');
            let uraian = $('#pilih_rincian_dpt').find(':selected').data('uraian');
            let bukti = $('#pilih_rincian_dpt').find(':selected').data('bukti');
            let nilai = $('#pilih_rincian_dpt').find(':selected').data('nilai');
            let sumber = $('#pilih_rincian_dpt').find(':selected').data('sumber');
            let nm_sumber = $('#pilih_rincian_dpt').find(':selected').data('nm_sumber');
            let pembayaran = $('#pilih_rincian_dpt').find(':selected').data('pembayaran');
            let nm_pembayaran = '';

            if (pembayaran == '1') {
                nm_pembayaran = 'KATALOG';
            } else if (pembayaran == '2') {
                nm_pembayaran = 'TOKO DARING';
            } else if (pembayaran == '3') {
                nm_pembayaran = 'LPSE';
            } else if (pembayaran == '4') {
                nm_pembayaran = 'LAIN-LAIN';
            }

            let tampungan = tabel_rekening.rows().data().toArray().map((value) => {
                let result = {
                    id: value.id,
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    sumber: value.sumber,
                };
                return result;
            });

            let kondisi = tampungan.map(function(data) {
                if (data.kd_rek6 == kd_rek6 && data.sumber == sumber) {
                    return '2';
                } else if (data.id == id) {
                    return '3';
                } else if (data.kd_sub_kegiatan != kd_sub_kegiatan) {
                    return '4';
                } else if (data.kd_rek6 == kd_rek6 && data.kd_sub_kegiatan ==
                    kd_sub_kegiatan && data.sumber == sumber) {
                    return '5';
                } else {
                    return '1';
                }
            });

            if (kondisi.includes("2")) {
                alert('Tdk boleh memilih rekening yang berbeda dalam 1 kegiatan');
                return;
            }

            if (kondisi.includes("4")) {
                alert('Tdk boleh memilih kegiatan yang berbeda dalam 1 kegiatan');
                return;
            }

            if (kondisi.includes("3")) {
                alert('Rincian telah ada di bawah!');
                return;
            }

            if (kondisi.includes("5")) {
                alert('Tidak boleh memilih kegiatan,rekening dan sumber yang sama!');
                return;
            }

            tabel_rekening.row.add({
                'id': id,
                'kd_sub_kegiatan': kd_sub_kegiatan,
                'nm_sub_kegiatan': nm_sub_kegiatan,
                'kd_rek6': kd_rek6,
                'nm_rek6': nm_rek6,
                'nilai': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai),
                'sumber': sumber,
                'nm_sumber': nm_sumber,
                'bukti': bukti,
                'nm_bukti': bukti == '1' ? 'YA' : 'TIDAK',
                'uraian': uraian,
                'pembayaran': pembayaran,
                'nm_pembayaran': nm_pembayaran,
                'aksi': `<a href="javascript:void(0);" onclick="hapus('${id}','${kd_sub_kegiatan}','${kd_rek6}','${sumber}','${nilai}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>`,
            }).draw();

            let total = rupiah(document.getElementById('total_belanja').value);

            $('#total_belanja').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total + parseFloat(nilai)));

            $('#modal_tambah').modal('hide');
            load_rincian()
        });

        $('#simpan').on('click', function() {
            let tgl_voucher = document.getElementById('tgl_voucher').value;
            let no_sp2d = document.getElementById('no_sp2d').value;
            let no_spp = document.getElementById('no_spp').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let nm_skpd = document.getElementById('nm_skpd').value;
            let keterangan = document.getElementById('keterangan').value;
            let tahun_anggaran = "{{ tahun_anggaran() }}";

            let total_belanja = rupiah(document.getElementById('total_belanja').value);
            let sisa_kas = rupiah(document.getElementById('sisa_kas').value);

            let rincian_rekening1 = tabel_rekening.rows().data().toArray().map((value) => {
                let data = {
                    id: value.id,
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

            let tahun_input = tgl_voucher.substr(0, 4);

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (total_belanja > sisa_kas) {
                alert('Nilai Melebihi sisa Kas KKPD');
                return;
            }

            if (!no_sp2d) {
                alert('Nomor SP2D Tidak Boleh Kosong');
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

            if (!keterangan) {
                alert('Keterangan harus diisi!');
                return;
            }

            if (keterangan.length > 1000) {
                alert('Keterangan maksimal 1000 karakter');
                return;
            }

            let response = {
                no_sp2d,
                no_spp,
                tgl_voucher,
                kd_skpd,
                nm_skpd,
                total_belanja,
                keterangan,
                rincian_rekening,
            };

            $('#simpan').prop('disabled', true);

            $.ajax({
                url: "{{ route('trans_kkpd.simpan') }}",
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
                        alert('Data Berhasil Tersimpan...!!!');
                        window.location.href = "{{ route('trans_kkpd.index') }}";
                    } else if (data.message == '2') {
                        alert('Nomor DPT Telah Dipakai!');
                        $('#simpan').prop('disabled', false);
                    } else {
                        alert('Data gagal tersimpan...!');
                        $('#simpan').prop('disabled', false);
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        });

        function load_rincian() {
            let detail_rincian = tabel_rekening.rows().data().toArray().map((value) => {
                let data = {
                    id: value.id,
                };
                return data;
            });

            $.ajax({
                url: "{{ route('trans_kkpd.load_rincian') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    // no_dpt: document.getElementById('no_dpt').value,
                    no_sp2d: document.getElementById('no_sp2d').value,
                    kd_skpd: document.getElementById('kd_skpd').value,
                    id: detail_rincian.length == 0 ? '0' : detail_rincian,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#pilih_rincian_dpt').empty();
                    $('#pilih_rincian_dpt').append(
                        `<option value="" disabled selected>Silahkan Pilih</option>`);
                    $.each(data, function(index, data) {
                        $('#pilih_rincian_dpt').append(
                            `<option value="${data.no_bukti}" data-kd_sub_kegiatan="${data.kd_sub_kegiatan}" data-nm_sub_kegiatan="${data.nm_sub_kegiatan}" data-kd_rek6="${data.kd_rek6}" data-nm_rek6="${data.nm_rek6}" data-uraian="${data.uraian}" data-bukti="${data.bukti}" data-nilai="${data.nilai}" data-sumber="${data.sumber}" data-nm_sumber="${data.nm_sumber}" data-pembayaran="${data.pembayaran}">${data.no_bukti} | ${data.nm_sub_kegiatan} | ${data.nm_rek6} | ${data.sumber} | ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(data.nilai)}</option>`
                        );
                    })
                }
            })
        }
    });

    function nilai(n) {
        let nilai = n.split(',').join('');
        return parseFloat(nilai) || 0;
    }

    function rupiah(n) {
        let n1 = n.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }

    function hapus(id, kd_sub_kegiatan, kd_rek6, sumber, nilai) {
        let hapus = confirm('Yakin Ingin Menghapus Data, ID : ' + id + ' ?');
        let total = rupiah(document.getElementById('total_belanja').value);
        let tabel = $('#rincian_pengeluaran').DataTable();

        if (hapus == true) {
            tabel.rows(function(idx, data, node) {
                return data.id == id && data.kd_sub_kegiatan == kd_sub_kegiatan && data.kd_rek6 == kd_rek6 &&
                    data.sumber == sumber && rupiah(data.nilai) == parseFloat(nilai)
            }).remove().draw();
            $('#total_belanja').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total - parseFloat(nilai)));
        }
    }
</script>
