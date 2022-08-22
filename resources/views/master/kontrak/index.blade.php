@extends('template.app')
@section('title', 'Kontrak | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('kontrak.create') }}" class="btn btn-primary" style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>No Kontrak</th>
                                        <th>Nilai Kontrak</th>
                                        <th>Nama Pekerjaan</th>
                                        <th>Pelaksana Pekerjaan</th>
                                        <th>Tanggal Kontrak</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_kontrak as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->no_kontrak }}</td>
                                            <td>{{ $data->nilai }}</td>
                                            <td>{{ $data->nm_kerja }}</td>
                                            <td>{{ $data->nmpel }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tgl_kerja)->locale('id')->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('kontrak.show', $data->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fas fa-info-circle"></i></a>
                                                <a href="{{ route('kontrak.edit', $data->id) }}"
                                                    class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="javascript:void(0);" onclick="deleteData({{ $data->id }});"
                                                    class="btn btn-danger btn-sm" id="delete"
                                                    data-id={{ $data->id }}><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
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
        });

        function deleteData(id) {
            var r = confirm("Hapus?");
            if (r == true) {
                let url = '{{ route('kontrak.destroy', ':id') }}';
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        id: id,
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
            } else {
                return false;
            }
        }
    </script>
@endsection
