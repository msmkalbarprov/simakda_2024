<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let tabelBelanja = $('#spd_belanja').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ordering: false,
            searchDelay: 1000,
            deferLoading: 0,
            ajax: {
                url: "{{ route('spd.spd_belanja.load_spd_belanja') }}",
                "type": "POST",
                data: function(data) {
                    data.kd_skpd = document.getElementById('kd_skpd').value;
                    data.jns_ang = document.getElementById('jenis_anggaran').value;
                    data.nomor = document.getElementById('nomor').value;
                    data.tanggal = document.getElementById('tanggal').value;
                    data.bln_awal = document.getElementById('bulan_awal').value;
                    data.bln_akhir = document.getElementById('bulan_akhir').value;
                    data.jenis = document.getElementById('jenis').value;
                    data.page = document.getElementById('idpage').value;
                    data.status_ang = document.getElementById('status_angkas').value;
                    if (document.getElementById("revisi").checked == true) {
                        data.revisi = '1';
                    } else {
                        data.revisi = '0';
                    }
                }
            },
            columns: [{
                    data: 'kd_unit',
                    name: 'kd_unit',
                },
                {
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan',
                },
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6',
                },
                {
                    data: 'nilai',
                    name: 'nilai',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data)
                    },
                    "className": "text-right",
                },
                {
                    data: 'lalu',
                    name: 'lalu',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data)
                    },
                    "className": "text-right",
                },
                {
                    data: 'anggaran',
                    name: 'anggaran',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data)
                    },
                    "className": "text-right",
                }
            ]}
        );

        let daftarSpdTempTable = $('#spd_belanja_temp').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ordering: false,
            searchDelay: 1000,
            deferLoading: 0,
            ajax: {
                url: "{{ route('spd.spd_belanja.load_spd_belanja_temp') }}",
                "type": "POST",
                data: function(data) {
                    data.kd_skpd = document.getElementById('kd_skpd').value;
                    data.jns_ang = document.getElementById('jenis_anggaran').value;
                    data.nomor = document.getElementById('nomor').value;
                    data.tanggal = document.getElementById('tanggal').value;
                    data.bln_awal = document.getElementById('bulan_awal').value;
                    data.bln_akhir = document.getElementById('bulan_akhir').value;
                    data.jenis = document.getElementById('jenis').value;
                    data.status_ang = document.getElementById('status_angkas').value;
                    data.page = document.getElementById('idpage').value;
                    if (document.getElementById("revisi").checked == true) {
                        data.revisi = '1';
                    } else {
                        data.revisi = '0';
                    }
                },
            },
            columns: [{
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                },
                {
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan',
                },
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6',
                },
                {
                    data: 'nilai',
                    name: 'nilai',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data)
                    },
                    "className": "text-right",
                },
                {
                    data: 'nilai_lalu',
                    name: 'nilai_lalu',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data)
                    },
                    "className": "text-right",
                },
                {
                    data: 'anggaran',
                    name: 'anggaran',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data)
                    },
                    "className": "text-right",
                }
            ],
        })
        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('#jenis').on('select2:select', function() {
            let jenis = document.getElementById('jenis').value;
            if (jenis == 5) {
                $('#revisi').prop('disabled', false)
            } else {
                $('#revisi').prop('disabled', true)
            }
        }).trigger('select2:select');

        // skpd
        $('#kd_skpd').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5',
            ajax: {
                delay: 1000,
                url: "{{ route('spd.spd_belanja.skpd') }}",
                type: 'POST',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                    }
                    return query
                },
            },
            dropdownAutoWidth: true,
            templateResult: function(result) {
                if (!result.id) return 'Searching';
                return `${result.id} | ${result.text}`;
            },
            escapeMarkup: (m) => m,
            templateSelection: function(result) {
                return result.id || result.text;
            },
        });


        $('#kd_skpd').on('select2:select', function() {
            tabelBelanja.clear().draw();
            daftarSpdTempTable.clear().draw();
            var skpd = $(this).select2('data')[0];
            let tahun = "{{ tahun_anggaran() }}";
            $('#nip').val(null).trigger('change').trigger('select2:select');
            if (skpd) {
                $('#nm_skpd').val(skpd.nm_skpd)
                $('#nip').prop('disabled', false)
                $('#nomor').prop('disabled', false)
                $("#nomor").val('13.00/01.0//' + skpd.kd_skpd + '/M/1/' + tahun)
                $('#jenis_anggaran').prop('disabled', false)
            } else {
                $('#nm_skpd').val(null)
                $('#nip').prop('disabled', true)
                $('#nomor').prop('disabled', true)
                $('#jenis_anggaran').prop('disabled', true)
            }
        }).trigger('select2:select');

        //nip
        $('#nip').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5',
            ajax: {
                delay: 1000,
                type: 'POST',
                url: "{{ route('spd.spd_belanja.nip') }}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                    }
                    var skpd = $('#kd_skpd').select2('data')[0]
                    if (skpd) query.kd_skpd = skpd.kd_skpd
                    return query
                },
            },
            dropdownAutoWidth: true,
            templateResult: function(result) {
                if (!result.id) return 'Searching';
                return `${result.id} | ${result.text}`;
            },
            escapeMarkup: (m) => m,
            templateSelection: function(result) {
                return result.id || result.text;
            },
        });

        $('#nip').on('select2:select', function() {
            var data = $(this).select2('data')[0]
            $('#nama_bend').val(data ? data.nama : null)
        });

        //jenis_anggaran
        $('#jenis_anggaran').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5',
            ajax: {
                delay: 1000,
                type: 'POST',
                url: "{{ route('spd.spd_belanja.jns_ang') }}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                    }
                    var skpd = $('#kd_skpd').select2('data')[0]
                    if (skpd) query.kd_skpd = skpd.kd_skpd
                    return query
                },
            },
            dropdownAutoWidth: true,
            templateResult: function(result) {
                if (!result.id) return 'Searching';
                return `${result.text}`;
            },
            escapeMarkup: (m) => m,
            templateSelection: function(result) {
                return result.text;
            },
        });

        $('#jenis_anggaran').on('select2:select', function() {
            var jenis_anggaran = $(this).select2('data')[0];
            $('#status_angkas').val(null).trigger('change').trigger('select2:select');
            if (jenis_anggaran) {
                $('#status_angkas').prop('disabled', false)
            } else {
                $('#status_angkas').prop('disabled', true)
            }
        }).trigger('select2:select');

        //status angkas 
        $('#status_angkas').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5',
            ajax: {
                delay: 1000,
                type: 'POST',
                url: "{{ route('spd.spd_belanja.status_angkas') }}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        term: params.term,
                    }
                    var skpd = $('#kd_skpd').select2('data')[0];
                    var jenis_anggaran = $('#jenis_anggaran').select2('data')[0]
                    if (skpd && jenis_anggaran) query.kd_skpd = skpd.kd_skpd;
                    query.kode = jenis_anggaran.kode
                    return query
                },
            },
            dropdownAutoWidth: true,
            escapeMarkup: (m) => m,
            templateSelection: function(result) {
                return result.text;
            },
        });

        $('#status_angkas').change(function() {
            tabelBelanja.clear().draw();
            daftarSpdTempTable.clear().draw();
        })

        $('#bulan_awal').change(function() {
            tabelBelanja.clear().draw();
            daftarSpdTempTable.clear().draw();
        })

        $('#bulan_akhir').change(function() {
            tabelBelanja.clear().draw();
            daftarSpdTempTable.clear().draw();
        })

        $('#tanggal').change(function() {
            tabelBelanja.clear().draw();
            daftarSpdTempTable.clear().draw();
        })

        $('#jenis').change(function() {
            tabelBelanja.clear().draw();
            daftarSpdTempTable.clear().draw();
        })

        $('#nomor').change(function() {
            tabelBelanja.clear().draw();
            daftarSpdTempTable.clear().draw();
        })

        $('#revisi').change(function() {
            tabelBelanja.clear().draw();
            daftarSpdTempTable.clear().draw();
        })

        $('#spd_belanja tbody').on('click', 'tr', function() {
            let tabel = tabelBelanja.row(this).data();
            if (!tabel) return
            let kd_skpd = document.getElementById('kd_skpd').value;
            let tanggal = document.getElementById('tanggal').value;
            let bln_awal = document.getElementById('bulan_awal').value;
            let bln_akhir = document.getElementById('bulan_akhir').value;
            let jns_ang = document.getElementById('jenis_anggaran').value;
            let jenis = document.getElementById('jenis').value;
            let page = document.getElementById('idpage').value;
            let status_ang = document.getElementById('status_angkas').value;
            if (document.getElementById("revisi").checked == true) {
                revisi = '1';
            } else {
                revisi = '0';
            }

            let data = {
                page: page,
                kd_skpd: kd_skpd,
                bln_awal: bln_awal,
                bln_akhir: bln_akhir,
                jns_ang: jns_ang,
                revisi: revisi,
                jenis: jenis,
                status_ang: status_ang,
                kd_rek6: tabel.kd_rek6,
                kd_sub_kegiatan: tabel.kd_sub_kegiatan,
                nilai: tabel.nilai,
                lalu: tabel.lalu,
                anggaran: tabel.anggaran,
            }
            $.ajax({
                delay: 1000,
                url: "{{ route('spd.spd_belanja.insert_spd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },

                success: function(data) {
                    if (data.message == '1') {
                        toastr.success('Sub Kegiatan dan Rekening telah berhasil ditambahkan');
                        tabelBelanja.clear().draw();
                        daftarSpdTempTable.clear().draw();
                    } else {
                        toastr.error('Sub Kegiatan dan Rekening gagal ditambahkan');
                        tabelBelanja.clear().draw();
                    }
                }
            })
        })

        $('#spd_belanja_temp tbody').on('click', 'tr', function() {
            let tabel = daftarSpdTempTable.row(this).data();
            if (!tabel) return
            let kd_skpd = document.getElementById('kd_skpd').value;
            let tanggal = document.getElementById('tanggal').value;
            let bln_awal = document.getElementById('bulan_awal').value;
            let bln_akhir = document.getElementById('bulan_akhir').value;
            let jns_ang = document.getElementById('jenis_anggaran').value;
            let jenis = document.getElementById('jenis').value;
            let page = document.getElementById('idpage').value;
            let status_ang = document.getElementById('status_angkas').value;
            if (document.getElementById("revisi").checked == true) {
                revisi = '1';
            } else {
                revisi = '0';
            }

            let data = {
                page: page,
                kd_skpd: kd_skpd,
                bln_awal: bln_awal,
                bln_akhir: bln_akhir,
                jns_ang: jns_ang,
                revisi: revisi,
                jenis: jenis,
                status_ang: status_ang,
                kd_rek6: tabel.kd_rek6,
                kd_sub_kegiatan: tabel.kd_sub_kegiatan,
                nilai: tabel.nilai,
                lalu: tabel.nilai_lalu,
                anggaran: tabel.anggaran,
            }

            $.ajax({
                delay: 1000,
                url: "{{ route('spd.spd_belanja.delete_spd_temp') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                success: function(data) {
                    if (data.message == '1') {
                        toastr.success('Sub Kegiatan dan Rekening telah berhasil dihapus');
                        daftarSpdTempTable.clear().draw();
                        tabelBelanja.clear().draw();
                    } else {
                        toastr.error('Sub Kegiatan dan Rekening gagal dihapus');
                        daftarSpdTempTable.clear().draw();
                    }
                }
            })
        })

        $('#insert-all').click(function () {
            let kd_skpd = document.getElementById('kd_skpd').value;
            let jns_ang = document.getElementById('jenis_anggaran').value;
            let nomor = document.getElementById('nomor').value;
            let tanggal = document.getElementById('tanggal').value;
            let bln_awal = document.getElementById('bulan_awal').value;
            let bln_akhir = document.getElementById('bulan_akhir').value;
            let jenis = document.getElementById('jenis').value;
            let page = document.getElementById('idpage').value;
            let status_ang = document.getElementById('status_angkas').value;
            if (document.getElementById("revisi").checked == true) {
                revisi = '1';
            } else {
                revisi = '0';
            }

            let data = {
                page: page,
                kd_skpd: kd_skpd,
                nomor: nomor,
                tanggal: tanggal,
                bln_awal: bln_awal,
                bln_akhir: bln_akhir,
                jns_ang: jns_ang,
                revisi: revisi,
                jenis: jenis,
                status_ang: status_ang,
            }
            $.ajax({
                // delay: 1000,
                url: "{{ route('spd.spd_belanja.insert_all_spd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                success: function(data) {
                    if (data.message == '1') {
                        toastr.success('Sub Kegiatan dan Rekening telah berhasil ditambahkan');
                        tabelBelanja.clear().draw();
                        daftarSpdTempTable.clear().draw();
                    } else {
                        toastr.error('Sub Kegiatan dan Rekening gagal ditambahkan');
                        tabelBelanja.clear().draw();
                    }
                }
            })   
        })

        $('#delete-all').click(function () {
            let kd_skpd = document.getElementById('kd_skpd').value;
            let jns_ang = document.getElementById('jenis_anggaran').value;
            let bln_awal = document.getElementById('bulan_awal').value;
            let bln_akhir = document.getElementById('bulan_akhir').value;
            let jenis = document.getElementById('jenis').value;
            let page = document.getElementById('idpage').value;
            let status_ang = document.getElementById('status_angkas').value;
            if (document.getElementById("revisi").checked == true) {
                revisi = '1';
            } else {
                revisi = '0';
            }

            let data = {
                page: page,
                kd_skpd: kd_skpd,
                bln_awal: bln_awal,
                bln_akhir: bln_akhir,
                jns_ang: jns_ang,
                revisi: revisi,
                jenis: jenis,
                status_ang: status_ang,
            }
            $.ajax({
                // delay: 1000,
                url: "{{ route('spd.spd_belanja.delete_all_spd') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    data: data
                },
                success: function(data) {
                    if (data.message == '1') {
                        toastr.success('Sub Kegiatan dan Rekening telah berhasil dihapus');
                        tabelBelanja.clear().draw();
                        daftarSpdTempTable.clear().draw();
                    } else {
                        toastr.error('Sub Kegiatan dan Rekening gagal dihapus');
                        tabelBelanja.clear().draw();
                    }
                }
            })   
        })

        $('#simpan_spd').on('click', function() {
            let kd_skpd = $('#kd_skpd').select2('data')[0];
            let nip = $('#nip').select2('data')[0];
            let nomor = document.getElementById('nomor').value;
            let tanggal = document.getElementById('tanggal').value;
            let bulan_awal = document.getElementById('bulan_awal').value;
            let bulan_akhir = document.getElementById('bulan_akhir').value;
            let jenis = document.getElementById('jenis').value;
            let revisi = document.getElementById('revisi').value;
            let jenis_anggaran = document.getElementById('jenis_anggaran').value;
            let status_angkas = document.getElementById('status_angkas').value;
            let keterangan = document.getElementById('keterangan').value;

            let daftar_spd = daftarSpdTempTable.rows().data().toArray().map((value) => {
                let data = {
                    no_bukti: value.no_bukti,
                    no_sp2d: value.no_sp2d,
                    kd_sub_kegiatan: value.kd_sub_kegiatan,
                    nm_sub_kegiatan: value.nm_sub_kegiatan,
                    kd_rek6: value.kd_rek6,
                    nm_rek6: value.nm_rek6,
                    nilai: rupiah(value.nilai),
                    sumber: value.sumber,
                    lalu: value.lalu,
                    sp2d: value.sp2d,
                    anggaran: value.anggaran,
                    volume: value.volume,
                    satuan: value.satuan,
                };
                return data;
            });

            if (rincian_rekening.length == 0) {
                alert('Rincian Rekening tidak boleh kosong!');
                return;
            }

            let no_sp2d = tabel_rekening1.rows().data().toArray().map((value) => {
                let data = {
                    no_sp2d: value.no_sp2d,
                };
                return data;
            });
            let sp2d = no_sp2d[0]['no_sp2d'];

            let rincian_rek_tujuan = tabel_tujuan.rows().data().toArray().map((value) => {
                let data = {
                    no_bukti: value.no_bukti,
                    rekening_awal: value.rekening_awal,
                    nm_rekening_tujuan: value.nm_rekening_tujuan,
                    rekening_tujuan: value.rekening_tujuan,
                    bank_tujuan: value.bank_tujuan,
                    kd_skpd: value.kd_skpd,
                    nilai: rupiah(value.nilai),
                };
                return data;
            });

            let tahun_input = tgl_voucher.substr(0, 4);

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (pembayaran == 'BANK' && total_belanja > total_sisa) {
                alert('Nilai Melebihi sisa Simpanan Bank');
                return;
            }

            if (!no_bukti) {
                alert('Nomor Bukti Tidak Boleh Kosong');
                return;
            }

            if (!tgl_voucher) {
                alert('Tanggal Bukti Tidak Boleh Kosong');
                return;
            }

            if (!kd_skpd) {
                alert('Kode SKPD Tidak Boleh Kosong');
                return;
            }

            if (!beban) {
                alert('Jenis beban Tidak Boleh Kosong');
                return;
            }

            if (!keterangan) {
                alert('Keterangan Tidak Boleh Kosong');
                return;
            }

            if (!pembayaran) {
                alert('Jenis Pembayaran Tidak Boleh Kosong');
                return;
            }

            if (total_belanja == 0) {
                alert('Rincian Tidak ada rekening!');
                return;
            }

            if (total_transfer == 0) {
                alert('Rincian Tidak ada rekening!');
                return;
            }

            if (total_transfer > total_belanja) {
                alert('Total Transfer melebihi Total Belanja!');
                return;
            }

            if (!rekening) {
                alert('Isian Rekening Belum Lengkap!');
                return;
            }

            let response = {
                no_bukti,
                tgl_voucher,
                kd_skpd,
                nm_skpd,
                beban,
                keterangan,
                total_belanja,
                pembayaran,
                rekening,
                ketcms,
                sp2d,
                rincian_rekening,
                rincian_rek_tujuan
            };

            $.ajax({
                url: "{{ route('skpd.transaksi_cms.cek_simpan') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    no_bukti: no_bukti
                },
                success: function(data) {
                    if (data == '1') {
                        alert('Nomor Telah Dipakai!');
                        return;
                    } else {
                        alert("Nomor Bisa dipakai");
                        simpan_cms(response);
                    }
                }
            })
        });

    });
</script>