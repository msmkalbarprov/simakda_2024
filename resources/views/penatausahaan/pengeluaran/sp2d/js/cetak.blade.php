<style>
    table.dataTable tbody tr td {
        font-size: 11px
    }

    table.dataTable thead tr th {
        font-size: 11px
    }
</style>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let tabel = $('#sp2d').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10, 50, 100],
            // ajax: {
            //     "url": "{{ route('sp2d.load_data') }}",
            //     "type": "POST",
            // },
            ajax: {
                "url": "{{ route('sp2d.load_data') }}",
                "type": "POST",
                "_token": "{{ csrf_token() }}",
                "data": function(d) {
                    d.tipe = document.getElementById('tipe').value;
                },
            },
            createdRow: function(row, data, index) {
                if (data.status_bud == '1' && data.status_sp2d == '2') {
                    $(row).css("background-color", "#4bbe68");
                    $(row).css("color", "white");
                } else if (data.sp2d_batal == '1') {
                    $(row).css("background-color", "#ff0000");
                    $(row).css("color", "white");
                } else if (data.status_bud == '1' && data.status_sp2d == '4') {
                    $(row).css("background-color", "#bf00ff");
                    $(row).css("color", "white");
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center"
                }, {
                    data: 'no_sp2d',
                    name: 'no_sp2d',
                    className: "text-center",
                },
                {
                    data: 'tgl_kas_bud',
                    name: 'tgl_kas_bud',
                    className: "text-center",
                },
                {
                    data: 'no_uji',
                    name: 'no_uji',
                    // className: "text-center",
                },
                {
                    data: 'no_spm',
                    name: 'no_spm',
                    // className: "text-center",
                },
                {
                    data: 'tgl_sp2d',
                    name: 'tgl_sp2d',
                    className: "text-center",
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: 200,
                    className: "text-center",
                },
            ],
            drawCallback: function(settings) {
                console.log('drawCallback');
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        let detail_potongan_mpn = $('#detail_potongan_mpn').DataTable({
            responsive: true,
            ordering: false,
            // serverSide: true,
            processing: true,
            lengthMenu: [5, 10, 50, 100],
            columns: [{
                    data: 'nama_akun',
                    name: 'nama_akun',
                },
                {
                    data: 'nilai_potongan',
                    name: 'nilai_potongan',
                },
                {
                    data: 'id_billing',
                    name: 'id_billing',
                },
                {
                    data: 'ntpn',
                    name: 'ntpn',
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                },
            ],
            drawCallback: function(settings) {
                console.log('drawCallback');
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        let detail_potongan_nonmpn = $('#detail_potongan_nonmpn').DataTable({
            responsive: true,
            ordering: false,
            // serverSide: true,
            processing: true,
            lengthMenu: [5, 10, 50, 100],
            columns: [{
                    data: 'nama_akun',
                    name: 'nama_akun',
                },
                {
                    data: 'kode_map',
                    name: 'kode_map',
                    visible: false
                },
                {
                    data: 'nilai_potongan',
                    name: 'nilai_potongan',
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                },
            ],
            drawCallback: function(settings) {
                console.log('drawCallback');
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        $('#ttd_bud').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#ttd1').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#ttd2').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#jenis').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('#kop').select2({
            dropdownParent: $('#modal_cetak .modal-content'),
            theme: 'bootstrap-5'
        });

        $('.tipe').on('click', function() {
            let jenis = $(this).data("jenis");
            $('#tipe').val(jenis);
            tabel.ajax.reload();
        });

        // cetak sp2d
        $('.sp2d').on('click', function() {
            let no_sp2d = document.getElementById('no_sp2d').value;
            let ttd_bud = document.getElementById('ttd_bud').value;
            let ttd1 = document.getElementById('ttd1').value;
            let ttd2 = document.getElementById('ttd2').value;
            let baris = document.getElementById('baris').value;
            let jenis = document.getElementById('jenis').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let beban = document.getElementById('beban').value;
            let kop = document.getElementById('kop').value;
            let margin_atas = document.getElementById('margin_atas').value;
            if (!ttd_bud) {
                alert('Pilih Penandatangan BUD Terlebih Dahulu!');
                return;
            }
            // if (!ttd1) {
            //     alert("Pilih Penandatangan I Terlebih Dahulu!");
            //     return;
            // }
            // if (!ttd2) {
            //     alert("Pilih Penandatangan II Terlebih Dahulu!");
            //     return;
            // }
            if (!jenis) {
                alert("Pilih Jenis Terlebih Dahulu!");
                return;
            }
            if (!kop) {
                alert("Pilih KOP Terlebih Dahulu!");
                return;
            }

            let url = new URL("{{ route('sp2d.cetak_sp2d') }}");
            let searchParams = url.searchParams;
            searchParams.append("jenis", jenis);
            searchParams.append("no_sp2d", no_sp2d);
            searchParams.append("ttd_bud", ttd_bud);
            searchParams.append("ttd1", ttd1);
            searchParams.append("ttd2", ttd2);
            searchParams.append("baris", baris);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("beban", beban);
            searchParams.append("kop", kop);
            searchParams.append("margin_atas", margin_atas);
            window.open(url.toString(), "_blank");
        });

        // cetak lampiran
        $('.lampiran').on('click', function() {
            let no_sp2d = document.getElementById('no_sp2d').value;
            let ttd_bud = document.getElementById('ttd_bud').value;
            let ttd1 = document.getElementById('ttd1').value;
            let ttd2 = document.getElementById('ttd2').value;
            let baris = document.getElementById('baris').value;
            let jenis = document.getElementById('jenis').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let beban = document.getElementById('beban').value;
            let margin_atas = document.getElementById('margin_atas').value;
            if (!ttd_bud) {
                alert('Pilih Penandatangan BUD Terlebih Dahulu!');
                return;
            }
            if (!ttd1) {
                alert("Pilih Penandatangan I Terlebih Dahulu!");
                return;
            }
            if (!ttd2) {
                alert("Pilih Penandatangan II Terlebih Dahulu!");
                return;
            }
            if (!jenis) {
                alert("Pilih Jenis Terlebih Dahulu!");
                return;
            }
            let url = new URL("{{ route('sp2d.cetak_lampiran') }}");
            let searchParams = url.searchParams;
            searchParams.append("jenis", jenis);
            searchParams.append("no_sp2d", no_sp2d);
            searchParams.append("ttd_bud", ttd_bud);
            searchParams.append("ttd1", ttd1);
            searchParams.append("ttd2", ttd2);
            searchParams.append("baris", baris);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("beban", beban);
            searchParams.append("margin_atas", margin_atas);
            window.open(url.toString(), "_blank");
        });

        // cetak lampiran lama
        $('.lampiran_lama').on('click', function() {
            let no_sp2d = document.getElementById('no_sp2d').value;
            let ttd_bud = document.getElementById('ttd_bud').value;
            let ttd1 = document.getElementById('ttd1').value;
            let ttd2 = document.getElementById('ttd2').value;
            let baris = document.getElementById('baris').value;
            let jenis = document.getElementById('jenis').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let beban = document.getElementById('beban').value;
            if (!ttd_bud) {
                alert('Pilih Penandatangan BUD Terlebih Dahulu!');
                return;
            }
            if (!ttd1) {
                alert("Pilih Penandatangan I Terlebih Dahulu!");
                return;
            }
            if (!ttd2) {
                alert("Pilih Penandatangan II Terlebih Dahulu!");
                return;
            }
            if (!jenis) {
                alert("Pilih Jenis Terlebih Dahulu!");
                return;
            }
            let url = new URL("{{ route('sp2d.cetak_lampiran_lama') }}");
            let searchParams = url.searchParams;
            searchParams.append("jenis", jenis);
            searchParams.append("no_sp2d", no_sp2d);
            searchParams.append("ttd_bud", ttd_bud);
            searchParams.append("ttd1", ttd1);
            searchParams.append("ttd2", ttd2);
            searchParams.append("baris", baris);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("beban", beban);
            window.open(url.toString(), "_blank");
        });

        // cetak kelengkapan
        $('.kelengkapan').on('click', function() {
            let no_sp2d = document.getElementById('no_sp2d').value;
            let ttd_bud = document.getElementById('ttd_bud').value;
            let ttd1 = document.getElementById('ttd1').value;
            let ttd2 = document.getElementById('ttd2').value;
            let baris = document.getElementById('baris').value;
            let jenis = document.getElementById('jenis').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let beban = document.getElementById('beban').value;
            if (!ttd_bud) {
                alert('Pilih Penandatangan BUD Terlebih Dahulu!');
                return;
            }
            if (!ttd1) {
                alert("Pilih Penandatangan I Terlebih Dahulu!");
                return;
            }
            if (!ttd2) {
                alert("Pilih Penandatangan II Terlebih Dahulu!");
                return;
            }
            if (!jenis) {
                alert("Pilih Jenis Terlebih Dahulu!");
                return;
            }
            let url = new URL("{{ route('sp2d.cetak_kelengkapan') }}");
            let searchParams = url.searchParams;
            searchParams.append("jenis", jenis);
            searchParams.append("no_sp2d", no_sp2d);
            searchParams.append("ttd_bud", ttd_bud);
            searchParams.append("ttd1", ttd1);
            searchParams.append("ttd2", ttd2);
            searchParams.append("baris", baris);
            searchParams.append("kd_skpd", kd_skpd);
            searchParams.append("beban", beban);
            window.open(url.toString(), "_blank");
        });

        $('#input_batal').on('click', function() {
            let no_sp2d = document.getElementById('no_sp2d_batal').value;
            let no_spm = document.getElementById('no_spm_batal').value;
            let no_spp = document.getElementById('no_spp_batal').value;
            let beban = document.getElementById('beban_batal').value;
            let status_bud = document.getElementById('status_bud').value;
            let keterangan = document.getElementById('keterangan_batal').value;
            if (status_bud == '1') {
                alert("SP2D telah diterima SKPD. Batalkan Penerimaan terlebih dahulu");
                return;
            }
            let tanya = confirm('Anda yakin akan Membatalkan SP2D: ' + no_sp2d + '  ?');
            if (tanya == true) {
                if (!keterangan) {
                    alert('Keterangan pembatalan SP2D diisi terlebih dahulu!');
                    return;
                }
                $.ajax({
                    url: "{{ route('sp2d.batal_sp2d') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        no_sp2d: no_sp2d,
                        no_spm: no_spm,
                        no_spp: no_spp,
                        keterangan: keterangan,
                        beban: beban,
                    },
                    success: function(data) {
                        if (data.message == '1') {
                            alert('SP2D Berhasil Dibatalkan');
                            window.location.href = "{{ route('sp2d.index') }}";
                        } else {
                            alert('SP2D Tidak Berhasil Dibatalkan');
                            return;
                        }
                    }
                })
            }
        });

        $('#callback').on('click', function() {
            let no_sp2d = document.getElementById('no_sp2d_callback').value;
            let no_spm = document.getElementById('no_spm_callback').value;
            let status = document.getElementById('status_callback').value;

            let detail_mpn1 = detail_potongan_mpn.rows().data().toArray().map((value) => {
                let data = {
                    nama_akun: value.nama_akun,
                    nilai_potongan: rupiah(value.nilai_potongan),
                    id_billing: value.id_billing,
                    ntpn: value.ntpn,
                    keterangan: value.keterangan,
                };
                return data;
            });

            let detail_nonmpn1 = detail_potongan_nonmpn.rows().data().toArray().map((value) => {
                let data = {
                    nama_akun: value.nama_akun,
                    kode_map: value.kode_map,
                    nilai_potongan: rupiah(value.nilai_potongan),
                    keterangan: value.keterangan,
                };
                return data;
            });

            let detail_mpn = JSON.stringify(detail_mpn1);

            let detail_nonmpn = JSON.stringify(detail_nonmpn1);

            let data = {
                no_sp2d,
                no_spm,
                status,
                detail_mpn,
                detail_nonmpn
            };

            $.ajax({
                url: "{{ route('sp2d.callback') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                success: function(data) {
                    alert(data.message);
                    $('#modal_callback').modal('hide');
                }
            })
        });

    });

    function cetak(no_sp2d, beban, kd_skpd) {
        $('#no_sp2d').val(no_sp2d);
        $('#beban').val(beban);
        $('#kd_skpd').val(kd_skpd);
        if (beban == '4') {
            $('#lampiran_lama').show();
        } else {
            $('#lampiran_lama').hide();
        }
        $('#modal_cetak').modal('show');
    }

    function batal_sp2d(no_sp2d, beban, kd_skpd, no_spm, no_spp, status) {
        $('#no_sp2d_batal').val(no_sp2d);
        $('#beban_batal').val(beban);
        $('#no_spm_batal').val(no_spm);
        $('#no_spp_batal').val(no_spp);
        $('#status_bud').val(status);
        $('#sp2d_batal').modal('show');
    }

    function callback(no_sp2d) {
        let tabel1 = $('#detail_potongan_mpn').DataTable();
        let tabel2 = $('#detail_potongan_nonmpn').DataTable();

        $.ajax({
            url: "{{ route('sp2d.data_callback') }}",
            type: "POST",
            dataType: 'json',
            data: {
                no_sp2d: no_sp2d,
            },
            beforeSend: function() {
                $("#overlay").fadeIn(100);
            },
            success: function(data) {
                let data1 = JSON.parse(data);
                tabel1.clear().draw();
                tabel2.clear().draw();

                $('#no_sp2d_callback').val(data1.data[0].data.nomorSP2D);
                $('#no_spm_callback').val(data1.data[0].data.nomorSPM);
                $('#tgl_transaksi').val(data1.data[0].data.tanggalTransaksi);
                $('#nilai_transaksi').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(data1.data[0].data.jumlahNominalTransaksi));
                $('#npwp').val(data1.data[0].data.penerima.npwp);
                $('#skpd').val(data1.data[0].data.pengirim.namaOpd);
                $('#penerima').val(data1.data[0].data.penerima.namaPenerima);
                $('#rekening').val(data1.data[0].data.penerima.noRekening);
                $('#bank').val(data1.data[0].data.penerima.namaBank);
                $('#jumlah_bayar').val(new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(data1.data[0].data.jumlahDibayar));
                $('#keperluan').val(data1.data[0].data.notes);
                $('#status_callback').val(data1.data[0].data.messageDetail);

                $.each(data1.data[0].data.detailPotonganMpn, function(index, potongan_mpn) {
                    tabel1.row.add({
                        'nama_akun': potongan_mpn.keteranganPotongan,
                        'nilai_potongan': new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(potongan_mpn.nominalPotongan),
                        'id_billing': potongan_mpn.idBilling,
                        'ntpn': potongan_mpn.ntpn,
                        'keterangan': potongan_mpn.messageDetail
                    }).draw();
                });

                $.each(data1.data[0].data.detailPotonganNonMpn, function(index, potongan_nonmpn) {
                    tabel2.row.add({
                        'nama_akun': potongan_nonmpn.keteranganKodeMap,
                        'kode_map': potongan_nonmpn.kodeMap,
                        'nilai_potongan': new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(potongan_nonmpn.nominalPotongan),
                        'keterangan': potongan_nonmpn.messageDetail
                    }).draw();
                });
            },
            complete: function(data) {
                $("#overlay").fadeOut(100);
            }
        });

        $('#modal_callback').modal('show');
    }

    function deleteData(no_spp) {
        let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor SPP : ' + no_spp)
        if (tanya == true) {
            $.ajax({
                url: "{{ route('sppls.hapus_sppls') }}",
                type: "DELETE",
                dataType: 'json',
                data: {
                    no_spp: no_spp
                },
                success: function(data) {
                    if (data.message == '1') {
                        alert('Data berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Data gagal dihapus!');
                        location.reload();
                    }
                }
            })
        } else {
            return false;
        }
    }

    function rupiah(n) {
        let n1 = n.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }
</script>
