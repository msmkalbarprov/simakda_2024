@extends('template.app')
@section('title', 'Tambah SPP LS | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Data Penagihan
                </div>
                <div class="card-body">
                    @csrf
                    {{-- No Tersimpan dan Tanggal SPP --}}
                    <div class="mb-3 row">
                        <label for="no_tersimpan" class="col-md-2 col-form-label">No. Tersimpan</label>
                        <div class="col-md-4">
                            <input class="form-control @error('no_tersimpan') is-invalid @enderror" type="text"
                                id="no_tersimpan" name="no_tersimpan" required readonly>
                            @error('no_tersimpan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="tgl_spp" class="col-md-2 col-form-label">Tanggal SPP</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control @error('tgl_spp') is-invalid @enderror"
                                value="{{ old('tgl_spp') }}" id="tgl_spp" name="tgl_spp">
                            @error('tgl_spp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- No SPP dan Bulan --}}
                    <div class="mb-3 row">
                        <label for="no_spp" class="col-md-2 col-form-label">No. SPP</label>
                        <div class="col-md-4">
                            <input class="form-control @error('no_spp') is-invalid @enderror" type="text" id="no_spp"
                                name="no_spp" required>
                            @error('no_spp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="bulan" class="col-md-2 col-form-label">Bulan</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('bulan') is-invalid @enderror"
                                style="width: 100%" id="bulan" name="bulan" data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Bulan">
                                    <option value="" disabled selected>...Pilih Kebutuhan Bulan... </option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </optgroup>
                            </select>
                            @error('bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- KD SKPD dan Keperluan --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">Kode SKPD/Unit</label>
                        <div class="col-md-4">
                            <input class="form-control @error('kd_skpd') is-invalid @enderror" type="text" id="kd_skpd"
                                name="kd_skpd" required readonly value="{{ $data_skpd->kd_skpd }}">
                            @error('kd_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="keperluan" class="col-md-2 col-form-label">Keperluan</label>
                        <div class="col-md-4">
                            <textarea type="text" class="form-control @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan"></textarea>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Nama SKPD dan Bank --}}
                    <div class="mb-3 row">
                        <label for="nm_skpd" class="col-md-2 col-form-label">Nama SKPD/Unit</label>
                        <div class="col-md-4">
                            <input class="form-control @error('nm_skpd') is-invalid @enderror" type="text" id="nm_skpd"
                                name="nm_skpd" required readonly value="{{ $data_skpd->nm_skpd }}">
                            @error('nm_skpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="bank" class="col-md-2 col-form-label">Bank</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('bank') is-invalid @enderror"
                                style="width: 100%;" id="bank" name="bank" data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Bank">
                                    <option value="" disabled selected>Silahkan Pilih Bank</option>
                                    @foreach ($daftar_bank as $bank)
                                        <option value="{{ $bank->kode }}" data-nama="{{ $bank->nama }}"
                                            {{ old('bank') == $bank->kode ? 'selected' : '' }}>
                                            {{ $bank->kode }} | {{ $bank->nama }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Beban dan Rekanan --}}
                    <div class="mb-3 row">
                        <label for="beban" class="col-md-2 col-form-label">Beban</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('beban') is-invalid @enderror"
                                style="width: 100%" id="beban" name="beban" data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Beban">
                                    <option value="" disabled selected>...Pilih Beban... </option>
                                    <option value="4">LS GAJI</option>
                                    <option value="6">LS Barang Jasa</option>
                                    <option value="5">LS Piihak Ketiga Lainnya</option>
                                </optgroup>
                            </select>
                            @error('beban')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="rekanan" class="col-md-2 col-form-label">Rekanan</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('rekanan') is-invalid @enderror"
                                style="width: 100%;" id="rekanan" name="rekanan" data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Rekanan">
                                    <option value="" disabled selected>Silahkan Pilih Rekanan</option>
                                    @foreach ($daftar_rekanan as $rekanan)
                                        <option value="{{ $rekanan->nmrekan }}" data-pimpinan="{{ $rekanan->pimpinan }}"
                                            data-alamat="{{ $rekanan->alamat }}"
                                            {{ old('rekanan') == $rekanan->nmrekan ? 'selected' : '' }}>
                                            {{ $rekanan->nmrekan }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('rekanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Jenis dan Pimpinan --}}
                    <div class="mb-3 row">
                        <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('jenis') is-invalid @enderror"
                                style=" width: 100%;" id="jenis" name="jenis" data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Jenis">
                                </optgroup>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="pimpinan" class="col-md-2 col-form-label">Pimpinan</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('pimpinan') is-invalid @enderror"
                                value="{{ old('pimpinan') }}" id="pimpinan" name="pimpinan" readonly>
                            @error('pimpinan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Nomor SPD dan Alamat Perusahaan --}}
                    <div class="mb-3 row">
                        <label for="nomor_spd" class="col-md-2 col-form-label">Nomor SPD</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('nomor_spd') is-invalid @enderror"
                                style=" width: 100%;" id="nomor_spd" name="nomor_spd" data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Nomor SPD">
                                </optgroup>
                            </select>
                            @error('nomor_spd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="alamat" class="col-md-2 col-form-label">Alamat Perusahaan</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                value="{{ old('alamat') }}" id="alamat" name="alamat" readonly>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Tanggal SPD dan Nama Penerima --}}
                    <div class="mb-3 row">
                        <label for="tgl_spd" class="col-md-2 col-form-label">Tanggal SPD</label>
                        <div class="col-md-4">
                            <input class="form-control @error('tgl_spd') is-invalid @enderror" type="date"
                                id="tgl_spd" name="tgl_spd" required readonly>
                            @error('tgl_spd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="nm_penerima" class="col-md-2 col-form-label">Nama Penerima</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('nm_penerima') is-invalid @enderror"
                                style="width: 100%;" id="nm_penerima" name="nm_penerima"
                                data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Penerima">
                                    <option value="" disabled selected>Silahkan Pilih Penerima</option>
                                    @foreach ($daftar_penerima as $penerima)
                                        <option value="{{ $penerima->nm_rekening }}" data-npwp="{{ $penerima->npwp }}"
                                            data-rekening="{{ $penerima->rekening }}"
                                            {{ old('nm_penerima') == $penerima->nm_rekening ? 'selected' : '' }}>
                                            {{ $penerima->nm_rekening }} | {{ $penerima->rekening }} |
                                            {{ $penerima->npwp }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('nm_penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Kode Sub Kegiatan dan Rekening --}}
                    <div class="mb-3 row">
                        <label for="kd_sub_kegiatan" class="col-md-2 col-form-label">Kode Sub Kegiatan</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('kd_sub_kegiatan') is-invalid @enderror"
                                style=" width: 100%;" id="kd_sub_kegiatan" name="kd_sub_kegiatan"
                                data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Sub Kegiatan">
                                </optgroup>
                            </select>
                            @error('kd_sub_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="rekening" class="col-md-2 col-form-label">Rekening</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('rekening') is-invalid @enderror"
                                value="{{ old('rekening') }}" id="rekening" name="rekening" readonly>
                            @error('rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Nama Sub Kegiatan dan NPWP --}}
                    <div class="mb-3 row">
                        <label for="nm_sub_kegiatan" class="col-md-2 col-form-label">Nama Sub Kegiatan</label>
                        <div class="col-md-4">
                            <input class="form-control @error('nm_sub_kegiatan') is-invalid @enderror" type="text"
                                id="nm_sub_kegiatan" name="nm_sub_kegiatan" required readonly>
                            @error('nm_sub_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="npwp" class="col-md-2 col-form-label">NPWP</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('npwp') is-invalid @enderror"
                                value="{{ old('npwp') }}" id="npwp" name="npwp" readonly>
                            @error('npwp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Tanggal Mulai dan Tanggal Akhir --}}
                    <div class="mb-3 row">
                        <label for="tgl_awal" class="col-md-2 col-form-label">Tanggal Mulai</label>
                        <div class="col-md-4">
                            <input class="form-control @error('tgl_awal') is-invalid @enderror" type="date"
                                id="tgl_awal" name="tgl_awal" required>
                            @error('tgl_awal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="tgl_akhir" class="col-md-2 col-form-label">Tanggal Akhir</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control @error('tgl_akhir') is-invalid @enderror"
                                value="{{ old('tgl_akhir') }}" id="tgl_akhir" name="tgl_akhir">
                            @error('tgl_akhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- Lanjut dan Nomor Kontrak --}}
                    <div class="mb-3 row">
                        <label for="lanjut" class="col-md-2 col-form-label">Lanjut</label>
                        <div class="col-md-4">
                            <select class="form-control select2-multiple @error('lanjut') is-invalid @enderror"
                                style="width: 100%" id="lanjut" name="lanjut" data-placeholder="Silahkan Pilih">
                                <optgroup label="Daftar Pilihan">
                                    <option value="" disabled selected>...Pilih... </option>
                                    <option value="1">YA</option>
                                    <option value="2">TIDAK</option>
                                </optgroup>
                            </select>
                            @error('lanjut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="no_kontrak" class="col-md-2 col-form-label">Nomor Kontrak</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control @error('no_kontrak') is-invalid @enderror"
                                value="{{ old('no_kontrak') }}" id="no_kontrak" name="no_kontrak">
                            @error('no_kontrak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- SIMPAN -->
                    <div style="float: right;">
                        <button id="simpan_penagihan" class="btn btn-primary btn-md">Simpan</button>
                        <a href="{{ route('sppls.index') }}" class="btn btn-warning btn-md">Kembali</a>
                    </div>
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

            $('.select2-multiple').select2({
                theme: 'bootstrap-5'
            });

            $('#rekanan').on('change', function() {
                let pimpinan = $(this).find(':selected').data('pimpinan');
                let alamat = $(this).find(':selected').data('alamat');
                $("#pimpinan").val(pimpinan);
                $("#alamat").val(alamat);
            });

            $('#nm_penerima').on('change', function() {
                let rekening = $(this).find(':selected').data('rekening');
                let npwp = $(this).find(':selected').data('npwp');
                $("#rekening").val(rekening);
                $("#npwp").val(npwp);
            });

            $('#beban').on('change', function() {
                let beban = this.value;
                let tgl_spp = document.getElementById('tgl_spp').value;
                if (!tgl_spp) {
                    alert('Pilih tanggal SPD terlebih dahulu!');
                    return;
                }
                // cari jenis
                $.ajax({
                    url: "{{ route('sppls.cari_jenis') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        beban: beban,
                    },
                    success: function(data) {
                        $('#jenis').empty();
                        $('#jenis').append(`<option value="0">Silahkan Pilih</option>`);
                        $.each(data, function(index, data) {
                            $('#jenis').append(
                                `<option value="${data.id}" data-nama="${data.text}">${data.text}</option>`
                            );
                        })
                    }
                })
                // cari nomor spd
                $.ajax({
                    url: "{{ route('sppls.cari_nomor_spd') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        beban: beban,
                        tgl_spp: tgl_spp,
                    },
                    success: function(data) {
                        $('#nomor_spd').empty();
                        $('#nomor_spd').append(`<option value="0">Silahkan Pilih</option>`);
                        $.each(data, function(index, data) {
                            $('#nomor_spd').append(
                                `<option value="${data.no_spd}" data-tgl="${data.tgl_spd}" data-total="${data.total}">${data.no_spd} | ${data.tgl_spd} | ${data.total}</option>`
                            );
                        })
                    }
                })
            });

            $('#nomor_spd').on('change', function() {
                let spd = this.value;
                let tgl = $(this).find(':selected').data('tgl');
                let total = $(this).find(':selected').data('total');
                $("#tgl_spd").val(tgl);
                // cari kode sub kegiatan
                $.ajax({
                    url: "{{ route('sppls.cari_sub_kegiatan') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        spd: spd,
                    },
                    success: function(data) {
                        $('#kd_sub_kegiatan').empty();
                        $('#kd_sub_kegiatan').append(
                            `<option value="0">Silahkan Pilih</option>`);
                        $.each(data, function(index, data) {
                            $('#kd_sub_kegiatan').append(
                                `<option value="${data.kd_sub_kegiatan}" data-nama="${data.nm_sub_kegiatan}">${data.kd_sub_kegiatan} | ${data.nm_sub_kegiatan}</option>`
                            );
                        })
                    }
                })
            });

            $('#kd_sub_kegiatan').on('change', function() {
                let nama = $(this).find(':selected').data('nama');
                $("#nm_sub_kegiatan").val(nama);
            });
        });
    </script>
@endsection
