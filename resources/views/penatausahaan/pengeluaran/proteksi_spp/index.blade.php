@extends('template.app')
@section('title', 'PROTEKSI SPP | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    PROTEKSI SPP
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="spp_ls" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 25px">No.</th>
                                        <th style="width: 150px">Nomor SPP</th>
                                        <th style="width: 100px">Tanggal</th>
                                        <th style="width: 100px">Keterangan</th>
                                        <th style="width: 200px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
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

            $('#spp_ls').DataTable({
                responsive: true,
                ordering: false,
                serverSide: true,
                processing: true,
                lengthMenu: [10, 20, 50],
                ajax: {
                    "url": "{{ route('proteksi_spp.load_data') }}",
                    "type": "POST",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'no_spp',
                        name: 'no_spp'
                    },
                    {
                        data: 'tgl_spp',
                        name: 'tgl_spp'
                    },
                    {
                        data: null,
                        name: 'keperluan',
                        render: function(data, type, row, meta) {
                            return data.keperluan.substr(0, 10) + '.....';
                        }
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        width: '200px',
                        className: 'text-center'
                    },
                ],
            });
        });
    </script>
@endsection
