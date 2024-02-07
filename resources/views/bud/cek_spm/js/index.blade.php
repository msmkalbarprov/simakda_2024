<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2-multiple').select2({
            placeholder: "Silahkan Pilih",
            theme: 'bootstrap-5'
        });

        let daftar_spm = $('#tabel_spm').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ route('cek_spm.load') }}",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "data": function(d) {
                    d.kd_skpd = document.getElementById('kd_skpd').value;
                    d.jenis = document.getElementById('jenis').value;
                }
            },
            ordering: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: "text-center",
                },
                {
                    data: 'nm_skpd',
                    name: 'nm_skpd',
                },
                {
                    data: 'no_spm',
                    name: 'no_spm',
                },
                {
                    data: null,
                    name: 'status',
                    render: function(data, type, row, meta) {
                        if (data.status == 0) {
                            return 'BELUM SP2D';
                        } else {
                            return 'SUDAH SP2D'
                        }
                    }
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    className: 'text-center'
                },
            ]
        });

        $('#jenis').on('select2:select', function() {
            daftar_spm.ajax.reload();
        });
        $('#kd_skpd').on('select2:select', function() {
            daftar_spm.ajax.reload();
        });

        $('#simpan').on('click', function() {
            let no_spm = document.getElementById('no_spm').value;
            let kd_skpd = document.getElementById('kd_skpd').value;
            let tgl_verifikasi = document.getElementById('tgl_verifikasi').value;
            let keterangan_verifikasi = document.getElementById('keterangan_verifikasi').value;
            let status_verifikasi = document.getElementById('status_verifikasi').value;
            let beban = document.getElementById('beban').value;
            let jenis_beban = document.getElementById('jenis_beban').value;
            let jenis_kelengkapan = document.getElementById('jenis_kelengkapan').value;
            let tahun_anggaran = "{{ tahun_anggaran() }}";

            let tahun_input = tgl_verifikasi.substr(0, 4);

            if (!no_spm || !kd_skpd) {
                alert('No SPM atau SKPD tidak boleh kosong!');
                return;
            }

            if (!tgl_verifikasi) {
                alert('Silahkan pilih tanggal verifikasi');
                return
            }

            if (tahun_anggaran != tahun_input) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (!keterangan_verifikasi) {
                alert('Silahkan isi keterangan verifikasi');
                return;
            }

            if (keterangan_verifikasi.length > 1000) {
                alert('Keterangan maksimal 1000 karakter');
                return;
            }

            if (!status_verifikasi) {
                alert('Silahkan pilih status!');
                return;
            }

            // UP
            if (beban == '1') {
                let pengantar_spp_up = document.getElementById('pengantar_spp_up').checked;
                let spp_up = document.getElementById('spp_up').checked;
                let ringkasan_spp_up = document.getElementById('ringkasan_spp_up').checked;
                let rincian_spp_up = document.getElementById('rincian_spp_up').checked;
                let pernyataan_pengajuan_up = document.getElementById('pernyataan_pengajuan_up')
                    .checked;
                let lampiran_spp_up = document.getElementById('lampiran_spp_up').checked;
                let salinan_spd_up = document.getElementById('salinan_spd_up').checked;
                let rekening_koran_up = document.getElementById('rekening_koran_up').checked;
                let keputusan_gubernur_up = document.getElementById('keputusan_gubernur_up').checked;

                if ((!pengantar_spp_up && !spp_up && !ringkasan_spp_up && !rincian_spp_up && !
                        pernyataan_pengajuan_up && !lampiran_spp_up && !salinan_spd_up && !
                        rekening_koran_up && !keputusan_gubernur_up) && status_verifikasi == '1') {
                    alert('Silahkan centang, jika ingin diterima');
                    return
                }

                let tanya = confirm("Apakah data yang diverifikasi sudah benar ?");
                if (tanya == true) {
                    $('#simpan').prop("disabled", true);
                    $.ajax({
                        url: "{{ route('cek_spm.simpan_up') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            no_spm: no_spm,
                            kd_skpd: kd_skpd,
                            tgl_verifikasi: tgl_verifikasi,
                            keterangan_verifikasi: keterangan_verifikasi,
                            pengantar_spp_up: pengantar_spp_up,
                            spp_up: spp_up,
                            ringkasan_spp_up: ringkasan_spp_up,
                            rincian_spp_up: rincian_spp_up,
                            pernyataan_pengajuan_up: pernyataan_pengajuan_up,
                            lampiran_spp_up: lampiran_spp_up,
                            salinan_spd_up: salinan_spd_up,
                            rekening_koran_up: rekening_koran_up,
                            keputusan_gubernur_up: keputusan_gubernur_up,
                            status_verifikasi: status_verifikasi,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            $("#overlay").fadeIn(100);
                        },
                        success: function(data) {
                            $('#simpan').prop("disabled", false);
                            if (data.message == '1') {
                                alert('Data berhasil diverifikasi');
                                $('#detail_spm').modal('hide');
                                // document.getElementById("form_detail").reset();
                                daftar_spm.ajax.reload();
                            } else {
                                alert('Data tidak berhasil diverifikasi!');
                            }
                        },
                        complete: function(data) {
                            $("#overlay").fadeOut(100);
                        }
                    })
                } else {
                    alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
                    $('#simpan').prop("disabled", false);
                }
            }

            // GU
            if (beban == '2') {
                let pengantar_spp_gu = document.getElementById('pengantar_spp_gu').checked;
                let spp_gu = document.getElementById('spp_gu').checked;
                let ringkasan_spp_gu = document.getElementById('ringkasan_spp_gu').checked;
                let rincian_spp_gu = document.getElementById('rincian_spp_gu').checked;
                let pernyataan_pengajuan_gu = document.getElementById('pernyataan_pengajuan_gu')
                    .checked;
                let lampiran_spp_gu = document.getElementById('lampiran_spp_gu').checked;
                let salinan_spd_gu = document.getElementById('salinan_spd_gu').checked;
                let lpj_gu = document.getElementById('lpj_gu').checked;
                let sptb_gu = document.getElementById('sptb_gu').checked;
                let sse_gu = document.getElementById('sse_gu').checked;

                if ((!pengantar_spp_gu && !spp_gu && !ringkasan_spp_gu && !rincian_spp_gu && !
                        pernyataan_pengajuan_gu && !lampiran_spp_gu && !salinan_spd_gu && !
                        lpj_gu && !sptb_gu && !sse_gu) && status_verifikasi == '1') {
                    alert('Silahkan centang, jika ingin diterima');
                    return
                }

                let tanya = confirm("Apakah data yang diverifikasi sudah benar ?");
                if (tanya == true) {
                    $('#simpan').prop("disabled", true);
                    $.ajax({
                        url: "{{ route('cek_spm.simpan_gu') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            no_spm: no_spm,
                            kd_skpd: kd_skpd,
                            tgl_verifikasi: tgl_verifikasi,
                            keterangan_verifikasi: keterangan_verifikasi,
                            pengantar_spp_gu: pengantar_spp_gu,
                            spp_gu: spp_gu,
                            ringkasan_spp_gu: ringkasan_spp_gu,
                            rincian_spp_gu: rincian_spp_gu,
                            pernyataan_pengajuan_gu: pernyataan_pengajuan_gu,
                            lampiran_spp_gu: lampiran_spp_gu,
                            salinan_spd_gu: salinan_spd_gu,
                            lpj_gu: lpj_gu,
                            sptb_gu: sptb_gu,
                            sse_gu: sse_gu,
                            status_verifikasi: status_verifikasi,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            $("#overlay").fadeIn(100);
                        },
                        success: function(data) {
                            $('#simpan').prop("disabled", false);
                            if (data.message == '1') {
                                alert('Data berhasil diverifikasi');
                                $('#detail_spm').modal('hide');
                                // document.getElementById("form_detail").reset();
                                daftar_spm.ajax.reload();
                            } else {
                                alert('Data tidak berhasil diverifikasi!');
                            }
                        },
                        complete: function(data) {
                            $("#overlay").fadeOut(100);
                        }
                    })
                } else {
                    alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
                    $('#simpan').prop("disabled", false);
                }
            }

            // TU
            if (beban == '3') {
                let pengantar_spp_tu = document.getElementById('pengantar_spp_tu').checked;
                let spp_tu = document.getElementById('spp_tu').checked;
                let ringkasan_spp_tu = document.getElementById('ringkasan_spp_tu').checked;
                let rencana_penggunaan_tu = document.getElementById('rencana_penggunaan_tu').checked;
                let pernyataan_pengajuan_tu = document.getElementById('pernyataan_pengajuan_tu')
                    .checked;
                let lampiran_spp_tu = document.getElementById('lampiran_spp_tu').checked;
                let salinan_spd_tu = document.getElementById('salinan_spd_tu').checked;
                let jadwal_pelaksanaan_kegiatan_tu = document.getElementById(
                    'jadwal_pelaksanaan_kegiatan_tu').checked;
                let rekening_koran_tu = document.getElementById('rekening_koran_tu').checked;
                let lpj_untuk_tu = document.getElementById('lpj_untuk_tu').checked;
                let sptb_tu = document.getElementById('sptb_tu').checked;
                let sse_tu = document.getElementById('sse_tu').checked;
                let bukti_setor_tu = document.getElementById('bukti_setor_tu').checked;
                let dokumen_lain_tu = document.getElementById('dokumen_lain_tu').checked;

                if ((!pengantar_spp_tu && !spp_tu && !ringkasan_spp_tu && !rencana_penggunaan_tu && !
                        pernyataan_pengajuan_tu && !lampiran_spp_tu && !salinan_spd_tu && !
                        jadwal_pelaksanaan_kegiatan_tu && !rekening_koran_tu && !lpj_untuk_tu && !
                        sptb_tu &&
                        !sse_tu && !bukti_setor_tu && !dokumen_lain_tu) && status_verifikasi == '1') {
                    alert('Silahkan centang, jika ingin diterima');
                    return
                }

                let tanya = confirm("Apakah data yang diverifikasi sudah benar ?");
                if (tanya == true) {
                    $('#simpan').prop("disabled", true);
                    $.ajax({
                        url: "{{ route('cek_spm.simpan_tu') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            no_spm: no_spm,
                            kd_skpd: kd_skpd,
                            tgl_verifikasi: tgl_verifikasi,
                            keterangan_verifikasi: keterangan_verifikasi,
                            pengantar_spp_tu: pengantar_spp_tu,
                            spp_tu: spp_tu,
                            ringkasan_spp_tu: ringkasan_spp_tu,
                            rencana_penggunaan_tu: rencana_penggunaan_tu,
                            pernyataan_pengajuan_tu: pernyataan_pengajuan_tu,
                            lampiran_spp_tu: lampiran_spp_tu,
                            salinan_spd_tu: salinan_spd_tu,
                            jadwal_pelaksanaan_kegiatan_tu: jadwal_pelaksanaan_kegiatan_tu,
                            rekening_koran_tu: rekening_koran_tu,
                            lpj_untuk_tu: lpj_untuk_tu,
                            sptb_tu: sptb_tu,
                            sse_tu: sse_tu,
                            bukti_setor_tu: bukti_setor_tu,
                            dokumen_lain_tu: dokumen_lain_tu,
                            status_verifikasi: status_verifikasi,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            $("#overlay").fadeIn(100);
                        },
                        success: function(data) {
                            $('#simpan').prop("disabled", false);
                            if (data.message == '1') {
                                alert('Data berhasil diverifikasi');
                                $('#detail_spm').modal('hide');
                                // document.getElementById("form_detail").reset();
                                daftar_spm.ajax.reload();
                            } else {
                                alert('Data tidak berhasil diverifikasi!');
                            }
                        },
                        complete: function(data) {
                            $("#overlay").fadeOut(100);
                        }
                    })
                } else {
                    alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
                    $('#detail_spm').modal('hide');
                    // document.getElementById("form_detail").reset();
                    daftar_spm.ajax.reload();
                    $('#simpan').prop("disabled", false);
                }
            }

            // LS GAJI
            if (beban == '4') {
                let pengantar_spp_gaji = document.getElementById('pengantar_spp_gaji').checked;
                let spp_gaji = document.getElementById('spp_gaji').checked;
                let ringkasan_spp_gaji = document.getElementById('ringkasan_spp_gaji').checked;
                let rincian_spp_gaji = document.getElementById('rincian_spp_gaji').checked;
                let pernyataan_pengajuan_gaji = document.getElementById('pernyataan_pengajuan_gaji')
                    .checked;
                let lampiran_spp_gaji = document.getElementById('lampiran_spp_gaji').checked;
                let salinan_spd_gaji = document.getElementById('salinan_spd_gaji').checked;
                let daftar_gaji = document.getElementById('daftar_gaji').checked;
                let rekap_gaji_induk = document.getElementById('rekap_gaji_induk').checked;
                let rekap_gaji_golongan = document.getElementById('rekap_gaji_golongan').checked;
                let sse_gaji = document.getElementById('sse_gaji').checked;
                let sk_perubahan_gaji = document.getElementById('sk_perubahan_gaji').checked;
                let sk_kenaikan_gaji = document.getElementById('sk_kenaikan_gaji').checked;
                let sk_struktural_gaji = document.getElementById('sk_struktural_gaji').checked;
                let keputusan_kenaikan_gaji = document.getElementById('keputusan_kenaikan_gaji')
                    .checked;
                let keputusan_pindah_gaji = document.getElementById('keputusan_pindah_gaji').checked;
                let daftar_keluarga_gaji = document.getElementById('daftar_keluarga_gaji').checked;
                let pernyataan_tugas_gaji = document.getElementById('pernyataan_tugas_gaji').checked;
                let cerai_gaji = document.getElementById('cerai_gaji').checked;
                let sk_pengangkatan_gaji = document.getElementById('sk_pengangkatan_gaji').checked;
                let sptjm_gaji = document.getElementById('sptjm_gaji').checked;
                let sk_mutasi_gaji = document.getElementById('sk_mutasi_gaji').checked;
                let skpp_gaji = document.getElementById('skpp_gaji').checked;

                if ((!pengantar_spp_gaji && !spp_gaji && !ringkasan_spp_gaji && !rincian_spp_gaji &&
                        !
                        pernyataan_pengajuan_gaji && !lampiran_spp_gaji && !salinan_spd_gaji && !
                        daftar_gaji && !rekap_gaji_induk && !rekap_gaji_golongan && !sse_gaji &&
                        !sk_perubahan_gaji && !sk_kenaikan_gaji && !sk_struktural_gaji && !
                        keputusan_kenaikan_gaji && !keputusan_pindah_gaji && !daftar_keluarga_gaji && !
                        pernyataan_tugas_gaji && !cerai_gaji && !sk_pengangkatan_gaji && !
                        sptjm_gaji && !sk_mutasi_gaji && !skpp_gaji) && status_verifikasi == '1') {
                    alert('Silahkan centang, jika ingin diterima');
                    return;
                }

                let tanya = confirm("Apakah data yang diverifikasi sudah benar ?");
                if (tanya == true) {
                    $('#simpan').prop("disabled", true);
                    $.ajax({
                        url: "{{ route('cek_spm.simpan_gaji') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            jenis_beban: jenis_beban,
                            jenis_kelengkapan: jenis_kelengkapan,
                            no_spm: no_spm,
                            kd_skpd: kd_skpd,
                            tgl_verifikasi: tgl_verifikasi,
                            keterangan_verifikasi: keterangan_verifikasi,
                            pengantar_spp_gaji: pengantar_spp_gaji,
                            spp_gaji: spp_gaji,
                            ringkasan_spp_gaji: ringkasan_spp_gaji,
                            rincian_spp_gaji: rincian_spp_gaji,
                            pernyataan_pengajuan_gaji: pernyataan_pengajuan_gaji,
                            lampiran_spp_gaji: lampiran_spp_gaji,
                            salinan_spd_gaji: salinan_spd_gaji,
                            daftar_gaji: daftar_gaji,
                            rekap_gaji_induk: rekap_gaji_induk,
                            rekap_gaji_golongan: rekap_gaji_golongan,
                            sse_gaji: sse_gaji,
                            sk_perubahan_gaji: sk_perubahan_gaji,
                            sk_kenaikan_gaji: sk_kenaikan_gaji,
                            sk_struktural_gaji: sk_struktural_gaji,
                            keputusan_kenaikan_gaji: keputusan_kenaikan_gaji,
                            keputusan_pindah_gaji: keputusan_pindah_gaji,
                            daftar_keluarga_gaji: daftar_keluarga_gaji,
                            pernyataan_tugas_gaji: pernyataan_tugas_gaji,
                            cerai_gaji: cerai_gaji,
                            sk_pengangkatan_gaji: sk_pengangkatan_gaji,
                            sptjm_gaji: sptjm_gaji,
                            sk_mutasi_gaji: sk_mutasi_gaji,
                            skpp_gaji: skpp_gaji,
                            status_verifikasi: status_verifikasi,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            $("#overlay").fadeIn(100);
                        },
                        success: function(data) {
                            $('#simpan').prop("disabled", false);
                            if (data.message == '1') {
                                alert('Data berhasil diverifikasi');
                                $('#detail_spm').modal('hide');
                                // document.getElementById("form_detail").reset();
                                daftar_spm.ajax.reload();
                            } else {
                                alert('Data tidak berhasil diverifikasi!');
                            }
                        },
                        complete: function(data) {
                            $("#overlay").fadeOut(100);
                        }
                    })
                } else {
                    alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
                    $('#detail_spm').modal('hide');
                    // document.getElementById("form_detail").reset();
                    daftar_spm.ajax.reload();
                    $('#simpan').prop("disabled", false);
                }
            }

            // LS PIHAK KETIGA
            if (beban == '5') {
                let pengantar_spp_ketiga = document.getElementById('pengantar_spp_ketiga').checked;
                let spp_ketiga = document.getElementById('spp_ketiga').checked;
                let ringkasan_spp_ketiga = document.getElementById('ringkasan_spp_ketiga').checked;
                let rincian_spp_ketiga = document.getElementById('rincian_spp_ketiga').checked;
                let pernyataan_ketiga = document.getElementById('pernyataan_ketiga').checked;
                let lampiran_spp_ketiga = document.getElementById('lampiran_spp_ketiga').checked;
                let proposal_bansos_ketiga = document.getElementById('proposal_bansos_ketiga').checked;
                let kepgub_bansos_ketiga = document.getElementById('kepgub_bansos_ketiga').checked;
                let nphd_ketiga = document.getElementById('nphd_ketiga').checked;
                let kab_ketiga = document.getElementById('kab_ketiga').checked;
                let penerima_bansos_ketiga = document.getElementById('penerima_bansos_ketiga').checked;
                let penerima_hibah_ketiga = document.getElementById('penerima_hibah_ketiga').checked;
                let sptjm_hibah_ketiga = document.getElementById('sptjm_hibah_ketiga').checked;
                let sptjm_bansos_ketiga = document.getElementById('sptjm_bansos_ketiga').checked;
                let kepgub_bankeu_ketiga = document.getElementById('kepgub_bankeu_ketiga').checked;
                let sk_kud_ketiga = document.getElementById('sk_kud_ketiga').checked;
                let kepgub_bagihasil_ketiga = document.getElementById('kepgub_bagihasil_ketiga')
                    .checked;
                let fc_bagihasil_ketiga = document.getElementById('fc_bagihasil_ketiga').checked;
                let sptjm_pembiayaan_ketiga = document.getElementById('sptjm_pembiayaan_ketiga')
                    .checked;
                let kepgub_btt_ketiga = document.getElementById('kepgub_btt_ketiga')
                    .checked;
                let sptjm_btt_ketiga = document.getElementById('sptjm_btt_ketiga')
                    .checked;
                let syarat_lain_ketiga = document.getElementById('syarat_lain_ketiga').checked;

                if ((!pengantar_spp_ketiga && !spp_ketiga && !ringkasan_spp_ketiga && !
                        rincian_spp_ketiga &&
                        !pernyataan_ketiga && !lampiran_spp_ketiga && !proposal_bansos_ketiga && !
                        kepgub_bansos_ketiga && !nphd_ketiga && !kab_ketiga && !
                        penerima_bansos_ketiga &&
                        !penerima_hibah_ketiga && !sptjm_hibah_ketiga && !sptjm_bansos_ketiga && !
                        kepgub_bankeu_ketiga && !kepgub_bagihasil_ketiga && !fc_bagihasil_ketiga && !
                        sptjm_pembiayaan_ketiga && !kepgub_btt_ketiga && !sptjm_btt_ketiga && !
                        syarat_lain_ketiga) && status_verifikasi == '1') {
                    alert('Silahkan centang, jika ingin diterima');
                    return;
                }

                let tanya = confirm("Apakah data yang diverifikasi sudah benar ?");
                if (tanya == true) {
                    $('#simpan').prop("disabled", true);
                    $.ajax({
                        url: "{{ route('cek_spm.simpan_ketiga') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            jenis_beban: jenis_beban,
                            no_spm: no_spm,
                            kd_skpd: kd_skpd,
                            tgl_verifikasi: tgl_verifikasi,
                            keterangan_verifikasi: keterangan_verifikasi,
                            pengantar_spp_ketiga: pengantar_spp_ketiga,
                            spp_ketiga: spp_ketiga,
                            ringkasan_spp_ketiga: ringkasan_spp_ketiga,
                            rincian_spp_ketiga: rincian_spp_ketiga,
                            pernyataan_ketiga: pernyataan_ketiga,
                            lampiran_spp_ketiga: lampiran_spp_ketiga,
                            proposal_bansos_ketiga: proposal_bansos_ketiga,
                            kepgub_bansos_ketiga: kepgub_bansos_ketiga,
                            nphd_ketiga: nphd_ketiga,
                            kab_ketiga: kab_ketiga,
                            penerima_bansos_ketiga: penerima_bansos_ketiga,
                            penerima_hibah_ketiga: penerima_hibah_ketiga,
                            sptjm_hibah_ketiga: sptjm_hibah_ketiga,
                            sptjm_bansos_ketiga: sptjm_bansos_ketiga,
                            kepgub_bankeu_ketiga: kepgub_bankeu_ketiga,
                            sk_kud_ketiga: sk_kud_ketiga,
                            kepgub_bagihasil_ketiga: kepgub_bagihasil_ketiga,
                            fc_bagihasil_ketiga: fc_bagihasil_ketiga,
                            sptjm_pembiayaan_ketiga: sptjm_pembiayaan_ketiga,
                            sptjm_btt_ketiga: sptjm_btt_ketiga,
                            kepgub_btt_ketiga: kepgub_btt_ketiga,
                            syarat_lain_ketiga: syarat_lain_ketiga,
                            status_verifikasi: status_verifikasi,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            $("#overlay").fadeIn(100);
                        },
                        success: function(data) {
                            $('#simpan').prop("disabled", false);
                            if (data.message == '1') {
                                alert('Data berhasil diverifikasi');
                                $('#detail_spm').modal('hide');
                                // document.getElementById("form_detail").reset();
                                daftar_spm.ajax.reload();
                            } else {
                                alert('Data tidak berhasil diverifikasi!');
                            }
                        },
                        complete: function(data) {
                            $("#overlay").fadeOut(100);
                        }
                    })
                } else {
                    alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
                    $('#detail_spm').modal('hide');
                    // document.getElementById("form_detail").reset();
                    daftar_spm.ajax.reload();
                    $('#simpan').prop("disabled", false);
                }
            }

            // LS BARANG JASA
            if (beban == '6') {
                let pengantar_spp_barjas = document.getElementById('pengantar_spp_barjas').checked;
                let spp_barjas = document.getElementById('spp_barjas').checked;
                let ringkasan_spp_barjas = document.getElementById('ringkasan_spp_barjas').checked;
                let rincian_spp_barjas = document.getElementById('rincian_spp_barjas').checked;
                let pernyataan_barjas = document.getElementById('pernyataan_barjas').checked;
                let lampiran_spp_barjas = document.getElementById('lampiran_spp_barjas').checked;

                if (!pengantar_spp_barjas && !spp_barjas && !ringkasan_spp_barjas && !
                    rincian_spp_barjas &&
                    !pernyataan_barjas && !lampiran_spp_barjas) {
                    alert('Silahkan centang, jika ingin diterima');
                    return;
                }

                // LS BARJAS TAMBAHAN PENGHASILAN
                let salinan_barjas1 = document.getElementById('salinan_barjas1').checked;
                let penerima_barjas1 = document.getElementById('penerima_barjas1').checked;
                let absensi_barjas1 = document.getElementById('absensi_barjas1').checked;
                let rekap_absensi_barjas1 = document.getElementById('rekap_absensi_barjas1').checked;
                let ka_barjas1 = document.getElementById('ka_barjas1').checked;
                let sse_barjas1 = document.getElementById('sse_barjas1').checked;
                let sts_barjas1 = document.getElementById('sts_barjas1').checked;

                if ((!salinan_barjas1 && !penerima_barjas1 && !absensi_barjas1 && !
                        rekap_absensi_barjas1 &&
                        !ka_barjas1 && !sse_barjas1 && !sts_barjas1) && status_verifikasi == '1' &&
                    jenis_beban == '1') {
                    alert('Silahkan centang, jika ingin diterima');
                    return;
                }
                // LS BARJAS HONOR PNS
                let salinan_barjas2 = document.getElementById('salinan_barjas2').checked;
                let sk_barjas2 = document.getElementById('sk_barjas2').checked;
                let terima_barjas2 = document.getElementById('terima_barjas2').checked;
                let ka_barjas2 = document.getElementById('ka_barjas2').checked;
                let sse_barjas2 = document.getElementById('sse_barjas2').checked;

                if ((!salinan_barjas2 && !sk_barjas2 && !terima_barjas2 && !
                        ka_barjas2 &&
                        !sse_barjas2) && status_verifikasi == '1' && jenis_beban == '7') {
                    alert('Silahkan centang, jika ingin diterima');
                    return;
                }
                // LS BARJAS HONOR KONTRAK
                let salinan_barjas3 = document.getElementById('salinan_barjas3').checked;
                let sk_barjas3 = document.getElementById('sk_barjas3').checked;
                let spk_barjas3 = document.getElementById('spk_barjas3').checked;
                let terima_barjas3 = document.getElementById('terima_barjas3').checked;
                let ka_barjas3 = document.getElementById('ka_barjas3').checked;
                let sse_barjas3 = document.getElementById('sse_barjas3').checked;
                let sse_pnbp_barjas3 = document.getElementById('sse_pnbp_barjas3').checked;

                if ((!salinan_barjas3 && !sk_barjas3 && !spk_barjas3 && !
                        terima_barjas3 &&
                        !ka_barjas3 && !sse_barjas3 && !sse_pnbp_barjas3) && status_verifikasi == '1' &&
                    jenis_beban == '4') {
                    alert('Silahkan centang, jika ingin diterima');
                    return;
                }
                // LS BARJAS PIHAK KETIGA
                let salinan_barjas4 = document.getElementById('salinan_barjas4').checked;
                let nota_barjas4 = document.getElementById('nota_barjas4').checked;
                let kontrak_barjas4 = document.getElementById('kontrak_barjas4').checked;
                let kwintansi_barjas4 = document.getElementById('kwintansi_barjas4').checked;
                let referensi_barjas4 = document.getElementById('referensi_barjas4').checked;
                let npwp_barjas4 = document.getElementById('npwp_barjas4').checked;
                let jum_barjas4 = document.getElementById('jum_barjas4').checked;
                let jp_barjas4 = document.getElementById('jp_barjas4').checked;
                let ringkasan_barjas4 = document.getElementById('ringkasan_barjas4').checked;
                let lkp_barjas4 = document.getElementById('lkp_barjas4').checked;
                let bap1_barjas4 = document.getElementById('bap1_barjas4').checked;
                let bap2_barjas4 = document.getElementById('bap2_barjas4').checked;
                let bas_barjas4 = document.getElementById('bas_barjas4').checked;
                let bap3_barjas4 = document.getElementById('bap3_barjas4').checked;
                let jppa_barjas4 = document.getElementById('jppa_barjas4').checked;
                let ffp_barjas4 = document.getElementById('ffp_barjas4').checked;
                let sse_barjas4 = document.getElementById('sse_barjas4').checked;
                let dokumen_barjas4 = document.getElementById('dokumen_barjas4').checked;

                if ((!salinan_barjas4 && !nota_barjas4 && !kontrak_barjas4 && !
                        kwintansi_barjas4 &&
                        !referensi_barjas4 && !npwp_barjas4 && !jum_barjas4 && !jp_barjas4 && !
                        ringkasan_barjas4 && !lkp_barjas4 && !bap1_barjas4 && !bap2_barjas4 && !
                        bas_barjas4 && !bap3_barjas4 && !jppa_barjas4 && !ffp_barjas4 && !sse_barjas4 &&
                        !
                        dokumen_barjas4) && status_verifikasi == '1' && (jenis_beban == '5' ||
                        jenis_beban == '6')) {
                    alert('Silahkan centang, jika ingin diterima');
                    return;
                }
                // LS BARJAS KDH/WKDH
                let salinan_barjas5 = document.getElementById('salinan_barjas5').checked;
                let ka_barjas5 = document.getElementById('ka_barjas5').checked;
                let penerima_barjas5 = document.getElementById('penerima_barjas5').checked;
                let fakta_barjas5 = document.getElementById('fakta_barjas5').checked;
                let syarat_barjas5 = document.getElementById('syarat_barjas5').checked;

                if ((!salinan_barjas5 && !ka_barjas5 && !penerima_barjas5 && !
                        fakta_barjas5 &&
                        !syarat_barjas5) && status_verifikasi == '1' && (jenis_beban == '2' ||
                        jenis_beban == '3')) {
                    alert('Silahkan centang, jika ingin diterima');
                    return;
                }

                let tanya = confirm("Apakah data yang diverifikasi sudah benar ?");
                if (tanya == true) {
                    $('#simpan').prop("disabled", true);
                    $.ajax({
                        url: "{{ route('cek_spm.simpan_barjas') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            jenis_beban: jenis_beban,
                            no_spm: no_spm,
                            kd_skpd: kd_skpd,
                            tgl_verifikasi: tgl_verifikasi,
                            keterangan_verifikasi: keterangan_verifikasi,
                            pengantar_spp_barjas: pengantar_spp_barjas,
                            spp_barjas: spp_barjas,
                            ringkasan_spp_barjas: ringkasan_spp_barjas,
                            rincian_spp_barjas: rincian_spp_barjas,
                            pernyataan_barjas: pernyataan_barjas,
                            lampiran_spp_barjas: lampiran_spp_barjas,
                            salinan_barjas1: salinan_barjas1,
                            penerima_barjas1: penerima_barjas1,
                            absensi_barjas1: absensi_barjas1,
                            rekap_absensi_barjas1: rekap_absensi_barjas1,
                            ka_barjas1: ka_barjas1,
                            sse_barjas1: sse_barjas1,
                            sts_barjas1: sts_barjas1,
                            salinan_barjas2: salinan_barjas2,
                            sk_barjas2: sk_barjas2,
                            terima_barjas2: terima_barjas2,
                            ka_barjas2: ka_barjas2,
                            sse_barjas2: sse_barjas2,
                            salinan_barjas3: salinan_barjas3,
                            sk_barjas3: sk_barjas3,
                            spk_barjas3: spk_barjas3,
                            terima_barjas3: terima_barjas3,
                            ka_barjas3: ka_barjas3,
                            sse_barjas3: sse_barjas3,
                            sse_pnbp_barjas3: sse_pnbp_barjas3,
                            salinan_barjas4: salinan_barjas4,
                            nota_barjas4: nota_barjas4,
                            kontrak_barjas4: kontrak_barjas4,
                            kwintansi_barjas4: kwintansi_barjas4,
                            referensi_barjas4: referensi_barjas4,
                            npwp_barjas4: npwp_barjas4,
                            jum_barjas4: jum_barjas4,
                            jp_barjas4: jp_barjas4,
                            ringkasan_barjas4: ringkasan_barjas4,
                            lkp_barjas4: lkp_barjas4,
                            bap1_barjas4: bap1_barjas4,
                            bap2_barjas4: bap2_barjas4,
                            bas_barjas4: bas_barjas4,
                            bap3_barjas4: bap3_barjas4,
                            jppa_barjas4: jppa_barjas4,
                            ffp_barjas4: ffp_barjas4,
                            sse_barjas4: sse_barjas4,
                            dokumen_barjas4: dokumen_barjas4,
                            salinan_barjas5: salinan_barjas5,
                            ka_barjas5: ka_barjas5,
                            penerima_barjas5: penerima_barjas5,
                            fakta_barjas5: fakta_barjas5,
                            syarat_barjas5: syarat_barjas5,
                            status_verifikasi: status_verifikasi,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function() {
                            $("#overlay").fadeIn(100);
                        },
                        success: function(data) {
                            $('#simpan').prop("disabled", false);
                            if (data.message == '1') {
                                alert('Data berhasil diverifikasi');
                                $('#detail_spm').modal('hide');
                                // document.getElementById("form_detail").reset();
                                daftar_spm.ajax.reload();
                            } else {
                                alert('Data tidak berhasil diverifikasi!');
                            }
                        },
                        complete: function(data) {
                            $("#overlay").fadeOut(100);
                        }
                    })
                } else {
                    alert('Silahkan Cek lagi, Pastikan Data Sudah Benar...');
                    $('#detail_spm').modal('hide');
                    // document.getElementById("form_detail").reset();
                    daftar_spm.ajax.reload();
                    $('#simpan').prop("disabled", false);
                }
            }
        });
    });

    function detail(no_spm, no_spp, jns_spp, kd_skpd, nm_skpd, jenis_beban, jenis_kelengkapan) {
        // document.getElementById("form_detail").reset();

        $('#no_spm').val(no_spm);
        $('#no_spp').val(no_spp);
        $('#kd_unit').val(kd_skpd);
        $('#nm_unit').val(nm_skpd);
        $('#beban').val(jns_spp);
        $('#jenis_beban').val(jenis_beban);
        $('#jenis_kelengkapan').val(jenis_kelengkapan);

        let response;
        $.ajax({
            url: "{{ route('cek_spm.cari') }}",
            type: "POST",
            dataType: 'json',
            async: false,
            data: {
                no_spm: no_spm,
                kd_skpd: kd_skpd,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                response = data;
            }
        });

        $('#tgl_verifikasi').val(response.tgl_verifikasi);
        $('#keterangan_verifikasi').val(response.keterangan_verifikasi);
        $('#status_verifikasi').val(response.status).change();

        if (jns_spp == '1') {
            $('#khusus_up').show();

            response.pengantar == 1 ? $('#pengantar_spp_up').prop('checked', true) : $('#pengantar_spp_up').prop(
                'checked', false);
            response.spp == 1 ? $('#spp_up').prop('checked', true) : $('#spp_up').prop(
                'checked', false);
            response.ringkasan == 1 ? $('#ringkasan_spp_up').prop('checked', true) : $('#ringkasan_spp_up').prop(
                'checked', false);
            response.rincian == 1 ? $('#rincian_spp_up').prop('checked', true) : $('#rincian_spp_up').prop(
                'checked', false);
            response.pernyataan == 1 ? $('#pernyataan_pengajuan_up').prop('checked', true) : $(
                '#pernyataan_pengajuan_up').prop('checked', false);
            response.lampiran == 1 ? $('#lampiran_spp_up').prop('checked', true) : $(
                '#lampiran_spp_up').prop('checked', false);
            response.salinan_spd == 1 ? $('#salinan_spd_up').prop('checked', true) : $(
                '#salinan_spd_up').prop('checked', false);
            response.rekening_koran_up == 1 ? $('#rekening_koran_up').prop('checked', true) : $(
                '#rekening_koran_up').prop('checked', false);
            response.kepgub_up == 1 ? $('#keputusan_gubernur_up').prop('checked', true) : $(
                '#keputusan_gubernur_up').prop('checked', false);

            $('#khusus_gu').hide();
            $('#khusus_tu').hide();
            $('#khusus_ls_gaji').hide();
            $('#khusus_ls_ketiga').hide();
            $('#khusus_ls_barjas').hide();
        } else if (jns_spp == '2') {
            $('#khusus_gu').show();

            response.pengantar == 1 ? $('#pengantar_spp_gu').prop('checked', true) : $('#pengantar_spp_gu').prop(
                'checked', false);
            response.spp == 1 ? $('#spp_gu').prop('checked', true) : $('#spp_gu').prop(
                'checked', false);
            response.ringkasan == 1 ? $('#ringkasan_spp_gu').prop('checked', true) : $('#ringkasan_spp_gu').prop(
                'checked', false);
            response.rincian == 1 ? $('#rincian_spp_gu').prop('checked', true) : $('#rincian_spp_gu').prop(
                'checked', false);
            response.pernyataan == 1 ? $('#pernyataan_pengajuan_gu').prop('checked', true) : $(
                '#pernyataan_pengajuan_gu').prop('checked', false);
            response.lampiran == 1 ? $('#lampiran_spp_gu').prop('checked', true) : $(
                '#lampiran_spp_gu').prop('checked', false);
            response.salinan_spd == 1 ? $('#salinan_spd_gu').prop('checked', true) : $(
                '#salinan_spd_gu').prop('checked', false);
            response.lpj_gu == 1 ? $('#lpj_gu').prop('checked', true) : $(
                '#lpj_gu').prop('checked', false);
            response.sptb_gu == 1 ? $('#sptb_gu').prop('checked', true) : $(
                '#sptb_gu').prop('checked', false);
            response.sse_gu == 1 ? $('#sse_gu').prop('checked', true) : $(
                '#sse_gu').prop('checked', false);

            $('#khusus_up').hide();
            $('#khusus_tu').hide();
            $('#khusus_ls_gaji').hide();
            $('#khusus_ls_ketiga').hide();
            $('#khusus_ls_barjas').hide();
        } else if (jns_spp == '3') {
            $('#khusus_tu').show();

            response.pengantar == 1 ? $('#pengantar_spp_tu').prop('checked', true) : $('#pengantar_spp_tu').prop(
                'checked', false);
            response.spp == 1 ? $('#spp_tu').prop('checked', true) : $('#spp_tu').prop(
                'checked', false);
            response.ringkasan == 1 ? $('#ringkasan_spp_tu').prop('checked', true) : $('#ringkasan_spp_tu').prop(
                'checked', false);
            response.rincian == 1 ? $('#rencana_penggunaan_tu').prop('checked', true) : $('#rencana_penggunaan_tu')
                .prop('checked', false);
            response.pernyataan == 1 ? $('#pernyataan_pengajuan_tu').prop('checked', true) : $(
                '#pernyataan_pengajuan_tu').prop('checked', false);
            response.lampiran == 1 ? $('#lampiran_spp_tu').prop('checked', true) : $(
                '#lampiran_spp_tu').prop('checked', false);
            response.salinan_spd == 1 ? $('#salinan_spd_tu').prop('checked', true) : $(
                '#salinan_spd_tu').prop('checked', false);
            response.jadwal_pelaksanaan_tu == 1 ? $('#jadwal_pelaksanaan_kegiatan_tu').prop('checked', true) : $(
                '#jadwal_pelaksanaan_kegiatan_tu').prop('checked', false);
            response.rekening_koran_tu == 1 ? $('#rekening_koran_tu').prop('checked', true) : $(
                '#rekening_koran_tu').prop('checked', false);
            response.lpj_untuk_tu == 1 ? $('#lpj_untuk_tu').prop('checked', true) : $(
                '#lpj_untuk_tu').prop('checked', false);
            response.sptb_tu == 1 ? $('#sptb_tu').prop('checked', true) : $(
                '#sptb_tu').prop('checked', false);
            response.sse_tu == 1 ? $('#sse_tu').prop('checked', true) : $(
                '#sse_tu').prop('checked', false);
            response.bukti_setor_tu == 1 ? $('#bukti_setor_tu').prop('checked', true) : $(
                '#bukti_setor_tu').prop('checked', false);
            response.dokumen_lain_tu == 1 ? $('#dokumen_lain_tu').prop('checked', true) : $(
                '#dokumen_lain_tu').prop('checked', false);

            $('#khusus_up').hide();
            $('#khusus_gu').hide();
            $('#khusus_ls_gaji').hide();
            $('#khusus_ls_ketiga').hide();
            $('#khusus_ls_barjas').hide();
        } else if (jns_spp == '4') {
            $('#khusus_up').hide();
            $('#khusus_gu').hide();
            $('#khusus_tu').hide();
            $('#khusus_ls_ketiga').hide();
            $('#khusus_ls_barjas').hide();

            $('#khusus_ls_gaji').show();

            response.pengantar == 1 ? $('#pengantar_spp_gaji').prop('checked', true) : $('#pengantar_spp_gaji').prop(
                'checked', false);
            response.spp == 1 ? $('#spp_gaji').prop('checked', true) : $('#spp_gaji').prop(
                'checked', false);
            response.ringkasan == 1 ? $('#ringkasan_spp_gaji').prop('checked', true) : $('#ringkasan_spp_gaji').prop(
                'checked', false);
            response.rincian == 1 ? $('#rincian_spp_gaji').prop('checked', true) : $('#rincian_spp_gaji')
                .prop('checked', false);
            response.pernyataan == 1 ? $('#pernyataan_pengajuan_gaji').prop('checked', true) : $(
                '#pernyataan_pengajuan_gaji').prop('checked', false);
            response.lampiran == 1 ? $('#lampiran_spp_gaji').prop('checked', true) : $(
                '#lampiran_spp_gaji').prop('checked', false);
            response.salinan_spd == 1 ? $('#salinan_spd_gaji').prop('checked', true) : $(
                '#salinan_spd_gaji').prop('checked', false);
            response.daftar_gaji == 1 ? $('#daftar_gaji').prop('checked', true) : $(
                '#daftar_gaji').prop('checked', false);
            response.rekap_gaji_induk == 1 ? $('#rekap_gaji_induk').prop('checked', true) : $(
                '#rekap_gaji_induk').prop('checked', false);
            response.rekap_gaji_golongan == 1 ? $('#rekap_gaji_golongan').prop('checked', true) : $(
                '#rekap_gaji_golongan').prop('checked', false);
            response.sse_gaji == 1 ? $('#sse_gaji').prop('checked', true) : $(
                '#sse_gaji').prop('checked', false);
            response.sk_perubahan_gaji == 1 ? $('#sk_perubahan_gaji').prop('checked', true) : $(
                '#sk_perubahan_gaji').prop('checked', false);
            response.sk_kenaikan_gaji == 1 ? $('#sk_kenaikan_gaji').prop('checked', true) : $(
                '#sk_kenaikan_gaji').prop('checked', false);
            response.sk_struktural_gaji == 1 ? $('#sk_struktural_gaji').prop('checked', true) : $(
                '#sk_struktural_gaji').prop('checked', false);
            response.keputusan_kenaikan_gaji == 1 ? $('#keputusan_kenaikan_gaji').prop('checked', true) : $(
                '#keputusan_kenaikan_gaji').prop('checked', false);
            response.keputusan_pindah_gaji == 1 ? $('#keputusan_pindah_gaji').prop('checked', true) : $(
                '#keputusan_pindah_gaji').prop('checked', false);
            response.daftar_keluarga_gaji == 1 ? $('#daftar_keluarga_gaji').prop('checked', true) : $(
                '#daftar_keluarga_gaji').prop('checked', false);
            response.pernyataan_tugas_gaji == 1 ? $('#pernyataan_tugas_gaji').prop('checked', true) : $(
                '#pernyataan_tugas_gaji').prop('checked', false);
            response.cerai_gaji == 1 ? $('#cerai_gaji').prop('checked', true) : $(
                '#cerai_gaji').prop('checked', false);
            response.sk_pengangkatan_gaji == 1 ? $('#sk_pengangkatan_gaji').prop('checked', true) : $(
                '#sk_pengangkatan_gaji').prop('checked', false);
            response.sptjm_gaji == 1 ? $('#sptjm_gaji').prop('checked', true) : $(
                '#sptjm_gaji').prop('checked', false);
            response.sk_mutasi_gaji == 1 ? $('#sk_mutasi_gaji').prop('checked', true) : $(
                '#sk_mutasi_gaji').prop('checked', false);
            response.skpp_gaji == 1 ? $('#skpp_gaji').prop('checked', true) : $(
                '#skpp_gaji').prop('checked', false);

            if (jenis_kelengkapan.length == 0) $('#khusus_ls_gaji').hide();

            if (jenis_beban == '1' || (jenis_beban == '7' && jenis_kelengkapan == '1')) {
                $('.gaji1').show();
                $('.gaji7').hide();
            } else if (jenis_beban == '7' && jenis_kelengkapan == '2') {
                $('.gaji1').hide();
                $('.gaji7').show();
            }
        } else if (jns_spp == '5') {
            $('#khusus_up').hide();
            $('#khusus_gu').hide();
            $('#khusus_tu').hide();
            $('#khusus_ls_gaji').hide();
            $('#khusus_ls_barjas').hide();

            response.pengantar == 1 ? $('#pengantar_spp_ketiga').prop('checked', true) : $('#pengantar_spp_ketiga')
                .prop('checked', false);
            response.spp == 1 ? $('#spp_ketiga').prop('checked', true) : $('#spp_ketiga').prop('checked', false);
            response.ringkasan == 1 ? $('#ringkasan_spp_ketiga').prop('checked', true) : $('#ringkasan_spp_ketiga')
                .prop('checked', false);
            response.rincian == 1 ? $('#rincian_spp_ketiga').prop('checked', true) : $('#rincian_spp_ketiga')
                .prop('checked', false);
            response.pernyataan == 1 ? $('#pernyataan_ketiga').prop('checked', true) : $(
                '#pernyataan_ketiga').prop('checked', false);
            response.lampiran == 1 ? $('#lampiran_spp_ketiga').prop('checked', true) : $(
                '#lampiran_spp_ketiga').prop('checked', false);

            response.proposal_bansos_ketiga == 1 ? $('#proposal_bansos_ketiga').prop('checked', true) : $(
                '#proposal_bansos_ketiga').prop('checked', false);
            response.kepgub_bansos_ketiga == 1 ? $('#kepgub_bansos_ketiga').prop('checked', true) : $(
                '#kepgub_bansos_ketiga').prop('checked', false);
            response.nphd_ketiga == 1 ? $('#nphd_ketiga').prop('checked', true) : $(
                '#nphd_ketiga').prop('checked', false);
            response.kab_ketiga == 1 ? $('#kab_ketiga').prop('checked', true) : $(
                '#kab_ketiga').prop('checked', false);
            response.penerima_bansos_ketiga == 1 ? $('#penerima_bansos_ketiga').prop('checked', true) : $(
                '#penerima_bansos_ketiga').prop('checked', false);
            response.penerima_hibah_ketiga == 1 ? $('#penerima_hibah_ketiga').prop('checked', true) : $(
                '#penerima_hibah_ketiga').prop('checked', false);
            response.sptjm_hibah_ketiga == 1 ? $('#sptjm_hibah_ketiga').prop('checked', true) : $(
                '#sptjm_hibah_ketiga').prop('checked', false);
            response.sptjm_bansos_ketiga == 1 ? $('#sptjm_bansos_ketiga').prop('checked', true) : $(
                '#sptjm_bansos_ketiga').prop('checked', false);
            response.kepgub_bankeu_ketiga == 1 ? $('#kepgub_bankeu_ketiga').prop('checked', true) : $(
                '#kepgub_bankeu_ketiga').prop('checked', false);
            response.sk_kud_ketiga == 1 ? $('#sk_kud_ketiga').prop('checked', true) : $(
                '#sk_kud_ketiga').prop('checked', false);
            response.kepgub_bagihasil_ketiga == 1 ? $('#kepgub_bagihasil_ketiga').prop('checked', true) : $(
                '#kepgub_bagihasil_ketiga').prop('checked', false);
            response.fc_bagihasil_ketiga == 1 ? $('#fc_bagihasil_ketiga').prop('checked', true) : $(
                '#fc_bagihasil_ketiga').prop('checked', false);
            response.sptjm_pembiayaan_ketiga == 1 ? $('#sptjm_pembiayaan_ketiga').prop('checked', true) : $(
                '#sptjm_pembiayaan_ketiga').prop('checked', false);
            response.syarat_lain_ketiga == 1 ? $('#syarat_lain_ketiga').prop('checked', true) : $(
                '#syarat_lain_ketiga').prop('checked', false);
            response.sptjm_btt_ketiga == 1 ? $('#sptjm_btt_ketiga').prop('checked', true) : $(
                '#sptjm_btt_ketiga').prop('checked', false);
            response.kepgub_btt_ketiga == 1 ? $('#kepgub_btt_ketiga').prop('checked', true) : $(
                '#kepgub_btt_ketiga').prop('checked', false);

            $('#khusus_ls_ketiga').show();

            if (jenis_beban == '1') {
                // HIBAH UANG
                $('.hibah_ketiga').show();
                $('.hibah_bansos_ketiga').show();
                $('.hibah_bansos_pembiayaan_ketiga').show();

                $('.bansos_ketiga').hide();
                $('.bankeu_ketiga').hide();
                $('.bagihasil_ketiga').hide();
                $('.pembiayaan_ketiga').hide();
            } else if (jenis_beban == '2') {
                // BANTUAN SOSIAL
                $('.bansos_ketiga').show();
                $('.hibah_bansos_ketiga').show();
                $('.hibah_bansos_pembiayaan_ketiga').show();

                $('.hibah_ketiga').hide();
                $('.bankeu_ketiga').hide();
                $('.bagihasil_ketiga').hide();
                $('.pembiayaan_ketiga').hide();
            } else if (jenis_beban == '3') {
                // BANTUAN KEUANGAN
                $('.bankeu_ketiga').show();

                $('.hibah_bansos_pembiayaan_ketiga').hide();
                $('.hibah_ketiga').hide();
                $('.bansos_ketiga').hide();
                $('.bagihasil_ketiga').hide();
                $('.pembiayaan_ketiga').hide();
                $('.hibah_bansos_ketiga').hide();
            } else if (jenis_beban == '5') {
                // BAGI HASIL
                $('.bagihasil_ketiga').show();

                $('.hibah_bansos_pembiayaan_ketiga').hide();
                $('.hibah_ketiga').hide();
                $('.bansos_ketiga').hide();
                $('.bankeu_ketiga').hide();
                $('.pembiayaan_ketiga').hide();
                $('.hibah_bansos_ketiga').hide();
            } else if (jenis_beban == '6') {
                // BAGI HASIL
                $('.btt_ketiga').show();

                $('.hibah_bansos_pembiayaan_ketiga').hide();
                $('.hibah_ketiga').hide();
                $('.bansos_ketiga').hide();
                $('.bankeu_ketiga').hide();
                $('.pembiayaan_ketiga').hide();
                $('.hibah_bansos_ketiga').hide();
                $('.bagihasil_ketiga').hide();
            } else if (jenis_beban == '8') {
                // PENGELUARAN PEMBIAYAAN
                $('.pembiayaan_ketiga').show();
                $('.hibah_bansos_pembiayaan_ketiga').show();

                $('.hibah_ketiga').hide();
                $('.bansos_ketiga').hide();
                $('.bankeu_ketiga').hide();
                $('.bagihasil_ketiga').hide();
                $('.hibah_bansos_ketiga').hide();
            } else {
                $('#khusus_ls_ketiga').hide();
            }
        } else if (jns_spp == '6') {
            $('#khusus_up').hide();
            $('#khusus_gu').hide();
            $('#khusus_tu').hide();
            $('#khusus_ls_gaji').hide();
            $('#khusus_ls_ketiga').hide();

            response.pengantar == 1 ? $('#pengantar_spp_barjas').prop('checked', true) : $('#pengantar_spp_barjas')
                .prop('checked', false);
            response.spp == 1 ? $('#spp_barjas').prop('checked', true) : $('#spp_barjas')
                .prop('checked', false);
            response.ringkasan == 1 ? $('#ringkasan_spp_barjas').prop('checked', true) : $('#ringkasan_spp_barjas')
                .prop('checked', false);
            response.spp == 1 ? $('#rincian_spp_barjas').prop('checked', true) : $('#rincian_spp_barjas')
                .prop('checked', false);
            response.pernyataan == 1 ? $('#pernyataan_barjas').prop('checked', true) : $('#pernyataan_barjas')
                .prop('checked', false);
            response.lampiran == 1 ? $('#lampiran_spp_barjas').prop('checked', true) : $('#lampiran_spp_barjas')
                .prop('checked', false);

            if (jenis_beban == '1') {
                response.salinan == 1 ? $('#salinan_barjas1').prop('checked', true) : $('#salinan_barjas1')
                    .prop('checked', false);
            } else if (jenis_beban == '2' || jenis_beban == '3') {
                response.salinan == 1 ? $('#salinan_barjas5').prop('checked', true) : $('#salinan_barjas5')
                    .prop('checked', false);
            } else if (jenis_beban == '4') {
                response.salinan == 1 ? $('#salinan_barjas3').prop('checked', true) : $('#salinan_barjas3')
                    .prop('checked', false);
            } else if (jenis_beban == '5' || jenis_beban == '6') {
                response.salinan == 1 ? $('#salinan_barjas4').prop('checked', true) : $('#salinan_barjas4')
                    .prop('checked', false);
            } else if (jenis_beban == '7') {
                response.salinan == 1 ? $('#salinan_barjas2').prop('checked', true) : $('#salinan_barjas2')
                    .prop('checked', false);
            }

            response.penerima_tpp_barjas == 1 ? $('#penerima_barjas1').prop('checked', true) : $('#penerima_barjas1')
                .prop('checked', false);
            response.absensi_tpp_barjas == 1 ? $('#absensi_barjas1').prop('checked', true) : $('#absensi_barjas1')
                .prop('checked', false);
            response.rekap_absensi_tpp_barjas == 1 ? $('#rekap_absensi_barjas1').prop('checked', true) : $(
                    '#rekap_absensi_barjas1')
                .prop('checked', false);
            response.ka_tpp_barjas == 1 ? $('#ka_barjas1').prop('checked', true) : $('#ka_barjas1')
                .prop('checked', false);
            response.sse_tpp_barjas == 1 ? $('#sse_barjas1').prop('checked', true) : $('#sse_barjas1')
                .prop('checked', false);
            response.sts_tpp_barjas == 1 ? $('#sts_barjas1').prop('checked', true) : $('#sts_barjas1')
                .prop('checked', false);

            response.sk_pns_barjas == 1 ? $('#sk_barjas2').prop('checked', true) : $('#sk_barjas2')
                .prop('checked', false);
            response.terima_pns_barjas == 1 ? $('#terima_barjas2').prop('checked', true) : $('#terima_barjas2')
                .prop('checked', false);
            response.ka_pns_barjas == 1 ? $('#ka_barjas2').prop('checked', true) : $(
                    '#ka_barjas2')
                .prop('checked', false);
            response.sse_pns_barjas == 1 ? $('#sse_barjas2').prop('checked', true) : $('#sse_barjas2')
                .prop('checked', false);

            response.sk_kontrak_barjas == 1 ? $('#sk_barjas3').prop('checked', true) : $('#sk_barjas3')
                .prop('checked', false);
            response.spk_kontrak_barjas == 1 ? $('#spk_barjas3').prop('checked', true) : $('#spk_barjas3')
                .prop('checked', false);
            response.terima_kontrak_barjas == 1 ? $('#terima_barjas3').prop('checked', true) : $(
                    '#terima_barjas3')
                .prop('checked', false);
            response.ka_kontrak_barjas == 1 ? $('#ka_barjas3').prop('checked', true) : $('#ka_barjas3')
                .prop('checked', false);
            response.sse_kontrak_barjas == 1 ? $('#sse_barjas3').prop('checked', true) : $('#sse_barjas3')
                .prop('checked', false);
            response.ssepnbp_kontrak_barjas == 1 ? $('#sse_pnbp_barjas3').prop('checked', true) : $('#sse_pnbp_barjas3')
                .prop('checked', false);

            response.nota_jasa_barjas == 1 ? $('#nota_barjas4').prop('checked', true) : $('#nota_barjas4')
                .prop('checked', false);
            response.kontrak_jasa_barjas == 1 ? $('#kontrak_barjas4').prop('checked', true) : $('#kontrak_barjas4')
                .prop('checked', false);
            response.kwintansi_jasa_barjas == 1 ? $('#kwintansi_barjas4').prop('checked', true) : $(
                    '#kwintansi_barjas4')
                .prop('checked', false);
            response.referensi_jasa_barjas == 1 ? $('#referensi_barjas4').prop('checked', true) : $(
                    '#referensi_barjas4')
                .prop('checked', false);
            response.npwp_jasa_barjas == 1 ? $('#npwp_barjas4').prop('checked', true) : $('#npwp_barjas4')
                .prop('checked', false);
            response.jum_jasa_barjas == 1 ? $('#jum_barjas4').prop('checked', true) : $('#jum_barjas4')
                .prop('checked', false);

            response.jp_jasa_barjas == 1 ? $('#jp_barjas4').prop('checked', true) : $('#jp_barjas4')
                .prop('checked', false);
            response.ringkasan_jasa_barjas == 1 ? $('#ringkasan_barjas4').prop('checked', true) : $(
                    '#ringkasan_barjas4')
                .prop('checked', false);
            response.lkp_jasa_barjas == 1 ? $('#lkp_barjas4').prop('checked', true) : $(
                    '#lkp_barjas4')
                .prop('checked', false);
            response.bap1_jasa_barjas == 1 ? $('#bap1_barjas4').prop('checked', true) : $('#bap1_barjas4')
                .prop('checked', false);
            response.bap2_jasa_barjas == 1 ? $('#bap2_barjas4').prop('checked', true) : $('#bap2_barjas4')
                .prop('checked', false);
            response.bas_jasa_barjas == 1 ? $('#bas_barjas4').prop('checked', true) : $('#bas_barjas4')
                .prop('checked', false);

            response.bap3_jasa_barjas == 1 ? $('#bap3_barjas4').prop('checked', true) : $('#bap3_barjas4')
                .prop('checked', false);
            response.jppa_jasa_barjas == 1 ? $('#jppa_barjas4').prop('checked', true) : $('#jppa_barjas4')
                .prop('checked', false);
            response.ffp_jasa_barjas == 1 ? $('#ffp_barjas4').prop('checked', true) : $(
                    '#ffp_barjas4')
                .prop('checked', false);
            response.sse_jasa_barjas == 1 ? $('#sse_barjas4').prop('checked', true) : $('#sse_barjas4')
                .prop('checked', false);
            response.dokumen_jasa_barjas == 1 ? $('#dokumen_barjas4').prop('checked', true) : $('#dokumen_barjas4')
                .prop('checked', false);

            response.ka_kdh_barjas == 1 ? $('#ka_barjas5').prop('checked', true) : $('#ka_barjas5')
                .prop('checked', false);
            response.penerima_kdh_barjas == 1 ? $('#penerima_barjas5').prop('checked', true) : $('#penerima_barjas5')
                .prop('checked', false);
            response.fakta_kdh_barjas == 1 ? $('#fakta_barjas5').prop('checked', true) : $(
                    '#fakta_barjas5')
                .prop('checked', false);
            response.syarat_barjas == 1 ? $('#syarat_barjas5').prop('checked', true) : $('#syarat_barjas5')
                .prop('checked', false);

            $('#khusus_ls_barjas').show();

            if (jenis_beban == '1') {
                $('#label_pernyataan_barjas').text('Surat Pernyataan Pengajuan SPP-Tambahan Penghasilan');
                $('#label_lampiran_barjas').text('Lampiran SPP-Tambahan Penghasilan');

                $('#tambahan_penghasilan').show();
                $('#barjas').show();

                $('#honor_pns').hide();
                $('#honor_kontrak').hide();
                $('#pihak_ketiga').hide();
                $('#kdh_wkdh').hide();
            } else if (jenis_beban == '2' || jenis_beban == '3') {
                $('#label_pernyataan_barjas').text('Surat Pernyataan SPP-LSk');
                $('#label_lampiran_barjas').text('Lampiran SPP-LS');

                $('#kdh_wkdh').show();
                $('#barjas').show();

                $('#tambahan_penghasilan').hide();
                $('#honor_pns').hide();
                $('#honor_kontrak').hide();
                $('#pihak_ketiga').hide();
            } else if (jenis_beban == '4') {
                $('#label_pernyataan_barjas').text('');
                $('#label_lampiran_barjas').text('Lampiran SPP-Honorarium Tenaga Kontrak');

                $('#honor_kontrak').show();
                $('#barjas').hide();

                $('#tambahan_penghasilan').hide();
                $('#honor_pns').hide();
                $('#pihak_ketiga').hide();
                $('#kdh_wkdh').hide();
            } else if (jenis_beban == '6' || jenis_beban == '5') {
                $('#label_pernyataan_barjas').text('Surat Pernyataan Pengajuan SPP-LS');
                $('#label_lampiran_barjas').text('Lampiran SPP-LS');

                $('#pihak_ketiga').show();
                $('#barjas').show();

                $('#tambahan_penghasilan').hide();
                $('#honor_pns').hide();
                $('#honor_kontrak').hide();
                $('#kdh_wkdh').hide();
            } else if (jenis_beban == '7') {
                $('#label_pernyataan_barjas').text('Surat Pernyataan Pengajuan SPP-Honorarium PNS');
                $('#label_lampiran_barjas').text('Lampiran SPP-Honorarium PNS');

                $('#honor_pns').show();
                $('#barjas').show();

                $('#tambahan_penghasilan').hide();
                $('#pihak_ketiga').hide();
                $('#honor_kontrak').hide();
                $('#kdh_wkdh').hide();
            } else {
                $('#khusus_ls_barjas').hide();
                $('#honor_pns').hide();
                $('#tambahan_penghasilan').hide();
                $('#pihak_ketiga').hide();
                $('#honor_kontrak').hide();
                $('#kdh_wkdh').hide();
            }
        }

        $('#detail_spm').modal('show');
    }
</script>
