@extends('template.app')
@section('title', 'REKENING KELOMPOK | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="#" class="btn btn-primary" style="float: right;" id="tambah" data-tipe="tambah"
                        {{ Auth::user()->is_admin == '1' ? '' : 'hidden' }}>Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="kelompok" class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Kelompok</th>
                                        <th>Kode Akun</th>
                                        <th>Nama Rekening</th>
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

    <div class="modal fade" id="modal_kelompok">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">REKENING KELOMPOK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="kode_akun" class="col-form-label col-md-4">Kode Akun</label>
                        <div class="col-md-8">
                            <select name="kode_akun" id="kode_akun" class="form-control select2-modal">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($daftar_akun as $akun)
                                    <option value="{{ $akun->kd_rek1 }}">{{ $akun->kd_rek1 }} - {{ $akun->nm_rek1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="kode_rekening" class="col-form-label col-md-4">Kode Rekening</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="kode_rekening">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama_rekening" class="col-form-label col-md-4">Nama Rekening</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_rekening">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="text" hidden readonly id="tipe">
                    <button type="button" class="btn btn-primary" id="simpan">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
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

            $('.select2-modal').select2({
                dropdownParent: $('#modal_kelompok .modal-content'),
                theme: 'bootstrap-5'
            });

            let tabel_kelompok = $('#kelompok').DataTable({
                responsive: true,
                ordering: false,
                serverSide: true,
                processing: true,
                lengthMenu: [10, 20],
                ajax: {
                    "url": "{{ route('kelompok.load') }}",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: "text-center",
                    },
                    {
                        data: 'kd_rek2',
                        name: 'kd_rek2',
                    },
                    {
                        data: 'kd_rek1',
                        name: 'kd_rek1',
                    },
                    {
                        data: 'nm_rek2',
                        name: 'nm_rek2',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        className: 'text-center',
                        width: 150
                    },
                ],
            });

            $('#tambah').on('click', function() {
                $('#kode_akun').val(null).change();
                $('#kode_rekening').val(null);
                $('#kode_rekening').prop('disabled', false);
                $('#nama_rekening').val(null);

                let tipe = $(this).data("tipe");
                $('#tipe').val(tipe);
                $('#modal_kelompok').modal('show');
            });

            $('#simpan').on('click', function() {
                let tipe = document.getElementById('tipe').value;

                let kode_akun = document.getElementById('kode_akun').value;
                let kode_rekening = document.getElementById('kode_rekening').value;
                let nama_rekening = document.getElementById('nama_rekening').value;

                if (!kode_akun) {
                    alert('Silahkan pilih kode akun');
                    return;
                }

                if (!kode_rekening && !nama_rekening) {
                    alert('Silahkan isi kode dan nama rekening');
                    return;
                }

                $.ajax({
                    url: "{{ route('kelompok.simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        kode_akun: kode_akun,
                        kode_rekening: kode_rekening,
                        nama_rekening: nama_rekening,
                        tipe: tipe,
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function() {
                        $("#overlay").fadeIn(100);
                    },
                    success: function(response) {
                        if (response.message == '0') {
                            alert('Data gagal ditambahkan');
                            return;
                        } else if (response.message == '1') {
                            alert('Data berhasil ditambahkan');
                            tabel_kelompok.ajax.reload();
                            $('#kode_akun').val(null).change();
                            $('#kode_rekening').val(null);
                            $('#nama_rekening').val(null);
                            $('#modal_kelompok').modal('hide');
                        } else if (response.message == '2') {
                            alert('Data sudah ada');
                            return;
                        }
                    },
                    complete: function(data) {
                        $("#overlay").fadeOut(100);
                    }
                });
            });
        });

        function edit(kode_akun, kode_rekening, nama_rekening) {
            $('#tipe').val('edit');
            $('#kode_akun').val(kode_akun).change();
            $('#kode_rekening').val(kode_rekening);
            $('#kode_rekening').prop('disabled', true);
            $('#nama_rekening').val(nama_rekening);
            $('#modal_kelompok').modal('show');
        }

        function deleteData(id) {
            let tanya = confirm('Apakah anda yakin untuk menghapus data ini ?');
            let tabel = $('#kelompok').DataTable();
            if (tanya == true) {
                $.ajax({
                    url: "{{ route('kelompok.hapus') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        kode_rekening: id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data.message == '1') {
                            alert('Data Berhasil dihapus');
                            tabel.ajax.reload();
                        } else {
                            alert('Proses Hapus Gagal...!!!');
                        }
                    }
                })
            } else {
                return false;
            }
        }
    </script>
@endsection
