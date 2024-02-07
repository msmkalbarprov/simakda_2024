@extends('template.app')
@section('title', 'Konfigurasi SPD | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Konfigurasi SPD
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="konfig_spd" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>No. Konfig</th>
                                        <th>Jenis Anggaran</th>
                                        <th>Aksi</th>
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
@section('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#konfig_spd').DataTable({
                responsive: true,
                ordering: false,
                serverSide: true,
                processing: true,
                lengthMenu: [5, 10],
                ajax: {
                    "url": "{{ route('konfigurasi_spd.load') }}",
                    "type": "POST",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: "text-center",
                    },
                    {
                        data: 'no_konfig_spd',
                        name: 'no_konfig_spd',
                    },
                    {
                        data: 'nama_ang',
                        name: 'nama_ang',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        className: 'text-center',
                        width: 150
                    },
                ],
            });
        });

        function deleteData(id) {
            var r = confirm("Hapus?");
            if (r == true) {
                $.ajax({
                    url: "{{ route('penerima.cekPenerima') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        id: id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data > 0) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'warning',
                                title: 'Data Penerima telah digunakan di kontrak',
                                width: 300,
                                height: 300,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            let url = '{{ route('penerima.destroy', ':id') }}';
                            url = url.replace(':id', id);
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                data: {
                                    id: id,
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(data) {
                                    if (data.message == '1') {
                                        alert('Data berhasil dihapus!');
                                        window.location.reload();
                                    } else {
                                        alert('Data gagal dihapus!');
                                    }
                                }
                            });
                        }
                    }
                })
            } else {
                return false;
            }
        }
    </script>
@endsection
