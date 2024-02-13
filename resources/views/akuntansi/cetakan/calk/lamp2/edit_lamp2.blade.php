@extends('template.app')
@section('title', 'CALK EDIT LAMP 2 | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Edit Uraian Lamp 2
                    <button id="tambah" class="btn btn-md btn-primary" style="float: right;">Tambah</button>
                </div>
                
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="table_edit" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;text-align:center">Nama SKPD</th>
                                        <th style="width: 50px;text-align:center">Kode</th>
                                        <th style="width: 50px;text-align:center">Kode Rinci</th>
                                        <th style="width: 50px;text-align:center">Keterangan</th>
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

    


@include('akuntansi.cetakan.calk.lamp2.modal_edit_lamp2')
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

        $(".select_").select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modal_edit_lamp2 .modal-content'),
                
        });

        cari_jenis();

        let data=$('#table_edit').DataTable({
            responsive: true,
            ordering: false,
            serverSide: true,
            processing: true,
            lengthMenu: [10, 20],
            ajax: {
                "url": "{{ route('calk.load_calklamp2') }}",
                "type": "POST",
                "data": {kd_skpd:'{{$kd_skpd}}',bulan:'{{$bulan}}',jns_ang:'{{$jns_ang}}',kd_rek:'{{$kd_rek}}'}
            },
            columns: [{
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                    className: "text-center",
                },
                {
                    data: 'kd_rek',
                    name: 'kd_rek',
                    className: "text-center",
                },
                {
                    data: 'kd_rinci',
                    name: 'kd_rinci',
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
                    data: 'ket',
                    name: 'ket',
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

    function cari_jenis() {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('calk.cari_jenis_calklamp2') }}",
            type: "POST", 
            dataType: 'json',
            success: function(data) {
                $('#jenis').empty();
                $('#jenis').append(
                    `<option value="" disabled selected>Pilih Jenis</option>`);
                $.each(data, function(index, data) {
                    $('#jenis').append(
                        `<option value="${data.kd_rek}" data-nama="${data.nm_rek}">${data.kd_rek} | ${data.nm_rek}</option>`
                    );
                })
            }
        })
    }

    $('#jenis').on('select2:select', function() {
        let jenis = this.value;
        cari_rek(jenis);
    });

    function cari_rek(jenis){
        kd_skpd = "{{$kd_skpd}}";
        if(jenis=='131'){
           $("#kd_rek").val('131');
        }else if(jenis=='132'){
           $("#kd_rek").val('132');
        }else if(jenis=='133'){
           $("#kd_rek").val('133');
        }else if(jenis=='134'){
           $("#kd_rek").val('134');
        }else if(jenis=='135'){
           $("#kd_rek").val('135');
        }else if(jenis=='136'){
           $("#kd_rek").val('136');
        }
       
       load_kd_rinci(kd_skpd,jenis);
       uraian(jenis);
    }

    function load_kd_rinci(kd_skpd,jenis){
        $.ajax({
            url: "{{ route('calk.load_kd_rinci_calklamp2') }}",
            type: "POST",
            data: {kd_skpd:'{{$kd_skpd}}',kd_rek:jenis},
            dataType: 'json',
            success: function(data) {
                // console.log(data[0])
                $('#kd_rinci').val(data[0].kd_rinci);
            }
        })
    }

    function uraian(jenis) {
        // let kd_skpd = "{{ $data_skpd->kd_skpd }}";
        $.ajax({
            url: "{{ route('calk.cari_uraian_calklamp2') }}",
            type: "POST",
            data: {jenis:jenis},
            dataType: 'json',
            success: function(data) {
                $('#ket').empty();
                $('#ket').append(
                    `<option value="" disabled selected>Pilih Keterangan</option>`);
                $.each(data, function(index, data) {
                    $('#ket').append(
                        `<option value="${data.uraian}">${data.uraian}</option>`
                    );
                })
            }
        })
    }

    $('#tambah').on('click', function() {
        status_input = 'tambah';
        kd_skpd = "{{$kd_skpd}}";
        leng_skpd = kd_skpd.length;
        org_skpd = kd_skpd.substring(0,17);
        if (leng_skpd==17) {
            nm_skpd= nama_org(org_skpd);
        }else{
            nm_skpd= "{{nama_skpd($kd_skpd)}}";
        }
        $('#status_input').val(status_input);
        $('#kd_skpd').val(kd_skpd);
        $('#nm_skpd').val(nm_skpd);
        $("#nilai").val(null);
        $("#kd_rek").val(null);
        $("#kd_rinci").val(null);
        $("#jenis").val(null).change();
        $("#ket").val(null).change();
        $('#modal_edit_lamp2').modal('show');
    });

        $('.bku_layar').on('click', function() {
            Cetak(1)
        });
        $('.bku_pdf').on('click', function() {
            Cetak(2)
        });
        $('.bku_excel').on('click', function() {
            Cetak(3)
        });

    function simpan(){
        let kd_skpd = document.getElementById('kd_skpd').value;
        let kd_rek = document.getElementById('kd_rek').value;
        let kd_rinci = document.getElementById('kd_rinci').value;
        var ket = document.getElementById('ket').value;
        let nilai1 = angka(document.getElementById('nilai').value);
        if(document.getElementById('minus').checked) {
            var nilai = nilai1*-1;
        } else {
            var nilai = nilai1;
        }
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: "{{ route('calk.simpan_calklamp2') }}",
                data: ({tabel:'isi_analisis_calk',kd_skpd:kd_skpd,kd_rek:kd_rek,kd_rinci:kd_rinci,ket:ket,nilai:nilai}),
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
    function hapus(kd_skpd, nm_skpd, kd_rek, kd_rinci, ket,nilai) {

        var del=confirm('Anda yakin akan menghapus "'+ket+'" dengan nilai '+numfot(nilai)+' ?');
        if  (del==true){
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : "{{ route('calk.hapus_calklamp2') }}",
                    data     : ({tabel:'isi_analisis_calk',kd_skpd:kd_skpd,kd_rek:kd_rek,ket:ket,nilai:nilai,kd_rinci:kd_rinci}),
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
</script>
@endsection
1