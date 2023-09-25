@extends('template.app')
@section('title', 'INPUT KAPITALISASI | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Input Jurnal Umum
                </div>
                <div class="card-body">
                    @csrf
                    {{-- SKPD DAN NAMA SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="kd_skpd" name="kd_skpd" required readonly
                                value="{{ $kd_skpd }}">
                        </div>
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" id="nm_skpd" name="nm_skpd" required readonly
                                value="{{ nama_skpd($kd_skpd) }}">
                        </div>
                    </div>
                    
                    <div class="mb-3 row" id="pilihan_mutasi">
                        <label for="mutasi" class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="sub_kegiatan"
                                name="mutasi">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($sub_kegiatan as $sub_kegiatan)
                                    <option value="{{ $sub_kegiatan->kd_sub_kegiatan }}" data-nama="{{ $sub_kegiatan->nm_sub_kegiatan }}">
                                        {{ $sub_kegiatan->kd_sub_kegiatan }} | {{ $sub_kegiatan->nm_sub_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div class="mb-6 row" style="text-align;center">
                        <div class="col-md-12" style="text-align: center">
                            <button id="simpan" class="btn btn-primary btn-md">Simpan</button>
                            <a href="{{ route('input_jurnal.index') }}" class="btn btn-warning btn-md">Kembali</a>
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
        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });
        cari_rek3();


        $('#list_kapit').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [5, 10],
            ajax: {
                "url": "{{ route('input_kapitalisasi.load') }}",
                "type": "POST",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                },
                {
                    data: 'nm_sub_kegiatan',
                    name: 'nm_sub_kegiatan',
                }, 
                {
                    data: 'kd_rek6',
                    name: 'kd_rek6',
                    className: "text-center",
                },
                {
                    data: 'nm_rek6',
                    name: 'nm_rek6',
                    className: "text-center",
                },
                {
                    data: null,
                    name: 'anggaran',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.sal_awal)
                    }
                },
                {
                    data: null,
                    name: 'kapitalisasi',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.sal_awal)
                    }
                },
                {
                    data: null,
                    name: 'transaksi',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.sal_awal)
                    }
                },
                {
                    data: 'jenis',
                    name: 'jenis',
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

    $('#sub_kegiatan').on('select2:select', function() {
        let kd_sub_kegiatan = this.value;
        cari_rek5(kd_sub_kegiatan);
        tampil_rek3(rek3);
    });

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
            $("#piutang_awal1").attr('hidden',false);
            $("#piutang_awal0").attr('hidden',false);
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
            $("#piutang_awal1").attr('hidden',false);
            $("#piutang_awal0").attr('hidden',false);
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


</script>
@endsection