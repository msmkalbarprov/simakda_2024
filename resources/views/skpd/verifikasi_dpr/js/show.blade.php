<style>
    table.dataTable tr th.select-checkbox.selected::after {
        content: "✔";
        margin-top: -11px;
        margin-left: -4px;
        text-align: center;
        text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
    }

    /*
    .table.dataTabel tr td.dt-checkboxes.selected {
        content: "✔";
        margin-top: -11px;
        margin-left: -4px;
        text-align: center;
        text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
    } */
</style>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        $('#jenis_belanja').prop('disabled', true);

        let tabel_rekening1 = $('#verifikasi_dpr').DataTable({
            responsive: true,
            processing: true,
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
                    name: 'nilai',
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
                    data: 'nm_bukti',
                    name: 'nm_bukti',
                },
                {
                    data: 'status',
                    name: 'status',
                    visible: false
                },
                {
                    data: 'urut',
                    name: 'urut',
                    visible: false
                },
                {
                    data: null,
                    name: 'aksi',
                    className: 'text-center'
                },
            ],
            columnDefs: [{
                'targets': 10,
                'checkboxes': {
                    'selectRow': true,
                },

            }],
            select: {
                'style': 'multi'
            },
            order: [
                [1, 'asc']
            ]
        });

        tabel_rekening1.rows(function(idx, data, node) {
            if (data.status == '1') $(node).find('.dt-checkboxes:input[type="checkbox"]').prop(
                "checked", true);
        });

        // let tabel_rekening1 = $('#verifikasi_dpr').DataTable({
        //     // responsive: true,
        //     // processing: true,
        //     // ordering: false,
        //     // lengthMenu: [
        //     //     [-1],
        //     //     ["All"]
        //     // ],
        //     columnDefs: [{
        //         'targets': 7,
        //         'checkboxes': {
        //             'selectRow': true
        //         }
        //     }],
        //     select: {
        //         'style': 'multi'
        //     },
        //     order: [
        //         [1, 'asc']
        //     ]
        // });

        $('#simpan').on('click', function() {
            let rincian_rekening1 = tabel_rekening1.rows(function(idx, data, node) {
                return $(node).find('.dt-checkboxes:input[type="checkbox"]').prop('checked');
            }).data().toArray().map((value) => {
                let data = {
                    kode: value.kd_sub_kegiatan + '.' + value.kd_rek6 + '.' + value.sumber +
                        '.' + value.urut
                };
                return data;
            });

            let tipe = document.getElementById('simpan').innerText;

            let no_dpr = document.getElementById('no_dpr').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let tgl_verifikasi = document.getElementById('tgl_verifikasi').value;
            let keterangan = document.getElementById('keterangan').value;


            if (!tgl_verifikasi) {
                alert('Silahkan isi tanggal verifikasi!');
                return;
            }

            if (rincian_rekening1.length == 0) {
                alert('Silahkan verifikasi detail DPR');
                return;
            }

            if (!keterangan && rincian_rekening1.length > 0) {
                alert('Silahkan isi keterangan');
                return;
            }

            if (keterangan.length > 1000) {
                alert('Keterangan tidak boleh melebihi 1000 karakter');
                return;
            }

            let rincian_rekening = JSON.stringify(rincian_rekening1);

            let data = {
                no_dpr,
                tipe,
                kd_skpd,
                tgl_verifikasi,
                keterangan,
                rincian_rekening
            };

            $('#simpan').prop('disabled', true);

            $.ajax({
                url: "{{ route('dpr.simpan_verifikasi') }}",
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
                        alert('Data berhasil diverfikasi!');
                        window.location.href =
                            "{{ route('dpr.index_verifikasi') }}";
                    } else {
                        alert('Data tidak berhasil diverifikasi!');
                        $('#simpan').prop('disabled', false);
                        return;
                    }
                },
                complete: function(data) {
                    $("#overlay").fadeOut(100);
                }
            })
        });
    });

    function detail(no_dpr, kd_skpd) {
        $.ajax({
            url: "{{ route('dpr.detail_verifikasi') }}",
            type: "POST",
            dataType: 'json',
            data: {
                no_dpr: no_dpr,
                kd_skpd: kd_skpd,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                console.log(data);
            }
        })
    }
</script>
