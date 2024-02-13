@extends('template.app')
@section('title', 'CALK EDIT BAB III KETERANGAN NERACA AKUMULASI | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Edit Uraian Bab III Rinci NERACA AKUMULASI
                    <button id="tambah" class="btn btn-md btn-primary" style="float: right;">Tambah</button>
                </div>
                
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="table_edit" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">Kode SKPD</th>
                                        <th style="width: 50px;text-align:center">Kode Koreksi</th>
                                        <th style="width: 50px;text-align:center">Nama Koreksi</th>
                                        <th style="width: 50px;text-align:center">Kode Rincian Koreksi</th>
                                        <th style="width: 50px;text-align:center">Nama Rincian Koreksi</th>
                                        <th style="width: 50px;text-align:center">Ket</th>
                                        <th style="width: 50px;text-align:center">Nilai</th>
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

    


@include('akuntansi.cetakan.calk.bab3.neraca.modal_edit_bab3_neraca_edit_akum')
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
        $(".select_").select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modal_edit_bab3_neraca_edit_akum .modal-content'),
                
        });

        cari_rek("{{$kd_rek}}");

        let data=$('#table_edit').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [10, 20],
            ajax: {
                "url": "{{ route('calk.load_calkbab3_neraca_edit_akum') }}",
                "type": "POST",
                "data": {kd_skpd:'{{$kd_skpd}}',bulan:'{{$bulan}}',jns_ang:'{{$jns_ang}}',kd_rek:'{{$kd_rek}}'}
            },
            columns: [{
                    data: 'kd_skpd',
                    name: 'kd_skpd',
                    className: "text-center",
                },
                {
                    data: 'kd_rek',
                    name: 'kd_rek',
                    className: "text-center",
                },
                {
                    data: 'nm_rek',
                    name: 'nm_rek',
                    className: "text-center",
                },
                {
                    data: 'kd_rek2',
                    name: 'kd_rek2',
                    className: "text-center",
                },
                {
                    data: 'nm_rek2',
                    name: 'nm_rek2',
                    className: "text-center",
                },
                {
                    data: 'ket',
                    name: 'ket',
                    className: "text-center",
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
                    data: 'aksi',
                    name: 'aksi',
                    width: 100,
                    className: "text-center",
                },
            ],
        });


    });

    $('#tambah').on('click', function() {
        status_input = 'tambah';
        skpd = "{{$kd_skpd}}";
        leng_skpd = skpd.length;
        org_skpd = skpd.substring(0,17);
        if (leng_skpd==17) {
            nm_skpd= nama_org(org_skpd);
        }else{
            nm_skpd= "{{nama_skpd($kd_skpd)}}";
        }
        $('#status_input').val(status_input);
        $('#kd_skpd').val(skpd);
        $('#nm_skpd').val(nm_skpd);
        $('#kd_rek').val(null).change();
        $('#ket').val(null).change();
        $('#nilai').val(null);
        // tampil_nilai("");
        $('#modal_edit_bab3_neraca_edit_akum').modal('show');
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

    function hapus(kd_skpd, nm_skpd, kd_rek, nm_rek, kd_rek2, nm_rek2, ket, nilai) {

        var del=confirm('Anda yakin akan menghapus '+nm_rek2+' dengan Keterangan "'+ket+'" dan nilai '+numfot(nilai)+' ?');
        if  (del==true){
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : "{{ route('calk.hapus_calkbab3_neraca_edit_akum') }}",
                    data     : ({tabel:'isi_neraca_calk_baru',kd_skpd:kd_skpd,kd_rek:kd_rek,kd_rek2:kd_rek2,ket:ket,nilai:nilai}),
                    dataType : "json",
                    success  : function(data){                    
                        status_hapus = data.pesan;
                        // alert(status_hapus); 
                        if ( status_hapus=='1' ){
                            alert('Data Terhapus...!!!');
                            let list_table = $('#table_edit').DataTable();
                            list_table.ajax.reload();
                            return;
                        }
                        
                        if ( status_hapus=='0' ){
                            alert('Gagal Terhapus...!!!');
                            let list_table = $('#table_edit').DataTable();
                            list_table.ajax.reload();
                            return;
                        }  
                            
                    }
                });
            });             
        }
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

    function cari_rek(rek1) {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('calk.cari_rek_akum_calk') }}",
            type: "POST", 
            data: {
                rek1: rek1
            },
            dataType: 'json',
            success: function(data) {
                $('#rek').empty();
                $('#rek').append(
                    `<option value="" disabled selected>Pilih Koreksi</option>`);
                $.each(data, function(index, data) {
                    $('#rek').append(
                        `<option value="${data.kd_rek}" data-nama="${data.nm_rek}">${data.kd_rek} | ${data.nm_rek}</option>`
                    );
                })
            }
        })
    }

    $('#rek').on('select2:select', function() {
        let rek = this.value;
        cari_rek2(rek);
    });

    function cari_rek2(rek) {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('calk.cari_rek2_akum_calk') }}",
            type: "POST",
            data: {
                rek: rek
            }, 
            dataType: 'json',
            success: function(data) {
                $('#rek2').empty();
                $('#rek2').append(
                    `<option value="" disabled selected>Pilih Rincian Koreksi</option>`);
                $.each(data, function(index, data) {
                    $('#rek2').append(
                        `<option value="${data.kd_rek2}" data-nama="${data.nm_rek2}">${data.kd_rek2} | ${data.nm_rek2}</option>`
                    );
                })
            }
        })
    }

    function simpan(){
        var status_input  = document.getElementById('status_input').value;
        let kd_skpd = document.getElementById('kd_skpd').value;
        let kd_rek = document.getElementById('rek').value;
        let kd_rek2 = document.getElementById('rek2').value;
        let ket = document.getElementById('ket').value;
        let nilai = angka(document.getElementById('nilai').value);


        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: "{{ route('calk.simpan_calkbab3_neraca_edit_akum') }}",
                data: ({tabel:'isi_neraca_calk_baru',kd_skpd:kd_skpd,kd_rek:kd_rek,kd_rek2:kd_rek2,ket:ket,nilai:nilai}),
                dataType:"json",
                success  : function(data){
                    status = data;
                    if ( status=='1' ){
                        alert('Data Tersimpan...!!!');
                        let list_table = $('#table_edit').DataTable();
                        list_table.ajax.reload();
                        return;
                    }
                    
                    if ( status=='0' ){
                        alert('Gagal Simpan...!!!');
                        let list_table = $('#table_edit').DataTable();
                        list_table.ajax.reload();

                        return;
                    }
                }
            });
        });
    }
</script>
@endsection
