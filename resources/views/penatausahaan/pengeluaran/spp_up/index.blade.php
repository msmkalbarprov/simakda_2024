@extends('template.app')
@section('title', 'SPP UP | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('sppup.create') }}" id="tambah_spp_ls" class="btn btn-primary"
                        style="float: right;">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="spp_ls" class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 25px">No.</th>
                                        <th style="width: 150px">Nomor SPP</th>
                                        <th style="width: 100px">Tanggal</th>
                                        <th style="width: 100px">Keterangan</th>
                                        <th style="width: 200px;text-align:center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_spp as $data)
                                        <tr>
                                            <td style="width: 25px">{{ $loop->iteration }}</td>
                                            <td style="width: 150px">{{ $data->no_spp }}</td>
                                            <td style="width: 100px">
                                                {{ \Carbon\Carbon::parse($data->tgl_spp)->locale('id')->isoFormat('D MMMM Y') }}
                                            </td>
                                            <td style="width: 100px">{{ Str::limit($data->keperluan, 20) }}</td>
                                            <td></td>
                                            {{-- <td style="width: 200px">
                                                <a href="{{ route('sppls.show', $data->no_spp) }}"
                                                    class="btn btn-info btn-sm"><i class="fas fa-info-circle"></i></a>
                                                <a href="{{ route('sppls.edit', $data->no_spp) }}"
                                                    class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                <button type="button"
                                                    onclick="cetak('{{ $data->no_spp }}', '{{ $data->jns_spp }}', '{{ $data->kd_skpd }}')"
                                                    class="btn btn-success btn-sm"><i class="uil-print"></i></button>
                                                @if ($data->status == 0)
                                                    <a href="javascript:void(0);"
                                                        onclick="deleteData('{{ $data->no_spp }}');"
                                                        class="btn btn-danger btn-sm" id="delete"><i
                                                            class="fas fa-trash-alt"></i></a>
                                                    <button type="button"
                                                        onclick="batal_spp('{{ $data->no_spp }}', '{{ $data->jns_spp }}', '{{ $data->kd_skpd }}')"
                                                        class="btn btn-success btn-sm"><i class="uil-ban"></i></button>
                                                @endif
                                            </td> --}}
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
    @include('penatausahaan.pengeluaran.spp_up.js.index')
@endsection
