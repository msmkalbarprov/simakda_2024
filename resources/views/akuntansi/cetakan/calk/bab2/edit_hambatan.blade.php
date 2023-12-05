@extends('template.app')
@section('title', 'CALK EDIT BAB II HAMBATAN | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Edit Hambatan Bab II
                    
                </div>
                
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="table_hambatan" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">Kode Program</th>
                                        <th style="width: 50px;text-align:center">nama Program</th>
                                        <th style="width: 50px;text-align:center">anggaran</th>
                                        <th style="width: 50px;text-align:center">realisasi</th>
                                        <th style="width: 50px;text-align:center">Persen</th>
                                        <th style="width: 50px;text-align:center">Hambatan/Kendala</th>
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

    


@include('akuntansi.cetakan.calk.bab2.bab2_hambatan')
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

        let data=$('#table_hambatan').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [10, 20],
            ajax: {
                "url": "{{ route('calk.load_calkbab2_hambatan') }}",
                "type": "POST",
                "data": {kd_skpd:'{{$kd_skpd}}',bulan:'{{$bulan}}',jns_ang:'{{$jns_ang}}'}
            },
            columns: [{
                    data: 'kode2',
                    name: 'kode2',
                    className: "text-center",
                },
                {
                    data: 'bidang',
                    name: 'bidang',
                    className: "text-center",
                },
                {
                    data: null,
                    name: 'anggaran',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.anggaran)
                    }
                }, 
                {
                    data: null,
                    name: 'realisasi',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.realisasi)
                    }
                }, 
                {
                    data: null,
                    name: 'persen',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2
                        }).format(data.persen)
                    }
                },
                {
                    data: 'hambatan',
                    name: 'hambatan',
                    className: "text-center",
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    width: 100,
                    className: "text-center",
                },
            ],
        });

        $('#bulan').on('change',function(){
            let bulan = this.value
            data.ajax.reload()
        });


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
    function numfot(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    function edit(kode, kode2, bidang, anggaran, realisasi,selisih, persen, hambatan) {
        let anggarani = angka(anggaran);
        let realisasii = angka(realisasi);
        let perseni=angka(persen);
         anggarann = numfot(anggarani.toFixed(2));
         realisasii = numfot(realisasii.toFixed(2));
         persenn = numfot(perseni.toFixed(2));

        $('#kode').val(kode);
        $('#kode2').val(kode2);
        $('#bidang').val(bidang);
        $('#anggaran').val(anggarann);
        $('#realisasi').val(realisasii);
        $('#persen').val(persenn);
        $('#hambatan').val(hambatan);

        
        $('#modal_edit_bab2_hambatan').modal('show');
    }

        $('.bku_layar').on('click', function() {
            Cetak(1)
        });
        $('.bku_pdf').on('click', function() {
            Cetak(2)
        });
        $('.bku_excel').on('click', function() {
            Cetak(3)
        });
    function Cetak(jns_cetak) {

            // GET DATA
            let bulan                    = document.getElementById('bulan').value;
            // alert(labelcetak_semester)
            // PERINGATAN
                if (!bulan) {
                    alert('Bulan tidak boleh kosong!');
                    return;
                }

            // SET CETAKAN

                let url             = new URL("{{ route('pengesahan_spj.cetak_penerimaan_spj') }}");
                let searchParams    = url.searchParams;
                searchParams.append("bulan", bulan);
                searchParams.append("cetak", jns_cetak);
                window.open(url.toString(), "_blank");
            

    }

    function simpan_hambatan(){
        let kd_skpd = document.getElementById('kode').value;
        let kd_sub_kegiatan = document.getElementById('kode2').value;
        let anggaran = angka(document.getElementById('anggaran').value);
        let realisasi = angka(document.getElementById('realisasi').value);
        let persen = angka(document.getElementById('persen').value);
        let hambatan = document.getElementById('hambatan').value;

        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: "{{ route('calk.simpan_calkbab2_hambatan') }}",
                data: ({tabel:'calk_babII',kd_skpd:kd_skpd,kd_sub_kegiatan:kd_sub_kegiatan,hambatan:hambatan}),
                dataType:"json",
                success  : function(data){
                    status = data;
                    if ( status=='1' ){
                        alert('Data Tersimpan...!!!');
                        let list_table = $('#table_hambatan').DataTable();
                        list_table.ajax.reload();
                        return;
                    }
                    
                    if ( status=='0' ){
                        alert('Gagal Simpan...!!!');
                        let list_table = $('#table_hambatan').DataTable();
                        list_table.ajax.reload();

                        return;
                    }
                }
            });
        });
    }
</script>
@endsection
