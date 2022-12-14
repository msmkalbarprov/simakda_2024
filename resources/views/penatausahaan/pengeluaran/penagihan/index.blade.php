@extends('template.app')
@section('title', 'Penagihan | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('penagihan.create') }}" id="tambah_penagihan" class="btn btn-primary"
                        style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="penagihan" class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 25px">No.</th>
                                        <th style="width: 100px">Nomor Bukti</th>
                                        <th>Tanggal</th>
                                        <th>Rekanan</th>
                                        <th style="width: 100px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($data_penagihan as $data)
                                        <tr>
                                            <td style="width: 25px">{{ $loop->iteration }}</td>
                                            <td style="width: 100px">{{ $data->no_bukti }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tgl_bukti)->locale('id')->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td style="width: 100px">{{ $data->nm_rekanan }}</td>
                                            <td>
                                                <a href="{{ route('penagihan.show', $data->no_bukti) }}"
                                                    class="btn btn-info btn-sm"><i class="fas fa-info-circle"></i></a>
                                                <a href="{{ route('penagihan.edit', $data->no_bukti) }}"
                                                    class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="javascript:void(0);"
                                                    onclick="deleteData('{{ $data->no_bukti }}', '{{ $data->status }}');"
                                                    class="btn btn-danger btn-sm" id="delete"><i
                                                        class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
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

            $('#penagihan').DataTable({
                responsive: true,
                ordering: false,
                serverSide: true,
                processing: true,
                lengthMenu: [5, 10],
                ajax: {
                    "url": "{{ route('penagihan.load_data') }}",
                    "type": "POST",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'no_bukti',
                        name: 'no_bukti'
                    },
                    {
                        data: 'tgl_bukti',
                        name: 'tgl_bukti'
                    },
                    {
                        data: 'nm_rekanan',
                        name: 'nm_rekanan',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        width: 200,
                    },
                ],
            });

            $('#tambah_penagihan').on('click', function() {
                $.ajax({
                    url: "{{ route('penagihan.hapus_semua_tampungan') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {},
                    success: function(data) {}
                })
            })
        });

        function deleteData(no_bukti, status) {
            if (status != '1') {
                alert('Status Bayar Belum Selesai');
                exit();
            }
            let r = confirm('Yakin Ingin Menghapus Data, Nomor Penagihan : ' + no_bukti);
            if (r == true) {
                $.ajax({
                    url: "{{ route('penagihan.hapus_penagihan') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        no_bukti: no_bukti,
                    },
                    success: function(data) {
                        if (data.message == '1') {
                            alert('Data Berhasil Terhapus');
                            location.reload();
                        } else {
                            alert('Gagal Hapus');
                        }
                    }
                })
            } else {
                return false;
            }
        }
    </script>
@endsection
