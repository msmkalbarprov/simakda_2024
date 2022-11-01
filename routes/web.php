<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\PenagihanController;
use App\Http\Controllers\BankKalbarController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SppLsController;
use App\Http\Controllers\SppUpController;
use App\Http\Controllers\SpmController;
use App\Http\Controllers\Sp2dController;
use App\Http\Controllers\DaftarPengujiController;
use App\Http\Controllers\PencairanSp2dController;
use App\Http\Controllers\Skpd\PencairanSp2dController as CairSp2dController;
use App\Http\Controllers\Skpd\TerimaSp2dController;
use App\Http\Controllers\Skpd\TransaksiCmsController;
use App\Http\Controllers\Skpd\UploadCmsController;
use App\Http\Controllers\Skpd\ValidasiCmsController;
use App\Http\Controllers\Skpd\PotonganPajakCmsController;
use App\Http\Controllers\Skpd\TransaksiPemindahbukuanController;
use App\Http\Controllers\Skpd\TransaksiTunaiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

Route::get('/simakda_2023', function () {
    return view('auth.login');
});

Route::get('coba', [PenerimaController::class, 'coba'])->name('penerima.coba');

// Auth::routes();
Route::group(['prefix' => 'simakda_2023'], function () {
    Route::group(['prefix' => 'kelola-akses'], function () {
        Route::resource('hak-akses', PermissionController::class);
        Route::resource('peran', RoleController::class);
        Route::resource('user', UserController::class);
    });

    // index, create, store, update, show, destroy
    
    Route::group(['prefix' => 'master'], function () {
        Route::resource('penerima', PenerimaController::class);
        Route::resource('kontrak', KontrakController::class);
        Route::post('cabang', [PenerimaController::class, 'cabang'])->name('penerima.cabang');
        Route::post('kode-setor', [PenerimaController::class, 'kode_setor'])->name('penerima.kodeSetor');
        Route::post('cek-rekening', [BankKalbarController::class, 'cek_rekening'])->name('penerima.cekRekening');
        Route::post('cek-npwp', [BankKalbarController::class, 'cek_npwp'])->name('penerima.cekNpwp');
        Route::post('cek-penerima', [PenerimaController::class, 'cekPenerima'])->name('penerima.cekPenerima');
    });

    Route::group(['prefix' => 'penatausahaan/pengeluaran'], function () {
        // Penagihan
        Route::group(['prefix' => 'penagihan'], function () {
            Route::get('', [PenagihanController::class, 'index'])->name('penagihan.index');
            Route::get('create', [PenagihanController::class, 'create'])->name('penagihan.create');
            Route::get('show/{no_bukti?}', [PenagihanController::class, 'show'])->where('no_bukti', '(.*)')->name('penagihan.show');
            Route::get('edit/{no_bukti?}', [PenagihanController::class, 'edit'])->where('no_bukti', '(.*)')->name('penagihan.edit');
            Route::delete('hapus_penagihan', [PenagihanController::class, 'hapusPenagihan'])->name('penagihan.hapus_penagihan');
            Route::post('cek_status_ang_new', [PenagihanController::class, 'cekStatusAngNew'])->name('penagihan.cek_status_ang_new');
            Route::post('cek_status_ang', [PenagihanController::class, 'cekStatusAng'])->name('penagihan.cek_status_ang');
            Route::post('cari_rekening', [PenagihanController::class, 'cariRekening'])->name('penagihan.cari_rekening');
            Route::post('cari_sumber_dana', [PenagihanController::class, 'cariSumberDana'])->name('penagihan.cari_sumber_dana');
            Route::post('cari_nama_sumber', [PenagihanController::class, 'cariNamaSumber'])->name('penagihan.cari_nama_sumber');
            Route::post('cari_total_kontrak', [PenagihanController::class, 'cariTotalKontrak'])->name('penagihan.cari_total_kontrak');
            Route::post('simpan_tampungan', [PenagihanController::class, 'simpanTampungan'])->name('penagihan.simpan_tampungan');
            Route::post('cek_nilai_kontrak', [PenagihanController::class, 'cekNilaiKontrak'])->name('penagihan.cek_nilai_kontrak');
            Route::post('cek_nilai_kontrak2', [PenagihanController::class, 'cekNilaiKontrak2'])->name('penagihan.cek_nilai_kontrak2');
            Route::post('cek_simpan_penagihan', [PenagihanController::class, 'cekSimpanPenagihan'])->name('penagihan.cek_simpan_penagihan');
            Route::post('simpan_penagihan', [PenagihanController::class, 'simpanPenagihan'])->name('penagihan.simpan_penagihan');
            Route::post('simpan_detail_penagihan', [PenagihanController::class, 'simpanDetailPenagihan'])->name('penagihan.simpan_detail_penagihan');
            Route::post('hapus_detail_tampungan_penagihan', [PenagihanController::class, 'hapusTampunganPenagihan'])->name('penagihan.hapus_detail_tampungan_penagihan');
            Route::post('hapus_semua_tampungan', [PenagihanController::class, 'hapusSemuaTampungan'])->name('penagihan.hapus_semua_tampungan');
            Route::post('hapus_detail_edit_penagihan', [PenagihanController::class, 'hapusDetailEditPenagihan'])->name(
                'penagihan.hapus_detail_edit_penagihan'
            );
            Route::post('update_penagihan', [PenagihanController::class, 'updatePenagihan'])->name('penagihan.update_penagihan');
            Route::post('update_detail_penagihan', [PenagihanController::class, 'updateDetailPenagihan'])->name('penagihan.update_detail_penagihan');
            Route::post('simpan_edit_tampungan', [PenagihanController::class, 'simpanEditTampungan'])->name('penagihan.simpan_edit_tampungan');
        });

        // SPP LS
        Route::group(['prefix' => 'spp_ls'], function () {
            Route::get('', [SppLsController::class, 'index'])->name('sppls.index');
            Route::get('create', [SppLsController::class, 'create'])->name('sppls.create');
            Route::post('cari_jenis', [SppLsController::class, 'cariJenis'])->name('sppls.cari_jenis');
            Route::post('cari_nomor_spd', [SppLsController::class, 'cariNomorSpd'])->name('sppls.cari_nomor_spd');
            Route::post('cari_sub_kegiatan', [SppLsController::class, 'cariSubKegiatan'])->name('sppls.cari_sub_kegiatan');
            Route::post('cari_rekening', [SppLsController::class, 'cariRekening'])->name('sppls.cari_rekening');
            Route::post('jumlah_anggaran_penyusunan', [SppLsController::class, 'jumlahAnggaranPenyusunan'])->name('sppls.jumlah_anggaran_penyusunan');
            Route::post('total_spd', [SppLsController::class, 'totalSpd'])->name('sppls.total_spd');
            Route::post('total_angkas', [SppLsController::class, 'totalAngkas'])->name('sppls.total_angkas');
            Route::post('realisasi_spd', [SppLsController::class, 'realisasiSpd'])->name('sppls.realisasi_spd');
            Route::post('sumber_dana', [SppLsController::class, 'sumberDana'])->name('sppls.sumber_dana');
            Route::post('cari_nospp', [SppLsController::class, 'cariNoSpp'])->name('sppls.cari_nospp');
            Route::post('cek_simpan', [SppLsController::class, 'cekSimpan'])->name('sppls.cek_simpan');
            Route::post('simpan_sppls', [SppLsController::class, 'simpanSppLs'])->name('sppls.simpan_sppls');
            Route::post('simpan_detail_sppls', [SppLsController::class, 'simpanDetailSppLs'])->name('sppls.simpan_detail_sppls');
            Route::get('tampil/{no_spp}', [SppLsController::class, 'tampilSppLs'])->where('no_spp', '(.*)')->name('sppls.show');
            Route::delete('hapus_sppls', [SppLsController::class, 'hapusSppLs'])->name('sppls.hapus_sppls');
            Route::post('cari_penagihan_sppls', [SppLsController::class, 'cariPenagihanSpp'])->name('sppls.cari_penagihan_sppls');
            Route::get('edit/{no_spp}', [SppLsController::class, 'editSppLs'])->where('no_spp', '(.*)')->name('sppls.edit');
            Route::post('simpan_sppls_edit', [SppLsController::class, 'simpanEditSppLs'])->name('sppls.simpan_sppls_edit');
            Route::post('batal_sppls', [SppLsController::class, 'batalSppLs'])->name('sppls.batal_sppls');
            // Cetakan SPPLS
            Route::get('cetak_pengantar', [SppLsController::class, 'cetakPengantarLayar'])->name('sppls.cetak_pengantar_layar');
            Route::get('cetak_rincian', [SppLsController::class, 'cetakRincianLayar'])->name('sppls.cetak_rincian_layar');
            Route::get('cetak_permintaan', [SppLsController::class, 'cetakPermintaanLayar'])->name('sppls.cetak_permintaan_layar');
            Route::get('cetak_ringkasan', [SppLsController::class, 'cetakRingkasanLayar'])->name('sppls.cetak_ringkasan_layar');
            Route::get('cetak_pernyataan', [SppLsController::class, 'cetakPernyataanLayar'])->name('sppls.cetak_pernyataan_layar');
            Route::get('cetak_sptb', [SppLsController::class, 'cetakSptbLayar'])->name('sppls.cetak_sptb_layar');
            Route::get('cetak_spp77', [SppLsController::class, 'cetakSpp77Layar'])->name('sppls.cetak_spp77_layar');
            Route::get('cetak_rincian77', [SppLsController::class, 'cetakRincian77Layar'])->name('sppls.cetak_rincian77_layar');
        });

        // SPM
        Route::group(['prefix' => 'spm'], function () {
            Route::get('', [SpmController::class, 'index'])->name('spm.index');
            Route::get('create', [SpmController::class, 'create'])->name('spm.create');
            Route::post('cari_jenis', [SpmController::class, 'cariJenis'])->name('spm.cari_jenis');
            Route::post('cari_bank', [SpmController::class, 'cariBank'])->name('spm.cari_bank');
            Route::post('detail_spm', [SpmController::class, 'detailSpm'])->name('spm.detail_spm');
            Route::post('cari_nospd', [SpmController::class, 'cariNoSpd'])->name('spm.cari_nospd');
            Route::post('cari_nospm', [SpmController::class, 'cariNoSpm'])->name('spm.cari_nospm');
            Route::post('tgl_spm_lalu', [SpmController::class, 'tglSpmLalu'])->name('spm.tgl_spm_lalu');
            Route::post('simpan_spm', [SpmController::class, 'simpanSpm'])->name('spm.simpan_spm');
            Route::post('cari_rek_pot', [SpmController::class, 'cariRekPot'])->name('spm.cari_rek_pot');
            Route::post('tambah_list_potongan', [SpmController::class, 'tambahListPotongan'])->name('spm.tambah_list_potongan');
            Route::post('isi_list_pot', [BankKalbarController::class, 'isiListPot'])->name('spm.isi_list_pot');
            Route::post('create_id_billing', [BankKalbarController::class, 'createBilling'])->name('spm.create_id_billing');
            Route::post('create_report', [BankKalbarController::class, 'createReport'])->name('spm.create_report');
            Route::post('load_rincian', [SpmController::class, 'loadRincian'])->name('spm.load_rincian');
            Route::post('load_rincian_show', [SpmController::class, 'loadRincianShow'])->name('spm.load_rincian_show');
            Route::post('hapus_rincian_pajak', [SpmController::class, 'hapusRincianPajak'])->name('spm.hapus_rincian_pajak');
            Route::post('total_show', [SpmController::class, 'totalShow'])->name('spm.total_show');
            Route::get('tambah_potongan/{no_spm?}', [SpmController::class, 'tambahPotongan'])->where('no_spm', '(.*)')->name('spm.tambah_potongan');
            Route::get('tampil/{no_spm?}', [SpmController::class, 'tampilSpm'])->where('no_spm', '(.*)')->name('spm.tampil');
            // Cetakan
            Route::get('cetak_kelengkapan', [SpmController::class, 'cetakKelengkapan'])->name('spm.cetak_kelengkapan');
            Route::get('berkas_spm', [SpmController::class, 'cetakBerkas'])->name('spm.berkas_spm');
            Route::get('pengantar', [SpmController::class, 'cetakPengantar'])->name('spm.pengantar');
            Route::get('lampiran', [SpmController::class, 'cetakLampiran'])->name('spm.lampiran');
            Route::get('tanggung', [SpmController::class, 'cetakTanggung'])->name('spm.tanggung');
            Route::get('pernyataan', [SpmController::class, 'cetakPernyataan'])->name('spm.pernyataan');
            Route::get('ringkasan_up', [SpmController::class, 'cetakRingkasanUp'])->name('spm.ringkasan_up');
            Route::get('ringkasan_gu', [SpmController::class, 'cetakRingkasanGu'])->name('spm.ringkasan_gu');
            Route::get('ringkasan_tu', [SpmController::class, 'cetakRingkasanTu'])->name('spm.ringkasan_tu');
            Route::post('batal_spm', [SpmController::class, 'batalSpmSpp'])->name('spm.batal_spm');
        });

        // SP2D
        Route::group(['prefix' => 'sp2d'], function () {
            Route::get('', [Sp2dController::class, 'index'])->name('sp2d.index');
            Route::get('tambah', [Sp2dController::class, 'create'])->name('sp2d.create');
            Route::post('cari_spm', [Sp2dController::class, 'cariSpm'])->name('sp2d.cari_spm');
            Route::post('cari_jenis', [Sp2dController::class, 'cariJenis'])->name('sp2d.cari_jenis');
            Route::post('cari_bulan', [Sp2dController::class, 'cariBulan'])->name('sp2d.cari_bulan');
            Route::post('load_rincian_spm', [Sp2dController::class, 'loadRincianSpm'])->name('sp2d.load_rincian_spm');
            Route::post('load_rincian_potongan', [Sp2dController::class, 'loadRincianPotongan'])->name('sp2d.load_rincian_potongan');
            Route::post('cari_total', [Sp2dController::class, 'cariTotal'])->name('sp2d.cari_total');
            Route::post('cari_nomor', [Sp2dController::class, 'cariNomor'])->name('sp2d.cari_nomor');
            Route::post('simpan_sp2d', [Sp2dController::class, 'simpanSp2d'])->name('sp2d.simpan_sp2d');
            Route::post('batal_sp2d', [Sp2dController::class, 'batalSp2d'])->name('sp2d.batal_sp2d');
            Route::get('tampil/{no_sp2d?}', [Sp2dController::class, 'tampilSp2d'])->where('no_sp2d', '(.*)')->name('sp2d.tampil');
            // cetakan sp2d
            Route::get('cetak_sp2d', [Sp2dController::class, 'cetakSp2d'])->name('sp2d.cetak_sp2d');
            Route::get('cetak_lampiran', [Sp2dController::class, 'cetakLampiran'])->name('sp2d.cetak_lampiran');
            Route::get('cetak_lampiran_lama', [Sp2dController::class, 'cetakLampiranLama'])->name('sp2d.cetak_lampiran_lama');
            Route::get('cetak_kelengkapan', [Sp2dController::class, 'cetakKelengkapan'])->name('sp2d.cetak_kelengkapan');
        });

        // Daftar Penguji
        Route::group(['prefix' => 'daftar_penguji'], function () {
            Route::get('', [DaftarPengujiController::class, 'index'])->name('daftar_penguji.index');
            Route::get('tambah', [DaftarPengujiController::class, 'create'])->name('daftar_penguji.create');
            Route::post('simpan_penguji', [DaftarPengujiController::class, 'simpanPenguji'])->name('daftar_penguji.simpan_penguji');
            Route::post('simpan_detail_penguji', [DaftarPengujiController::class, 'simpanDetailPenguji'])->name('daftar_penguji.simpan_detail_penguji');
            Route::get('tampil/{no_uji?}', [DaftarPengujiController::class, 'editPenguji'])->where('no_uji', '(.*)')->name('daftar_penguji.tampil');
            Route::post('load_rincian_penguji', [DaftarPengujiController::class, 'loadRincianPenguji'])->name('daftar_penguji.load_rincian_penguji');
            Route::post('hapus_rincian_penguji', [DaftarPengujiController::class, 'hapusRincianPenguji'])->name('daftar_penguji.hapus_rincian_penguji');
            Route::post('load_sp2d', [DaftarPengujiController::class, 'loadSp2d'])->name('daftar_penguji.load_sp2d');
            Route::post('tambah_rincian', [DaftarPengujiController::class, 'tambahRincian'])->name('daftar_penguji.tambah_rincian');
            Route::post('simpan_edit_penguji', [DaftarPengujiController::class, 'simpanEditPenguji'])->name('daftar_penguji.simpan_edit_penguji');
            Route::post('hapus_penguji', [DaftarPengujiController::class, 'hapusPenguji'])->name('daftar_penguji.hapus_penguji');

            // cetakan
            Route::get('cetak_penguji', [DaftarPengujiController::class, 'cetakPenguji'])->name('daftar_penguji.cetak_penguji');
        });

        // Pencairan SP2D
        Route::group(['prefix' => 'pencairan_sp2d'], function () {
            Route::get('', [PencairanSp2dController::class, 'index'])->name('pencairan_sp2d.index');
            Route::get('tampil/{no_sp2d?}', [PencairanSp2dController::class, 'tampilCair'])->where('no_sp2d', '(.*)')->name('pencairan_sp2d.tampil');
            Route::post('load_rincian_spm', [PencairanSp2dController::class, 'loadRincianSpm'])->name('pencairan_sp2d.load_rincian_spm');
            Route::post('load_rincian_potongan', [PencairanSp2dController::class, 'loadRincianPotongan'])->name('pencairan_sp2d.load_rincian_potongan');
            Route::post('cek_simpan', [PencairanSp2dController::class, 'cekSimpan'])->name('pencairan_sp2d.cek_simpan');
            Route::post('simpan_cair', [PencairanSp2dController::class, 'simpanCair'])->name('pencairan_sp2d.simpan_cair');
            Route::post('batal_cair', [PencairanSp2dController::class, 'batalCair'])->name('pencairan_sp2d.batal_cair');
        });

        // SPP UP
        Route::group(['prefix' => 'spp_up'], function () {
            Route::get('', [SppUpController::class, 'index'])->name('sppup.index');
            Route::get('tambah', [SppUpController::class, 'create'])->name('sppup.create');
            Route::get('edit/{no_spp?}', [SppUpController::class, 'edit'])->where('no_spp', '(.*)')->name('sppup.edit');
            Route::post('simpan_spp', [SppUpController::class, 'simpanSpp'])->name('sppup.simpan_spp');
            Route::post('simpan_detail_spp', [SppUpController::class, 'simpanDetailSpp'])->name('sppup.simpan_detail_spp');
            Route::post('edit_spp', [SppUpController::class, 'editSpp'])->name('sppup.edit_spp');
            Route::post('hapus', [SppUpController::class, 'hapus'])->name('sppup.hapus');
            Route::get('pengantar_up', [SppUpController::class, 'pengantarUp'])->name('sppup.pengantar_up');
            Route::get('ringkasan_up', [SppUpController::class, 'ringkasanUp'])->name('sppup.ringkasan_up');
            Route::get('rincian_up', [SppUpController::class, 'rincianUp'])->name('sppup.rincian_up');
            Route::get('pernyataan_up', [SppUpController::class, 'pernyataanUp'])->name('sppup.pernyataan_up');
            Route::get('spp_up', [SppUpController::class, 'sppUp'])->name('sppup.spp_up');
            Route::get('rincian77_up', [SppUpController::class, 'rincian77Up'])->name('sppup.rincian77_up');
        });
    });

    Route::group(['prefix' => 'skpd'], function () {
        // Terima SP2D
        Route::group(['prefix' => 'terima_sp2d'], function () {
            Route::get('', [TerimaSp2dController::class, 'index'])->name('terima_sp2d.index');
            Route::get('tampil_sp2d/{no_sp2d?}', [TerimaSp2dController::class, 'tampilSp2d'])->where('no_sp2d', '(.*)')->name('terima_sp2d.tampil_sp2d');
        });
        // Pencairan SP2D
        Route::group(['prefix' => 'pencairan_sp2d'], function () {
            Route::get('', [CairSp2dController::class, 'index'])->name('skpd.pencairan_sp2d.index');
            Route::get('tampil_sp2d/{no_sp2d?}', [CairSp2dController::class, 'tampilSp2d'])->where('no_sp2d', '(.*)')->name('skpd.pencairan_sp2d.tampil_sp2d');
            Route::post('batal_cair', [CairSp2dController::class, 'batalCair'])->where('no_sp2d', '(.*)')->name('skpd.pencairan_sp2d.batal_cair');
            Route::post('simpan_cair', [CairSp2dController::class, 'simpanCair'])->where('no_sp2d', '(.*)')->name('skpd.pencairan_sp2d.simpan_cair');
        });
        // Transaksi CMS
        Route::group(['prefix' => 'transaksi_cms'], function () {
            Route::get('', [TransaksiCmsController::class, 'index'])->name('skpd.transaksi_cms.index');
            Route::get('tambah', [TransaksiCmsController::class, 'create'])->name('skpd.transaksi_cms.create');
            Route::post('no_urut', [TransaksiCmsController::class, 'no_urut'])->name('skpd.transaksi_cms.no_urut');
            Route::post('skpd', [TransaksiCmsController::class, 'skpd'])->name('skpd.transaksi_cms.skpd');
            Route::post('cariKegiatan', [TransaksiCmsController::class, 'cariKegiatan'])->name('skpd.transaksi_cms.kegiatan');
            Route::post('cariSp2d', [TransaksiCmsController::class, 'cariSp2d'])->name('skpd.transaksi_cms.nomor_sp2d');
            Route::post('cariRekening', [TransaksiCmsController::class, 'cariRekening'])->name('skpd.transaksi_cms.rekening');
            Route::post('cariSumber', [TransaksiCmsController::class, 'cariSumber'])->name('skpd.transaksi_cms.sumber');
            Route::post('sisaBank', [TransaksiCmsController::class, 'sisaBank'])->name('skpd.transaksi_cms.sisa_bank');
            Route::post('potonganLs', [TransaksiCmsController::class, 'potonganLs'])->name('skpd.transaksi_cms.potongan_ls');
            Route::post('loadDana', [TransaksiCmsController::class, 'loadDana'])->name('skpd.transaksi_cms.load_dana');
            Route::post('statusAng', [TransaksiCmsController::class, 'statusAng'])->name('skpd.transaksi_cms.status_ang');
            Route::post('loadAngkas', [TransaksiCmsController::class, 'loadAngkas'])->name('skpd.transaksi_cms.load_angkas');
            Route::post('loadAngkasLalu', [TransaksiCmsController::class, 'loadAngkasLalu'])->name('skpd.transaksi_cms.load_angkas_lalu');
            Route::post('loadSpd', [TransaksiCmsController::class, 'loadSpd'])->name('skpd.transaksi_cms.load_spd');
            Route::post('cekSimpan', [TransaksiCmsController::class, 'cekSimpan'])->name('skpd.transaksi_cms.cek_simpan');
            Route::post('simpanCms', [TransaksiCmsController::class, 'simpanCms'])->name('skpd.transaksi_cms.simpan_cms');
            Route::post('simpanDetailCms', [TransaksiCmsController::class, 'simpanDetailCms'])->name('skpd.transaksi_cms.simpan_detail_cms');
            // EDIT
            Route::get('edit/{no_voucher?}', [TransaksiCmsController::class, 'edit'])->where('no_voucher', '(.*)')->name('skpd.transaksi_cms.edit');
            // HAPUS
            Route::post('hapusCms', [TransaksiCmsController::class, 'hapusCms'])->name('skpd.transaksi_cms.hapus_cms');
            // Cetak List
            Route::get('cetak_list', [TransaksiCmsController::class, 'cetakList'])->name('skpd.transaksi_cms.cetak_list');
        });
        // UPLOAD TRANSAKSI CMS
        Route::group(['prefix' => 'upload_cms'], function () {
            Route::get('', [UploadCmsController::class, 'index'])->name('skpd.upload_cms.index');
            Route::post('load_upload', [UploadCmsController::class, 'loadUpload'])->name('skpd.upload_cms.load_data');
            Route::post('draft_upload', [UploadCmsController::class, 'draftUpload'])->name('skpd.upload_cms.draft_upload');
            Route::post('data_upload', [UploadCmsController::class, 'dataUpload'])->name('skpd.upload_cms.data_upload');
            Route::get('tambah', [UploadCmsController::class, 'create'])->name('skpd.upload_cms.create');
            Route::post('proses_upload', [UploadCmsController::class, 'prosesUpload'])->name('skpd.upload_cms.proses_upload');
            Route::post('batal_upload', [UploadCmsController::class, 'batalUpload'])->name('skpd.upload_cms.batal_upload');
            Route::get('cetak_csv_kalbar', [UploadCmsController::class, 'cetakCsvKalbar'])->name('skpd.upload_cms.cetak_csv_kalbar');
            Route::get('cetak_csv_luar_kalbar', [UploadCmsController::class, 'cetakCsvLuarKalbar'])->name('skpd.upload_cms.cetak_csv_luar_kalbar');
            Route::post('rekening_transaksi', [UploadCmsController::class, 'rekeningTransaksi'])->name('skpd.upload_cms.rekening_transaksi');
            Route::post('rekening_potongan', [UploadCmsController::class, 'rekeningPotongan'])->name('skpd.upload_cms.rekening_potongan');
            Route::post('rekening_tujuan', [UploadCmsController::class, 'rekeningTujuan'])->name('skpd.upload_cms.rekening_tujuan');
        });
        // VALIDASI TRANSAKSI CMS
        Route::group(['prefix' => 'validasi_cms'], function () {
            Route::get('', [ValidasiCmsController::class, 'index'])->name('skpd.validasi_cms.index');
            Route::post('load_data', [ValidasiCmsController::class, 'loadData'])->name('skpd.validasi_cms.load_data');
            Route::post('draft_validasi', [ValidasiCmsController::class, 'draftValidasi'])->name('skpd.validasi_cms.draft_validasi');
            Route::post('data_upload', [ValidasiCmsController::class, 'dataUpload'])->name('skpd.validasi_cms.data_upload');
            Route::get('tambah', [ValidasiCmsController::class, 'create'])->name('skpd.validasi_cms.create');
            Route::post('proses_validasi', [ValidasiCmsController::class, 'prosesValidasi'])->name('skpd.validasi_cms.proses_validasi');
            Route::post('batal_validasi', [ValidasiCmsController::class, 'batalValidasi'])->name('skpd.validasi_cms.batal_validasi');
        });
        // Terima Potongan Pajak CMS
        Route::group(['prefix' => 'potongan_pajak_cms'], function () {
            Route::get('', [PotonganPajakCmsController::class, 'index'])->name('skpd.potongan_pajak_cms.index');
            Route::post('load_data', [PotonganPajakCmsController::class, 'loadData'])->name('skpd.potongan_pajak_cms.load_data');
            Route::get('tambah', [PotonganPajakCmsController::class, 'create'])->name('skpd.potongan_pajak_cms.create');
            Route::get('edit/{no_bukti?}', [PotonganPajakCmsController::class, 'edit'])->where('no_bukti', '(.*)')->name('skpd.potongan_pajak_cms.edit');
            Route::post('cari_kegiatan', [PotonganPajakCmsController::class, 'cariKegiatan'])->name('skpd.potongan_pajak_cms.cari_kegiatan');
            Route::post('simpan_potongan', [PotonganPajakCmsController::class, 'simpanPotongan'])->name('skpd.potongan_pajak_cms.simpan_potongan');
            Route::post('edit_potongan', [PotonganPajakCmsController::class, 'editPotongan'])->name('skpd.potongan_pajak_cms.edit_potongan');
            Route::post('hapus_potongan', [PotonganPajakCmsController::class, 'hapusPotongan'])->name('skpd.potongan_pajak_cms.hapus_potongan');
        });
        // Transaksi PemindahBukuan
        Route::group(['prefix' => 'transaksi_pemindahbukuan'], function () {
            Route::get('', [TransaksiPemindahbukuanController::class, 'index'])->name('skpd.transaksi_pemindahbukuan.index');
            Route::post('load_data', [TransaksiPemindahbukuanController::class, 'loadData'])->name('skpd.transaksi_pemindahbukuan.load_data');
            Route::get('tambah', [TransaksiPemindahbukuanController::class, 'create'])->name('skpd.transaksi_pemindahbukuan.create');
            Route::post('simpan_transaksi', [TransaksiPemindahbukuanController::class, 'simpanTransaksi'])->name('skpd.transaksi_pemindahbukuan.simpan_transaksi');
            Route::post('hapus_transaksi', [TransaksiPemindahbukuanController::class, 'hapusTransaksi'])->name('skpd.transaksi_pemindahbukuan.hapus_transaksi');
            Route::get('edit/{no_bukti?}', [TransaksiPemindahbukuanController::class, 'edit'])->where('no_bukti', '(.*)')->name('skpd.transaksi_pemindahbukuan.edit');
            Route::post('edit_transaksi', [TransaksiPemindahbukuanController::class, 'editTransaksi'])->name('skpd.transaksi_pemindahbukuan.edit_transaksi');
        });
        // Transaksi Tunai
        Route::group(['prefix' => 'transaksi_tunai'], function () {
            Route::get('', [TransaksiTunaiController::class, 'index'])->name('skpd.transaksi_tunai.index');
            Route::post('load_data', [TransaksiTunaiController::class, 'loadData'])->name('skpd.transaksi_tunai.load_data');
            Route::get('tambah', [TransaksiTunaiController::class, 'create'])->name('skpd.transaksi_tunai.create');
            Route::post('nomor_sp2d', [TransaksiTunaiController::class, 'nomorSp2d'])->name('skpd.transaksi_tunai.nomor_sp2d');
            Route::post('cari_rekening', [TransaksiTunaiController::class, 'cariRekening'])->name('skpd.transaksi_tunai.cari_rekening');
            Route::post('cari_sumber', [TransaksiTunaiController::class, 'cariSumber'])->name('skpd.transaksi_tunai.cari_sumber');
            Route::post('sisa_tunai', [TransaksiTunaiController::class, 'sisaTunai'])->name('skpd.transaksi_tunai.sisa_tunai');
            Route::post('simpan_transaksi', [TransaksiTunaiController::class, 'simpanTransaksi'])->name('skpd.transaksi_tunai.simpan_transaksi');
            Route::post('hapus_transaksi', [TransaksiTunaiController::class, 'hapusTransaksi'])->name('skpd.transaksi_tunai.hapus_transaksi');
            Route::get('edit/{no_bukti?}', [TransaksiTunaiController::class, 'edit'])->where('no_bukti', '(.*)')->name('skpd.transaksi_tunai.edit');
            Route::post('edit_transaksi', [TransaksiTunaiController::class, 'editTransaksi'])->name('skpd.transaksi_tunai.edit_transaksi');
        });
    });

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/coba', [HomeController::class, 'coba'])->name('coba');
    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login')->middleware("throttle:3,30");
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/{any}', function () {
    return view('auth.login');
})->where('any', '.*');
