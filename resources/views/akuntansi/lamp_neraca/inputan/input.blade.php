@extends('template.app')
@section('title', 'INPUT LAMPIRAN NERACA | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"id="tambah">
                    List Lampiran Neraca
                    <a href="#" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="list_lamp_aset" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No.</th>
                                        <th style="width: 50px;text-align:center">No Lamp</th>
                                        <th style="width: 100px;text-align:center">Rekening</th>
                                        <th style="width: 50px;text-align:center">Rek. Rinci</th>
                                        <th style="width: 50px;text-align:center">Saldo Awal</th>
                                        <th style="width: 200px;text-align:center">Keterangan</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@include('akuntansi.modal.lamp_neraca.input.input_lamp_neraca')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });

        cari_lokasi();
        cari_rek3();

        $(".select_lamp_neraca").select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modal_input_lamp_neraca .modal-content'),
        });

        $('#list_lamp_aset').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('input_lamp_neraca.load') }}",
                "type": "POST",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                },
                {
                    data: 'no_lamp',
                    name: 'no_lamp',
                }, 
                {
                    data: 'nm_rek5',
                    name: 'nm_rek5',
                    className: "text-center",
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6',
                    className: "text-center",
                },
                {
                    data: null,
                    name: 'sal_awal',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.sal_awal)
                    }
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: '100px',
                    className: "text-center",
                },
            ],
        });
    });
        $('#tambah').on('click', function() {
            // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
            status_input = 'tambah';
            $('#status_input').val(status_input);
            // $('#nomor').val("{{$no_lamp->nomor}}");
            // $('#no_simpan').val("{{$no_lamp->nomor}}");
            $('#rek3').val(null).change();
            $('#nm_rek3').val(null);
            $('#rek5').val(null).change();
            $('#nm_rek5').val(null);
            $('#rek6').val(null).change();
            $('#nm_rek6').val(null);
            $('#tahun').val(null).change();
            $('#merk').val(null);
            $('#no_polisi').val(null);
            $('#fungsi').val(null);
            $('#hukum').val(null);
            $('#lokasi').val(null);
            $('#alamat').val(null);
            $('#sert').val(null);
            $('#luas').val(null);
            $('#satuan').val(null);
            $('#harga_satuan').val(null);
            $('#piutang_awal').val(null);
            $('#piutang_koreksi').val(null);
            $('#piutang_sudah').val(null);
            $('#investasi_awal').val(null);
            $('#sal_awal').val(null);
            $('#kurang').val(null);
            $('#tambah').val(null);
            $('#tahun_n').val(null);
            $('#akhir').val(null);
            $('#kondisi_b').val(null);
            $('#kondisi_rr').val(null);
            $('#kondisi_rb').val(null);
            $('#keterangan').val(null);
            $('#jumlah').val(null);
            $('#kepemilikan').val(null);
            $('#rincian_bebas').val(null);
            $('#jenis_aset').val(null);
            $('#realisasi_janji').val(null);
            $('#nama_perusahaan').val(null);
            $('#no_polis').val(null);
            $('#tgl_awal').val(null);
            $('#tgl_akhir').val(null);
            $('#jam').val(null);
            $('#bulan').val(null);
            $('#masa').val(null);
            $('#tmasa').val(null);
            $('#korplus').val(null);
            $('#kormin').val(null);
            $('#akum_penyu').val(null);
            $('#sisa_umur').val(null);
            $('#status_aset').val(null).change();
            $('#akum_penyub').val(null);
            $('#kondisi_x').val(null);
            $('#nil_kurang_excomp').val(null);
            $('#status_extracomp').val(null);
            $('#sekolah').val(null);
            $('#kd_rek7').val(null);
            $('#nm_rek7').val(null);
            $('#jenis').val(null);
            load_no_lamp()
            tampil_rek3("");


            $('#modal_input_lamp_neraca').modal('show');
            $("#labelcetak_semester").html("Cetak Lampiran Neraca");
            // document.getElementById('row-hidden').hidden = true; // Hide
        });

    function load_no_lamp(){
        $.ajax({
            url: "{{ route('input_kapitalisasi.no_lamp.load') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {
                // console.log(data[0])
                $('#nomor').val(data[0].nomor);
                $('#no_simpan').val(data[0].nomor);
            }
        })
    }

    function cari_lokasi() {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('input_lamp_neraca.cari_lokasi') }}",
            type: "POST", 
            dataType: 'json',
            success: function(data) {
                $('#lokasi').empty();
                $('#lokasi').append(
                    `<option value="" disabled selected>Pilih Lokasi</option>`);
                $.each(data, function(index, data) {
                    $('#lokasi').append(
                        `<option value="${data.lokasi}">${data.lokasi} </option>`
                    );
                })
            }
        })
    }

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }

        // original length
        var original_len = input_val.length;

        // initial caret position
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = input_val;

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

    function angka(n) {
        let nilai = n.split(',').join('');
        return parseFloat(nilai) || 0;
    }

    function rupiah(n) {
        let n1 = n.split('.').join('');
        let rupiah = n1.split(',').join('.');
        return parseFloat(rupiah) || 0;
    }

    function cari_rek3() {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('input_lamp_neraca.cari_rek3') }}",
            type: "POST", 
            dataType: 'json',
            success: function(data) {
                $('#rek3').empty();
                $('#rek3').append(
                    `<option value="" disabled selected>Pilih Rekening Objek</option>`);
                $.each(data, function(index, data) {
                    $('#rek3').append(
                        `<option value="${data.kd_rek3}" data-nama="${data.nm_rek3}">${data.kd_rek3} | ${data.nm_rek3}</option>`
                    );
                })
            }
        })
    }
    function tampil_rek3(rek3){
        if (rek3==1301){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',false);
            $("#lokasi0").attr('hidden',false);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',false);
            $("#alamat0").attr('hidden',false);
            $("#sert1").attr('hidden',false);
            $("#sert0").attr('hidden',false);
            $("#luas1").attr('hidden',false);
            $("#luas0").attr('hidden',false);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',false);
            $("#kondisi_b0").attr('hidden',false);
            $("#kondisi_rb1").attr('hidden',false);
            $("#kondisi_rb0").attr('hidden',false);
            $("#kondisi_rr1").attr('hidden',false);
            $("#kondisi_rr0").attr('hidden',false);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if ((rek3==1501) || (rek3==1502) || (rek3==1503) || (rek3==1504)){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',false);
            $("#merk0").attr('hidden',false);
            $("#no_polisi1").attr('hidden',false);
            $("#no_polisi0").attr('hidden',false);
            $("#fungsi1").attr('hidden',false);
            $("#fungsi0").attr('hidden',false);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',false);
            $("#lokasi0").attr('hidden',false);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',false);
            $("#alamat0").attr('hidden',false);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',false);
            $("#luas0").attr('hidden',false);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',false);
            $("#kondisi_b0").attr('hidden',false);
            $("#kondisi_rb1").attr('hidden',false);
            $("#kondisi_rb0").attr('hidden',false);
            $("#kondisi_rr1").attr('hidden',false);
            $("#kondisi_rr0").attr('hidden',false);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        }else if (rek3==2101){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',false);
            $("#lokasi0").attr('hidden',false);

            var skpd = "{{Auth::user()->kd_skpd}}";
            if (skpd=='1.01.01.01') {
                $("#sekolah0").attr('hidden',false);
                $("#sekolah1").attr('hidden',false);
            }else{
                $("#sekolah0").attr('hidden',true);
                $("#sekolah1").attr('hidden',true);
            }
            
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#jumlah1").attr('hidden',true);
            $("#jumlah0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#harga_satuan1").attr('hidden',true);
            $("#harga_satuan0").attr('hidden',true);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=false;

        }else if ((rek3==2102) || (rek3==2104) || (rek3==2107) || (rek3==2106) || (rek3==2201) || (rek3==2202||rek3==1108)){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#jumlah1").attr('hidden',true);
            $("#jumlah0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#harga_satuan1").attr('hidden',true);
            $("#harga_satuan0").attr('hidden',true);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=false;

        }else if (rek3==2105){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',false);
            $("#lokasi0").attr('hidden',false);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',true);
            $("#jumlah0").attr('hidden',true);
            $("#harga_satuan1").attr('hidden',true);
            $("#harga_satuan0").attr('hidden',true);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',false);
            $("#jenis_aset0").attr('hidden',false);
            $("#realisasi_janji1").attr('hidden',false);
            $("#realisasi_janji0").attr('hidden',false);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',false);
            $("#tgl_awal0").attr('hidden',false);
            $("#tgl_akhir1").attr('hidden',false);
            $("#tgl_akhir0").attr('hidden',false);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "block";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=true;
            document.getElementById('tahun_n').disabled=true;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1401){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',false);
            $("#hukum0").attr('hidden',false);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        }else if (rek3==1306) {
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',false);
            $("#fungsi0").attr('hidden',false);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',false);
            $("#lokasi0").attr('hidden',false);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',false);
            $("#alamat0").attr('hidden',false);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#luas1").attr('hidden',false);
            $("#luas0").attr('hidden',false);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#koreksi1").attr('hidden',false);
            $("#koreksi0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',false);
            $("#kondisi_b0").attr('hidden',false);
            $("#kondisi_rb1").attr('hidden',false);
            $("#kondisi_rb0").attr('hidden',false);
            $("#kondisi_rr1").attr('hidden',false);
            $("#kondisi_rr0").attr('hidden',false);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1305) {
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',false);
            $("#merk0").attr('hidden',false);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',false);
            $("#kondisi_b0").attr('hidden',false);
            $("#kondisi_rb1").attr('hidden',false);
            $("#kondisi_rb0").attr('hidden',false);
            $("#kondisi_rr1").attr('hidden',false);
            $("#kondisi_rr0").attr('hidden',false);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1303) {
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',false);
            $("#tmasa0").attr('hidden',false);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',false);
            $("#fungsi0").attr('hidden',false);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',false);
            $("#lokasi0").attr('hidden',false);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',false);
            $("#alamat0").attr('hidden',false);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#luas1").attr('hidden',false);
            $("#luas0").attr('hidden',false);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',false);
            $("#akum_penyub0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',false);
            $("#kondisi_b0").attr('hidden',false);
            $("#kondisi_rb1").attr('hidden',false);
            $("#kondisi_rb0").attr('hidden',false);
            $("#kondisi_rr1").attr('hidden',false);
            $("#kondisi_rr0").attr('hidden',false);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1304) {
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',false);
            $("#fungsi0").attr('hidden',false);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',false);
            $("#lokasi0").attr('hidden',false);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',false);
            $("#alamat0").attr('hidden',false);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#luas1").attr('hidden',false);
            $("#luas0").attr('hidden',false);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',false);
            $("#sisa_umur0").attr('hidden',false);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',false);
            $("#akum_penyub0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',false);
            $("#kondisi_b0").attr('hidden',false);
            $("#kondisi_rb1").attr('hidden',false);
            $("#kondisi_rb0").attr('hidden',false);
            $("#kondisi_rr1").attr('hidden',false);
            $("#kondisi_rr0").attr('hidden',false);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if(rek3==1302){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',false);
            $("#merk0").attr('hidden',false);
            $("#no_polisi1").attr('hidden',false);
            $("#no_polisi0").attr('hidden',false);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',false);
            $("#kondisi_b0").attr('hidden',false);
            $("#kondisi_rb1").attr('hidden',false);
            $("#kondisi_rb0").attr('hidden',false);
            $("#kondisi_rr1").attr('hidden',false);
            $("#kondisi_rr0").attr('hidden',false);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',false);
            $("#nil_kurang_excomp0").attr('hidden',false);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if(rek3==3103){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',true);
            $("#jumlah0").attr('hidden',true);
            $("#harga_satuan1").attr('hidden',true);
            $("#harga_satuan0").attr('hidden',true);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',true);
            $("#tahun_n0").attr('hidden',true);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        }else if(rek3==1112){
            /*alert("Form masih dalam tahap penyesuaian !!");
            return;*/
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',false);
            $("#merk0").attr('hidden',false);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',false);
            $("#kondisi_b0").attr('hidden',false);
            $("#kondisi_rb1").attr('hidden',false);
            $("#kondisi_rb0").attr('hidden',false);
            $("#kondisi_rr1").attr('hidden',false);
            $("#kondisi_rr0").attr('hidden',false);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',false);
            $("#nil_kurang_excomp0").attr('hidden',false);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',false);
            $("#kondisi_x0").attr('hidden',false);
            $("#kondisi_x2").attr('hidden',false);
            $("#rek_subrinci1").attr('hidden',false);
            $("#rek_subrinci0").attr('hidden',false);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1111){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',false);
            $("#status_asuransi0").attr('hidden',false);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polis1").attr('hidden',false);
            $("#no_polis0").attr('hidden',false);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',true);
            $("#jumlah0").attr('hidden',true);
            $("#harga_satuan1").attr('hidden',true);
            $("#harga_satuan0").attr('hidden',true);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis1").attr('hidden',false);
            $("#no_polis0").attr('hidden',false);
            $("#jenis_aset1").attr('hidden',false);
            $("#jenis_aset0").attr('hidden',false);
            $("#realisasi_janji1").attr('hidden',false);
            $("#realisasi_janji0").attr('hidden',false);
            $("#nama_perusahaan1").attr('hidden',false);
            $("#nama_perusahaan0").attr('hidden',false);
            $("#tgl_awal1").attr('hidden',false);
            $("#tgl_awal0").attr('hidden',false);
            $("#tgl_akhir1").attr('hidden',false);
            $("#tgl_akhir0").attr('hidden',false);
            $("#jam1").attr('hidden',false);
            $("#jam0").attr('hidden',false);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "block";            
            document.getElementById('kurang').disabled=true;
            document.getElementById('tahun_n').disabled=true;
            document.getElementById('sal_awal').disabled=true;

        } else if(rek3 ==1109 || rek3==1110){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',false);
            $("#bulan_oleh0").attr('hidden',false);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',false);
            $("#piutang_koreksi0").attr('hidden',false);
            $("#piutang_sudah1").attr('hidden',false);
            $("#piutang_sudah0").attr('hidden',false);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none"; 
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3 == 1103 || (rek3==1104) || (rek3==1106)){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',false);
            $("#bulan_oleh0").attr('hidden',false);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',false);
            $("#lokasi0").attr('hidden',false);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1101){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1102){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#milik1").attr('hidden',false);
            $("#milik0").attr('hidden',false);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',false);
            $("#hukum0").attr('hidden',false);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',false);
            $("#investasi_awal0").attr('hidden',false);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',true);
            $("#tahun_n0").attr('hidden',true);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1201){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#milik1").attr('hidden',false);
            $("#milik0").attr('hidden',false);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',false);
            $("#hukum0").attr('hidden',false);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',false);
            $("#investasi_awal0").attr('hidden',false);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',true);
            $("#tahun_n0").attr('hidden',true);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3==1202){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#milik1").attr('hidden',false);
            $("#milik0").attr('hidden',false);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',false);
            $("#hukum0").attr('hidden',false);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#harga_satuan1").attr('hidden',false);
            $("#harga_satuan0").attr('hidden',false);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',false);
            $("#investasi_awal0").attr('hidden',false);
            $("#sal_awal1").attr('hidden',false);
            $("#sal_awal0").attr('hidden',false);
            $("#kurang1").attr('hidden',false);
            $("#kurang0").attr('hidden',false);
            $("#tambah1").attr('hidden',false);
            $("#tambah0").attr('hidden',false);
            $("#tahun_n1").attr('hidden',true);
            $("#tahun_n0").attr('hidden',true);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',false);
            $("#korplus0").attr('hidden',false);
            $("#kormin1").attr('hidden',false);
            $("#kormin0").attr('hidden',false);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',false);
            $("#keterangan0").attr('hidden',false);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);

            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        } else if (rek3=="") {
            // alert("Belum ada form input");
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',true);
            $("#jumlah0").attr('hidden',true);
            $("#harga_satuan1").attr('hidden',true);
            $("#harga_satuan0").attr('hidden',true);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',true);
            $("#tahun_n0").attr('hidden',true);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',true);
            $("#korplus0").attr('hidden',true);
            $("#kormin1").attr('hidden',true);
            $("#kormin0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);


            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        }
        else {
            alert("Belum ada form input");
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#status_asuransi1").attr('hidden',true);
            $("#status_asuransi0").attr('hidden',true);
            $("#masa1").attr('hidden',true);
            $("#masa0").attr('hidden',true);
            $("#tmasa1").attr('hidden',true);
            $("#tmasa0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#sekolah0").attr('hidden',true);
            $("#sekolah1").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',true);
            $("#satuan0").attr('hidden',true);
            $("#jumlah1").attr('hidden',true);
            $("#jumlah0").attr('hidden',true);
            $("#harga_satuan1").attr('hidden',true);
            $("#harga_satuan0").attr('hidden',true);
            $("#rincian_bebas1").attr('hidden',true);
            $("#rincian_bebas0").attr('hidden',true);
            $("#piutang_awal1").attr('hidden',true);
            $("#piutang_awal0").attr('hidden',true);
            $("#piutang_koreksi1").attr('hidden',true);
            $("#piutang_koreksi0").attr('hidden',true);
            $("#piutang_sudah1").attr('hidden',true);
            $("#piutang_sudah0").attr('hidden',true);
            $("#investasi_awal1").attr('hidden',true);
            $("#investasi_awal0").attr('hidden',true);
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',true);
            $("#tahun_n0").attr('hidden',true);
            $("#sisa_umur1").attr('hidden',true);
            $("#sisa_umur0").attr('hidden',true);
            $("#akum_penyu1").attr('hidden',true);
            $("#akum_penyu0").attr('hidden',true);
            $("#akum_penyub1").attr('hidden',true);
            $("#akum_penyub0").attr('hidden',true);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#korplus1").attr('hidden',true);
            $("#korplus0").attr('hidden',true);
            $("#kormin1").attr('hidden',true);
            $("#kormin0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#nil_kurang_excomp1").attr('hidden',true);
            $("#nil_kurang_excomp0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis1").attr('hidden',true);
            $("#no_polis0").attr('hidden',true);
            $("#jenis_aset1").attr('hidden',true);
            $("#jenis_aset0").attr('hidden',true);
            $("#realisasi_janji1").attr('hidden',true);
            $("#realisasi_janji0").attr('hidden',true);
            $("#nama_perusahaan1").attr('hidden',true);
            $("#nama_perusahaan0").attr('hidden',true);
            $("#tgl_awal1").attr('hidden',true);
            $("#tgl_awal0").attr('hidden',true);
            $("#tgl_akhir1").attr('hidden',true);
            $("#tgl_akhir0").attr('hidden',true);
            $("#jam1").attr('hidden',true);
            $("#jam0").attr('hidden',true);
            $("#kondisi_x1").attr('hidden',true);
            $("#kondisi_x0").attr('hidden',true);
            $("#kondisi_x2").attr('hidden',true);
            $("#rek_subrinci1").attr('hidden',true);
            $("#rek_subrinci0").attr('hidden',true);


            document.getElementById('hitung_pendapatan').style.display = "none";
            document.getElementById('hitung_asuransi1').style.display = "none";
            document.getElementById('kurang').disabled=false;
            document.getElementById('tahun_n').disabled=false;
            document.getElementById('sal_awal').disabled=true;

        }
    }
    $('#rek3').on('select2:select', function() {
        let rek3 = this.value;
        cari_rek5(rek3);
        tampil_rek3(rek3);
    });
    function cari_rek5(rek3,kd_rek5) {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('input_lamp_neraca.cari_rek5') }}",
            type: "POST", 
            data: {
                rek3: rek3
            }, 
            dataType: 'json',
            success: function(data) {
                $('#rek5').empty();
                $('#rek5').append(
                    `<option value="" disabled selected>Pilih Rekening</option>`);
                $.each(data, function(index, data) {
                    if (data.kd_rek5 == kd_rek5) {
                        $('#rek5').append(
                        `<option value="${data.kd_rek5}" data-nama="${data.nm_rek5}" selected>${data.kd_rek5} | ${data.nm_rek5}</option>`
                        );
                    } else {
                        $('#rek5').append(
                        `<option value="${data.kd_rek5}" data-nama="${data.nm_rek5}">${data.kd_rek5} | ${data.nm_rek5}</option>`
                        );
                    }
                    
                })
            }
        })
    }
    $('#rek5').on('select2:select', function() {
        let rek5 = this.value;
        cari_rek6(rek5);
    });
    function cari_rek6(rek5,kd_rek6) {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('input_lamp_neraca.cari_rek6') }}",
            type: "POST", 
            data: {
                rek5: rek5
            }, 
            dataType: 'json',
            success: function(data) {
                $('#rek6').empty();
                $('#rek6').append(
                    `<option value="" disabled selected>Pilih Rekening Rinci</option>`);
                $.each(data, function(index, data) {
                    if (data.kd_rek6 == kd_rek6) {
                        $('#rek6').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}" selected>${data.kd_rek6} | ${data.nm_rek6}</option>`
                        );
                    } else {
                        $('#rek6').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}">${data.kd_rek6} | ${data.nm_rek6}</option>`
                        );
                    }
                })
            }
        })
    }
    $('#rek6').on('select2:select', function() {
        let rek6 = this.value;
        cari_rek7(rek6);
    });


    function cari_rek7(rek6,kd_rek7) {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('input_lamp_neraca.cari_rek7') }}",
            type: "POST", 
            data: {
                rek6: rek6
            }, 
            dataType: 'json',
            success: function(data) {
                $('#rek7').empty();
                $('#rek7').append(
                    `<option value="" disabled selected>Pilih Rekening Rinci</option>`);
                $.each(data, function(index, data) {
                    if (data.kd_rek7 == kd_rek7) {
                        $('#rek7').append(
                            `<option value="${data.kd_rek7}" data-nama="${data.nm_rek7}" selected>${data.kd_rek7} | ${data.nm_rek7}</option>`
                        );
                    } else {
                        $('#rek7').append(
                            `<option value="${data.kd_rek7}" data-nama="${data.nm_rek7}">${data.kd_rek7} | ${data.nm_rek7}</option>`
                        );
                    }
                })
            }
        })
    }

    function edit(no_lamp, kd_rek3, nm_rek3, kd_rek5, nm_rek5, kd_rek6, nm_rek6, tahun, merk, no_polisi, fungsi, hukum, lokasi, alamat, sert, luas, satuan, harga_satuan, piutang_awal, piutang_koreksi, piutang_sudah, investasi_awal, sal_awal, kurang, tambah, tahun_n, akhir, kondisi_b, kondisi_rr, kondisi_rb, keterangan, kd_skpd, jumlah, kepemilikan, rincian_beban, jenis_aset, realisasi_janji, nama_perusahaan, no_polis, tgl_awal, tgl_akhir, jam, bulan, masa, tmasa, korplus, kormin, akum_penyu, sisa_umur, status, akum_penyub, kondisi_x, nil_kurang_excomp, status_extracomp, sekolah, kd_rek7, nm_rek7, jenis) {

        status_input = 'edit';
        $('#status_input').val(status_input);
        $('#nomor').val(no_lamp);
        $('#no_simpan').val(no_lamp);
        $('#rek3').val(kd_rek3).change();
        tampil_rek3(kd_rek3);
        $('#nm_rek3').val(nm_rek3);
        cari_rek5(kd_rek3,kd_rek5);
        $('#rek5').val(kd_rek5).change();
        $('#nm_rek5').val(nm_rek5);
        cari_rek6(kd_rek5,kd_rek6);
        $('#rek6').val(kd_rek6).change();
        $('#nm_rek6').val(nm_rek6);
        cari_rek7(kd_rek6,kd_rek7);
        $('#rek7').val(kd_rek7).change();
        $('#nm_rek7').val(nm_rek7);
        $('#tahun').val(tahun).change();
        $('#merk').val(merk);
        $('#no_polisi').val(no_polisi);
        $('#fungsi').val(fungsi);
        $('#hukum').val(hukum);
        $('#lokasi').val(lokasi).change();
        $('#alamat').val(alamat);
        $('#sert').val(sert);
        $('#luas').val(luas);
        $('#satuan').val(satuan);
        $('#harga_satuan').val(harga_satuan);
        $('#piutang_awal').val(piutang_awal);
        $('#piutang_koreksi').val(piutang_koreksi);
        $('#piutang_sudah').val(piutang_sudah);
        $('#investasi_awal').val(investasi_awal);
        $('#sal_awal').val(sal_awal);
        $('#kurang').val(kurang);
        $('#tambah').val(tambah);
        $('#tahun_n').val(tahun_n);
        $('#akhir').val(akhir);
        $('#kondisi_b').val(kondisi_b);
        $('#kondisi_rr').val(kondisi_rr);
        $('#kondisi_rb').val(kondisi_rb);
        $('#keterangan').val(keterangan);
        $('#kd_skpd').val(kd_skpd);
        $('#jumlah').val(jumlah);
        $('#kepemilikan').val(kepemilikan);
        $('#rincian_bebas').val(rincian_beban);
        $('#jenis_aset').val(jenis_aset);
        $('#realisasi_janji').val(realisasi_janji);
        $('#nama_perusahaan').val(nama_perusahaan);
        $('#no_polis').val(no_polis);
        $('#tgl_awal').val(tgl_awal);
        $('#tgl_akhir').val(tgl_akhir);
        $('#jam').val(jam);
        $('#bulan').val(bulan);
        $('#masa').val(masa);
        $('#tmasa').val(tmasa);
        $('#korplus').val(korplus);
        $('#kormin').val(kormin);
        $('#akum_penyu').val(akum_penyu);
        $('#sisa_umur').val(sisa_umur);
        $('#status_aset').val(status).change();
        $('#akum_penyub').val(akum_penyub);
        $('#kondisi_x').val(kondisi_x);
        $('#nil_kurang_excomp').val(nil_kurang_excomp);
        $('#status_extracomp').val(status_extracomp);
        $('#sekolah').val(sekolah);
        $('#kd_rek7').val(kd_rek7);
        $('#nm_rek7').val(nm_rek7);
        $('#jenis').val(jenis);


        
        $('#modal_input_lamp_neraca').modal('show');
    }

    function hapus(no_lamp, kd_rek3, nm_rek3, kd_rek5, nm_rek5, kd_rek6, nm_rek6, tahun, merk, no_polisi, fungsi, hukum, lokasi, alamat, sert, luas, satuan, harga_satuan, piutang_awal, piutang_koreksi, piutang_sudah, investasi_awal, sal_awal, kurang, tambah, tahun_n, akhir, kondisi_b, kondisi_rr, kondisi_rb, keterangan, kd_skpd, jumlah, kepemilikan, rincian_beban, jenis_aset, realisasi_janji, nama_perusahaan, no_polis, tgl_awal, tgl_akhir, jam, bulan, masa, tmasa, korplus, kormin, akum_penyu, sisa_umur, status, akum_penyub, kondisi_x, nil_kurang_excomp, status_extracomp, sekolah, kd_rek7, nm_rek7, jenis) {
        var nomor       = no_lamp;
        var status_aset = status;

        if ( status_aset =='1' ){
            alert('Data Audited Tahun Lalu tidak boleh dihapus. Hubungi Verifikator Bidang Akuntansi jika Ingin Menghapus.');
            return;
        }
        
        var urll= "{{ route('input_lamp_neraca.cari_hapus_lamp_aset') }}";                           
        if (nomor !=''){
            var del=confirm('Anda yakin akan menghapus ?');
            if  (del==true){
                $(document).ready(function(){
                    $.ajax({
                        type     : "POST",
                        url      : "{{ route('input_lamp_neraca.cari_hapus_lamp_aset') }}",
                        data     : ({no:nomor}),
                        dataType : "json",
                        success  : function(data){                    
                            status_hapus = data.pesan;
                            alert(status_hapus); 
                            if ( status_hapus=='1' ){
                                alert('Data Terhapus...!!!');
                                let list_table = $('#list_lamp_aset').DataTable();
                                list_table.ajax.reload();
                                return;
                            }
                            
                            if ( status_hapus=='0' ){
                                alert('Gagal Terhapus...!!!');
                                let list_table = $('#list_lamp_aset').DataTable();
                                list_table.ajax.reload();
                                return;
                            }  
                                
                        }
                    });
                });             
            }
        } 
    }



    function formatangka(objek) {
        a = objek.value;
        b = a.replace(/\$|\,/g,"");
        c = "";
        panjang = b.length;
        j = 1;
        for (i = panjang; i > 0; i--) {
        j = j + 1;
        if (((j % 3) == 1) && (j != 1))
        {c = b.substr(i-1,1) + "," + c;} 
        else 
        {c = b.substr(i-1,1) + c;}
        }
        //objek.value = trimNumber(c);
                return c;

    }
    function replaceChars(entry) {
        out = "."; // replace this
        add = ""; // with this
        temp = "" + entry; // temporary holder
        while (temp.indexOf(out)>-1) {
        pos= temp.indexOf(out);
        temp = "" + (temp.substring(0, pos) + add + 
        temp.substring((pos + out.length), temp.length));
        }
        document.f.uang.value = temp;
    }

    function trimNumber(s) {
        decimal=false;
        while (s.substr(0,1) == '0' && s.length>1) { s = s.substr(1,9999); }
        while (s.substr(0,1) == '.' && s.length>1) { s = s.substr(1,9999); }
        return s;
    }

    function hitung_saldo_awal(){
        
        let jumlah = document.getElementById('jumlah').value;
        if(jumlah==''){
            jumlah=0;
        } else {
            jumlah=jumlah;
        }
        let jum=jumlah;
        
        let harga_satuan = angka(document.getElementById('harga_satuan').value);
        if(harga_satuan==''){
            harga_satuan=0;
        } else {
            harga_satuan=harga_satuan;
        }
        let hrg_satuan=harga_satuan;
        saldo_awal=jum*hrg_satuan;

        $("#harga_awal").val(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(saldo_awal))
    }

    function hitung_asuransi(){
        var rek5            = $("#rek5").val();
        // alert(rek5);
        // return;
        var w               = document.getElementById('sal_awal').value;
        var rj              = document.getElementById('realisasi_janji').value;
        var np              = document.getElementById('nama_perusahaan').value;
        var jj              = document.getElementById('no_polis').value;
        var tgl_awal        = $('#tgl_awal').val();
        var tgl_akhir       = $('#tgl_akhir').val();
        var jam             = $('#jam').val();
        var kk              = $("#status_aset").val();
        var jns_beban_sewa  = $("#asuransi").val();


        
        if (rj==''){
            rj=0;
        }else{
            rj=angka(rj);
        }
        
        if (w==''){
            w=0;
        }else{
            w=angka(w);
        }
        
        var ta              = "{{ tahun_anggaran() }}";
        var ta_awal         = ta-1;
        var tgl_ta_awal     = ta_awal+'-12-31';
        var tgl_ta          = ta+'-12-31';
        var tgl_next_ta     = ta+'-01-01';
        var hjam_std        = 12;
        var mjam_std        = 0;
        
        var h_jam           = jam.substring(0,2);
        var m_jam           = jam.substring(3,5);
        
        var jam_std         = ((hjam_std-h_jam)*3600)+((mjam_std-m_jam)*60)
        // alert(jam_std);
        var tahun_tgl_awal         = tgl_awal.substring(0,4);
        var tahun_tgl_akhir        = tgl_akhir.substring(0,4);
        
        var tgl_awal1       = new Date(tgl_awal);
        var tgl_akhir1      = new Date(tgl_akhir);
        var tgl_ta_awal1    = new Date(tgl_ta_awal);
        var tgl_ta1         = new Date(tgl_ta);
        var tgl_next_ta1    = new Date(tgl_next_ta);
        
        var tgl_awal2       = Date.parse(tgl_awal1);
        var tgl_akhir2      = Date.parse(tgl_akhir1);
        var tgl_ta_awal2    = Date.parse(tgl_ta_awal1);
        var tgl_ta2         = Date.parse(tgl_ta1);
        

        if (ta==tahun_tgl_awal) {
            var sisa_bulan_ta_y    = (tgl_ta1.getFullYear() - tgl_awal1.getFullYear()) * 12;  //tahun
            var sisa_bulan_ta_m    = (tgl_ta1.getMonth() - tgl_awal1.getMonth());             //bulan
            var sisa_bulan_ta      = (sisa_bulan_ta_y + sisa_bulan_ta_m);                 //sisa bulan tahun anggaran



            var jumlah_bulan_all_y    = (tgl_akhir1.getFullYear() - tgl_awal1.getFullYear()) * 12;  
            var jumlah_bulan_all_m    = (tgl_akhir1.getMonth() - tgl_awal1.getMonth());  
            var jumlah_bulan_all      = (jumlah_bulan_all_y + jumlah_bulan_all_m);  

             if (tgl_awal1.getDate() <= 15) {
                tambah_bulan = 1;
             }else{
                tambah_bulan=0;
             } 

             if (jumlah_bulan_all==11){
                jumlah_bulan_all1= jumlah_bulan_all+1;
             }else{
                jumlah_bulan_all1= jumlah_bulan_all+0;
             }
             
             var beban_sewa = (sisa_bulan_ta + tambah_bulan)/jumlah_bulan_all1*rj;

        }else if (ta>tahun_tgl_awal && ta<tahun_tgl_akhir) {
            var sisa_bulan_ta_y    = (tgl_ta1.getFullYear() - tgl_next_ta1.getFullYear()) * 12;  //tahun
            var sisa_bulan_ta_m    = (tgl_ta1.getMonth() - tgl_next_ta1.getMonth());             //bulan
            var sisa_bulan_ta      = (sisa_bulan_ta_y + sisa_bulan_ta_m);                       //sisa bulan tahun anggaran

            var jumlah_bulan_all_y    = (tgl_akhir1.getFullYear() - tgl_next_ta1.getFullYear()) * 12;  
            var jumlah_bulan_all_m    = (tgl_akhir1.getMonth() - tgl_next_ta1.getMonth());  
            var jumlah_bulan_all      = (jumlah_bulan_all_y + jumlah_bulan_all_m);  

             if (tgl_next_ta1.getDate() <= 15) {
                tambah_bulan = 1;
             }else{
                tambah_bulan=0;
             } 

             if (jumlah_bulan_all==11){
                jumlah_bulan_all1= jumlah_bulan_all+1;
             }else{
                jumlah_bulan_all1= jumlah_bulan_all+0;
             }

             var beban_sewa = (sisa_bulan_ta + tambah_bulan)/jumlah_bulan_all1*rj;
        }else{
            var beban_sewa  = w;  
        }


        var jml_hari        = (tgl_akhir2-tgl_awal2)/86400000;
        var sisa_ta         = (tgl_ta2-tgl_awal2)/86400000;
        var sisa_pertahun   = (tgl_ta2-tgl_ta_awal2)/86400000;
        var sisa_akhir      = (tgl_akhir2-tgl_ta_awal2)/86400000;
        var sisa_hari       = jml_hari-sisa_ta;
        var nilai_hari      = rj/jml_hari;
        var nilai_stg_hari  = nilai_hari/2;
 
        var nilai_pertahun  = nilai_hari*sisa_pertahun;
        var nilai_total_n   = nilai_hari*(sisa_hari-1);
        var nilai_total_k   = nilai_hari*sisa_akhir;
        
  
        // alert(rj);
        if(jam_std<=0 && ta==tahun_tgl_awal && tahun_tgl_akhir>ta){
            nilai_bersih_n=nilai_total_n-nilai_stg_hari;
        } else if (jam_std>0 && ta==tahun_tgl_awal && tahun_tgl_akhir>ta){
            nilai_bersih_n=nilai_total_n;
        } else {
            nilai_bersih_n=0;
        }

        
        /*if(ta>ta_awal && ta<ta_akhir){
            nilai_bersih_k=nilai_pertahun;
        } else if (jam_std>0 && ta==ta_akhir){
            nilai_bersih_k=nilai_total_k+nilai_stg_hari;
        } else if (jam_std<=0 && ta==ta_akhir){
            nilai_bersih_k=nilai_total_k;
        } else {
            nilai_bersih_k=0;
        }*/
        
        if(ta>tahun_tgl_awal && ta<tahun_tgl_akhir){              //untuk asuransi beban jasa dibayar dimuka
            nilai_bersih_k=nilai_pertahun;
        } else if (ta==tahun_tgl_akhir){
            nilai_bersih_k=w;
        } else {
            nilai_bersih_k=0;
        }
  

        
        //------------------------------------------beban---------------------------------//
         if(ta==tahun_tgl_awal){
            beban_sewa_n=rj-beban_sewa;
        } else {
            beban_sewa_n=0;
        }





        if(ta>tahun_tgl_awal && ta<tahun_tgl_akhir){               //untuk beban sewa 
            beban_sewa_k=beban_sewa;
        } else if (ta==tahun_tgl_akhir){
            beban_sewa_k=w;
        } else {
            beban_sewa_k=0;
        }

 
        //---------------------------------------------------------------------------------//

        //alert(sisa_hari);     
        //document.getElementById('tahun_n').disabled=false;
        
        /*if(rek5=='1160302'){
            $("#tahun_n").attr("Value",number_format(beban_sewa_n,2,'.',','));        
            $("#kurang").attr("Value",number_format(beban_sewa_k,2,'.',',')); 
        }else{

            $("#tahun_n").attr("Value",number_format(nilai_bersih_n,2,'.',','));        
            $("#kurang").attr("Value",number_format(nilai_bersih_k,2,'.',',')); 

        } */



        if(jns_beban_sewa=='sewa'){
            $("#tahun_n").val(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(beban_sewa_n));        
            $("#kurang").val(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(beban_sewa_k)); 
        }else{

            $("#tahun_n").val(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(nilai_bersih_n));        
            $("#kurang").val(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(nilai_bersih_k)); 

        }        
        // alert(nilai_bersih_n);
        
        //document.getElementById('tahun_n').value=nilai_bersih_n;
        //document.getElementById('kurang').value=nilai_bersih_k;
    }
     
    function hitung_piutang_koreksi(){
        
        var piutang_awal = angka(document.getElementById('piutang_awal').value);
        if(piutang_awal==''){
            piutang_awal=0;
        } else {
            piutang_awal=piutang_awal;
        }
        var piutang=piutang_awal;
        
        var piutang_koreksi = angka(document.getElementById('piutang_koreksi').value);
        if(piutang_koreksi==''){
            piutang_koreksi=0;
        } else {
            piutang_koreksi=piutang_koreksi;
        }
        var koreksi=piutang_koreksi;
        piutang_sesudah=piutang-koreksi;
        $("#piutang_sudah").val(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(piutang_sesudah))  
    }

    function hitung_pendapatan(){
        
        // var saldo_awal  = document.getElementById('sal_awal').value;
        // var sal_awal    = saldo_awal.replace(/,/gi, "");
        var tahun_oleh0 = $('#tahun').val();
        var tgl_awal    = $('#tgl_awal').val();
        var tgl_akhir   = $('#tgl_akhir').val();
        var rj          = document.getElementById('realisasi_janji').value;
        var real_janji    = rj.replace(/,/gi, "");


        if (tgl_awal.substring(8,10)>15) {
            tgl_kur = 1;
        }
        else{
            tgl_kur = 0;
        }

        var bln_awal = tgl_awal.substring(5, 7);
        var bln_akhir = tgl_akhir.substring(5, 7);

        if (bln_awal.substring(0,1)==0) {
            bln_awal = bln_awal.substring(1,2);
        }
        if (bln_akhir.substring(0,1)==0) {
            bln_akhir = bln_akhir.substring(1,2);
        }

        var thn_awal = tgl_awal.substring(0,4);
        var thn_akhir = tgl_akhir.substring(0,4);

        var nawal = parseInt(bln_akhir) + parseInt(tgl_kur);
        var nakhir = (bln_akhir-1)+tgl_kur;

        var blnx = bln_awal-bln_akhir+tgl_kur;

        var blnd = bln_awal-12+tgl_kur;

        var thnx = thn_akhir-thn_awal;

        var thnd = tahun_oleh0-thn_awal;

        var jbulan = (12*thnx)-blnx;

        var jblndana = (12*thnd)-blnd;

        var perbulan = real_janji/jbulan; 

        var blno = 13-nawal;

        var dblna = perbulan*(13-bln_awal);

        var bln_sal= jblndana-11;

        if (tahun_oleh0==thn_awal) {
            var nilai = blno*perbulan;
            var dana = real_janji-dblna;
            var sal_awal = real_janji;
            
        } else if(tahun_oleh0==thn_akhir){
            var nilai = nakhir*perbulan;
            // var dana = perbulan*jbulan;
            var danan = perbulan*bln_sal;
            var dana = 0;
            var sal_awal = real_janji-danan;
        } else{
            var nilai = 12*perbulan;
            var danan = perbulan*bln_sal;
            var dana = 0;
            var sal_awal = real_janji-danan;
        }

        kurangi= rupiah(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(nilai));
        tahun_ni = rupiah(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(dana));
        sal_awali = rupiah(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(sal_awal));

        $("#kurang").val(kurangi);
        $("#tahun_n").val(tahun_ni);
        $("#sal_awal").val(sal_awali);
        // $("#keterangan").attr("Value",blnd);
    }

    function hsimpan(){
        var status_input        = document.getElementById('status_input').value;
        var nomor               = document.getElementById('nomor').value;
        var no_simpan           = document.getElementById('no_simpan').value;
        var rek3                = document.getElementById('rek3').value;
        let rek3n                = $('#rek3').find('option:selected');
        let nm_rek3             = rek3n.data('nama');
        // var nm_rek3             = document.getElementById('nm_rek3').value; 
        var rek5                = document.getElementById('rek5').value;
        // var nm_rek5             = document.getElementById('nm_rek5').value;
        let rek5n                = $('#rek5').find('option:selected');
        let nm_rek5             = rek5n.data('nama');
        var rek6                = document.getElementById('rek6').value;
        // var nm_rek6             = document.getElementById('nm_rek6').value;
        let rek6n                = $('#rek6').find('option:selected');
        let nm_rek6             = rek6n.data('nama');
        var rk7                = document.getElementById('rek7').value;
        // var nm_rek7             = document.getElementById('nm_rek7').value;
        let rek7n                = $('#rek7').find('option:selected');
        let nm7             = rek7n.data('nama');
        var tahun               = document.getElementById('tahun').value;
        var bulan               = document.getElementById('bulan').value;
        var masa                = document.getElementById('masa').value;        
        var tmasa               = document.getElementById('tmasa').value;   
        var merk                = document.getElementById('merk').value;
        var no_polis            = document.getElementById('no_polis').value;
        var fungsi              = document.getElementById('fungsi').value;
        var hukum               = document.getElementById('hukum').value;
        var lokasi              = document.getElementById('lokasi').value;
        var sekolah             = document.getElementById('sekolah').value;
        var alamat              = document.getElementById('alamat').value;
        var sert                = document.getElementById('sert').value;
        var luas                = document.getElementById('luas').value;
        var satuan              = document.getElementById('satuan').value;
        var harga_satuan        = document.getElementById('harga_satuan').value;
        var piutang_awal        = document.getElementById('piutang_awal').value;
        var piutang_koreksi     = document.getElementById('piutang_koreksi').value;
        var piutang_sudah       = document.getElementById('piutang_sudah').value;
        var investasi_awal        = document.getElementById('investasi_awal').value;
        var sal_awal            = document.getElementById('sal_awal').value;
        var kurang              = document.getElementById('kurang').value;
        var bertambah           = document.getElementById('bertambah').value;
        var tahun_n       = document.getElementById('tahun_n').value;
        var sisa_umur     = document.getElementById('sisa_umur').value;
        var akum_penyu    = document.getElementById('akum_penyu').value;
        var akum_penyub    = document.getElementById('akum_penyub').value;
        var korplus    = document.getElementById('korplus').value;
        /*alert(plus);*/
        var kormin     = document.getElementById('kormin').value;
        /*alert(min);*/
        var aa       = 0;
        var kondisi_b       = document.getElementById('kondisi_b').value;
        var kondisi_rr       = document.getElementById('kondisi_rr').value;
        var kondisi_rb       = document.getElementById('kondisi_rb').value;
        var keterangan       = document.getElementById('keterangan').value;
        var dn       = "{{ $data_skpd->kd_skpd }}"
        var jumlah       = document.getElementById('jumlah').value;
        var milik       = document.getElementById('milik').value;
        var rincian_bebas       = document.getElementById('rincian_bebas').value;
        var jenis_aset       = document.getElementById('jenis_aset').value;
        var realisasi_janji       = document.getElementById('realisasi_janji').value;
        var nama_perusahaan       = document.getElementById('nama_perusahaan').value;
        var no_polisi       = document.getElementById('no_polisi').value;
        var tgl_awal = document.getElementById('tgl_awal').value;
        var tgl_akhir = document.getElementById('tgl_akhir').value;
        var jam       = document.getElementById('jam').value;
        var status_aset       = document.getElementById('status_aset').value;
        var kondisi_x       = document.getElementById('kondisi_x').value;
        var nil_kurang_excomp       = document.getElementById('nil_kurang_excomp').value;

        // alert(kurang);
        //     return;

        if ( rek3.length!=4 ){
            alert("Pastikan Rek. Kelompok diisi dengan Benar") ;
            return;
        }
        
        if ( rek5.length!=8 ){
            alert("Pastikan Rekening diisi dengan Benar") ;
            return;
        }
                
        if ( rek6.length <12){
            alert("Pastikan Rekening Rinci diisi dengan Benar") ;
            return;
        }
        
        if ( tmasa == '' && (rek3 == 1303) ){
            alert("Isi Jenis Aset Terlebih Dahulu") ;
            return;
        }

        if (nil_kurang_excomp==''){
            nil_kurang_excomp=0;
        }else{
            nil_kurang_excomp=angka(nil_kurang_excomp);
        }

        if (luas==''){
            luas=0;
        }else{
            luas=angka(luas);
        }
        
        if (harga_satuan==''){
            harga_satuan=0;
        }else{
            harga_satuan=angka(harga_satuan);
        }
        
        if (piutang_awal==''){
            piutang_awal=0;
        }else{
            piutang_awal=angka(piutang_awal);
        }
        if (piutang_koreksi==''){
            piutang_koreksi=0;
        }else{
            piutang_koreksi=angka(piutang_koreksi);
        }
        if (piutang_sudah==''){
            piutang_sudah=0;
        }else{
            piutang_sudah=angka(piutang_sudah);
        }
        if (investasi_awal==''){
            investasi_awal=0;
        }else{
            investasi_awal=angka(investasi_awal);
        }
        if (sal_awal==''){
            sal_awal=0;
        }else{
            sal_awal=angka(sal_awal);
        }
        if (kurang==''){
            kurang=0;
        }else{
            kurang=angka(kurang);
        }
        
        if (bertambah==''){
            bertambah=0;
        }else{
            bertambah=angka(bertambah);
        }
        
        if (tahun_n==''){
            tahun_n=0;
        }else{
            tahun_n=rupiah(tahun_n);
        }
        if (korplus==''){
            korplus=0;
        }else{
            korplus=angka(korplus);
        }
        if (kormin==''){
            kormin=0;
        }else{
            kormin=angka(kormin);
        }
        if (jumlah==''){
            jumlah=0;
        }else{
            jumlah=angka(jumlah);
        }
        
        if (kondisi_b==''){
            kondisi_b=0;
        }else{
            kondisi_b=angka(kondisi_b);
        }
        if (kondisi_rr==''){
            kondisi_rr=0;
        }else{
            kondisi_rr=angka(kondisi_rr);
        }
        
        if (kondisi_rb==''){
            kondisi_rb=0;
        }else{
            kondisi_rb=angka(kondisi_rb);
        }
        
        if (kondisi_x==''){
            kondisi_x=0;
        }else{
            kondisi_x=angka(kondisi_x);
        }
        
        if (masa==''){
            masa=0;
        }else{
            masa=angka(masa);
        }
        
        if (tmasa==''){
            tmasa=0;
        }else{
            tmasa=angka(tmasa);
        }
        
        if (sisa_umur==''){
            sisa_umur=0;
        }else{
            sisa_umur=angka(sisa_umur);
        }
        if (akum_penyu==''){
            akum_penyu=0;
        }else{
            akum_penyu=angka(akum_penyu);
        }
        if (akum_penyub==''){
            akum_penyub=0;
        }else{
            akum_penyub=angka(akum_penyub);
        }
        
        if (realisasi_janji==''){
            realisasi_janji=0;
        }else{
            realisasi_janji=angka(realisasi_janji);
        }
        
        if ( nomor == '' ){
            alert("Isi Nomor Terlebih Dahulu") ;
            return;
        }
         if ( tahun == '' ){
            alert("Isi Tahun Terlebih Dahulu") ;
            return;
        }
        if ( status_aset == '' ){
            alert("Pilih Status Terlebih Dahulu") ;
            return;
        }
        
        if ( tgl_awal == '' && (rek3 == 1111) ){
            alert("Isi Tanggal Awal Perjanjian Terlebih Dahulu") ;
            return;
        }
        
        if ( tgl_akhir == '' && (rek3 == 1111) ){
            alert("Isi Tanggal Akhir Perjanjian Terlebih Dahulu") ;
            return;
        }
        
        if ( jam == '' && (rek3 == 1111) ){
            alert("Isi Waktu Perjanjian Terlebih Dahulu") ;
            return;
        }
                
        if ( bulan == '' ){
            bulan=0;
        }else{
            bulan=angka(bulan);
        }   
        



        if (jumlah != (kondisi_b+kondisi_rr+kondisi_rb+kondisi_x) && ((rek3 == 1301) || (rek3 == 1501) ||(rek3 == 1502) ||(rek3 == 1503) ||(rek3 == 1504) ||(rek3 == 1306) ||(rek3 == 1305) ||(rek3 == 1304) ||(rek3 == 1303) 
            ||(rek3 == 1112) ||(rek3 == 1302))){
            alert('Jumlah Barang tidak sesuai dengan Jumlah Kondisi Barang');
            return;
        } 
        
        alert(sal_awal);

        if(status_input == "tambah"){
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:nomor,tabel:"lamp_aset",field:"no_lamp"}),
                    url: "{{ route('input_lamp_neraca.cari_cek_simpan') }}",
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                        alert("Nomor Telah Dipakai!");
                        return;
                        } 
                        if(status_cek==0){
                            alert("Nomor Bisa dipakai");
        
                            //---------
                            lcinsert = "(no_lamp, kd_rek3, nm_rek3, kd_rek5, nm_rek5, kd_rek6, nm_rek6, tahun, bulan, masa, tmasa, merk, no_polisi, fungsi, hukum, lokasi, alamat, sert, luas, satuan, harga_satuan, piutang_awal, piutang_koreksi, piutang_sudah, investasi_awal, sal_awal, kurang, tambah, tahun_n, sisa_umur, akum_penyu, akum_penyub, akhir, korplus, kormin, kondisi_b, kondisi_rr, kondisi_rb, keterangan,kd_skpd,jumlah,kepemilikan,rincian_beban,jenis_aset,realisasi_janji,nama_perusahaan,no_polis,tgl_awal,tgl_akhir,jam,status,kondisi_x,nil_kurang_excomp,sekolah,kd_rek7,nm_rek7)"; 
                            lcvalues = "('"+nomor+"', '"+rek3+"', '"+nm_rek3+"', '"+rek5+"', '"+nm_rek5+"', '"+rek6+"', '"+nm_rek6+"','"+tahun+"','"+bulan+"','"+masa+"','"+tmasa+"','"+merk+"','"+no_polisi+"','"+fungsi+"','"+hukum+"','"+lokasi+"','"+alamat+"','"+sert+"','"+luas+"','"+satuan+"','"+harga_satuan+"','"+piutang_awal+"','"+piutang_koreksi+"','"+piutang_sudah+"','"+investasi_awal+"','"+sal_awal+"','"+kurang+"','"+bertambah+"','"+tahun_n+"','"+sisa_umur+"','"+akum_penyu+"','"+akum_penyub+"','"+aa+"','"+korplus+"','"+kormin+"','"+kondisi_b+"','"+kondisi_rr+"','"+kondisi_rb+"','"+keterangan+"','"+dn+"','"+jumlah+"','"+milik+"','"+rincian_bebas+"', '"+jenis_aset+"', '"+realisasi_janji+"', '"+nama_perusahaan+"', '"+no_polis+"', '"+tgl_awal+"', '"+tgl_akhir+"', '"+jam+"', '"+status_aset+"', '"+kondisi_x+"', '"+nil_kurang_excomp+"', '"+sekolah+"', '"+rk7+"', '"+nm7+"')";
                            $(document).ready(function(){
                                $.ajax({
                                    type     : "POST",
                                    url      : "{{ route('input_lamp_neraca.cari_simpan_lamp_aset') }}",
                                    data     : ({tabel:'lamp_aset',kolom:lcinsert,nilai:lcvalues,cid:'no_lamp',lcid:nomor}),
                                    dataType : "json",
                                    success  : function(data){
                                        status = data;
                                        if (status=='0'){
                                            alert('Gagal Simpan..!!');
                                            return;
                                        } else if(status=='1'){
                                            alert('Data Sudah Ada..!!');
                                            return;
                                        } else {
                                            alert('Data Tersimpan..!!');
                                            $("#no_simpan").attr("value",nomor);
                                            status_input = 'edit';
                                            return;
                                       }
                                    }
                                });
                            });   
                      
                            //----------
                    
                        }
                    }
                });
            });
        } else {
            //alert(z);
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:nomor,tabel:'lamp_aset',field:'no_lamp'}),
                    url: "{{ route('input_lamp_neraca.cari_cek_simpan') }}",
                    success:function(data){
                        status_cek = data.pesan;
                        if(status_cek==0 && nomor!=no_simpan){
                        alert("Nomor Tidak Tersedia!");
                        return;
                        } 
                        if(status_cek==1 || nomor!=no_simpan){
                            alert("Nomor Bisa dipakai");


                                //---------
                            lcquery = " UPDATE lamp_aset SET no_lamp ='"+nomor+"', kd_rek3='"+rek3+"', nm_rek3='"+nm_rek3+"', kd_rek5='"+rek5+"', nm_rek5='"+nm_rek5+"', kd_rek6='"+rek6+"', nm_rek6='"+nm_rek6+"', tahun='"+tahun+"', bulan='"+bulan+"', masa='"+masa+"', tmasa='"+tmasa+"', merk='"+merk+"', no_polisi='"+no_polisi+"', fungsi='"+rek6+"', hukum='"+hukum+"', lokasi='"+lokasi+"', alamat='"+alamat+"', sert='"+sert+"', luas='"+luas+"', satuan='"+satuan+"', harga_satuan='"+harga_satuan+"', piutang_awal='"+piutang_awal+"', piutang_koreksi='"+piutang_koreksi+"', piutang_sudah='"+piutang_sudah+"', investasi_awal='"+investasi_awal+"', sal_awal='"+sal_awal+"', kurang='"+kurang+"', tambah='"+bertambah+"', tahun_n='"+tahun_n+"', sisa_umur='"+sisa_umur+"', akum_penyu='"+akum_penyu+"', akum_penyub='"+akum_penyub+"', korplus='"+korplus+"', kormin='"+kormin+"', kondisi_b='"+kondisi_b+"', kondisi_rr='"+kondisi_rr+"', kondisi_rb='"+kondisi_rb+"', keterangan='"+keterangan+"',kd_skpd ='"+dn+"',jumlah ='"+jumlah+"',kepemilikan ='"+milik+"',rincian_beban ='"+rincian_bebas+"',jenis_aset ='"+jenis_aset+"',realisasi_janji ='"+realisasi_janji+"',nama_perusahaan ='"+nama_perusahaan+"',no_polis ='"+no_polis+"',tgl_awal ='"+tgl_awal+"',tgl_akhir ='"+tgl_akhir+"',jam ='"+jam+"',status ='"+status_aset+"',kondisi_x ='"+kondisi_x+"',nil_kurang_excomp ='"+nil_kurang_excomp+"',sekolah ='"+sekolah+"',kd_rek7 ='"+rk7+"',nm_rek7 ='"+nm7+"' where no_lamp='"+no_simpan+"' AND kd_skpd ='"+dn+"' "; 

                            //          alert(lcquery);
                            //return;
                            $(document).ready(function(){
                                $.ajax({
                                    type     : "POST",
                                    url      : "{{ route('input_lamp_neraca.cari_update_lamp_aset') }}",
                                    data     : ({st_query:lcquery,tabel:'lamp_aset',cid:'no_lamp',lcid:nomor,lcid_h:no_simpan}),
                                    dataType : "json",
                                    success  : function(data){
                                        status=data ;
                                                                        
                                        if ( status=='1' ){
                                            //alert("aaaa");
                                            alert('Nomor  Sudah Terpakai...!!!,  Ganti Nomor ...!!!');
                                            let list_table = $('#list_lamp_aset').DataTable();
                                            list_table.ajax.reload();
                                            return;
                                        }
                                        
                                        if ( status=='2' ){
                                            alert('Data Tersimpan...!!!');
                                            status_input = 'edit';
                                            $("#no_simpan").attr("value",nomor);
                                            let list_table = $('#list_lamp_aset').DataTable();
                                            list_table.ajax.reload();
                                            return;
                                        }
                                        
                                        if ( status=='0' ){
                                            alert('Gagal Simpan...!!!');
                                            let list_table = $('#list_lamp_aset').DataTable();
                                            list_table.ajax.reload();

                                            return;
                                        }
                                            
                                    }
                                });
                            });
                        }
                    }
                });
            });
        
        }
        
    }
</script>
@endsection
