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
                            <select class="form-control select2-multiple @error('sub_kegiatan') is-invalid @enderror" style=" width: 150px;" id="sub_kegiatan" name="sub_kegiatan">
                                <option value="" disabled selected>Silahkan Pilih</option>
                            </select>
                            @error('sub_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button id="refresh_kegiatan" class="btn btn-md btn-primary" onclick="javascript:refresh_kegiatan();">Refresh Kegiatan</button>
                    <button id="tambah" class="btn btn-md btn-primary" >Tambah</button>
                    <button id="hitung_kspit_kegiatan" class="btn btn-md btn-primary" onclick="javascript:hitung_kapit_kegiatan();">Hitung Kapitalisasi Perkegiatan</button>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    List Kapitalisasi
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="list_kapit" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">Sub Kegiatan</th>
                                        <th style="width: 50px;text-align:center">Kode Rekening</th>
                                        <th style="width: 50px;text-align:center">Nama Rekening</th>
                                        <th style="width: 50px;text-align:center">Anggaran</th>
                                        <th style="width: 50px;text-align:center">Kapitalisasi</th>
                                        <th style="width: 50px;text-align:center">Transaksi</th>
                                        <th style="width: 50px;text-align:center">Jenis</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    List Rinci Kapitalisasi
                    <input type="text" id="kd_rek6" style="border:0;width: 200px;" readonly />
                    <input type="text" id="kapit_input_rinci" style="border:0;width: 200px;" readonly />
                    <button align="center" id="tambah_rinci" class="btn btn-md btn-primary" style="float: right;" hidden>Tambah</button>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="list_rinci_kapit" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">No lamp Neraca</th>
                                        <th style="width: 50px;text-align:center">Kode Rekening</th>
                                        <th style="width: 50px;text-align:center">Nama Rekening</th>
                                        <th style="width: 50px;text-align:center">Harga Stuan</th>
                                        <th style="width: 50px;text-align:center">nilai</th>
                                        <th style="width: 50px;text-align:center">kapitalisasi</th>
                                        <th style="width: 50px;text-align:center">Sat+Kap</th>
                                        <th style="width: 50px;text-align:center">Nil+Kap</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Nilai Total Rinci Kapitalisasi
                    <button id="hitung_kapit_kegiatan" class="btn btn-md btn-primary" onclick="javascript:hitung_rincian_kapit();">Hitung Rincian Kapitalisasi</button>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="list_tot_rincian" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">Total</th>
                                        <th style="width: 50px;text-align:center">Total Transaksi</th>
                                        <th style="width: 50px;text-align:center">Total Kapitalisasi</th>
                                        <th style="width: 50px;text-align:center">Total Kapitalisasi Rekening</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="tot_rinci" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" readonly /></td>
                                        <td><input type="text" id="tot_trans" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" readonly /></td>
                                        <td><input type="text" id="tot_kapit" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" readonly /></td>
                                        <td><input type="text" id="tot_kapit_rek" style="width: 200px;text-align: right;" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" readonly /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@include('akuntansi.modal.kapit.input.input_kapit')
@include('akuntansi.modal.kapit.input.input_rinci_kapit')
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
        $(".select_tambah_kapit").select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modal_input_kapit .modal-content'),
                
        });
        $(".select_tambah_rinci_kapit").select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modal_input_rinci_kapit .modal-content'),
                
        });
        cari_sub_kegiatan();
        cari_rek3();


        $('#list_kapit').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [10, 20],
            ajax: {
                "url": "{{ route('input_kapitalisasi.load') }}",
                "type": "POST",
                "data": function(d) {
                    d.sub_kegiatan = document.getElementById('sub_kegiatan').value
                }
            },
            columns: [
                {
                    data: 'kd_sub_kegiatan',
                    name: 'kd_sub_kegiatan',
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
                        }).format(data.nil_ang)
                    }
                },
                {
                    data: null,
                    name: 'kapitalisasi',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.kapitalisasi)
                    }
                },
                {
                    data: null,
                    name: 'transaksi',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai_trans)
                    }
                },
                {
                    data: 'jenis',
                    name: 'jenis',
                    className: "text-center",
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: '100px',
                    className: "text-center",
                },
            ],
        });
        
        $('#list_rinci_kapit').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [10, 20],
            ajax: {
                "url": "{{ route('input_kapitalisasi.rinci.load') }}",
                "type": "POST",
                "data":function(d) {
                    d.kd_sub_kegiatan = document.getElementById('sub_kegiatan').value;
                    d.kd_rek6 = document.getElementById('kd_rek6').value;
                }
            },
            columns: [
                {
                    data: 'no_lamp',
                    name: 'no_lamp',
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
                    name: 'harga_satuan',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.harga_satuan)
                    }
                },
                {
                    data: null,
                    name: 'tahun_n',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.tahun_n)
                    }
                },
                {
                    data: null,
                    name: 'nilai',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.nilai)
                    }
                },
                {
                    data: null,
                    name: 'tot_sat_kap',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.tot_sat_kap)
                    }
                },
                {
                    data: null,
                    name: 'tot_kap',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.tot_kap)
                    }
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: '100px',
                    className: "text-center",
                },
            ],
        });

        $('#sub_kegiatan').on('change',function(){
            let sub_kegiatan = this.value;
            let list_table = $('#list_kapit').DataTable();
            cari_kd_rek6_input(sub_kegiatan);
            list_table.ajax.reload()
        });
    });

    $('#tambah').on('click', function() {
        status_input = 'tambah';
        $('#status_input').val(status_input);
        $('#kd_rek6input').val(null).change();

        cari_kd_rek6_input("");
        // tampil_nilai("");
        $('#modal_input_kapit').modal('show');
    });

    $('#tambah_rinci').on('click', function() {
        status_input_rinci = 'tambah';
        $('#status_input_rinci').val(status_input_rinci);
        // $('#no_simpan').val("{{$no_lamp->nomor}}");
        // $('#nomor').val("{{$no_lamp->nomor}}");
        $("#rek3").val(null).change();
        $("#nm_rek3").val(null);
        $("#rek5x").val(null).change();
        $("#rek5").val(null).change();
        $("#nm_rek5").val(null);
        $("#rek6").val(null).change();
        $("#nm_rek6").val(null);
        $("#tahun").val(null);
        $("#bulan").val(null);
        $("#merk").val(null);
        $("#no_polisi").val(null);
        $("#fungsi").val(null);
        $("#hukum").val(null);
        $("#lokasi").val(null);
        $("#alamat").val(null);
        $("#sert").val(null);
        $("#luas").val(null);
        $("#satuan").val(null);
        $("#harga_satuan").val(null);
        $("#piutang_awal").val(null);
        $("#piutang_koreksi").val(null);
        $("#piutang_sudah").val(null);
        $("#sal_awal").val(null);
        $("#investasi_awal").val(null);
        $("#kurang").val(null);
        $("#tambah").val(null);
        $("#tahun_n").val(null);
        $("#kondisi_b").val(null);
        $("#kondisi_rr").val(null);
        $("#kondisi_rb").val(null);
        $("#keterangan").val(null);
        $("#jumlah").val(null);
        $("#milik").val(null);
        $("#rincian_bebas").val(null);
        $("#no_polis").val(null);
        $("#harga_awal").val(null);
        $("#kapit_tot").val(null);
        $("#kapit_rincian").val(null);
        $("#kapit_rincian1").val(null);
        $("#sat_kap").val(null);
        $("#nil_kap").val(null);
        // $("#trans_tot").val(null);
        $("#kapit_tot").val(null);
        var ztot_trans = angka(document.getElementById('tot_trans').value);
        var ztot_rinci = angka(document.getElementById('tot_rinci').value);
        // alert(ztot_rinci);
        var ztahun_n= 0;
        var z_sisa=(ztot_trans-ztot_rinci)+ztahun_n;
        var z_sisa_a = rupiah(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(z_sisa));
        $("#trans_tot").val(z_sisa_a);
        tampil_rek3("");
        load_no_lamp();
        // alert(1);
        $('#modal_input_rinci_kapit').modal('show');
    });

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
            url: "{{ route('kapitalisasi.input.kd_rek3rinci') }}",
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

    function cari_sub_kegiatan() {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('kapitalisasi.sub_kegiatan') }}",
            type: "POST", 
            dataType: 'json',
            success: function(data) {
                $('#sub_kegiatan').empty();
                $('#sub_kegiatan').append(
                    `<option value="" disabled selected>Pilih Rekening Objek</option>`);
                $.each(data, function(index, data) {
                    $('#sub_kegiatan').append(
                        `<option value="${data.kd_sub_kegiatan}" data-nama="${data.nm_sub_kegiatan}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                    );
                })
            }
        })
    }

    function tampil_rek3(kdrek3){
        if (kdrek3==1301){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);
            
        } else if (kdrek3==1112){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);
            
        } else if ((kdrek3==1501) || (kdrek3==1502) || (kdrek3==1503) || (kdrek3==150)){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        }else if (kdrek3==1401){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        }else if (kdrek3==1306) {
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        } else if (kdrek3==1305) {
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true); 

        } else if (kdrek3==1303) {
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        } else if (kdrek3==1304) {
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#jumlah1").attr('hidden',false);
            $("#jumlah0").attr('hidden',false);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        } else if(kdrek3==1302){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

            } else if(kdrek3==3103){
            $("#tahun_oleh1").attr('hidden',false);
            $("#tahun_oleh0").attr('hidden',false);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
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
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        }else if(kdrek3==1107){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        } else if (kdrek3==1106){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);
            
        } else if(kdrek3 ==1104){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);
            
        } else if (kdrek3 == 1103){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);
            
        } else if (kdrek3==1101){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        } else if (kdrek3==1102){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
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
            $("#sal_awal1").attr('hidden',true);
            $("#sal_awal0").attr('hidden',true);
            $("#kurang1").attr('hidden',true);
            $("#kurang0").attr('hidden',true);
            $("#tambah1").attr('hidden',true);
            $("#tambah0").attr('hidden',true);
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',false);
            $("#harga_awal1").attr('hidden',false);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        }else if (kdrek3==8){
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
            $("#merk1").attr('hidden',true);
            $("#merk0").attr('hidden',true);
            $("#milik1").attr('hidden',true);
            $("#milik0").attr('hidden',true);
            $("#no_polisi1").attr('hidden',true);
            $("#no_polisi0").attr('hidden',true);
            $("#fungsi1").attr('hidden',true);
            $("#fungsi0").attr('hidden',true);
            $("#hukum1").attr('hidden',true);
            $("#hukum0").attr('hidden',true);
            $("#lokasi1").attr('hidden',true);
            $("#lokasi0").attr('hidden',true);
            $("#alamat1").attr('hidden',true);
            $("#alamat0").attr('hidden',true);
            $("#sert1").attr('hidden',true);
            $("#sert0").attr('hidden',true);
            $("#luas1").attr('hidden',true);
            $("#luas0").attr('hidden',true);
            $("#satuan1").attr('hidden',false);
            $("#satuan0").attr('hidden',false);
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
            $("#tahun_n1").attr('hidden',false);
            $("#tahun_n0").attr('hidden',false);
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        }else {
            alert("Belum ada form input");
            $("#tahun_oleh1").attr('hidden',true);
            $("#tahun_oleh0").attr('hidden',true);
            $("#bulan_oleh1").attr('hidden',true);
            $("#bulan_oleh0").attr('hidden',true);
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
            $("#akhir1").attr('hidden',true);
            $("#akhir0").attr('hidden',true);
            $("#kondisi_b1").attr('hidden',true);
            $("#kondisi_b0").attr('hidden',true);
            $("#kondisi_rb1").attr('hidden',true);
            $("#kondisi_rb0").attr('hidden',true);
            $("#kondisi_rr1").attr('hidden',true);
            $("#kondisi_rr0").attr('hidden',true);
            $("#keterangan1").attr('hidden',true);
            $("#keterangan0").attr('hidden',true);
            $("#harga_awal0").attr('hidden',true);
            $("#harga_awal1").attr('hidden',true);
            $("#no_polis").attr('hidden',true);
            $("#no_polis").attr('hidden',true);

        }
    }
    $('#rek3').on('select2:select', function() {
        let rek3 = this.value;
        cari_rek6(rek3);
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
    function cari_rek6(rek3,kd_rek6) {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('kapitalisasi.input.kd_rek6rinci') }}",
            type: "POST", 
            data: {
                rek3: rek3
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

    function cari_kd_rek6_input(kd_rek6){
        let kd_sub_kegiatan = document.getElementById('sub_kegiatan').value;
        $.ajax({
            url: "{{ route('kapitalisasi.input.kd_rek6') }}",
            type: "POST", 
            data: {
                kd_sub_kegiatan: kd_sub_kegiatan
            }, 
            dataType: 'json',
            success: function(data) {
                $('#kd_rek6input').empty();
                $('#kd_rek6input').append(
                    `<option value="" disabled selected>Pilih Rekening Rinci</option>`);
                $.each(data, function(index, data) {
                    if (data.kd_rek6 == kd_rek6) {
                        $('#kd_rek6input').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}" data-anggaran="${data.anggaran}" data-kapit="${data.kapitalisasi}" data-trans="${data.transaksi}"  data-jenis="${data.jenis}">${data.kd_rek6} | ${data.nm_rek6}</option>`
                        );
                    } else {
                        $('#kd_rek6input').append(
                            `<option value="${data.kd_rek6}" data-nama="${data.nm_rek6}" data-anggaran="${data.anggaran}" data-kapit="${data.kapitalisasi}" data-trans="${data.transaksi}"  data-jenis="${data.jenis}">${data.kd_rek6} | ${data.nm_rek6}</option>`
                        );
                    }
                })
            }
        })
    }
    $('#kd_rek6input').on('change', function() {
        let selected = $(this).find('option:selected');
        let kd_rek6input = this.value;
        
        let nama = $(this).find(':selected').data('nama');
        let anggaran = $(this).find(':selected').data('anggaran');
        let kapit = $(this).find(':selected').data('kapit');
        let trans = $(this).find(':selected').data('trans');
        let jenis = $(this).find(':selected').data('jenis');
        let nil_ang = rupiah(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(anggaran));
        let nil_kapit = rupiah(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(kapit));
        let nil_trans = rupiah(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(trans));
        $('#nm_rek6input').val(nama);
        $("#anggaran_input").val(nil_ang);
        $("#kapit_input").val(nil_kapit);
        $("#trans_input").val(nil_trans);
        $("#jenis_input").val(jenis).change();
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

    function refresh_kegiatan() {
        let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        let sub_kegiatan = document.getElementById('sub_kegiatan').value;
        if (!sub_kegiatan) {
            alert('Pilih Sub Kegiatan Terlebih Dahulu!');
            return;
        }
        $(document).ready(function(){
           // alert(csql);
            $.ajax({
                type: "POST",   
                dataType : 'json',                 
                data: ({kd_sub_kegiatan:sub_kegiatan,kd_skpd:kd_skpd}),
                url: "{{ route('input_kapitalisasi.refresh_kegiatan.simpan_tampungan') }}",
                success:function(data){
                    status_cek = data;
                    if(status_cek==0){
                    alert("Gagal delete");
                    return;
                    }
                    if(status_cek==1){
                        alert("Akan Segera Ditampilkan");
                        $(document).ready(function(){
                            $.ajax({
                                type     : "POST",
                                url      : "{{ route('input_kapitalisasi.refresh_kegiatan.refresh_simpan_tabel') }}",
                                data     : ({kd_sub_kegiatan:sub_kegiatan,kd_skpd:kd_skpd}),
                                dataType : "json",
                                success  : function(data){
                                    status=data ;
                                                                    
                                    if ( status=='1' ){
                                        //alert("aaaa");
                                        alert('Data Tersedia');
                                        let list_table = $('#list_kapit').DataTable();
                                        list_table.ajax.reload();
                                        return;
                                    }
                                    
                                    if ( status=='0' ){
                                        alert('Gagal Simpan...!!!');
                                        let list_table = $('#list_kapit').DataTable();
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

    function hitung_kapit_kegiatan() {
        let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        let sub_kegiatan = document.getElementById('sub_kegiatan').value;
        if (!sub_kegiatan) {
            alert('Pilih Sub Kegiatan Terlebih Dahulu!');
            return;
        }
        var del=confirm('Ini akan menghitung Ulang Kapitalisasi Kegiatan yang dipilih | Anda yakin ?');
        if  (del==true){
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({kd_sub_kegiatan:sub_kegiatan,kd_skpd:kd_skpd}),
                    url: "{{ route('input_kapitalisasi.hitung_kapit_kegiatan') }}",
                    success:function(data){
                        status=data ;
                                                                        
                        if ( status=='1' ){
                            //alert("aaaa");
                            alert('Hitungan Sudah Selesai');
                            let list_table = $('#list_kapit').DataTable();
                            list_table.ajax.reload();
                            return;
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal dihitung...!!!');
                            let list_table = $('#list_kapit').DataTable();
                            list_table.ajax.reload();
                            return;
                        }
                    }
                });
            });
        }
    }

    function rinci(kd_sub_kegiatan, kd_rek6, nm_rek6, nil_ang, kapitalisasi, nilai_trans, jenis) {  
        document.getElementById('tambah_rinci').hidden = false; // Hide
        $('#kd_rek6').val(kd_rek6);
        $('#jikd_rek6').val(kd_rek6);
        $('#kapit_input_rinci').val(kapitalisasi);
        // alert(kd_rek6);
        // $('anggaran_input').val(nil_ang);
        // $('trans_input').val(nilai_trans);
        // alert(kapitalisasi);
        let list_table = $('#list_rinci_kapit').DataTable();
        list_table.ajax.reload();
        load_tot_rinci(kd_sub_kegiatan,kd_rek6);
    }

    function edit(kd_sub_kegiatan, kd_rek6, nm_rek6, nil_ang, kapitalisasi, nilai_trans, jenis) {  
        status_input = 'edit';
        $('#status_input').val(status_input);
        $('#kd_rek6input').val(kd_rek6).change();
        $('#nm_rek6input').val(nm_rek6);
        $('anggaran_input').val(nil_ang);
        $('kapit_input').val(kapitalisasi);
        $('trans_input').val(nilai_trans);
        $('#jenis_input').val(jenis).change();
        // alert(kapitalisasi)
        // tampil_nilai("");
        $('#modal_input_kapit').modal('show');
    }

    function edit_rinci(no_rinci,kd_sub_kegiatan,kd_rek5_trans,no_lamp,kd_rek3,nm_rek3,kd_rek5,nm_rek5,kd_rek6,nm_rek6,tahun,merk,no_polisi,kd_sub_kegiatan,fungsi,hukum,lokasi,alamat,sert,luas,satuan,harga_satuan,piutang_awal,piutang_koreksi,piutang_sudah,investasi_awal,sal_awal,kurang,tambah,tahun_n,akhir,kondisi_b,kondisi_rr,kondisi_rb,keterangan,kd_skpd,jumlah,kepemilikan,rincian_beban,no_polis,bulan,nilai,kapitalisasi,tot_kap,tot_sat_kap,jenis) {  
        status_input_rinci = 'edit';
        $('#status_input_rinci').val(status_input_rinci);
        $("#nomor").val(no_lamp);
        $("#no_simpan").val(no_lamp);
        $('#rek3').val(kd_rek3).change();
        tampil_rek3(kd_rek3);
        $('#nm_rek3').val(nm_rek3);
        $("#rek5x").val(rek5x);
        $("#rek5").val(kd_rek5);
        $("#nm_rek5").val(nm_rek5);
        cari_rek6(kd_rek3,kd_rek6);
        $('#rek6').val(kd_rek6).change();
        $('#nm_rek6').val(nm_rek6);
        $("#tahun").val(tahun);
        $("#bulan").val(bulan);
        $("#merk").val(merk);
        $("#no_polisi").val(no_polisi);
        $("#fungsi").val(fungsi);
        $("#hukum").val(hukum);
        $("#lokasi").val(lokasi);
        $("#alamat").val(alamat);
        $("#sert").val(sert);
        $("#luas").val(luas);
        $("#satuan").val(satuan);
        $("#harga_satuan").val(harga_satuan);
        $("#piutang_awal").val(piutang_awal);
        $("#piutang_koreksi").val(piutang_koreksi);
        $("#piutang_sudah").val(piutang_sudah);
        $("#sal_awal").val(sal_awal);
        $("#investasi_awal").val(investasi_awal);
        $("#kurang").val(kurang);
        $("#tambah").val(tambah);
        $("#tahun_n").val(tahun_n);
        $("#kondisi_b").val(kondisi_b);
        $("#kondisi_rr").val(kondisi_rr);
        $("#kondisi_rb").val(kondisi_rb);
        $("#keterangan").val(keterangan);
        $("#jumlah").val(jumlah);
        $("#milik").val(kepemilikan);
        $("#rincian_bebas").val(rincian_beban);
        $("#no_polis").val(no_polis);
        $("#harga_awal").val(null);
        $("#kapit_tot").val(nilai);
        $("#kapit_rincian").val(nilai);
        $("#kapit_rincian1").val(nilai);
        $("#sat_kap").val(tot_sat_kap);
        $("#nil_kap").val(tot_kap);

        var ztot_trans = angka(document.getElementById('tot_trans').value);
        // alert(ztot_trans);
        var ztot_rinci = angka(document.getElementById('tot_rinci').value);
        var ztahun_n= angka(tahun_n);
        var z_sisa=(ztot_trans-ztot_rinci)+ztahun_n;
        var z_sisa_a = rupiah(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(z_sisa));
        $("#trans_tot").val(z_sisa_a);

        $('#modal_input_rinci_kapit').modal('show');
    }

    function hapus_rinci(no_rinci,kd_sub_kegiatan,kd_rek5_trans,no_lamp,kd_rek3,nm_rek3,kd_rek5,nm_rek5,kd_rek6,nm_rek6,tahun,merk,no_polisi,kd_sub_kegiatan,fungsi,hukum,lokasi,alamat,sert,luas,satuan,harga_satuan,piutang_awal,piutang_koreksi,piutang_sudah,investasi_awal,sal_awal,kurang,tambah,tahun_n,akhir,kondisi_b,kondisi_rr,kondisi_rb,keterangan,kd_skpd,jumlah,kepemilikan,rincian_beban,no_polis,bulan,nilai,kapitalisasi,tot_kap,tot_sat_kap,jenis) {  
        var nomor       = no_lamp;
        var status_aset = status;

        if ( status_aset =='1' ){
            alert('Data Audited Tahun Lalu tidak boleh dihapus. Hubungi Verifikator Bidang Akuntansi jika Ingin Menghapus.');
            return;
        }
        
        var urll= "{{ route('input_kapit.cari_hapus_rincian') }}";                           
        if (nomor !=''){
            var del=confirm('Anda yakin akan menghapus ?');
            if  (del==true){
                $(document).ready(function(){
                    $.ajax({
                        type     : "POST",
                        url      : "{{ route('input_kapit.cari_hapus_rincian') }}",
                        data     : ({no:nomor}),
                        dataType : "json",
                        success  : function(data){                    
                            status_hapus = data.pesan;
                            alert(status_hapus); 
                            if ( status_hapus=='1' ){
                                alert('Data Terhapus...!!!');
                                let list_table = $('#list_rinci_kapit').DataTable();
                                list_table.ajax.reload();
                                return;
                            }
                            
                            if ( status_hapus=='0' ){
                                alert('Gagal Terhapus...!!!');
                                let list_table = $('#list_rinci_kapit').DataTable();
                                list_table.ajax.reload();
                                return;
                            }  
                                
                        }
                    });
                });             
            }
        } 
    }

    function load_tot_rinci(kd_sub_kegiatan,kd_rek6){
        $.ajax({
            url: "{{ route('input_kapitalisasi.tot_rinci.load') }}",
            type: "POST",
            dataType: 'json',
            data: {
                kd_sub_kegiatan: kd_sub_kegiatan,
                kd_rek6: kd_rek6,
            },
            success: function(data) {
                // console.log(data[0])
                $('#tot_rinci').val(data[0].tot_rinci);
                $('#tot_trans').val(data[0].tot_trans);
                $('#tot_kapit').val(data[0].tot_kapit);
                $('#tot_kapit_rek').val(data[0].tot_kapit_rek);
            }
        })
    }

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

    function hitung_harga_satuan(){
        var transaksi = document.getElementById('trans_tot').value;
        var trans=angka(transaksi);
        var jumlah = document.getElementById('jumlah').value;
        var hrg_satuan=trans/jumlah;
        $("#harga_satuan").val(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(hrg_satuan))
        var sis_hrg = trans%jumlah;
        if((sis_hrg<=5)&&(sis_hrg>0)){
            alert("Sisa kurang dari 5 Rupiah!");
        }
        hitung_saldo_awal();
    }
    function hitung_saldo_awal(){
        var jumlah = document.getElementById('jumlah').value;
        if(jumlah==''){
            jumlah=0;
        } else {
            jumlah=jumlah;
        }
        var jum=angka(jumlah);
        var harga_satuan = document.getElementById('harga_satuan').value;
        if(harga_satuan==''){
            harga_satuan=0;
        } else {
            harga_satuan=harga_satuan;
        }
        var hrg_satuan=angka(harga_satuan);
        saldo_awal=jum*hrg_satuan;
        $("#harga_awal").val(new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(saldo_awal))      
    }

    function simpan_tr(){
        var skpd            = "{{ $data_skpd->kd_skpd }}";
        var sub_kegiatan    = document.getElementById('sub_kegiatan').value;
        var kd_rek6         = document.getElementById('kd_rek6input').value;
        var anggaran        = angka(document.getElementById('anggaran_input').value);
        var kapit           = angka(document.getElementById('kapit_input').value);
        var trans           = angka(document.getElementById('trans_input').value);
        var jenis           = document.getElementById('jenis_input').value;
        var status_input    = document.getElementById('status_input').value;
        // alert(kapit);
            // exit();
        if ( sub_kegiatan == '' ){
            alert('Pilih Kode Kegiatan Terlebih Dahulu...!!!');
            exit();
        }
        if ( kd_rek6 == '' ){
            alert('Pilih Rekening Terlebih Dahulu...!!!');
            exit();
        }
        
        $(document).ready(function(){
           // alert(csql);
            $.ajax({
                type: "POST",   
                dataType : 'json',                 
                data: ({kd_skpd:skpd,kd_sub_kegiatan:sub_kegiatan,kd_rek6:kd_rek6,anggaran:anggaran,kapit:kapit,trans:trans,jenis:jenis,status_input:status_input}),
                url: "{{ route('input_kapit_inputan.simpan_input') }}",
                success:function(data){                        
                    status_cek = data.kode;
                    status_pesan = data.pesan;
                    if (status=='0'){
                        alert(status_pesan);
                        return;
                    } else {
                        alert(status_pesan);
                        status_input = 'edit';
                        $("#kd_rek6input").attr("value",kd_rek6);
                        let list_table = $('#list_kapit').DataTable();
                        list_table.ajax.reload();
                        return;
                   }
                }
            });
        });
    }

    function hapus_tr(kd_sub_kegiatan, kd_rek6, nm_rek6, nil_ang, kapitalisasi, nilai_trans, jenis){
        var skpd            = "{{ $data_skpd->kd_skpd }}";
        var sub_kegiatan    = kd_sub_kegiatan;
        var kd_rek6         = kd_rek6;
        var jenis           = jenis;
        
        var del=confirm('Anda yakin akan menghapus ?');
            if  (del==true){
                $(document).ready(function(){
                   // alert(csql);
                    $.ajax({
                        type: "POST",   
                        dataType : 'json',                 
                        data: ({kd_skpd:skpd,kd_sub_kegiatan:sub_kegiatan,kd_rek6:kd_rek6,jenis:jenis}),
                        url: "{{ route('input_kapit_inputan.hapus_tr') }}",
                        success:function(data){                        
                            status_cek = data.kode;
                            status_pesan = data.pesan;
                            if (status=='0'){
                                alert(status_pesan);
                                return;
                            } else {
                                alert(status_pesan);
                                let list_table = $('#list_kapit').DataTable();
                                list_table.ajax.reload();
                                return;
                           }
                        }
                    });
                });
            }
    }

    function hsimpan(){ 
        var status_input_rinci     = document.getElementById('status_input_rinci').value;
        var sub_kegiatan     = document.getElementById('sub_kegiatan').value;
        var jikd_rek6   = document.getElementById('jikd_rek6').value;
        var a           = document.getElementById('nomor').value;
        var a_hide      = document.getElementById('no_simpan').value;
        var rek3                = document.getElementById('rek3').value;
        let rek3n                = $('#rek3').find('option:selected');
        let nm_rek3             = rek3n.data('nama');
        // var nm_rek3             = document.getElementById('nm_rek3').value; 
        
        var rek6                = document.getElementById('rek6').value;
        // var nm_rek6             = document.getElementById('nm_rek6').value;
        let rek6n                = $('#rek6').find('option:selected');
        let nm_rek6             = rek6n.data('nama');
        var dx          = document.getElementById('rek5x').value;
        
        var rek5        = document.getElementById('rek5').value; 
        var nm_rek5     = document.getElementById('nm_rek5').value;
        
        
        // var tahun       = document.getElementById('tahun').value; 

        var tahun       = 2023; 
        var bulan       = document.getElementById('bulan').value; 
        var merk        = document.getElementById('merk').value; 
        var no_polisi   = document.getElementById('no_polisi').value;
        var fungsi      = document.getElementById('fungsi').value;
        var hukum       = document.getElementById('hukum').value;
        var lokasi      = document.getElementById('lokasi').value; 
        var alamat          = document.getElementById('alamat').value;
        var sert            = document.getElementById('sert').value;
        var luas            = document.getElementById('luas').value;
        var satuan          = document.getElementById('satuan').value;
        var harga_satuan    = document.getElementById('harga_satuan').value;
        var piutang_awal    = document.getElementById('piutang_awal').value; 
        var piutang_koreksi = document.getElementById('piutang_koreksi').value;
        var piutang_sudah   = document.getElementById('piutang_sudah').value;
        var investasi_awal  = document.getElementById('investasi_awal').value;
        var sal_awal        = document.getElementById('sal_awal').value;
        var kurang          = document.getElementById('kurang').value;
        var tambah          = document.getElementById('tambah').value;
        var tahun_n         = document.getElementById('tahun_n').value;
        var aa       = 0;
        var kondisi_b       = document.getElementById('kondisi_b').value;
        var kondisi_rr      = document.getElementById('kondisi_rr').value;
        var kondisi_rb      = document.getElementById('kondisi_rb').value;
        var keterangan      = document.getElementById('keterangan').value;
        var skpd            = "{{ $data_skpd->kd_skpd }}";
        var jumlah          = document.getElementById('jumlah').value;
        var milik           = document.getElementById('milik').value;
        var rincian_bebas   = document.getElementById('rincian_bebas').value;
        var no_polis        = document.getElementById('no_polis').value;
        var kapitalisasi    = document.getElementById('kapit_input_rinci').value;
        var trans_tot      = document.getElementById('trans_tot').value;
        var kapit_tot      = angka(document.getElementById('kapit_tot').value);

        alert(kapitalisasi);


        if(angka(tahun_n)>angka(trans_tot)){
            alert('Nilai Pengadaan melebihi Sisa Uang Transaksi');
            exit();
        }
        
        /*if(gg == ''){
            alert('Jumlah Barang Tidak Boleh Kosong');
            exit();
        }*/
        
        if(tahun_n == ''){
            alert('Nilai Pengadaan Tahun Tidak Boleh Kosong');
            exit();
        }
        
        if ( rek3.length<1 ){
            alert("Pastikan Rek. Kelompok diisi dengan Benar") ;
            exit();
        }
        
        /*if ( d.length!=8 ){
            alert("Pastikan Rekening diisi dengan Benar") ;
            exit();
        }
        */
        /*if ( f.length <9 && dx==1 ){
            alert("Pastikan Rekening Rinci diisi dengan Benar") ;
            exit();
        }*/
        
        /*if ( s == '' && (b == 133) ){
            alert("Isi Jenis Aset Terlebih Dahulu") ;
            exit();
        }*/
        
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
        
        if (tambah==''){
            tambah=0;
        }else{
            tambah=angka(tambah);
        }
        
        if (tahun_n==''){
            tahun_n=0;
        }else{
            tahun_n=angka(tahun_n);
        }

        if (jumlah=='' && rek3=='8'){
            jumlah=1;
        }else if(jumlah==''){
            jumlah=0;
        }else{
            jumlah=angka(jumlah);
        }
    

        
        if (kapitalisasi==''){
            kapitalisasi=0;
        }else{
            kapitalisasi=angka(kapitalisasi);
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
        
        if ( a == '' ){
            alert("Isi Nomor Terlebih Dahulu") ;
            exit();
        }
         if ( tahun == '' ){
            alert("Isi Tahun Terlebih Dahulu") ;
            exit();
        }
        
        if ( bulan == '' ){
            bulan=0;
        }else{
            bulan=angka(bulan);
        }
        
        /*if(gg != (bb+cc+dd)){
            alert('Jumlah Barang tidak sesuai dengan Jumlah Kondisi Barang');
            exit();
        }*/
        
        var norinci  = skpd+'.'+sub_kegiatan+'.'+jikd_rek6 ;

        if(status_input_rinci == "tambah"){
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:"lamp_aset",field:"no_lamp",tabel2:"trdkapitalisasi",field2:"no_lamp"}),
                    url: "{{ route('input_kapit.cari_cek_simpan') }}",
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1){
                        alert("Nomor Telah Dipakai!");
                        exit();
                        } 
                        if(status_cek==0){
                        alert("Nomor Bisa dipakai");
        
                            //---------
                            lcinsert = "(no_rinci,kd_sub_kegiatan,kd_rek5_trans,no_lamp, kd_rek3, nm_rek3, kd_rek5, nm_rek5, kd_rek6, nm_rek6, tahun, bulan, merk, no_polisi, fungsi, hukum, lokasi, alamat, sert, luas, satuan, harga_satuan, piutang_awal, piutang_koreksi, piutang_sudah, investasi_awal, sal_awal, kurang, tambah, tahun_n, akhir, kondisi_b, kondisi_rr, kondisi_rb, keterangan,kd_skpd,jumlah,kepemilikan,rincian_beban,no_polis,nilai,kapitalisasi)"; 
                            lcvalues = "('"+norinci+"','"+sub_kegiatan+"','"+jikd_rek6+"','"+a+"', '"+rek3+"', '"+nm_rek3+"', '"+rek5+"', '"+nm_rek5+"', '"+rek6+"', '"+nm_rek6+"','"+tahun+"','"+bulan+"','"+merk+"','"+no_polisi+"','"+fungsi+"','"+hukum+"','"+lokasi+"','"+alamat+"','"+sert+"',"+luas+",'"+satuan+"',"+harga_satuan+",     "+piutang_awal+",        "+piutang_koreksi+" ,    "+piutang_sudah+",       "+investasi_awal+",      "+sal_awal+", "+kurang+" ,"+tambah+", "+tahun_n+",'"+aa+"','"+kondisi_b+"','"+kondisi_rr+"',     '"+kondisi_rb+"', '"+keterangan+"', '"+skpd+"', "+jumlah+", '"+milik+"', '"+rincian_bebas+"', '"+no_polis+"','"+kapit_tot+"','"+kapitalisasi+"')";
                            $(document).ready(function(){
                                $.ajax({
                                    type     : "POST",
                                    url      : "{{ route('input_kapit.cari_simpan_rincian') }}",
                                    data     : ({tabel:'trdkapitalisasi',kolom:lcinsert,nilai:lcvalues,cid:'no_lamp',lcid:a}),
                                    dataType : "json",
                                    success  : function(data){
                                        status = data;
                                        if (status=='0'){
                                            alert('Gagal Simpan..!!');
                                            let list_table = $('#list_rinci_kapit').DataTable();
                                            list_table.ajax.reload();
                                            load_tot_rinci(sub_kegiatan,jikd_rek6);
                                            return;
                                        } else if(status=='1'){
                                            alert('Data Sudah Ada..!!');
                                            let list_table = $('#list_rinci_kapit').DataTable();
                                            list_table.ajax.reload();
                                            load_tot_rinci(sub_kegiatan,jikd_rek6);
                                            return;
                                        } else {
                                            alert('Data Tersimpan..!!');
                                            let list_table = $('#list_rinci_kapit').DataTable();
                                            list_table.ajax.reload();
                                            load_tot_rinci(sub_kegiatan,jikd_rek6);
                                            $("#no_simpan").attr("value",a);
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
            $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:"lamp_aset",field:"no_lamp",tabel2:"trdkapitalisasi",field2:"no_lamp"}),
                    url: "{{ route('input_kapit.cari_cek_simpan') }}",
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 && a!=a_hide){
                            alert("Nomor Telah Dipakai!");
                            exit();
                        } 
                        if(status_cek==0 || a==a_hide){
                            alert("Nomor Bisa dipakai");
                            lcquery = " UPDATE trdkapitalisasi SET no_rinci='"+norinci+"', kd_sub_kegiatan='"+sub_kegiatan+"',kd_rek5_trans='"+jikd_rek6+"', no_lamp ='"+a+"', kd_rek3='"+rek3+"', nm_rek3='"+nm_rek3+"', kd_rek5='"+rek5+"', nm_rek5='"+nm_rek5+"', kd_rek6='"+rek6+"', nm_rek6='"+nm_rek6+"', tahun='"+tahun+"', bulan='"+bulan+"', merk='"+merk+"', no_polisi='"+no_polisi+"', fungsi='"+fungsi+"', hukum='"+hukum+"', lokasi='"+lokasi+"', alamat='"+alamat+"', sert='"+sert+"', luas='"+luas+"', satuan='"+satuan+"', harga_satuan='"+harga_satuan+"', piutang_awal='"+piutang_awal+"', piutang_koreksi='"+piutang_koreksi+"', piutang_sudah='"+piutang_sudah+"', investasi_awal='"+investasi_awal+"', sal_awal='"+sal_awal+"', kurang='"+kurang+"', tambah='"+tambah+"', tahun_n='"+tahun_n+"', kondisi_b='"+kondisi_b+"', kondisi_rr='"+kondisi_rr+"', kondisi_rb='"+kondisi_rb+"', keterangan='"+keterangan+"',kd_skpd ='"+skpd+"',jumlah ='"+jumlah+"',kepemilikan ='"+milik+"',rincian_beban ='"+rincian_bebas+"',no_polis ='"+no_polis+"',nilai ='"+kapit_tot+"',kapitalisasi ='"+kapitalisasi+"' where no_lamp='"+a_hide+"' AND kd_skpd ='"+skpd+"' "; 
                            $(document).ready(function(){
                                $.ajax({
                                    type     : "POST",
                                    url      : "{{ route('input_kapit.cari_update_rincian') }}",
                                    data     : ({st_query:lcquery,tabel:'trdkapitalisasi',cid:'no_lamp',lcid:a,lcid_h:a_hide}),
                                    dataType : "json",
                                    success  : function(data){
                                        status=data ;
                                                                        
                                        if ( status=='1' ){
                                            //alert("aaaa");
                                            alert('Nomor  Sudah Terpakai...!!!,  Ganti Nomor ...!!!');
                                            let list_table = $('#list_rinci_kapit').DataTable();
                                            list_table.ajax.reload();
                                            load_tot_rinci(sub_kegiatan,jikd_rek6);
                                            return;
                                        }
                                        
                                        if ( status=='2' ){
                                            alert('Data Tersimpan...!!!');
                                            status_input = 'edit';
                                            $("#no_simpan").attr("value",nomor);
                                            let list_table = $('#list_rinci_kapit').DataTable();
                                            list_table.ajax.reload();
                                            load_tot_rinci(sub_kegiatan,jikd_rek6);
                                            return;
                                        }
                                        
                                        if ( status=='0' ){
                                            alert('Gagal Simpan...!!!');
                                            let list_table = $('#list_rinci_kapit').DataTable();
                                            list_table.ajax.reload();
                                            load_tot_rinci(sub_kegiatan,jikd_rek6);

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

    function hitung_rincian_kapit(){
        var cgiat = document.getElementById('sub_kegiatan').value;
        var cskpd = "{{ $data_skpd->kd_skpd }}";
        var rek   = document.getElementById('jikd_rek6').value;
        // alert(cskpd);
        // alert(cgiat);
        // alert(rek);
        // exit();
        $(document).ready(function(){
        $.ajax({
            type     : 'POST',
            dataType : 'json',
            data     : ({giat:cgiat,skpd:cskpd,rek:rek}),
            url      : "{{ route('input_kapit.hitung_rincian_kapit') }}",
            
            success  : function(data){
                if (data = 1){
                    alert('Selesai!');
                    let list_table = $('#list_kapit').DataTable();
                    let list_table_rinci = $('#list_rinci_kapit').DataTable();
                    load_tot_rinci(cgiat,rek);
                    list_table.ajax.reload();
                    list_table_rinci.ajax.reload();
                    return;

                } else{
                    alert('Gagal!');
                    let list_table = $('#list_kapit').DataTable();
                    let list_table_rinci = $('#list_rinci_kapit').DataTable();
                    load_tot_rinci(cgiat,rek);
                    list_table.ajax.reload();
                    list_table_rinci.ajax.reload();
                    return;
                }
                }    
            });
        });
}
    
</script>
@endsection
