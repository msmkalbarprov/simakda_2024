<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let rincian_upload = $('#rincian_upload').DataTable({
            responsive: true,
            processing: true,
            ordering: false,
            columns: [{
                    data: 'no_voucher',
                    name: 'no_voucher',
                },
                {
                    data: 'tgl_voucher',
                    name: 'tgl_voucher',
                },
                {
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                },
                {
                    data: null,
                    name: 'ket',
                    render: function(data, type, row, meta) {
                        return data.ket.substr(0, 10) + '.....';
                    }
                },
                {
                    data: 'netto',
                    name: 'netto',
                    visible: false
                },
                {
                    data: 'potongan',
                    name: 'potongan',
                    visible: false
                },
                {
                    data: 'nilai_pengeluaran',
                    name: 'nilai_pengeluaran',
                },
                {
                    data: 'status_upload',
                    name: 'status_upload',
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
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });

        load_transaksi();

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('#data_transaksi').on('select2:select', function() {
            let no_voucher = this.value;
            let total_transaksi = angka(document.getElementById('total_transaksi').value);
            let total = parseFloat($(this).find(':selected').data('total'));

            let tampungan = rincian_upload.rows().data().toArray().map((value) => {
                let result = {
                    no_voucher: value.no_voucher,
                };
                return result;
            });
            let kondisi = tampungan.map(function(data) {
                if (data.no_voucher == no_voucher) {
                    return '1';
                }
            });
            if (kondisi.includes("1")) {
                alert('Nomor Transaksi ini sudah ada di LIST!');
                $("#data_transaksi").val(null).change();
                return;
            }

            let potongan = $(this).find(':selected').data('tot_pot');
            let nilai_pengeluaran = $(this).find(':selected').data('total');

            rincian_upload.row.add({
                'no_voucher': no_voucher,
                'tgl_voucher': $(this).find(':selected').data('tgl'),
                'kd_skpd': $(this).find(':selected').data('kd_skpd'),
                'ket': $(this).find(':selected').data('ket'),
                'netto': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai_pengeluaran - potongan),
                'potongan': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(potongan),
                'nilai_pengeluaran': new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2
                }).format(nilai_pengeluaran),
                'status_upload': $(this).find(':selected').data('status_upload'),
                'rekening_awal': $(this).find(':selected').data('rekening_awal'),
                'nm_rekening_tujuan': $(this).find(':selected').data('nm_rekening_tujuan'),
                'rekening_tujuan': $(this).find(':selected').data('rekening_tujuan'),
                'bank_tujuan': $(this).find(':selected').data('bank_tujuan'),
                'ket_tujuan': $(this).find(':selected').data('ket_tujuan'),
                'aksi': `<a href="javascript:void(0);" onclick="deleteData('${no_voucher}','${total}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>`,
            }).draw();
            $('#total_transaksi').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total_transaksi + total));
            $("#data_transaksi").val(null).change();
            load_transaksi();
        });

        $('#proses_upload').on('click', function() {
            let total_transaksi = angka(document.getElementById('total_transaksi').value);
            let sisa_saldo = angka(document.getElementById('sisa_saldo').value);
            let rincian = rincian_upload.rows().data().toArray();
            if (rincian.length == 0) {
                alert('List Data belum dipilih');
                return;
            }

            let total_potongan = rincian_upload.rows().data().toArray().reduce((previousValue,
                currentValue) => (previousValue += angka(currentValue.potongan)), 0);

            let total_transfer = total_transaksi - total_potongan;

            if (total_transfer > sisa_saldo) {
                alert('Total Transaksi melebihi sisa Bank, Cek Kembali !');
                return;
            }

            let tanya = confirm("Apakah data yang akan di-Upload sudah benar ?");
            if (tanya == true) {
                $('#proses_upload').prop("disabled", true);
                let rincian_data = rincian_upload.rows().data().toArray().map((value) => {
                    let data = {
                        no_voucher: value.no_voucher,
                        tgl_voucher: value.tgl_voucher,
                        kd_skpd: value.kd_skpd,
                        ket: value.ket,
                        netto: angka(value.netto),
                        potongan: angka(value.potongan),
                        nilai_pengeluaran: angka(value.nilai_pengeluaran),
                        status_upload: value.status_upload,
                        rekening_awal: value.rekening_awal,
                        nm_rekening_tujuan: value.nm_rekening_tujuan,
                        rekening_tujuan: value.rekening_tujuan,
                        bank_tujuan: value.bank_tujuan,
                        ket_tujuan: value.ket_tujuan,
                    };
                    return data;
                });
                $.ajax({
                    url: "{{ route('upl_kkpd.proses_upload') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        total_transaksi: total_transaksi,
                        rincian_data: rincian_data,
                    },
                    success: function(data) {
                        if (data.message == '1') {
                            alert('Data berhasil diupload');
                            window.location.href = "{{ route('upl_kkpd.index') }}";
                        } else {
                            alert('Data tidak berhasil diupload!');
                            $('#proses_upload').prop("disabled", false);
                        }
                    }
                })
            } else {
                alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
                $('#proses_upload').prop("disabled", false);
            }
        });
    });

    function angka(data) {
        let n1 = data.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }

    function load_transaksi() {
        $('#data_transaksi').empty();
        let rincian_upload = $('#rincian_upload').DataTable();
        let detail_rincian = rincian_upload.rows().data().toArray().map((value) => {
            let data = {
                no_voucher: value.no_voucher,
            };
            return data;
        });

        $.ajax({
            url: "{{ route('upl_kkpd.load_transaksi') }}",
            type: "POST",
            dataType: 'json',
            data: {
                no_voucher: detail_rincian.length == 0 ? '0' : detail_rincian
            },
            success: function(data) {
                $('#data_transaksi').empty();
                $('#data_transaksi').append(
                    `<option value="" disabled selected>Silahkan Pilih</option>`);
                $.each(data, function(index, data) {
                    $('#data_transaksi').append(
                        `<option value="${data.no_voucher}" data-tgl="${data.tgl_voucher}" data-kd_skpd="${data.kd_skpd}" data-ket="${data.ket}" data-bersih="${data.bersih}" data-tot_pot="${data.tot_pot}" data-total="${data.total}" data-status_upload="${data.status_upload}" data-rekening_awal="${data.rekening_awal}" data-nm_rekening_tujuan="${data.nm_rekening_tujuan}" data-rekening_tujuan="${data.rekening_tujuan}" data-bank_tujuan="${data.bank_tujuan}" data-ket_tujuan="${data.ket_tujuan}">${data.no_voucher} | ${data.tgl_voucher}</option>`
                    );
                })
            }
        })
    }

    function deleteData(no_voucher, total) {
        let tanya = confirm('Apakah anda yakin untuk menghapus dengan Nomor Transaksi : ' + no_voucher);
        let tabel = $('#rincian_upload').DataTable();
        let total_transaksi = angka(document.getElementById('total_transaksi').value);
        if (tanya == true) {
            tabel.rows(function(idx, data, node) {
                return data.no_voucher == no_voucher
            }).remove().draw();
            $('#total_transaksi').val(new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2
            }).format(total_transaksi - parseFloat(total)));
            load_transaksi();
        } else {
            return false;
        }
    }
</script>
