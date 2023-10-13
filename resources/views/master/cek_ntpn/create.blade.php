@extends('template.app')
@section('title', 'CEK NTPN | SIMAKDA')
@section('content')
    <div class="row">
        <div class="col-12">
            @if (session()->has('message'))
                <div class="alert {{ session('alert') ?? 'alert-info' }}">
                    {{ session('message') }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        @csrf

                        <!-- id_billing -->
                        <div class="mb-3 row">
                            <label for="id_billing" class="col-md-2 col-form-label">ID BILLING</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Silahkan isi dengan id_billing"
                                    id="id_billing" name="id_billing" maxlength="16">
                            </div>
                        </div>
                        <!-- Cek NPWP -->
                        <div class="mb-3 row">
                            <label for="cek_npwp" class="col-md-2 col-form-label"></label>
                            <div class="col-md-10">
                                <button type="button" id="cek_npwp" class="btn btn-primary btn-sm">Cek NTPN</button>
                            </div>
                        </div>
                        <!-- Hasil Validasi Bank -->
                        <div class="mb-3 row">
                            <label for="hasil_validasi" class="col-md-12 col-form-label" style="text-align: center;">Hasil
                                Validasi Bank</label>
                            <div class="col-md-10">
                            </div>
                        </div>
                        <!-- NPWP -->
                        <div class="mb-3 row">
                            <label for="ntpn" class="col-md-2 col-form-label">NTPN</label>
                            <div class="col-md-10">
                                <input class="form-control @error('ntpn') is-invalid @enderror" type="text" readonly
                                    id="ntpn" name="ntpn">
                                @error('ntpn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- SIMPAN -->
                        <div style="float: right;">
                            {{-- <button type="submit" id="save" class="btn btn-primary btn-md">Simpan</button> --}}
                            {{-- <a href="{{ route('penerima.index') }}" class="btn btn-warning btn-md">Kembali</a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#jenis').on('select2:select', function() {
                let bank = $('#bank').val();

                if (!bank) {
                    alert('Silahkan pilih Bank terlebih dahulu!');
                    $('#jenis').val(null).change();
                    return;
                }

                let jenis = this.value;

                $('#rekanan').val(null);
                $('#pimpinan').val(null);
                $('#alamat').val(null);
            });

            // $('#rekanan').on('change', function() {
            //     let rekanan = this.value;
            //     let pimpinan = $(this).find(':selected').data('pimpinan');
            //     let alamat = $(this).find(':selected').data('alamat');
            //     $("#nama_rekan").val(rekanan);
            //     $("#pimpinan").val(pimpinan);
            //     $("#alamat").val(alamat);
            // });

            // let bank1 = document.getElementById('bank').value;
            // if (bank1) {
            //     let bic = document.getElementById('bic').value;
            //     let cabang = "{{ old('cabang') }}";
            //     $.ajax({
            //         url: "{{ route('penerima.cabang') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         data: {
            //             "bic": bic,
            //         },
            //         success: function(data) {
            //             $('#cabang').empty();
            //             $('#cabang').append(`<option value="0">Pilih Cabang Pusat</option>`);
            //             $.each(data, function(index, data) {
            //                 if (data.kode == cabang) {
            //                     $('#cabang').append(
            //                         `<option value="${data.kode}" data-nama="${data.nama}" selected>${data.kode} | ${data.nama}</option>`
            //                     );
            //                 } else {
            //                     $('#cabang').append(
            //                         `<option value="${data.kode}" data-nama="${data.nama}">${data.kode} | ${data.nama}</option>`
            //                     );
            //                 }
            //             })
            //         }
            //     })
            // }

            // let kd_map = document.getElementById('kode_akun').value;
            // if (kd_map) {
            //     let kode_setor = "{{ old('kode_setor') }}";
            //     $.ajax({
            //         type: "POST",
            //         url: "{{ route('penerima.kodeSetor') }}",
            //         dataType: 'json',
            //         data: {
            //             "kd_map": kd_map
            //         },
            //         dataType: "json",
            //         success: function(data) {
            //             $('#kode_setor').empty();
            //             $('#kode_setor').append(`<option value="0">Pilih Kode Setor</option>`);
            //             $.each(data, function(index, data) {
            //                 if (data.kd_setor == kode_setor) {
            //                     $('#kode_setor').append(
            //                         `<option value="${data.kd_setor}" selected>${data.nm_setor}</option>`
            //                     );
            //                 } else {
            //                     $('#kode_setor').append(
            //                         `<option value="${data.kd_setor}">${data.nm_setor}</option>`
            //                     );
            //                 }
            //             })
            //         }
            //     })
            // }

            $('#bank').on("select2:select", function() {
                $("#nama_cabang").val("");
                let bic = $(this).find(':selected').data('bic');
                let nm_bank = $(this).find(':selected').data('nama');
                $("#nama_bank").val(nm_bank);
                $("#bic").val(bic);
                $.ajax({
                    url: "{{ route('penerima.cabang') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        bic: bic,
                    },
                    success: function(data) {
                        $('#cabang').empty();
                        $('#cabang').append(`<option value="0">Pilih Cabang Pusat</option>`);
                        $.each(data, function(index, data) {
                            $('#cabang').append(
                                `<option value="${data.kode}" data-nama="${data.nama}">${data.kode} | ${data.nama}</option>`
                            );
                        })
                    }
                })
            });

            $('#cabang').on('select2:select', function() {
                let selected = $(this).find('option:selected');
                let nama_cabang = selected.data('nama');
                $("#nama_cabang").val(nama_cabang);
            });

            $('#kode_akun').on("select2:select", function() {
                let kd_map = this.value;
                $.ajax({
                    type: "POST",
                    url: "{{ route('penerima.kodeSetor') }}",
                    dataType: 'json',
                    data: {
                        kd_map: kd_map
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#kode_setor').empty();
                        $('#kode_setor').append(`<option value="0">Pilih Kode Setor</option>`);
                        $.each(data, function(index, data) {
                            $('#kode_setor').append(
                                `<option value="${data.kd_setor}">${data.nm_setor}</option>`
                            );
                        })
                    }
                })
            });

            $('#cek_rekening').on("click", function() {
                let jenis = document.getElementById('jenis').value;
                let kode_bank = document.getElementById('bank').value;
                let no_rek = document.getElementById('rekening').value;
                let nm_rek = document.getElementById('nm_rekening').value;

                let digit = $('#bank').find('option:selected').data('digit');

                if (!kode_bank) {
                    alert('Bank harus dipilih!');
                    exit;
                }
                if (!no_rek) {
                    alert('No rekening harus diisi!');
                    exit;
                }
                if (!nm_rek) {
                    alert('Nama rekening harus diisi!');
                    exit;
                }

                // if (jenis == '4') {
                //     if (no_rek.length > digit) {
                //         alert('No Rekening melebihi digit inputan rekening, yaitu ' + digit + ' digit!');
                //         return;
                //     }

                //     Swal.fire({
                //         title: 'SUKSES!',
                //         text: 'Rekening bank ' + no_rek + '-' + nm_rek + ' tersimpan',
                //         icon: 'success',
                //         confirmButtonColor: '#5b73e8',
                //     })

                //     $("#no_rekening_validasi").val(no_rek);
                //     $("#nm_rekening_validasi").val(nm_rek);
                //     document.getElementById("save").disabled = false;
                // } else {
                //     if (kode_bank && no_rek && nm_rek) {
                //         swal.fire({
                //             allowOutsideClick: false,
                //             allowEscapeKey: false,
                //             title: 'Proses cek Rekening Bank',
                //             text: 'Silahkan tunggu !!!',
                //             onOpen: function() {
                //                 swal.showLoading()
                //             }
                //         })
                //         $.ajax({
                //             type: "POST",
                //             url: "{{ route('penerima.cekRekening') }}",
                //             dataType: 'json',
                //             data: {
                //                 kode_bank: kode_bank,
                //                 no_rek: no_rek,
                //                 nm_rek: nm_rek,
                //             },
                //             success: function(data) {
                //                 let data1 = $.parseJSON(data);
                //                 if (data1.status) {
                //                     Swal.fire({
                //                         title: 'SUKSES!',
                //                         text: 'Rekening bank ' + data1.data[0].data
                //                             .nomorRekening + '-' + data1.data[0].data
                //                             .namaPemilikRekening + ' tersedia',
                //                         icon: 'success',
                //                         confirmButtonColor: '#5b73e8',
                //                     })

                //                     $("#no_rekening_validasi").val(data1.data[0].data
                //                         .nomorRekening);
                //                     $("#nm_rekening_validasi").val(data1.data[0].data
                //                         .namaPemilikRekening);
                //                     if (jenis == '1') {
                //                         $("#rekanan").val(data1.data[0].data
                //                             .namaPemilikRekening);
                //                     }
                //                     document.getElementById("save").disabled = false;
                //                 } else {
                //                     let pesan = data1.message.replaceAll(" ", "\u00A0");
                //                     Swal.fire({
                //                         type: "error",
                //                         icon: "error",
                //                         title: "Oops...",
                //                         text: pesan,
                //                         confirmButtonClass: "btn btn-confirm mt-2",
                //                     })
                //                     document.getElementById("save").disabled = true;
                //                     $("#no_rekening_validasi").attr("value", '');
                //                     $("#nm_rekening_validasi").attr("value", '');
                //                     $('#rekanan').val(null);
                //                 }
                //             }
                //         })
                //     }
                // }

                if (kode_bank && no_rek && nm_rek) {
                    swal.fire({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        title: 'Proses cek Rekening Bank',
                        text: 'Silahkan tunggu !!!',
                        onOpen: function() {
                            swal.showLoading()
                        }
                    })
                    $.ajax({
                        type: "POST",
                        url: "{{ route('penerima.cekRekening') }}",
                        dataType: 'json',
                        data: {
                            kode_bank: kode_bank,
                            no_rek: no_rek,
                            nm_rek: nm_rek,
                        },
                        success: function(data) {
                            let data1 = $.parseJSON(data);
                            if (data1.status) {
                                Swal.fire({
                                    title: 'SUKSES!',
                                    text: 'Rekening bank ' + data1.data[0].data
                                        .nomorRekening + '-' + data1.data[0].data
                                        .namaPemilikRekening + ' tersedia',
                                    icon: 'success',
                                    confirmButtonColor: '#5b73e8',
                                })

                                $("#no_rekening_validasi").val(data1.data[0].data
                                    .nomorRekening);
                                $("#nm_rekening_validasi").val(data1.data[0].data
                                    .namaPemilikRekening);
                                if (jenis == '1') {
                                    $("#rekanan").val(data1.data[0].data
                                        .namaPemilikRekening);
                                }
                                document.getElementById("save").disabled = false;
                            } else {
                                let pesan = data1.message.replaceAll(" ", "\u00A0");
                                Swal.fire({
                                    type: "error",
                                    icon: "error",
                                    title: "Oops...",
                                    text: pesan,
                                    confirmButtonClass: "btn btn-confirm mt-2",
                                })
                                document.getElementById("save").disabled = true;
                                $("#no_rekening_validasi").attr("value", '');
                                $("#nm_rekening_validasi").attr("value", '');
                                $('#rekanan').val(null);
                            }
                        }
                    })
                }
            });

            $('#cek_npwp').on("click", function() {
                $('#ntpn').val(null)
                let id_billing = document.getElementById('id_billing').value;
                if (!id_billing) {
                    alert('ID BILLING harus diisi!');
                    exit;
                }

                if (id_billing) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('skpd.setor_potongan.cek_billing') }}",
                        dataType: 'json',
                        data: {
                            id_billing: id_billing,
                        },
                        dataType: "json",
                        beforeSend: function() {
                            $("#overlay").fadeIn(100);
                        },
                        success: function(data) {
                            let data1 = $.parseJSON(data);
                            // console.log(data1.data[0].data.ntpn);
                            $('#ntpn').val(data1.data[0].data.ntpn)
                        },
                        complete: function(data) {
                            $("#overlay").fadeOut(100);
                        }
                    })
                }
            });
        });
    </script>
@endsection
