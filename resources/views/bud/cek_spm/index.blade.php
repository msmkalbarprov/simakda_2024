@extends('template.app')
@section('title', 'Cek SPM | SIMAKDA')
@section('content')
    <div class="row">
        {{-- Input form --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    CEK SPM
                </div>
                <div class="card-body">
                    @csrf
                    {{-- JENIS --}}
                    <div class="mb-3 row">
                        <label for="jenis" class="col-md-2 col-form-label">JENIS</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="jenis" name="jenis">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1">UP</option>
                                <option value="2">GU</option>
                                <option value="3">TU</option>
                                <option value="4">LS GAJI</option>
                                <option value="5">LS PIHAK KETIGA LAINNYA</option>
                                <option value="6">LS BARANG JASA</option>
                            </select>
                        </div>
                    </div>
                    {{-- SKPD --}}
                    <div class="mb-3 row">
                        <label for="kd_skpd" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-10">
                            <select class="form-control select2-multiple" style="width: 100%" id="kd_skpd" name="kd_skpd">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                @foreach ($skpd as $kode)
                                    <option value="{{ $kode->kd_skpd }}" data-nama="{{ $kode->nm_skpd }}">
                                        {{ $kode->kd_skpd }} | {{ $kode->nm_skpd }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="text" name="beban" id="beban" hidden>
                    <input type="text" name="jenis_beban" id="jenis_beban" hidden>
                    <input type="text" name="jenis_kelengkapan" id="jenis_kelengkapan" hidden>
                    {{-- <div class="mb-3 row">
                        <label for="jenis_ls" class="col-md-2 col-form-label">Jenis</label>
                        <div class="col-md-10">
                            <select name="jenis_ls" class="form-control select2-multiple" id="jenis_ls">
                                <option value="" selected disabled>Silahkan Pilih</option>
                                <option value="1">Gaji Induk, Gaji Terusan, Kekurangan Gaji</option>
                                <option value="2">Gaji Susulan</option>
                                <option value="3">Tambahan Penghasilan</option>
                                <option value="4">Honorarium PNS</option>
                                <option value="5">Honorarium Tenaga Kontrak</option>
                                <option value="6">Pengadaan Barang dan Jasa/Konstruksi/Konsultansi</option>
                                <option value="7">Pengadaan Konsumsi</option>
                                <option value="8">Sewa Rumah Jabatan/Gedung untuk Kantor/Gedung Pertemuan/Tempat
                                    Pertemuan/Tempat Penginapan/Kendaraan</option>
                                <option value="9">Pengadaan Sertifikat Tanah</option>
                                <option value="10">Pengadaan Tanah</option>
                                <option value="11">Hibah Barang dan Jasa pada Pihak Ketiga</option>
                                <option value="12">LS Bantuan Sosial pada Pihak Ketiga</option>
                                <option value="13">Hibah Uang Pada Pihak Ketiga</option>
                                <option value="14">Bantuan Keuangan Pada Kabupaten/Kota</option>
                                <option value="15">Bagi Hasil Pajak dan Bukan Pajak</option>
                                <option value="16">Hibah Konstruksi pada Pihak Ketiga</option>
                                <option value="98">Belanja Operasional KDH/WKDH dan Pimpinan DPRD</option>
                                <option value="99">Pembiayaan pada Pihak Ketiga Lainnya</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        {{-- Daftar SPM --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Daftar SPM
                </div>
                <div class="card-body table-responsive">
                    <table id="tabel_spm" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>SKPD</th>
                                <th>No. SPM</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="detail_spm" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">DETAIL SPM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="no_spm" class="col-md-2 col-form-label">NO. SPM</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_spm" readonly>
                        </div>
                        <label for="no_spp" class="col-md-2 col-form-label">NO. SPP</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_spp" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="kd_unit" class="col-md-2 col-form-label">SKPD</label>
                        <div class="col-md-4">
                            <textarea id="kd_unit" class="form-control" readonly></textarea>
                        </div>
                        <label for="nm_unit" class="col-md-2 col-form-label">NAMA SKPD</label>
                        <div class="col-md-4">
                            <textarea id="nm_unit" class="form-control" readonly></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tgl_verifikasi" class="col-md-2 col-form-label">Tanggal Verifikasi</label>
                        <div class="col-md-4">
                            <input type="date" id="tgl_verifikasi" class="form-control">
                        </div>
                        <label for="keterangan_verifikasi" class="col-md-2 col-form-label">Keterangan Verifikasi</label>
                        <div class="col-md-4">
                            <textarea id="keterangan_verifikasi" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="status_verifikasi" class="col-md-2 col-form-label">Status</label>
                        <div class="col-md-4">
                            <select name="status_verifikasi" id="status_verifikasi"
                                class="form-control select2-multiple">
                                <option value="" disabled selected>Silahkan Pilih</option>
                                <option value="1">Diterima</option>
                                <option value="2">Ditunda</option>
                                <option value="3">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    {{-- UP --}}
                    <div class="mb-3 row" id="khusus_up">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pengantar_spp_up">
                            <label class="form-check-label" for="pengantar_spp_up">
                                Pengantar SPP-UP
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="spp_up">
                            <label class="form-check-label" for="spp_up">
                                SPP-UP
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="ringkasan_spp_up">
                            <label class="form-check-label" for="ringkasan_spp_up">
                                Ringkasan SPP-UP
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rincian_spp_up">
                            <label class="form-check-label" for="rincian_spp_up">
                                Rincian SPP-UP
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pernyataan_pengajuan_up">
                            <label class="form-check-label" for="pernyataan_pengajuan_up">
                                Surat Pernyataan Pengajuan SPP-UP
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="lampiran_spp_up">
                            <label class="form-check-label" for="lampiran_spp_up">
                                Lampiran SPP-UP
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="salinan_spd_up">
                            <label class="form-check-label" for="salinan_spd_up">
                                Salinan SPD
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="rekening_koran_up">
                            <label class="form-check-label" for="rekening_koran_up">
                                Fotocopy Rekening Koran Per 31 Desember
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="keputusan_gubernur_up">
                            <label class="form-check-label" for="keputusan_gubernur_up">
                                Fotocopy Keputusan Gubenur tentang Uang Persediaan
                            </label>
                        </div>
                    </div>
                    {{-- GU --}}
                    <div class="mb-3 row" id="khusus_gu">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pengantar_spp_gu">
                            <label class="form-check-label" for="pengantar_spp_gu">
                                Pengantar SPP-GU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="spp_gu">
                            <label class="form-check-label" for="spp_gu">
                                SPP-GU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="ringkasan_spp_gu">
                            <label class="form-check-label" for="ringkasan_spp_gu">
                                Ringkasan SPP-GU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rincian_spp_gu">
                            <label class="form-check-label" for="rincian_spp_gu">
                                Rincian SPP-GU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pernyataan_pengajuan_gu">
                            <label class="form-check-label" for="pernyataan_pengajuan_gu">
                                Surat Pernyataan Pengajuan SPP-GU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="lampiran_spp_gu">
                            <label class="form-check-label" for="lampiran_spp_gu">
                                Lampiran SPP-GU
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="salinan_spd_gu">
                            <label class="form-check-label" for="salinan_spd_gu">
                                Salinan SPD
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="lpj_gu">
                            <label class="form-check-label" for="lpj_gu">
                                Laporan Pertanggungjawaban(LPJ-UP)
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sptb_gu">
                            <label class="form-check-label" for="sptb_gu">
                                Surat Pernyataan Tanggung Jawab Belanja(SPTB)
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sse_gu">
                            <label class="form-check-label" for="sse_gu">
                                Fotocopy Surat Setoran Elektronik (SSE) PPn dan PPh (Resi)
                            </label>
                        </div>
                    </div>
                    {{-- TU --}}
                    <div class="mb-3 row" id="khusus_tu">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pengantar_spp_tu">
                            <label class="form-check-label" for="pengantar_spp_tu">
                                Pengantar SPP-TU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="spp_tu">
                            <label class="form-check-label" for="spp_tu">
                                SPP-TU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="ringkasan_spp_tu">
                            <label class="form-check-label" for="ringkasan_spp_tu">
                                Ringkasan SPP-TU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rencana_penggunaan_tu">
                            <label class="form-check-label" for="rencana_penggunaan_tu">
                                Rincian Rencana Penggunaan TU (Persetujuan BUD)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pernyataan_pengajuan_tu">
                            <label class="form-check-label" for="pernyataan_pengajuan_tu">
                                Surat Pernyataan Pengajuan SPP-TU
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="lampiran_spp_tu">
                            <label class="form-check-label" for="lampiran_spp_tu">
                                Lampiran SPP-TU
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="salinan_spd_tu">
                            <label class="form-check-label" for="salinan_spd_tu">
                                Salinan SPD
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value=""
                                id="jadwal_pelaksanaan_kegiatan_tu">
                            <label class="form-check-label" for="jadwal_pelaksanaan_kegiatan_tu">
                                Jadwal Pelaksanaan Kegiatan
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="rekening_koran_tu">
                            <label class="form-check-label" for="rekening_koran_tu">
                                Poto Copy Rekening Koran bulan berkenaan
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="lpj_untuk_tu">
                            <label class="form-check-label" for="lpj_untuk_tu">
                                Laporan Pertanggungjawaban (LPJ-TU) untuk pengajuan SPP-TU berikutnya
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sptb_tu">
                            <label class="form-check-label" for="sptb_tu">
                                Surat Pernyataan Tanggung Jawab Belanja (SPTB)
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sse_tu">
                            <label class="form-check-label" for="sse_tu">
                                Surat Setoran Elektronik (SSE) PPn dan PPh beserta resi (bukti setor)
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="bukti_setor_tu">
                            <label class="form-check-label" for="bukti_setor_tu">
                                Bukti Setor uang yang tidak habis dipertanggungjawabkan dalam waktu 1 (satu) bulan
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="dokumen_lain_tu">
                            <label class="form-check-label" for="dokumen_lain_tu">
                                Dokumen lain yang dipersyaratkan
                            </label>
                        </div>
                    </div>
                    {{-- LS GAJI --}}
                    <div class="mb-3 row" id="khusus_ls_gaji">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pengantar_spp_gaji">
                            <label class="form-check-label" for="pengantar_spp_gaji">
                                Pengantar SPP-Gaji
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="spp_gaji">
                            <label class="form-check-label" for="spp_gaji">
                                SPP-Gaji
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="ringkasan_spp_gaji">
                            <label class="form-check-label" for="ringkasan_spp_gaji">
                                Ringkasan SPP-Gaji
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rincian_spp_gaji">
                            <label class="form-check-label" for="rincian_spp_gaji">
                                Rincian SPP-Gaji
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value=""
                                id="pernyataan_pengajuan_gaji">
                            <label class="form-check-label" for="pernyataan_pengajuan_gaji">
                                Surat Pernyataan Pengajuan SPP-Gaji
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="lampiran_spp_gaji">
                            <label class="form-check-label" for="lampiran_spp_gaji">
                                Lampiran SPP-Gaji
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="salinan_spd_gaji">
                            <label class="form-check-label" for="salinan_spd_gaji">
                                Salinan SPD
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="daftar_gaji">
                            <label class="form-check-label" for="daftar_gaji">
                                Daftar Gaji
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="rekap_gaji_induk">
                            <label class="form-check-label" for="rekap_gaji_induk">
                                Rekapitulasi gaji Induk
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="rekap_gaji_golongan">
                            <label class="form-check-label" for="rekap_gaji_golongan">
                                Rekapitulasi Gaji golongan
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sse_gaji">
                            <label class="form-check-label" for="sse_gaji">
                                Surat Setoran Elektronik (SSE) PPh. 21
                            </label>
                        </div>
                        <div class="form-check gaji1" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sk_perubahan_gaji">
                            <label class="form-check-label" for="sk_perubahan_gaji">
                                SK. Perubahan status dari CPNS menjadi PNS
                            </label>
                        </div>
                        <div class="form-check gaji1" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sk_kenaikan_gaji">
                            <label class="form-check-label" for="sk_kenaikan_gaji">
                                SK. Kenaikan Pangkat/Penurunan Pangkat
                            </label>
                        </div>
                        <div class="form-check gaji1" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sk_struktural_gaji">
                            <label class="form-check-label" for="sk_struktural_gaji">
                                SK. Pengangkatan dalam jabatan Struktural/Fungsional
                            </label>
                        </div>
                        <div class="form-check gaji1" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="keputusan_kenaikan_gaji">
                            <label class="form-check-label" for="keputusan_kenaikan_gaji">
                                Keputusan Kenaikan Gaji Penyesuaian Masa Kerja.
                            </label>
                        </div>
                        <div class="form-check gaji1" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="keputusan_pindah_gaji">
                            <label class="form-check-label" for="keputusan_pindah_gaji">
                                Keputusan Pindah
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="daftar_keluarga_gaji">
                            <label class="form-check-label" for="daftar_keluarga_gaji">
                                Daftar Keluarga
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="pernyataan_tugas_gaji">
                            <label class="form-check-label" for="pernyataan_tugas_gaji">
                                Surat Pernyataan melaksanakan tugas(Surat)
                            </label>
                        </div>
                        <div class="form-check gaji1" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="cerai_gaji">
                            <label class="form-check-label" for="cerai_gaji">
                                Tambahan/pengurangan keluarga karena Kawin, dilampiri foto copy surat nikah/akte
                                perkawinan
                                Tambah anak, dilampiri foto copy akte kelahiran Cerai, dilampiri akte cerai
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sk_pengangkatan_gaji">
                            <label class="form-check-label" for="sk_pengangkatan_gaji">
                                Surat Keputusan Pengangkatan sebagai CPNS
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sptjm_gaji">
                            <label class="form-check-label" for="sptjm_gaji">
                                Surat Pernyataan Tanggungjawab mutlak(SPTJM)
                            </label>
                        </div>
                        <div class="form-check gaji7" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sk_mutasi_gaji">
                            <label class="form-check-label" for="sk_mutasi_gaji">
                                SK Mutasi Pindah
                            </label>
                        </div>
                        <div class="form-check gaji7" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="skpp_gaji">
                            <label class="form-check-label" for="skpp_gaji">
                                Surat Keterangan Pemberhentian Pembayaran (SKPP)
                            </label>
                        </div>
                    </div>
                    {{-- LS Pihak Ketiga --}}
                    <div class="mb-3 row" id="khusus_ls_ketiga">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pengantar_spp_ketiga">
                            <label class="form-check-label" for="pengantar_spp_ketiga">
                                Pengantar SPP-LS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="spp_ketiga">
                            <label class="form-check-label" for="spp_ketiga">
                                SPP-LS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="ringkasan_spp_ketiga">
                            <label class="form-check-label" for="ringkasan_spp_ketiga">
                                Ringkasan SPP-LS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rincian_spp_ketiga">
                            <label class="form-check-label" for="rincian_spp_ketiga">
                                Rincian SPP-LS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pernyataan_ketiga">
                            <label class="form-check-label" for="pernyataan_ketiga">
                                Surat Pernyataan SPP-LS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="lampiran_spp_ketiga">
                            <label class="form-check-label" for="lampiran_spp_ketiga">
                                Lampiran SPP-LS
                            </label>
                        </div>
                        <div class="form-check hibah_bansos_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="proposal_bansos_ketiga">
                            <label class="form-check-label" for="proposal_bansos_ketiga">
                                Proposal Bantuan Sosial dari Pihak Ketiga
                            </label>
                        </div>
                        <div class="form-check bansos_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="kepgub_bansos_ketiga">
                            <label class="form-check-label" for="kepgub_bansos_ketiga">
                                Keputusan Gubernur tentang Penetapan Bantuan Sosial
                            </label>
                        </div>
                        <div class="form-check hibah_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="nphd_ketiga">
                            <label class="form-check-label" for="nphd_ketiga">
                                Naskah Perjanjian Hibah Daerah (NPHD)
                            </label>
                        </div>
                        <div class="form-check hibah_bansos_pembiayaan_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="kab_ketiga">
                            <label class="form-check-label" for="kab_ketiga">
                                Kuitansi Asli Bermaterai (tanda tangan yang menerima dana, mengetahui PPTK dan setuju
                                dibayar oleh PA/KPA)
                            </label>
                        </div>
                        <div class="form-check bansos_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="penerima_bansos_ketiga">
                            <label class="form-check-label" for="penerima_bansos_ketiga">
                                Foto copy rekening bank atas nama penerima sesuai dengan yang tercantum dalan Surat
                                Keputusan
                            </label>
                        </div>
                        <div class="form-check hibah_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="penerima_hibah_ketiga">
                            <label class="form-check-label" for="penerima_hibah_ketiga">
                                Foto copy rekening bank atas nama penerima bantuan hibah sesuai dengan yang tercantum
                                dalam
                                Surat Keputusan atau Naskah Perjanjian Hibah Daerah
                            </label>
                        </div>
                        <div class="form-check hibah_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sptjm_hibah_ketiga">
                            <label class="form-check-label" for="sptjm_hibah_ketiga">
                                Surat Pernyataan Tanggung jawab Mutlak dari pihak penerima Hibah
                            </label>
                        </div>
                        <div class="form-check bansos_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sptjm_bansos_ketiga">
                            <label class="form-check-label" for="sptjm_bansos_ketiga">
                                Surat Pernyataan Tanggung jawab Mutlak dari pihak penerima bantuan sosial
                            </label>
                        </div>
                        <div class="form-check bankeu_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="kepgub_bankeu_ketiga">
                            <label class="form-check-label" for="kepgub_bankeu_ketiga">
                                Keputusan Gubernur tentang Penetapan Bantuan Keuangan kepada Kabupaten/Kota
                            </label>
                        </div>
                        <div class="form-check bankeu_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sk_kud_ketiga">
                            <label class="form-check-label" for="sk_kud_ketiga">
                                Surat keterangan rekening Kas Umum Daerah Kabupaten/Kota
                            </label>
                        </div>
                        <div class="form-check bagihasil_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="kepgub_bagihasil_ketiga">
                            <label class="form-check-label" for="kepgub_bagihasil_ketiga">
                                Keputusan Gubernur tentang Rencana Bagi Hasil Pajak Provinsi/Rencana Bagi Hasil Pajak
                                Rokok
                                provinsi kalimantan Barat Kepada Kabupaten/Kota Sekalimantan Barat Tahun Anggaran 2023
                            </label>
                        </div>
                        <div class="form-check bagihasil_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="fc_bagihasil_ketiga">
                            <label class="form-check-label" for="fc_bagihasil_ketiga">
                                Foto copy rekening Kas Umum Daerah Kabupaten/Kota(diutamakan Bank Pemerintah)
                            </label>
                        </div>
                        <div class="form-check pembiayaan_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sptjm_pembiayaan_ketiga">
                            <label class="form-check-label" for="sptjm_pembiayaan_ketiga">
                                Surat Pernyataan Tanggung jawab Mutlak dari pihak penerima Hibah
                            </label>
                        </div>

                        <div class="form-check btt_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="kepgub_btt_ketiga">
                            <label class="form-check-label" for="kepgub_btt_ketiga">
                                Surat Keputusan Gubernur tentang Penggunaan Dana Tidak Terduga
                            </label>
                        </div>
                        <div class="form-check btt_ketiga" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="sptjm_btt_ketiga">
                            <label class="form-check-label" for="sptjm_btt_ketiga">
                                Surat Pernyataan Tanggung Jawab Mutlak
                            </label>
                        </div>
                        <div class="form-check" style="margin-left:20px">
                            <input class="form-check-input" type="checkbox" value="" id="syarat_lain_ketiga">
                            <label class="form-check-label" for="syarat_lain_ketiga">
                                Syarat-syarat lainnya sesuai ketentuan yang berlaku
                            </label>
                        </div>
                    </div>
                    {{-- LS BARANG JASA --}}
                    <div class="mb-3 row" id="khusus_ls_barjas">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pengantar_spp_barjas">
                            <label class="form-check-label" for="pengantar_spp_barjas">
                                Pengantar SPP-LS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="spp_barjas">
                            <label class="form-check-label" for="spp_barjas">
                                SPP-LS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="ringkasan_spp_barjas">
                            <label class="form-check-label" for="ringkasan_spp_barjas">
                                Ringkasan SPP-LS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rincian_spp_barjas">
                            <label class="form-check-label" for="rincian_spp_barjas">
                                Rincian SPP-LS
                            </label>
                        </div>
                        <div class="form-check" id="barjas">
                            <input class="form-check-input" type="checkbox" value="" id="pernyataan_barjas">
                            <label class="form-check-label" for="pernyataan_barjas" id="label_pernyataan_barjas">
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="lampiran_spp_barjas">
                            <label class="form-check-label" for="lampiran_spp_barjas" id="label_lampiran_barjas">
                            </label>
                        </div>
                        {{-- TAMBAHAN PENGHASILAN --}}
                        <div id="tambahan_penghasilan">
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="salinan_barjas1">
                                <label class="form-check-label" for="salinan_barjas1">
                                    Salinan SPD
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="penerima_barjas1">
                                <label class="form-check-label" for="penerima_barjas1">
                                    Daftar penerima Tambahan Penghasilan(Tanda tangan Penerima, Pembuat Daftar, Setuju
                                    dibayar
                                    PA)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="absensi_barjas1">
                                <label class="form-check-label" for="absensi_barjas1">
                                    Daftar Hadir/Absensi Bulanan mengetahui Kepala SKPD
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="rekap_absensi_barjas1">
                                <label class="form-check-label" for="rekap_absensi_barjas1">
                                    Rekap daftar hadir harian Pegawai (Tanda tangan pembuat daftar dan mengetahui Kepala
                                    SKPD)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="ka_barjas1">
                                <label class="form-check-label" for="ka_barjas1">
                                    Kuitansi Asli (Tanda tangan Penerima Bendahara Pengeluaran dan Setuju dibayar PA)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="sse_barjas1">
                                <label class="form-check-label" for="sse_barjas1">
                                    Surat Setoran Elektronik(SSE) PPh 21
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="sts_barjas1">
                                <label class="form-check-label" for="sts_barjas1">
                                    Surat Tanda Setoran(STS) jika ada pemotongan
                                </label>
                            </div>
                        </div>
                        {{-- HONORARIUM PNS --}}
                        <div id="honor_pns">
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="salinan_barjas2">
                                <label class="form-check-label" for="salinan_barjas2">
                                    Salinan SPD
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="sk_barjas2">
                                <label class="form-check-label" for="sk_barjas2">
                                    SK. Pembentukan Tim/Panitia Pelaksanaan Kegiatan
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="terima_barjas2">
                                <label class="form-check-label" for="terima_barjas2">
                                    Daftar tanda terima Pembayaran Honor yang sudah di tanda tangan (Tanda tangan
                                    Pembuat
                                    Daftar, Mengetahui PPTK dan Setuju dibayar Pengguna Anggaran)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="ka_barjas2">
                                <label class="form-check-label" for="ka_barjas2">
                                    Kuitansi Asli(Tanda tangan Penerima Bendahara Pengeluaran, Mengetahui PPTK dan
                                    Setuju
                                    dibayar PA/KPA)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="sse_barjas2">
                                <label class="form-check-label" for="sse_barjas2">
                                    Surat Setoran Elektronik(SSE) PPh 21
                                </label>
                            </div>
                        </div>
                        {{-- HONORARIUM KONTRAK --}}
                        <div id="honor_kontrak">
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="salinan_barjas3">
                                <label class="form-check-label" for="salinan_barjas3">
                                    Salinan SPD
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="sk_barjas3">
                                <label class="form-check-label" for="sk_barjas3">
                                    SK. Pengangkatan sebagai Pegawai Non PNS/Kontrak
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="spk_barjas3">
                                <label class="form-check-label" for="spk_barjas3">
                                    Surat Perjanjian Kontrak pada saat pengajuan di awal tahun
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="terima_barjas3">
                                <label class="form-check-label" for="terima_barjas3">
                                    Daftar tanda terima Pembayaran Honor yang sudah di tanda tangan (Tanda tangan
                                    Pembuat
                                    Daftar, Mengetahui PPTK dan Setuju dibayar Pengguna Anggaran)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="ka_barjas3">
                                <label class="form-check-label" for="ka_barjas3">
                                    Kuitansi Asli (Tanda tangan Penerima Bendahara Pengeluaran, Mengetahui PPTK dan
                                    Setuju
                                    dibayar PA/KPA)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="sse_barjas3">
                                <label class="form-check-label" for="sse_barjas3">
                                    Surat Setoran Elektronik (SSE) PPh 21 melebihi PTKP
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="sse_pnbp_barjas3">
                                <label class="form-check-label" for="sse_pnbp_barjas3">
                                    Surat Setoran Elektronik PNBP 1% dan 4%
                                </label>
                            </div>
                        </div>
                        {{-- PIHAK KETIGA --}}
                        <div id="pihak_ketiga">
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="salinan_barjas4">
                                <label class="form-check-label" for="salinan_barjas4">
                                    Salinan SPD
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="nota_barjas4">
                                <label class="form-check-label" for="nota_barjas4">
                                    Nota Pencairan Dana yang ditandatangani oleh PPTK
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="kontrak_barjas4">
                                <label class="form-check-label" for="kontrak_barjas4">
                                    Dokumen Kontrak
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="kwintansi_barjas4">
                                <label class="form-check-label" for="kwintansi_barjas4">
                                    Kwitansi Asli bermaterai
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="referensi_barjas4">
                                <label class="form-check-label" for="referensi_barjas4">
                                    Referensi Bank Asli
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="npwp_barjas4">
                                <label class="form-check-label" for="npwp_barjas4">
                                    Fotocopy NPWP
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="jum_barjas4">
                                <label class="form-check-label" for="jum_barjas4">
                                    Jaminan Uang Muka(Asli)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="jp_barjas4">
                                <label class="form-check-label" for="jp_barjas4">
                                    Jaminan Pemeliharaan (Asli)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="ringkasan_barjas4">
                                <label class="form-check-label" for="ringkasan_barjas4">
                                    Ringkasan Kontrak
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="lkp_barjas4">
                                <label class="form-check-label" for="lkp_barjas4">
                                    Laporan Kemajuan Pekerjaan
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="bap1_barjas4">
                                <label class="form-check-label" for="bap1_barjas4">
                                    Berita Acara Pemeriksaan Barang/Jasa/Pekerjaan
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="bap2_barjas4">
                                <label class="form-check-label" for="bap2_barjas4">
                                    Berita Acara Penerimaan Barang/Jasa/Pekerjaan
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="bas_barjas4">
                                <label class="form-check-label" for="bas_barjas4">
                                    Berita Acara Serah Terima berdasarkan Kemajuan pekerjaan
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="bap3_barjas4">
                                <label class="form-check-label" for="bap3_barjas4">
                                    Berita Acara Pembayaran
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="jppa_barjas4">
                                <label class="form-check-label" for="jppa_barjas4">
                                    Jaminan Pelaksanaan Pekerjaan Asli
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="ffp_barjas4">
                                <label class="form-check-label" for="ffp_barjas4">
                                    Foto Fisik Pekerjaan(Masing-masing progress/kemajuan fisik)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="sse_barjas4">
                                <label class="form-check-label" for="sse_barjas4">
                                    e-Faktur pajak dan Surat Setoran Elektronik (SSE)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="dokumen_barjas4">
                                <label class="form-check-label" for="dokumen_barjas4">
                                    Dokumen lain yang diperlukan
                                </label>
                            </div>
                        </div>
                        {{-- KDH/WKDH --}}
                        <div id="kdh_wkdh">
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="salinan_barjas5">
                                <label class="form-check-label" for="salinan_barjas5">
                                    Photo Copy SPD
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="ka_barjas5">
                                <label class="form-check-label" for="ka_barjas5">
                                    Kuitansi Asli (ditandatangani oleh KDH/WKDH dan Pimpinan DPRD yang menerima,
                                    bendahara
                                    pengeluaran dan setuju bayar oleh PA)
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="penerima_barjas5">
                                <label class="form-check-label" for="penerima_barjas5">
                                    Daftar penerimaan biaya operasional KDH/WKDH dan Pimpinan DPRD
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="fakta_barjas5">
                                <label class="form-check-label" for="fakta_barjas5">
                                    Fakta Integritas penggunaan belanja operasional KDH/WKDH dan Pimpinan DPRD
                                </label>
                            </div>
                            <div class="form-check" style="margin-left:20px">
                                <input class="form-check-input" type="checkbox" value="" id="syarat_barjas5">
                                <label class="form-check-label" for="syarat_barjas5">
                                    Syarat-syarat lainnya sesuai ketentuan peraturan perundang-undangan
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- SIMPAN --}}
                    <div class="mb-3 row">
                        <div class="col-md-12 text-center">
                            <button id="simpan" class="btn btn-md btn-primary">Simpan</button>
                            <button type="button" class="btn btn-md btn-secondary"
                                data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('bud.cek_spm.js.index');
@endsection
