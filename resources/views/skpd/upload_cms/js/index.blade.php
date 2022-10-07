<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let data_awal = $('#upload_cms').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('skpd.upload_cms.load_data') }}",
                "type": "POST",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                }, {
                    data: 'no_voucher',
                    name: 'no_voucher'
                },
                {
                    data: 'tgl_voucher',
                    name: 'tgl_voucher'
                },
                {
                    data: 'tgl_upload',
                    name: 'tgl_upload',
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd'
                },
                {
                    data: null,
                    name: 'ket',
                    render: function(data, type, row, meta) {
                        return data.ket.substr(0, 10) + '.....';
                    }
                },
                {
                    data: null,
                    name: 'total',
                    className: 'text-right',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.total)
                    }
                },
                {
                    data: 'status_upload',
                    name: 'status_upload'
                },
                {
                    data: null,
                    name: 'aksi',
                    width: 200,
                    render: function(data, type, row, meta) {
                        return `<button type="button" onclick="lihatData('${data.nm_skpd}','${data.no_voucher}','${data.tgl_voucher}','${data.no_sp2d}','${data.ket}','${data.kd_sub_kegiatan}','${data.nm_sub_kegiatan}','${data.kd_skpd}')" class="btn btn-info btn-sm"><i class="fas fa-info-circle"></i></button>`
                    }
                },
                {
                    data: 'bersih',
                    name: 'bersih',
                    visible: false
                },
                {
                    data: 'rekening_awal',
                    name: 'rekening_awal',
                    visible: false
                },
                {
                    data: 'nm_rekening_tujuan',
                    name: 'nm_rekening_tujuan',
                    visible: false
                },
                {
                    data: 'rekening_tujuan',
                    name: 'rekening_tujuan',
                    visible: false
                },
                {
                    data: 'bank_tujuan',
                    name: 'bank_tujuan',
                    visible: false
                },
                {
                    data: 'ket_tujuan',
                    name: 'ket_tujuan',
                    visible: false
                },
            ]
        });

        let rekening_transaksi = $('#rekening_transaksi').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('skpd.upload_cms.rekening_transaksi') }}",
                "type": "POST",
                "data": function(d) {
                    d.nomor = document.getElementById('nomor').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                }
            },
            ordering: false,
            columns: [{
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
                },
            ]
        });

        let rekening_potongan = $('#rekening_potongan').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('skpd.upload_cms.rekening_potongan') }}",
                "type": "POST",
                "data": function(d) {
                    d.nomor = document.getElementById('nomor').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                }
            },
            ordering: false,
            columns: [{
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
            ]
        });

        let rekening_tujuan = $('#rekening_tujuan').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('skpd.upload_cms.rekening_tujuan') }}",
                "type": "POST",
                "data": function(d) {
                    d.nomor = document.getElementById('nomor').value;
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                }
            },
            ordering: false,
            columns: [{
                    data: 'no_voucher',
                    name: 'no_voucher',
                    visible: false
                },
                {
                    data: 'tgl_voucher',
                    name: 'tgl_voucher',
                    visible: false
                },
                {
                    data: 'rekening_awal',
                    name: 'rekening_awal',
                },
                {
                    data: 'nm_rekening_tujuan',
                    name: 'nm_rekening_tujuan',
                },
                {
                    data: 'rekening_tujuan',
                    name: 'rekening_tujuan',
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                    visible: false
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
                    data: 'bank_tujuan',
                    name: 'bank_tujuan',
                },
            ]
        });

        $('#cetak_cms').on('click', function() {
            let data = JSON.stringify(data_awal.rows().data().toArray());
            console.log(data);
        });

    });

    // function deleteData(no_voucher) {
    //     let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor Bukti : ' + no_voucher)
    //     if (tanya == true) {
    //         $.ajax({
    //             url: "{{ route('skpd.transaksi_cms.hapus_cms') }}",
    //             type: "POST",
    //             dataType: 'json',
    //             data: {
    //                 no_voucher: no_voucher
    //             },
    //             success: function(data) {
    //                 if (data.message == '1') {
    //                     alert('Data berhasil dihapus!');
    //                     location.reload();
    //                 } else {
    //                     alert('Data gagal dihapus!');
    //                     return;
    //                 }
    //             }
    //         })
    //     } else {
    //         return false;
    //     }
    // }

    function lihatData(nm_skpd, no_voucher, tgl_voucher, no_sp2d, ket, kd_sub_kegiatan, nm_sub_kegiatan, kd_skpd) {
        $('#kd_skpd').val(kd_skpd);
        $('#nm_skpd').val(nm_skpd);
        $('#nomor').val(no_voucher);
        $('#tanggal').val(tgl_voucher);
        $('#no_sp2d').val(no_sp2d);
        $('#keterangan').val(ket);
        $('#kd_sub_kegiatan').val(kd_sub_kegiatan);
        $('#nm_sub_kegiatan').val(nm_sub_kegiatan);
        $('#rekening_transaksi').DataTable().ajax.reload();
        $('#rekening_potongan').DataTable().ajax.reload();
        $('#rekening_tujuan').DataTable().ajax.reload();
        $('#modal_lihat').modal('show');
    }
</script>
