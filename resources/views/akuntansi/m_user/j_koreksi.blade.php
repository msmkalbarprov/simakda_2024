@extends('template.app')
@section('title', 'Penerima | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="load_jkoreksi" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th >Akses</th>
                                        <th >Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@include('akuntansi.modal.m_user.j_koreksi')
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

            $('#load_jkoreksi').DataTable({
                responsive: true,
                ordering: false,
                serverSide: true,
                processing: true,
                lengthMenu: [5, 10],
                ajax: {
                    "url": "{{ route('muser.load_jkoreksi') }}",
                    "type": "POST",
                },
                columns: [{
                        data: 'kd_skpd',
                        name: 'kd_skpd',
                        className: "text-center",
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                    },
                    {
                        data: 'koreksi',
                        name: 'koreksi',
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
        function keluar(){
        $("#modal_j_koreksi").dialog('close');
        lcstatus = 'edit';
     }
        function edit(kd_skpd, username, nama, koreksi) {
            $('#kd_skpd').val(kd_skpd);
            $('#username').val(username);
            $('#nama').val(nama);
            // $('#cek').val(cek);
            koreksi == 1 ? $('#koreksi').prop('checked', true) : $('#koreksi').prop('checked', false);
            
            $('#modal_j_koreksi').modal('show');
        }
        function detail(kd_skpd, username, nama, koreksi) {
            $('#kd_skpd').val(kd_skpd);
            $('#username').val(username);
            $('#nama').val(nama);
            
            $('#koreksi').val(koreksi);
        }
        function simpan_j_koreksi(){
            let kd_skpd = document.getElementById('kd_skpd').value;
            let username = document.getElementById('username').value;
            let nama = document.getElementById('nama').value;
            let data_j_koreksi = $('#load_jkoreksi').DataTable();
            let ccek = document.getElementById('koreksi').checked;
            if (ccek==false){
               ccek=0;
            }else{
                ccek=1;
            }
            
            
            
            
            
                $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: "{{ route('muser.simpan_j_koreksi') }}",
                        data: ({tabel:'pengguna',kdskpd:kd_skpd,username:username,nama:nama,koreksi:ccek}),
                        dataType:"json"
                    });
                });

            alert("Akun "+ nama +" Sudah Dapat Meng-Akses Jurnal Koreksi");
            $("#modal_j_koreksi").modal('hide');
            data_j_koreksi.ajax.reload();
        }

        
    </script>
@endsection
