<?php

use App\Http\Controllers\Anggaran\PengesahanAngkasController;
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
use App\Http\Controllers\BendaharaUmumDaerahController;
use App\Http\Controllers\SppLsController;
use App\Http\Controllers\SppUpController;
use App\Http\Controllers\SpmController;
use App\Http\Controllers\Sp2dController;
use App\Http\Controllers\DaftarPengujiController;
use App\Http\Controllers\JurnalKoreksiController;
use App\Http\Controllers\PencairanSp2dController;
use App\Http\Controllers\Skpd\PencairanSp2dController as CairSp2dController;
use App\Http\Controllers\Skpd\TerimaSp2dController;
use App\Http\Controllers\Skpd\TransaksiCmsController;
use App\Http\Controllers\Skpd\UploadCmsController;
use App\Http\Controllers\Skpd\ValidasiCmsController;
use App\Http\Controllers\Skpd\PotonganPajakCmsController;
use App\Http\Controllers\Skpd\PotonganPajakController;
use App\Http\Controllers\Skpd\SetorPotonganController;
use App\Http\Controllers\Skpd\TransaksiPemindahbukuanController;
use App\Http\Controllers\Skpd\TransaksiTunaiController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Http\Controllers\skpd\LaporanBendaharaController;
use App\Http\Controllers\skpd\SpjFungsionalController;
use App\Http\Controllers\Skpd\PelimpahanController;
use App\Http\Controllers\Skpd\SimpananBankController;
use App\Http\Controllers\Skpd\BpKasBankController;
use App\Http\Controllers\Skpd\BpKasTunaiController;
use App\Http\Controllers\Skpd\PelimpahanKegiatanController;
use App\Http\Controllers\Skpd\BpPajakController;
use App\Http\Controllers\Skpd\BpPanjarController;
use App\Http\Controllers\Skpd\RealisasiFisikController;
use App\Http\Controllers\Skpd\LaporanPenutupanKasBulananController;
use App\Http\Controllers\Skpd\LaporanDthController;
use App\Http\Controllers\Skpd\LaporanRegPajakController;
use App\Http\Controllers\Skpd\RegisterCpController;
use App\Http\Controllers\Skpd\SubRincianObjekController;
use App\Http\Controllers\Skpd\RegisterSppSpmSp2dController;
use App\Http\Controllers\Skpd\KartuKendaliSubkegiatanController;
use App\Http\Controllers\Skpd\PenerimaanLainController;
use App\Http\Controllers\Skpd\PengeluaranLainController;
use App\Http\Controllers\Skpd\SetorKasController;
use App\Http\Controllers\Skpd\SetorSisaController;
use App\Http\Controllers\Skpd\UyhdController;
use App\Http\Controllers\Skpd\UyhdPajakController;
use App\Http\Controllers\Skpd\Anggaran\RakController;
use App\Http\Controllers\Skpd\Pendapatan\PenetapanController;
use App\Http\Controllers\Skpd\TransaksiKKPDController;
use App\Http\Controllers\PenandatanganController;
use App\Http\Controllers\Skpd\Pendapatan\PenerimaanController;
//spd belanaja
use App\Http\Controllers\Spd\SPDBelanjaController;
use App\Http\Controllers\Spd\PembatalanSPDController;
use App\Http\Controllers\Spd\RegisterSPDController;
use App\Http\Controllers\Spd\KonfigurasiSPDController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Skpd\Pendapatan\PenyetoranController;

// Route::get('/simakda_2023', function () {
//     return view('auth.login');
// });

Route::get('coba', [PenerimaController::class, 'coba'])->name('penerima.coba');

// Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'kelola-akses'], function () {
        Route::resource('hak-akses', PermissionController::class);
        Route::post('data_hak_akses', [PermissionController::class, 'loadData'])->name('hak_akses.load_data');
        Route::resource('peran', RoleController::class);
        Route::post('data_peran', [RoleController::class, 'loadData'])->name('peran.load_data');
        Route::resource('user', UserController::class);
        Route::post('data_pengguna', [UserController::class, 'loadData'])->name('user.load_data');
    });

    // index, create, store, update, show, destroy

    Route::group(['prefix' => 'master'], function () {
        Route::resource('penerima', PenerimaController::class);
        Route::get('penerima/show/{rekening?}/{kd_skpd?}', [PenerimaController::class, 'showPenerima'])->name('penerima.show_penerima');
        Route::get('penerima/edit/{rekening?}/{kd_skpd?}', [PenerimaController::class, 'editPenerima'])->name('penerima.edit_penerima');
        Route::put('penerima/update/{rekening?}/{kd_skpd?}', [PenerimaController::class, 'updatePenerima'])->name('penerima.update_penerima');
        Route::post('load_penerima', [PenerimaController::class, 'loadData'])->name('penerima.load_data');
        Route::resource('kontrak', KontrakController::class);
        Route::post('hapus_kontrak', [KontrakController::class, 'hapus'])->name('kontrak.hapus');
        Route::post('load_kontrak', [KontrakController::class, 'loadData'])->name('kontrak.load_data');
        // penandatangan
        Route::resource('tandatangan', PenandatanganController::class);
        Route::post('hapus_tandatangan', [PenandatanganController::class, 'hapus'])->name('tandatangan.hapus');
        Route::post('load_tandatangan', [PenandatanganController::class, 'loadData'])->name('tandatangan.load_data');
        Route::post('skpd_tandatangan', [PenandatanganController::class, 'cariSkpd'])->name('tandatangan.skpd');
        // setting
        Route::get('setting', [SettingController::class, 'edit'])->name('setting.edit');
        Route::patch('setting/update', [SettingController::class, 'update'])->name('setting.update');
        // profile SKPD
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');

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
            Route::post('load_data', [PenagihanController::class, 'loadData'])->name('penagihan.load_data');
            Route::get('create', [PenagihanController::class, 'create'])->name('penagihan.create');
            Route::get('show/{no_bukti?}', [PenagihanController::class, 'show'])->where('no_bukti', '(.*)')->name('penagihan.show');
            Route::get('edit/{no_bukti?}', [PenagihanController::class, 'edit'])->where('no_bukti', '(.*)')->name('penagihan.edit');
            Route::post('hapus_penagihan', [PenagihanController::class, 'hapusPenagihan'])->name('penagihan.hapus_penagihan');
            Route::post('realisasi_sumber_dana', [PenagihanController::class, 'realisasiSumber'])->name('penagihan.realisasi_sumber_dana');
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
            Route::post('load_data', [SppLsController::class, 'loadData'])->name('sppls.load_data');
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
            Route::post('load_data', [SpmController::class, 'loadData'])->name('spm.load_data');
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
            Route::post('load_data', [Sp2dController::class, 'loadData'])->name('sp2d.load_data');
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
            Route::post('load_data', [DaftarPengujiController::class, 'loadData'])->name('daftar_penguji.load_data');
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
            Route::post('status_bank', [DaftarPengujiController::class, 'statusBank'])->name('daftar_penguji.status_bank');

            // cetakan
            Route::get('cetak_penguji', [DaftarPengujiController::class, 'cetakPenguji'])->name('daftar_penguji.cetak_penguji');
        });
        // Pencairan SP2D
        Route::group(['prefix' => 'pencairan_sp2d'], function () {
            Route::get('', [PencairanSp2dController::class, 'index'])->name('pencairan_sp2d.index');
            Route::post('load_data', [PencairanSp2dController::class, 'loadData'])->name('pencairan_sp2d.load_data');
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
            Route::post('load_data', [SppUpController::class, 'loadData'])->name('sppup.load_data');
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
        // Pengesahan Angkas
        Route::group(['prefix' => 'pengesahan_angkas'], function () {
            Route::get('', [PengesahanAngkasController::class, 'index'])->name('pengesahan_angkas.index');
            Route::post('load_data', [PengesahanAngkasController::class, 'loadData'])->name('pengesahan_angkas.load_data');
            Route::post('simpan', [PengesahanAngkasController::class, 'simpan'])->name('pengesahan_angkas.simpan');
        });

        Route::group(['prefix' => 'spd'], function () {
            // spd belanja
            Route::group(['prefix' => 'spd_belanja'], function () {
                Route::get('', [SPDBelanjaController::class, 'index'])->name('spd_belanja.index');
                Route::post('load_data', [SPDBelanjaController::class, 'loadData'])->name('spd.spd_belanja.load_data');
                Route::post('daftar-skpd', [SPDBelanjaController::class, 'getSKPD'])->name('spd.spd_belanja.skpd');
                Route::post('daftar-nip-skpd', [SPDBelanjaController::class, 'getNipSKPD'])->name('spd.spd_belanja.nip');
                Route::post('daftar-jenis-anggaran', [SPDBelanjaController::class, 'getJenisAng'])->name('spd.spd_belanja.jns_ang');
                Route::post('daftar-status-angkas', [SPDBelanjaController::class, 'getStatusAng'])->name('spd.spd_belanja.status_angkas');
                Route::post('daftar-spd-belanja', [SPDBelanjaController::class, 'getSpdBelanja'])->name('spd.spd_belanja.load_spd_belanja');
                Route::post('insert-spd-belanja', [SPDBelanjaController::class, 'getInsertSpd'])->name('spd.spd_belanja.insert_spd');
                Route::post('delete-spd-belanja-temp', [SPDBelanjaController::class, 'getDeleteSpdTemp'])->name('spd.spd_belanja.delete_spd_temp');
                Route::post('insert-all-spd-belanja', [SPDBelanjaController::class, 'getInsertAllSpdTemp'])->name('spd.spd_belanja.insert_all_spd');
                Route::post('delete-all-spd-belanja', [SPDBelanjaController::class, 'getDeleteAllSpdTemp'])->name('spd.spd_belanja.delete_all_spd');
                Route::post('daftar-spd-belanja-temp', [SPDBelanjaController::class, 'getSpdBelanjaTemp'])->name('spd.spd_belanja.load_spd_belanja_temp');
                Route::get('tambah', [SPDBelanjaController::class, 'create'])->name('spd.spd_belanja.create');
                Route::post('simpan-spp', [SPDBelanjaController::class, 'simpanSPP'])->name('spd.spd_belanja.simpanSpp');
                Route::get('cetak-otorisasi', [SPDBelanjaController::class, 'cetakOto'])->name('spd.spd_belanja.cetak_otorisasi');
                Route::get('cetak-lampiran', [SPDBelanjaController::class, 'cetakLamp'])->name('spd.spd_belanja.cetak_lampiran');
                Route::post('/hapus_data_spd', [SPDBelanjaController::class, 'destroy'])->name('spd.spd_belanja.hapus_data_spd');
                Route::get('tampil/{no_spd}', [SPDBelanjaController::class, 'tampilspdBP'])->where('no_spd', '(.*)')->name('spdBP.show');
                Route::post('show_load_data', [SPDBelanjaController::class, 'ShowloadData'])->name('spd.spd_belanja.show_load_data');
            });

            // register spd
            Route::group(['prefix' => 'register_spd'], function () {
                Route::get('', [RegisterSPDController::class, 'index'])->name('register_spd.index');
                Route::post('daftar-skpd', [RegisterSPDController::class, 'getSKPD'])->name('spd.register_spd.skpd');
                Route::post('daftar-nip-skpd', [RegisterSPDController::class, 'getNipSKPD'])->name('spd.register_spd.nip');
                Route::get('cetak-unit-register-spd', [RegisterSPDController::class, 'CetakURS'])->name('spd.register_spd.cetak_urs');
                Route::get('cetak-skpd-register-spd', [RegisterSPDController::class, 'CetakSRS'])->name('spd.register_spd.cetak_srs');
                Route::get('cetak-kseluruhan-register-spd', [RegisterSPDController::class, 'CetakKRS'])->name('spd.register_spd.cetak_krs');
                Route::get('cetak-kseluruhan-revisi-register-spd', [RegisterSPDController::class, 'CetakKRRS'])->name('spd.register_spd.cetak_krrs');
            });

            //konfigurasi spd
            Route::group(['prefix' => 'konfigurasi_spd'], function () {
                Route::get('', [KonfigurasiSPDController::class, 'index'])->name('konfigurasi_spd.index');
                Route::patch('update', [KonfigurasiSPDController::class, 'update'])->name('spd.konfigurasi_spd.update');
            });
            //pembatalan spd
            Route::group(['prefix' => 'pembatalan_spd'], function () {
                Route::get('', [PembatalanSPDController::class, 'index'])->name('pembatalan_spd.index');
                Route::get('load_data', [PembatalanSPDController::class, 'loadData'])->name('spd.pembatalan_spd.load_data');
                Route::patch('update-status', [PembatalanSPDController::class, 'updateStatus'])->name('spd.spd_belanja.update_status');
            });
        });
    });

    Route::group(['prefix' => 'skpd'], function () {
        // Terima SP2D
        Route::group(['prefix' => 'terima_sp2d'], function () {
            Route::get('', [TerimaSp2dController::class, 'index'])->name('terima_sp2d.index');
            Route::post('load_data', [TerimaSp2dController::class, 'loadData'])->name('terima_sp2d.load_data');
            Route::get('tampil_sp2d/{no_sp2d?}', [TerimaSp2dController::class, 'tampilSp2d'])->where('no_sp2d', '(.*)')->name('terima_sp2d.tampil_sp2d');
        });
        // Pencairan SP2D
        Route::group(['prefix' => 'pencairan_sp2d'], function () {
            Route::get('', [CairSp2dController::class, 'index'])->name('skpd.pencairan_sp2d.index');
            Route::post('load_data', [CairSp2dController::class, 'loadData'])->name('skpd.pencairan_sp2d.load_data');
            Route::get('tampil_sp2d/{no_sp2d?}', [CairSp2dController::class, 'tampilSp2d'])->where('no_sp2d', '(.*)')->name('skpd.pencairan_sp2d.tampil_sp2d');
            Route::post('batal_cair', [CairSp2dController::class, 'batalCair'])->where('no_sp2d', '(.*)')->name('skpd.pencairan_sp2d.batal_cair');
            Route::post('simpan_cair', [CairSp2dController::class, 'simpanCair'])->where('no_sp2d', '(.*)')->name('skpd.pencairan_sp2d.simpan_cair');
        });
        // Transaksi CMS
        Route::group(['prefix' => 'transaksi_cms'], function () {
            Route::get('', [TransaksiCmsController::class, 'index'])->name('skpd.transaksi_cms.index');
            Route::post('load_data', [TransaksiCmsController::class, 'loadData'])->name('skpd.transaksi_cms.load_data');
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
        // Terima Potongan Pajak
        Route::group(['prefix' => 'potongan_pajak'], function () {
            Route::get('', [PotonganPajakController::class, 'index'])->name('skpd.potongan_pajak.index');
            Route::post('load_data', [PotonganPajakController::class, 'loadData'])->name('skpd.potongan_pajak.load_data');
            Route::get('tambah', [PotonganPajakController::class, 'create'])->name('skpd.potongan_pajak.create');
            Route::get('edit/{no_bukti?}', [PotonganPajakController::class, 'edit'])->where('no_bukti', '(.*)')->name('skpd.potongan_pajak.edit');
            Route::post('cari_kegiatan', [PotonganPajakController::class, 'cariKegiatan'])->name('skpd.potongan_pajak.cari_kegiatan');
            Route::post('cari_rekening', [PotonganPajakController::class, 'cariRekening'])->name('skpd.potongan_pajak.cari_rekening');
            Route::post('simpan_potongan', [PotonganPajakController::class, 'simpanPotongan'])->name('skpd.potongan_pajak.simpan_potongan');
            Route::post('edit_potongan', [PotonganPajakController::class, 'editPotongan'])->name('skpd.potongan_pajak.edit_potongan');
            Route::post('hapus_potongan', [PotonganPajakController::class, 'hapusPotongan'])->name('skpd.potongan_pajak.hapus_potongan');
        });
        // Setor Potongan Pajak
        Route::group(['prefix' => 'setor_potongan'], function () {
            Route::get('', [SetorPotonganController::class, 'index'])->name('skpd.setor_potongan.index');
            Route::post('load_data', [SetorPotonganController::class, 'loadData'])->name('skpd.setor_potongan.load_data');
            Route::post('list_potongan', [SetorPotonganController::class, 'loadPotongan'])->name('skpd.setor_potongan.list_potongan');
            Route::post('total_potongan', [SetorPotonganController::class, 'totalPotongan'])->name('skpd.setor_potongan.total_potongan');
            Route::post('cek_billing', [BankKalbarController::class, 'cekBilling'])->name('skpd.setor_potongan.cek_billing');
            Route::post('simpan_ntpn', [SetorPotonganController::class, 'simpanNtpn'])->name('skpd.setor_potongan.simpan_ntpn');
            Route::post('edit_ntpn', [SetorPotonganController::class, 'editNtpn'])->name('skpd.setor_potongan.edit_ntpn');
            Route::get('tambah', [SetorPotonganController::class, 'create'])->name('skpd.setor_potongan.create');
            Route::post('simpan_potongan', [SetorPotonganController::class, 'simpanPotongan'])->name('skpd.setor_potongan.simpan_potongan');
            Route::get('edit/{no_bukti?}', [SetorPotonganController::class, 'edit'])->where('no_bukti', '(.*)')->name('skpd.setor_potongan.edit');
            Route::post('edit_potongan', [SetorPotonganController::class, 'editPotongan'])->name('skpd.setor_potongan.edit_potongan');
            Route::post('hapus_potongan', [SetorPotonganController::class, 'hapusPotongan'])->name('skpd.setor_potongan.hapus_potongan');
        });
        // Pelimpahan
        Route::group(['prefix' => 'pelimpahan'], function () {
            // Pelimpahan UP
            Route::get('up', [PelimpahanController::class, 'indexUp'])->name('skpd.pelimpahan.up_index');
            Route::post('load_data_up', [PelimpahanController::class, 'loadDataUp'])->name('skpd.pelimpahan.load_data_up');
            Route::get('tambah_up', [PelimpahanController::class, 'tambahUp'])->name('skpd.pelimpahan.tambah_up');
            Route::post('simpan_up', [PelimpahanController::class, 'simpanUp'])->name('skpd.pelimpahan.simpan_up');
            Route::get('edit_up/{no_kas?}', [PelimpahanController::class, 'editUp'])->where('no_kas', '(.*)')->name('skpd.pelimpahan.edit_up');
            Route::post('update_up', [PelimpahanController::class, 'updateUp'])->name('skpd.pelimpahan.update_up');
            Route::post('hapus_up', [PelimpahanController::class, 'hapusUp'])->name('skpd.pelimpahan.hapus_up');
            // Pelimpahan GU
            Route::get('gu', [PelimpahanController::class, 'indexGu'])->name('skpd.pelimpahan.gu_index');
            Route::post('load_data_gu', [PelimpahanController::class, 'loadDataGu'])->name('skpd.pelimpahan.load_data_gu');
            Route::get('tambah_gu', [PelimpahanController::class, 'tambahGu'])->name('skpd.pelimpahan.tambah_gu');
            Route::post('no_lpj', [PelimpahanController::class, 'noLpj'])->name('skpd.pelimpahan.no_lpj');
            Route::post('simpan_gu', [PelimpahanController::class, 'simpanGu'])->name('skpd.pelimpahan.simpan_gu');
            Route::get('edit_gu/{no_kas?}', [PelimpahanController::class, 'editGu'])->where('no_kas', '(.*)')->name('skpd.pelimpahan.edit_gu');
            Route::post('update_gu', [PelimpahanController::class, 'updateGu'])->name('skpd.pelimpahan.update_gu');
            Route::post('hapus_gu', [PelimpahanController::class, 'hapusGu'])->name('skpd.pelimpahan.hapus_gu');
            // Upload Pelimpahan
            Route::get('upload', [PelimpahanController::class, 'upload'])->name('skpd.pelimpahan.upload');
            Route::post('load_upload', [PelimpahanController::class, 'loadUpload'])->name('skpd.pelimpahan.load_upload');
            Route::post('draft_upload', [PelimpahanController::class, 'draftUpload'])->name('skpd.pelimpahan.draft_upload');
            Route::post('data_upload', [PelimpahanController::class, 'dataUpload'])->name('skpd.pelimpahan.data_upload');
            Route::get('tambah_upload', [PelimpahanController::class, 'tambahUpload'])->name('skpd.pelimpahan.tambah_upload');
            Route::post('proses_upload', [PelimpahanController::class, 'prosesUpload'])->name('skpd.pelimpahan.proses_upload');
            Route::post('batal_upload', [PelimpahanController::class, 'batalUpload'])->name('skpd.pelimpahan.batal_upload');
            Route::get('cetak_csv', [PelimpahanController::class, 'cetakCsv'])->name('skpd.pelimpahan.cetak_csv');
            Route::post('rekening_transaksi', [PelimpahanController::class, 'rekeningTransaksi'])->name('skpd.pelimpahan.rekening_transaksi');
            Route::post('rekening_potongan', [PelimpahanController::class, 'rekeningPotongan'])->name('skpd.pelimpahan.rekening_potongan');
            Route::post('rekening_tujuan', [PelimpahanController::class, 'rekeningTujuan'])->name('skpd.pelimpahan.rekening_tujuan');
            // Validasi Pelimpahan
            Route::get('validasi', [PelimpahanController::class, 'validasi'])->name('skpd.pelimpahan.validasi');
            Route::post('load_validasi', [PelimpahanController::class, 'loadValidasi'])->name('skpd.pelimpahan.load_validasi');
            Route::post('draft_validasi', [PelimpahanController::class, 'draftValidasi'])->name('skpd.pelimpahan.draft_validasi');
            Route::post('data_validasi', [PelimpahanController::class, 'dataValidasi'])->name('skpd.pelimpahan.data_validasi');
            Route::get('tambah_validasi', [PelimpahanController::class, 'tambahValidasi'])->name('skpd.pelimpahan.tambah_validasi');
            Route::post('proses_validasi', [PelimpahanController::class, 'prosesValidasi'])->name('skpd.pelimpahan.proses_validasi');
            Route::post('batal_validasi', [PelimpahanController::class, 'batalValidasi'])->name('skpd.pelimpahan.batal_validasi');
        });
        // Ambil Uang Simpanan
        Route::group(['prefix' => 'simpanan_bank'], function () {
            // Ambil Simpanan Bank ke Kasben
            Route::get('kasben', [SimpananBankController::class, 'kasben'])->name('skpd.simpanan_bank.kasben');
            Route::post('load_kasben', [SimpananBankController::class, 'loadKasben'])->name('skpd.simpanan_bank.load_kasben');
            Route::get('tambah_kasben', [SimpananBankController::class, 'tambahKasben'])->name('skpd.simpanan_bank.tambah_kasben');
            Route::post('simpan_kasben', [SimpananBankController::class, 'simpanKasben'])->name('skpd.simpanan_bank.simpan_kasben');
            Route::get('editKasben/{no_bukti?}', [SimpananBankController::class, 'editKasben'])->where('no_bukti', '(.*)')->name('skpd.simpanan_bank.edit_kasben');
            Route::post('update_kasben', [SimpananBankController::class, 'updateKasben'])->name('skpd.simpanan_bank.update_kasben');
            Route::post('hapus_kasben', [SimpananBankController::class, 'hapusKasben'])->name('skpd.simpanan_bank.hapus_kasben');
            // Ambil Simpanan Bank ke Tunai
            Route::get('tunai', [SimpananBankController::class, 'tunai'])->name('skpd.simpanan_bank.tunai');
            Route::post('load_tunai', [SimpananBankController::class, 'loadTunai'])->name('skpd.simpanan_bank.load_tunai');
            Route::get('tambah_tunai', [SimpananBankController::class, 'tambahTunai'])->name('skpd.simpanan_bank.tambah_tunai');
            Route::post('simpan_tunai', [SimpananBankController::class, 'simpanTunai'])->name('skpd.simpanan_bank.simpan_tunai');
            Route::get('editTunai/{no_bukti?}', [SimpananBankController::class, 'editTunai'])->where('no_bukti', '(.*)')->name('skpd.simpanan_bank.edit_tunai');
            Route::post('update_tunai', [SimpananBankController::class, 'updateTunai'])->name('skpd.simpanan_bank.update_tunai');
            Route::post('hapus_tunai', [SimpananBankController::class, 'hapusTunai'])->name('skpd.simpanan_bank.hapus_tunai');
            // Setor Simpanan Bank
            Route::get('setor', [SimpananBankController::class, 'setor'])->name('skpd.simpanan_bank.setor');
            Route::post('load_setor', [SimpananBankController::class, 'loadSetor'])->name('skpd.simpanan_bank.load_setor');
            Route::get('tambah_setor', [SimpananBankController::class, 'tambahSetor'])->name('skpd.simpanan_bank.tambah_setor');
            Route::post('simpan_setor', [SimpananBankController::class, 'simpanSetor'])->name('skpd.simpanan_bank.simpan_setor');
            Route::get('editSetor/{no_bukti?}', [SimpananBankController::class, 'editSetor'])->where('no_bukti', '(.*)')->name('skpd.simpanan_bank.edit_setor');
            Route::post('update_setor', [SimpananBankController::class, 'updateSetor'])->name('skpd.simpanan_bank.update_setor');
            Route::post('hapus_setor', [SimpananBankController::class, 'hapusSetor'])->name('skpd.simpanan_bank.hapus_setor');
        });
        // Pelimpahan Kegiatan
        Route::group(['prefix' => 'pelimpahan_kegiatan'], function () {
            // Pelimpahan Sub Kegiatan
            Route::get('index', [PelimpahanKegiatanController::class, 'index'])->name('skpd.pelimpahan_kegiatan.index');
            Route::post('load_data', [PelimpahanKegiatanController::class, 'loadData'])->name('skpd.pelimpahan_kegiatan.load_data');
            Route::get('create', [PelimpahanKegiatanController::class, 'create'])->name('skpd.pelimpahan_kegiatan.create');
            Route::post('simpan', [PelimpahanKegiatanController::class, 'simpan'])->name('skpd.pelimpahan_kegiatan.simpan');
            Route::get('edit/{id_user?}/{kd_bpp}', [PelimpahanKegiatanController::class, 'edit'])->where('id_user', '(.*)')->where('kd_bpp', '(.*)')->name('skpd.pelimpahan_kegiatan.edit');
            Route::post('update', [PelimpahanKegiatanController::class, 'update'])->name('skpd.pelimpahan_kegiatan.update');
            Route::post('hapus', [PelimpahanKegiatanController::class, 'hapus'])->name('skpd.pelimpahan_kegiatan.hapus');
        });
        // Transaksi Kas
        Route::group(['prefix' => 'transaksi_kas'], function () {
            // Setor Sisa Kas/CP
            Route::group(['prefix' => 'setor_sisa'], function () {
                Route::get('index', [SetorSisaController::class, 'index'])->name('skpd.setor_sisa.index');
                Route::post('load_data', [SetorSisaController::class, 'loadData'])->name('skpd.setor_sisa.load_data');
                Route::post('no_sp2d', [SetorSisaController::class, 'noSp2d'])->name('skpd.setor_sisa.no_sp2d');
                Route::post('kegiatan', [SetorSisaController::class, 'kegiatan'])->name('skpd.setor_sisa.kegiatan');
                Route::post('rekening', [SetorSisaController::class, 'rekening'])->name('skpd.setor_sisa.rekening');
                Route::get('create', [SetorSisaController::class, 'create'])->name('skpd.setor_sisa.create');
                Route::post('simpan', [SetorSisaController::class, 'simpan'])->name('skpd.setor_sisa.simpan');
                Route::get('edit/{no_sts?}', [SetorSisaController::class, 'edit'])->where('no_sts', '(.*)')->name('skpd.setor_sisa.edit');
                Route::post('update', [SetorSisaController::class, 'update'])->name('skpd.setor_sisa.update');
                Route::post('hapus', [SetorSisaController::class, 'hapus'])->name('skpd.setor_sisa.hapus');
            });
            // Penerimaan Lain-Lain Pengurang Belanja
            Route::group(['prefix' => 'penerimaan_lain'], function () {
                Route::get('index', [PenerimaanLainController::class, 'index'])->name('skpd.penerimaan_lain.index');
                Route::post('load_data', [PenerimaanLainController::class, 'loadData'])->name('skpd.penerimaan_lain.load_data');
                Route::get('create', [PenerimaanLainController::class, 'create'])->name('skpd.penerimaan_lain.create');
                Route::post('simpan', [PenerimaanLainController::class, 'simpan'])->name('skpd.penerimaan_lain.simpan');
                Route::get('edit/{id_user?}/{kd_bpp}', [PenerimaanLainController::class, 'edit'])->where('id_user', '(.*)')->where('kd_bpp', '(.*)')->name('skpd.penerimaan_lain.edit');
                Route::post('update', [PenerimaanLainController::class, 'update'])->name('skpd.penerimaan_lain.update');
                Route::post('hapus', [PenerimaanLainController::class, 'hapus'])->name('skpd.penerimaan_lain.hapus');
            });
            // Pengeluaran Lain-Lain
            Route::group(['prefix' => 'pengeluaran_lain'], function () {
                Route::get('index', [PengeluaranLainController::class, 'index'])->name('skpd.pengeluaran_lain.index');
                Route::post('load_data', [PengeluaranLainController::class, 'loadData'])->name('skpd.pengeluaran_lain.load_data');
                Route::get('create', [PengeluaranLainController::class, 'create'])->name('skpd.pengeluaran_lain.create');
                Route::post('simpan', [PengeluaranLainController::class, 'simpan'])->name('skpd.pengeluaran_lain.simpan');
                Route::get('edit/{no_bukti?}', [PengeluaranLainController::class, 'edit'])->where('no_bukti', '(.*)')->name('skpd.pengeluaran_lain.edit');
                Route::post('update', [PengeluaranLainController::class, 'update'])->name('skpd.pengeluaran_lain.update');
                Route::post('hapus', [PengeluaranLainController::class, 'hapus'])->name('skpd.pengeluaran_lain.hapus');
            });
            // Setor Kas Unit Ke SKPD
            Route::group(['prefix' => 'setor_kas'], function () {
                // List Setor (CMS)
                Route::group(['prefix' => 'setor'], function () {
                    Route::get('index', [SetorKasController::class, 'index'])->name('skpd.setor.index');
                    Route::post('load_data', [SetorKasController::class, 'loadData'])->name('skpd.setor.load_data');
                    Route::get('create', [SetorKasController::class, 'create'])->name('skpd.setor.create');
                    Route::post('simpan', [SetorKasController::class, 'simpan'])->name('skpd.setor.simpan');
                    Route::get('edit/{no_kas?}', [SetorKasController::class, 'edit'])->where('no_kas', '(.*)')->name('skpd.setor.edit');
                    Route::post('update', [SetorKasController::class, 'update'])->name('skpd.setor.update');
                    Route::post('hapus', [SetorKasController::class, 'hapus'])->name('skpd.setor.hapus');
                });
                // List Upload Setor (CMS)
                Route::group(['prefix' => 'upload_setor'], function () {
                    Route::get('index', [SetorKasController::class, 'indexUpload'])->name('skpd.upload_setor.index');
                    Route::post('load_data', [SetorKasController::class, 'loadDataUpload'])->name('skpd.upload_setor.load_data');
                    Route::post('draft_upload', [SetorKasController::class, 'draftUpload'])->name('skpd.upload_setor.draft_upload');
                    Route::post('data_upload', [SetorKasController::class, 'dataUpload'])->name('skpd.upload_setor.data_upload');
                    Route::get('create', [SetorKasController::class, 'createUpload'])->name('skpd.upload_setor.create');
                    Route::post('simpan', [SetorKasController::class, 'simpanUpload'])->name('skpd.upload_setor.simpan');
                    Route::post('hapus', [SetorKasController::class, 'hapusUpload'])->name('skpd.upload_setor.hapus');
                    Route::get('cetak_csv', [SetorKasController::class, 'cetakCsv'])->name('skpd.upload_setor.cetak_csv');
                });
                // List Validasi Setor (CMS)
                Route::group(['prefix' => 'validasi_setor'], function () {
                    Route::get('index', [SetorKasController::class, 'indexValidasi'])->name('skpd.validasi_setor.index');
                    Route::post('load_data', [SetorKasController::class, 'loadDataValidasi'])->name('skpd.validasi_setor.load_data');
                    Route::get('create', [SetorKasController::class, 'createValidasi'])->name('skpd.validasi_setor.create');
                    Route::post('simpan', [SetorKasController::class, 'simpanValidasi'])->name('skpd.validasi_setor.simpan');
                    Route::post('draft_validasi', [SetorKasController::class, 'draftValidasi'])->name('skpd.validasi_setor.draft_validasi');
                    Route::post('hapus', [SetorKasController::class, 'hapusValidasi'])->name('skpd.validasi_setor.hapus');
                });
                // List Setor (Tunai Ke Bank)
                Route::group(['prefix' => 'setor_tunai'], function () {
                    Route::get('index', [SetorKasController::class, 'indexTunai'])->name('skpd.setor_tunai.index');
                    Route::post('load_data', [SetorKasController::class, 'loadDataTunai'])->name('skpd.setor_tunai.load_data');
                    Route::get('create', [SetorKasController::class, 'createTunai'])->name('skpd.setor_tunai.create');
                    Route::post('simpan', [SetorKasController::class, 'simpanTunai'])->name('skpd.setor_tunai.simpan');
                    Route::post('bank', [SetorKasController::class, 'bank'])->name('skpd.setor_tunai.bank');
                    Route::get('edit/{no_kas?}', [SetorKasController::class, 'editTunai'])->where('no_kas', '(.*)')->name('skpd.setor_tunai.edit');
                    Route::post('update', [SetorKasController::class, 'updateTunai'])->name('skpd.setor_tunai.update');
                    Route::post('hapus', [SetorKasController::class, 'hapusTunai'])->name('skpd.setor_tunai.hapus');
                });
            });
            // UYHD
            Route::group(['prefix' => 'uyhd'], function () {
                Route::get('index', [UyhdController::class, 'index'])->name('skpd.uyhd.index');
                Route::post('load_data', [UyhdController::class, 'loadData'])->name('skpd.uyhd.load_data');
                Route::get('create', [UyhdController::class, 'create'])->name('skpd.uyhd.create');
                Route::post('simpan', [UyhdController::class, 'simpan'])->name('skpd.uyhd.simpan');
                Route::get('edit/{no_bukti?}', [UyhdController::class, 'edit'])->where('no_bukti', '(.*)')->name('skpd.uyhd.edit');
                Route::post('update', [UyhdController::class, 'update'])->name('skpd.uyhd.update');
                Route::post('hapus', [UyhdController::class, 'hapus'])->name('skpd.uyhd.hapus');
            });
            // UYHD Pajak
            Route::group(['prefix' => 'uyhd_pajak'], function () {
                Route::get('index', [UyhdPajakController::class, 'index'])->name('skpd.uyhd_pajak.index');
                Route::post('load_data', [UyhdPajakController::class, 'loadData'])->name('skpd.uyhd_pajak.load_data');
                Route::get('create', [UyhdPajakController::class, 'create'])->name('skpd.uyhd_pajak.create');
                Route::post('simpan', [UyhdPajakController::class, 'simpan'])->name('skpd.uyhd_pajak.simpan');
                Route::get('edit/{no_bukti?}', [UyhdPajakController::class, 'edit'])->where('no_bukti', '(.*)')->name('skpd.uyhd_pajak.edit');
                Route::post('update', [UyhdPajakController::class, 'update'])->name('skpd.uyhd_pajak.update');
                Route::post('hapus', [UyhdPajakController::class, 'hapus'])->name('skpd.uyhd_pajak.hapus');
            });
        });
        // Anggaran (RAK)
        Route::group(['prefix' => 'anggaran'], function () {
            // Input RAK
            Route::group(['prefix' => 'input_rak'], function () {
                Route::get('index', [RakController::class, 'index'])->name('skpd.input_rak.index');
                Route::post('load_data', [RakController::class, 'loadData'])->name('skpd.input_rak.load_data');
                Route::post('jenis_anggaran', [RakController::class, 'jenisAnggaran'])->name('skpd.input_rak.jenis_anggaran');
                Route::post('jenis_rak', [RakController::class, 'jenisRak'])->name('skpd.input_rak.jenis_rak');
                Route::post('sub_kegiatan', [RakController::class, 'subKegiatan'])->name('skpd.input_rak.sub_kegiatan');
                Route::post('rekening_rak', [RakController::class, 'rekeningRak'])->name('skpd.input_rak.rekening_rak');
                Route::post('nilai_triwulan', [RakController::class, 'nilaiTriwulan'])->name('skpd.input_rak.nilai_triwulan');
                Route::post('nilai_realisasi', [RakController::class, 'nilaiRealisasi'])->name('skpd.input_rak.nilai_realisasi');
                Route::post('nilai_realisasi_bulan', [RakController::class, 'nilaiRealisasiBulan'])->name('skpd.input_rak.nilai_realisasi_bulan');
                Route::post('status_kunci', [RakController::class, 'statusKunci'])->name('skpd.input_rak.status_kunci');
                Route::post('simpan_rak', [RakController::class, 'simpanRak'])->name('skpd.input_rak.simpan_rak');
            });
            // Cetak RAK
            Route::group(['prefix' => 'cetak_rak'], function () {
                Route::group(['prefix' => 'per_sub_kegiatan'], function () {
                    Route::get('cetak_anggaran_per_sub_kegiatan', [RakController::class, 'cetakPerSubKegiatanIndex'])->name('skpd.cetak_rak.per_sub_kegiatan');
                    Route::get('cetak_angkas_giat_preview', [RakController::class, 'cetakRakPerKegiatan'])->name('skpd.cetak_rak.cetak');
                });
                Route::group(['prefix' => 'per_sub_rincian_objek'], function () {
                    Route::get('cetak_anggaran_per_sub_rincian_objek', [RakController::class, 'rincianObjekIndex'])->name('skpd.cetak_rak.per_sub_rincian_objek');
                    Route::get('cetak_angkas_giat_preview', [RakController::class, 'cetakRakPerObjek'])->name('skpd.cetak_rak.cetak_objek');
                });
            });
            // CEK RAK
            Route::group(['prefix' => 'cek_rak'], function () {
                Route::get('cek_anggaran', [RakController::class, 'cekAnggaranIndex'])->name('skpd.cek_rak.cek_anggaran');
                Route::get('cetakan_cek_anggaran', [RakController::class, 'cetakCekAnggaran'])->name('skpd.cek_rak.cetakan_cek_anggaran');
            });
        });
        // Transaksi KKPD
        Route::group(['prefix' => 'transaksi_kkpd'], function () {
            // INPUT TRANSAKSI KKPD
            Route::get('', [TransaksiKKPDController::class, 'index'])->name('skpd.transaksi_kkpd.index');
            Route::post('load_data', [TransaksiKKPDController::class, 'loadData'])->name('skpd.transaksi_kkpd.load_data');
            Route::get('tambah', [TransaksiKKPDController::class, 'create'])->name('skpd.transaksi_kkpd.create');
            Route::post('no_urut', [TransaksiKKPDController::class, 'no_urut'])->name('skpd.transaksi_kkpd.no_urut');
            Route::post('skpd', [TransaksiKKPDController::class, 'skpd'])->name('skpd.transaksi_kkpd.skpd');
            Route::post('cariKegiatan', [TransaksiKKPDController::class, 'cariKegiatan'])->name('skpd.transaksi_kkpd.kegiatan');
            Route::post('cariSp2d', [TransaksiKKPDController::class, 'cariSp2d'])->name('skpd.transaksi_kkpd.nomor_sp2d');
            Route::post('cariRekening', [TransaksiKKPDController::class, 'cariRekening'])->name('skpd.transaksi_kkpd.rekening');
            Route::post('cariSumber', [TransaksiKKPDController::class, 'cariSumber'])->name('skpd.transaksi_kkpd.sumber');
            Route::post('sisaBank', [TransaksiKKPDController::class, 'sisaBank'])->name('skpd.transaksi_kkpd.sisa_bank');
            Route::post('potonganLs', [TransaksiKKPDController::class, 'potonganLs'])->name('skpd.transaksi_kkpd.potongan_ls');
            Route::post('loadDana', [TransaksiKKPDController::class, 'loadDana'])->name('skpd.transaksi_kkpd.load_dana');
            Route::post('statusAng', [TransaksiKKPDController::class, 'statusAng'])->name('skpd.transaksi_kkpd.status_ang');
            Route::post('loadAngkas', [TransaksiKKPDController::class, 'loadAngkas'])->name('skpd.transaksi_kkpd.load_angkas');
            Route::post('loadAngkasLalu', [TransaksiKKPDController::class, 'loadAngkasLalu'])->name('skpd.transaksi_kkpd.load_angkas_lalu');
            Route::post('loadSpd', [TransaksiKKPDController::class, 'loadSpd'])->name('skpd.transaksi_kkpd.load_spd');
            Route::post('cekSimpan', [TransaksiKKPDController::class, 'cekSimpan'])->name('skpd.transaksi_kkpd.cek_simpan');
            Route::post('simpanCms', [TransaksiKKPDController::class, 'simpanCms'])->name('skpd.transaksi_kkpd.simpan_cms');
            Route::post('simpanDetailCms', [TransaksiKKPDController::class, 'simpanDetailCms'])->name('skpd.transaksi_kkpd.simpan_detail_cms');
            Route::get('edit/{no_voucher?}', [TransaksiKKPDController::class, 'edit'])->where('no_voucher', '(.*)')->name('skpd.transaksi_kkpd.edit');
            Route::post('hapusCms', [TransaksiKKPDController::class, 'hapusCms'])->name('skpd.transaksi_kkpd.hapus_cms');
            Route::get('cetak_list', [TransaksiKKPDController::class, 'cetakList'])->name('skpd.transaksi_kkpd.cetak_list');

            // Validasi Transaksi KKPD
            Route::get('verifikasi', [TransaksiKKPDController::class, 'indexValidasi'])->name('skpd.transaksi_kkpd.index_validasi');
            Route::post('load_data_validasi', [TransaksiKKPDController::class, 'loadDataValidasi'])->name('skpd.transaksi_kkpd.load_data_validasi');
            Route::post('draft_validasi', [TransaksiKKPDController::class, 'draftValidasi'])->name('skpd.transaksi_kkpd.draft_validasi');
            Route::post('data_validasi', [TransaksiKKPDController::class, 'dataValidasi'])->name('skpd.transaksi_kkpd.data_validasi');
            Route::get('tambah_verifikasi', [TransaksiKKPDController::class, 'createValidasi'])->name('skpd.transaksi_kkpd.create_validasi');
            Route::post('proses_validasi', [TransaksiKKPDController::class, 'prosesValidasi'])->name('skpd.transaksi_kkpd.proses_validasi');
            Route::post('batal_validasi', [TransaksiKKPDController::class, 'batalValidasi'])->name('skpd.transaksi_kkpd.batal_validasi');

            // TERIMA POTONGAN PAJAK KKPD
            Route::get('terima_potongan_kkpd', [TransaksiKKPDController::class, 'indexPotongan'])->name('skpd.transaksi_kkpd.index_potongan');
            Route::post('load_data_potongan', [TransaksiKKPDController::class, 'loadDataPotongan'])->name('skpd.transaksi_kkpd.load_data_potongan');
            Route::get('tambah_potongan', [TransaksiKKPDController::class, 'createPotongan'])->name('skpd.transaksi_kkpd.create_potongan');
            Route::get('edit_potongan/{no_bukti?}', [TransaksiKKPDController::class, 'editPotongan'])->where('no_bukti', '(.*)')->name('skpd.transaksi_kkpd.edit_potongan');
            Route::post('cari_kegiatan_potongan', [TransaksiKKPDController::class, 'cariKegiatanPotongan'])->name('skpd.transaksi_kkpd.cari_kegiatan_potongan');
            Route::post('simpan_potongan', [TransaksiKKPDController::class, 'simpanPotongan'])->name('skpd.transaksi_kkpd.simpan_potongan');
            Route::post('simpan_edit_potongan', [TransaksiKKPDController::class, 'simpanEditPotongan'])->name('skpd.transaksi_kkpd.simpan_edit_potongan');
            Route::post('hapus_potongan', [TransaksiKKPDController::class, 'hapusPotongan'])->name('skpd.transaksi_kkpd.hapus_potongan');
        });
        // Laporan Bendahara
        Route::group(['prefix' => 'laporan_bendahara'], function () {
            Route::get('', [LaporanBendaharaController::class, 'index'])->name('skpd.laporan_bendahara_keluar.index');
            Route::post('cari_skpd', [LaporanBendaharaController::class, 'cariSkpd'])->name('skpd.laporan_bendahara.skpd');
            Route::post('cari_bendahara', [LaporanBendaharaController::class, 'cariBendahara'])->name('skpd.laporan_bendahara.bendahara');
            Route::post('cari_pakpa', [LaporanBendaharaController::class, 'cariPaKpa'])->name('skpd.laporan_bendahara.pakpa');
            Route::post('cari_subkegiatan', [LaporanBendaharaController::class, 'cariSubkegiatan'])->name('skpd.laporan_bendahara.subkegiatan');
            Route::post('cari_akunbelanja', [LaporanBendaharaController::class, 'cariAkunBelanja'])->name('skpd.laporan_bendahara.akunbelanja');
            // Cetak BKU
            Route::get('cetak_bku', [LaporanBendaharaController::class, 'cetakbku'])->name('skpd.laporan_bendahara.cetak_bku');
            // Cetak SPJ Fungsional
            Route::get('cetak_spj_fungsional', [SpjFungsionalController::class, 'cetakSpjFungsional'])->name('skpd.laporan_bendahara.cetak_spj_fungsional');
            // BP Kas Bank
            Route::get('cetak_bp_kasbank', [BpKasBankController::class, 'cetakBpkasBank'])->name('skpd.laporan_bendahara.cetak_bp_kasbank');
            // BP Kas Tunai
            Route::get('cetak_bp_kastunai', [BpKasTunaiController::class, 'cetakBpkasTunai'])->name('skpd.laporan_bendahara.cetak_bp_kastunai');
            // BP Pajak
            Route::get('cetak_bp_pajak1', [BpPajakController::class, 'cetakBpPajak'])->name('skpd.laporan_bendahara.cetak_bp_pajak1');
            Route::get('cetak_bp_pajak2', [BpPajakController::class, 'cetakBpPajak2'])->name('skpd.laporan_bendahara.cetak_bp_pajak2');
            Route::get('cetak_bp_pajak3', [BpPajakController::class, 'cetakBpPajak3'])->name('skpd.laporan_bendahara.cetak_bp_pajak3');
            Route::get('cetak_bp_pajak4', [BpPajakController::class, 'cetakBpPajak4'])->name('skpd.laporan_bendahara.cetak_bp_pajak4');
            Route::get('cetak_bp_pajak5', [BpPajakController::class, 'cetakBpPajak5'])->name('skpd.laporan_bendahara.cetak_bp_pajak5');
            Route::post('cari_jenis', [BpPajakController::class, 'cariJenis'])->name('cetak_bppajak.cari_jenis');
            Route::post('cari_pasal', [BpPajakController::class, 'cariPasal'])->name('cetak_bppajak.cari_pasal');
            // BP Panjar
            Route::get('cetak_bp_panjar', [BpPanjarController::class, 'cetakBpPanjar'])->name('skpd.laporan_bendahara.cetak_bp_panjar');
            // Realisasi Fisik
            Route::get('cetak_realisasi_fisik', [RealisasiFisikController::class, 'cetakRealisasiFisik'])->name('skpd.laporan_bendahara.cetak_realisasi_fisik');
            // cetak_laporan_penutupan_kas_bulanan
            Route::get('cetak_laporan_penutupan_kas_bulanan', [LaporanPenutupanKasBulananController::class, 'cetakLaporanPenutupanKasBulanan'])->name('skpd.laporan_bendahara.cetak_laporan_penutupan_kas_bulanan');
            // cetak_dth
            Route::get('cetak_dth', [LaporanDthController::class, 'cetakLaporanDth'])->name('skpd.laporan_bendahara.cetak_dth');
            // cetak register pajak
            Route::get('cetak_reg_pajak', [LaporanRegPajakController::class, 'cetakRegPajak'])->name('skpd.laporan_bendahara.cetak_reg_pajak');
            Route::get('cetak_reg_pajakpl', [LaporanRegPajakController::class, 'cetakRegPajakPl'])->name('skpd.laporan_bendahara.cetak_reg_pajakpl');
            Route::get('cetak_reg_pajak_rekap', [LaporanRegPajakController::class, 'cetakRekapPajakPotongan'])->name('skpd.laporan_bendahara.cetak_reg_pajak_rekap');
            // cetak_register_cp
            Route::get('cetak_register_cp', [RegisterCpController::class, 'cetakRegisterCp'])->name('skpd.laporan_bendahara.cetak_register_cp');
            // BP Kas Bank
            Route::get('cetak_regsppspm', [RegisterSppSpmSp2dController::class, 'cetakregisterSppSpmSp2d'])->name('skpd.laporan_bendahara.cetak_regsppspm');
            // cetak buku pembantu sub rincian objek
            Route::get('cetak_sub_rincian_objek', [SubRincianObjekController::class, 'cetakSubRincianObjek77'])->name('skpd.laporan_bendahara.cetak_sub_rincian_objek');
            Route::get('cetak_sub_rincian_objek2', [SubRincianObjekController::class, 'cetakSubRincianObjekRekening'])->name('skpd.laporan_bendahara.cetak_sub_rincian_objek2');
            Route::get('cetak_sub_rincian_objek3', [SubRincianObjekController::class, 'cetakSubRincianObjekSubkegiatan'])->name('skpd.laporan_bendahara.cetak_sub_rincian_objek3');
            Route::get('cetak_sub_rincian_objek4', [SubRincianObjekController::class, 'cetakSubRincianObjekSkpd'])->name('skpd.laporan_bendahara.cetak_sub_rincian_objek4');
            Route::get('cetak_sub_rincian_objek5', [SubRincianObjekController::class, 'cetakSubRincianObjekPemakaian'])->name('skpd.laporan_bendahara.cetak_sub_rincian_objek5');
            // cetak Kartu Kendali Sub Kegiatan
            Route::get('cetak_kk_pengajuan', [KartuKendaliSubkegiatanController::class, 'cetakKkpengajuan'])->name('skpd.laporan_bendahara.cetak_kk_pengajuan');
            Route::get('cetak_kk_spj', [KartuKendaliSubkegiatanController::class, 'cetakKkSpj'])->name('skpd.laporan_bendahara.cetak_kk_spj');
        });
        // Jurnal Koreksi
        Route::group(['prefix' => 'jurnal_koreksi'], function () {
            // Koreksi Atas Kegiatan atau Rekening
            Route::group(['prefix' => 'koreksi_rekening'], function () {
                Route::get('', [JurnalKoreksiController::class, 'indexRekening'])->name('koreksi_rekening.index');
                Route::post('load_data_rekening', [JurnalKoreksiController::class, 'loadDataRekening'])->name('koreksi_rekening.load_data');
                Route::get('tambah', [JurnalKoreksiController::class, 'tambahRekening'])->name('koreksi_rekening.create');
                Route::post('no_sp2d', [JurnalKoreksiController::class, 'nomorSp2d'])->name('koreksi_rekening.no_sp2d');
                Route::post('rekening', [JurnalKoreksiController::class, 'rekening'])->name('koreksi_rekening.rekening');
                Route::post('sumber', [JurnalKoreksiController::class, 'sumber'])->name('koreksi_rekening.sumber');
                Route::post('rekening_koreksi', [JurnalKoreksiController::class, 'rekeningKoreksi'])->name('koreksi_rekening.rekening_koreksi');
                Route::post('sumber_koreksi', [JurnalKoreksiController::class, 'sumberKoreksi'])->name('koreksi_rekening.sumber_koreksi');
                Route::post('simpan_koreksi', [JurnalKoreksiController::class, 'simpanKoreksi'])->name('koreksi_rekening.simpan_koreksi');
                Route::get('edit_koreksi_rekening/{no_bukti?}', [JurnalKoreksiController::class, 'editRekening'])->where('no_bukti', '(.*)')->name('koreksi_rekening.edit');
                Route::post('simpan_edit_rekening', [JurnalKoreksiController::class, 'updateRekening'])->name('koreksi_rekening.update');
                Route::post('hapus_rekening', [JurnalKoreksiController::class, 'hapusRekening'])->name('koreksi_rekening.hapus');
            });
            // Koreksi Atas Nominal
            Route::group(['prefix' => 'koreksi_nominal'], function () {
                Route::get('', [JurnalKoreksiController::class, 'indexNominal'])->name('koreksi_nominal.index');
                Route::post('load_data_rekening', [JurnalKoreksiController::class, 'loadDataNominal'])->name('koreksi_nominal.load_data');
                Route::get('tambah', [JurnalKoreksiController::class, 'tambahNominal'])->name('koreksi_nominal.create');
                Route::post('simpan_koreksi', [JurnalKoreksiController::class, 'simpanNominal'])->name('koreksi_nominal.simpan_koreksi');
                Route::get('edit_koreksi_nominal/{no_bukti?}', [JurnalKoreksiController::class, 'editNominal'])->where('no_bukti', '(.*)')->name('koreksi_nominal.edit');
                Route::post('simpan_edit_rekening', [JurnalKoreksiController::class, 'updateNominal'])->name('koreksi_nominal.update');
                Route::post('hapus_rekening', [JurnalKoreksiController::class, 'hapusNominal'])->name('koreksi_nominal.hapus');
            });
        });
        // Pendapatan
        Route::group(['prefix' => 'pendapatan'], function () {
            // Penetapan Pendapatan
            Route::group(['prefix' => 'penetapan'], function () {
                Route::get('', [PenetapanController::class, 'indexPenetapanPendapatan'])->name('penetapan_pendapatan.index');
                Route::post('load_data', [PenetapanController::class, 'loadDataPenetapanPendapatan'])->name('penetapan_pendapatan.load_data');
                Route::get('tambah', [PenetapanController::class, 'tambahPenetapanPendapatan'])->name('penetapan_pendapatan.tambah');
                Route::post('simpan', [PenetapanController::class, 'simpanPenetapanPendapatan'])->name('penetapan_pendapatan.simpan');
                Route::get('edit/{no_tetap?}', [PenetapanController::class, 'editPenetapanPendapatan'])->where('no_tetap', '(.*)')->name('penetapan_pendapatan.edit');
                Route::post('simpan_edit', [PenetapanController::class, 'simpanEditPenetapanPendapatan'])->name('penetapan_pendapatan.simpan_edit');
                Route::post('hapus', [PenetapanController::class, 'hapusPenetapanPendapatan'])->name('penetapan_pendapatan.hapus');
            });
            // Penetapan Penerimaan
            Route::group(['prefix' => 'penerimaan'], function () {
                Route::get('', [PenetapanController::class, 'indexPenetapanPenerimaan'])->name('penetapan_penerimaan.index');
                Route::post('load_data', [PenetapanController::class, 'loadDataPenetapanPenerimaan'])->name('penetapan_penerimaan.load_data');
                Route::get('tambah', [PenetapanController::class, 'tambahPenetapanPenerimaan'])->name('penetapan_penerimaan.tambah');
                Route::post('simpan', [PenetapanController::class, 'simpanPenetapanPenerimaan'])->name('penetapan_penerimaan.simpan');
                Route::get('edit/{no_tetap?}', [PenetapanController::class, 'editPenetapanPenerimaan'])->where('no_tetap', '(.*)')->name('penetapan_penerimaan.edit');
                Route::post('simpan_edit', [PenetapanController::class, 'simpanEditPenetapanPenerimaan'])->name('penetapan_penerimaan.simpan_edit');
                Route::post('hapus', [PenetapanController::class, 'hapusPenetapanPenerimaan'])->name('penetapan_penerimaan.hapus');
            });
            // Penerimaan Tahun Lalu
            Route::group(['prefix' => 'penerimaan_tahun_lalu'], function () {
                Route::get('', [PenerimaanController::class, 'indexPenerimaanLalu'])->name('penerimaan_lalu.index');
                Route::post('load_data', [PenerimaanController::class, 'loadDataPenerimaanLalu'])->name('penerimaan_lalu.load_data');
                Route::get('tambah', [PenerimaanController::class, 'tambahPenerimaanLalu'])->name('penerimaan_lalu.tambah');
                Route::post('simpan', [PenerimaanController::class, 'simpanPenerimaanLalu'])->name('penerimaan_lalu.simpan');
                Route::get('edit/{no_terima?}', [PenerimaanController::class, 'editPenerimaanLalu'])->where('no_terima', '(.*)')->name('penerimaan_lalu.edit');
                Route::post('simpan_edit', [PenerimaanController::class, 'simpanEditPenerimaanLalu'])->name('penerimaan_lalu.simpan_edit');
                Route::post('hapus', [PenerimaanController::class, 'hapusPenerimaanLalu'])->name('penerimaan_lalu.hapus');
            });
            // Penerimaan Tahun Ini
            Route::group(['prefix' => 'penerimaan_tahun_ini'], function () {
                Route::get('', [PenerimaanController::class, 'indexPenerimaanIni'])->name('penerimaan_ini.index');
                Route::post('load_data', [PenerimaanController::class, 'loadDataPenerimaanIni'])->name('penerimaan_ini.load_data');
                Route::get('tambah', [PenerimaanController::class, 'tambahPenerimaanIni'])->name('penerimaan_ini.tambah');
                Route::post('simpan', [PenerimaanController::class, 'simpanPenerimaanIni'])->name('penerimaan_ini.simpan');
                Route::get('edit/{no_terima?}', [PenerimaanController::class, 'editPenerimaanIni'])->where('no_terima', '(.*)')->name('penerimaan_ini.edit');
                Route::post('simpan_edit', [PenerimaanController::class, 'simpanEditPenerimaanIni'])->name('penerimaan_ini.simpan_edit');
                Route::post('hapus', [PenerimaanController::class, 'hapusPenerimaanIni'])->name('penerimaan_ini.hapus');
            });
            // Penyetoran Atas Penerimaan Tahun Lalu
            Route::group(['prefix' => 'penyetoran_tahun_lalu'], function () {
                Route::get('', [PenyetoranController::class, 'indexPenyetoranLalu'])->name('penyetoran_lalu.index');
                Route::post('load_data', [PenyetoranController::class, 'loadDataPenyetoranLalu'])->name('penyetoran_lalu.load_data');
                Route::get('tambah', [PenyetoranController::class, 'tambahPenyetoranLalu'])->name('penyetoran_lalu.tambah');
                Route::post('simpan', [PenyetoranController::class, 'simpanPenyetoranLalu'])->name('penyetoran_lalu.simpan');
                Route::post('rekening', [PenyetoranController::class, 'rekeningPenyetoranLalu'])->name('penyetoran_lalu.rekening');
                Route::get('edit/{no_sts?}', [PenyetoranController::class, 'editPenyetoranLalu'])->where('no_sts', '(.*)')->name('penyetoran_lalu.edit');
                Route::post('simpan_edit', [PenyetoranController::class, 'simpanEditPenyetoranLalu'])->name('penyetoran_lalu.simpan_edit');
                Route::post('hapus', [PenyetoranController::class, 'hapusPenyetoranLalu'])->name('penyetoran_lalu.hapus');
                Route::get('cek', [PenyetoranController::class, 'cekPenyetoranLalu'])->name('penyetoran_lalu.cek');
                Route::post('validasi', [PenyetoranController::class, 'validasiPenyetoranLalu'])->name('penyetoran_lalu.validasi');
            });
            // Penyetoran Atas Penerimaan Tahun Ini
            Route::group(['prefix' => 'penyetoran_tahun_ini'], function () {
                Route::get('', [PenyetoranController::class, 'indexPenyetoranIni'])->name('penyetoran_ini.index');
                Route::post('load_data', [PenyetoranController::class, 'loadDataPenyetoranIni'])->name('penyetoran_ini.load_data');
                Route::get('tambah', [PenyetoranController::class, 'tambahPenyetoranIni'])->name('penyetoran_ini.tambah');
                Route::post('no_terima', [PenyetoranController::class, 'nomorPenyetoranIni'])->name('penyetoran_ini.no_terima');
                Route::post('simpan', [PenyetoranController::class, 'simpanPenyetoranIni'])->name('penyetoran_ini.simpan');
                Route::get('edit/{no_sts?}', [PenyetoranController::class, 'editPenyetoranIni'])->where('no_sts', '(.*)')->name('penyetoran_ini.edit');
                Route::post('simpan_edit', [PenyetoranController::class, 'simpanEditPenyetoranIni'])->name('penyetoran_ini.simpan_edit');
                Route::post('hapus', [PenyetoranController::class, 'hapusPenyetoranIni'])->name('penyetoran_ini.hapus');
                Route::get('cek', [PenyetoranController::class, 'cekPenyetoranIni'])->name('penyetoran_ini.cek');
                Route::post('validasi', [PenyetoranController::class, 'validasiPenyetoranIni'])->name('penyetoran_ini.validasi');
            });
        });
    });

    Route::group(['prefix' => 'pendapatan'], function () {
        // Penerimaan Lain PPKD
        Route::group(['prefix' => 'penerimaan_lain_ppkd'], function () {
            Route::get('', [PenerimaanController::class, 'indexPenerimaanPpkd'])->name('penerimaan_ppkd.index');
            Route::post('load_data', [PenerimaanController::class, 'loadDataPenerimaanPpkd'])->name('penerimaan_ppkd.load_data');
            Route::get('tambah', [PenerimaanController::class, 'tambahPenerimaanPpkd'])->name('penerimaan_ppkd.tambah');
            Route::post('simpan', [PenerimaanController::class, 'simpanPenerimaanPpkd'])->name('penerimaan_ppkd.simpan');
            Route::get('edit/{no_kas?}', [PenerimaanController::class, 'editPenerimaanPpkd'])->where('no_kas', '(.*)')->name('penerimaan_ppkd.edit');
            Route::post('simpan_edit', [PenerimaanController::class, 'simpanEditPenerimaanPpkd'])->name('penerimaan_ppkd.simpan_edit');
            Route::post('hapus', [PenerimaanController::class, 'hapusPenerimaanPpkd'])->name('penerimaan_ppkd.hapus');
        });
        // Penerimaan Kas
        Route::group(['prefix' => 'penerimaan_kas'], function () {
            Route::get('', [PenerimaanController::class, 'indexPenerimaanKas'])->name('penerimaan_kas.index');
            Route::post('load_data', [PenerimaanController::class, 'loadDataPenerimaanKas'])->name('penerimaan_kas.load_data');
            Route::get('tambah', [PenerimaanController::class, 'tambahPenerimaanKas'])->name('penerimaan_kas.tambah');
            Route::post('cari_no_bukti', [PenerimaanController::class, 'noBuktiPenerimaanKas'])->name('penerimaan_kas.no_bukti');
            Route::post('detail_sts', [PenerimaanController::class, 'detailPenerimaanKas'])->name('penerimaan_kas.detail_sts');
            Route::post('nm_sub_kegiatan', [PenerimaanController::class, 'kegiatanPenerimaanKas'])->name('penerimaan_kas.nm_sub_kegiatan');
            Route::post('kunci_kasda', [PenerimaanController::class, 'kunciPenerimaanKas'])->name('penerimaan_kas.kunci_kasda');
            Route::post('simpan', [PenerimaanController::class, 'simpanPenerimaanKas'])->name('penerimaan_kas.simpan');
            Route::get('edit/{no_kas?}/{kd_skpd?}', [PenerimaanController::class, 'editPenerimaanKas'])->name('penerimaan_kas.edit');
            Route::post('simpan_edit', [PenerimaanController::class, 'simpanEditPenerimaanKas'])->name('penerimaan_kas.simpan_edit');
            Route::post('hapus', [PenerimaanController::class, 'hapusPenerimaanKas'])->name('penerimaan_kas.hapus');
            Route::get('cetak', [PenerimaanController::class, 'cetakPenerimaanKas'])->name('penerimaan_kas.cetak');
        });
        // Koreksi Pendapatan
        Route::group(['prefix' => 'koreksi_pendapatan'], function () {
            Route::get('', [PenerimaanController::class, 'indexKoreksi'])->name('koreksi_pendapatan.index');
            Route::post('load_data', [PenerimaanController::class, 'loadDataKoreksi'])->name('koreksi_pendapatan.load_data');
            Route::get('tambah', [PenerimaanController::class, 'tambahKoreksi'])->name('koreksi_pendapatan.tambah');
            Route::post('jenis', [PenerimaanController::class, 'jenisKoreksi'])->name('koreksi_pendapatan.jenis');
            Route::post('simpan', [PenerimaanController::class, 'simpanKoreksi'])->name('koreksi_pendapatan.simpan');
            Route::get('edit/{no?}', [PenerimaanController::class, 'editKoreksi'])->name('koreksi_pendapatan.edit');
            Route::post('simpan_edit', [PenerimaanController::class, 'simpanEditKoreksi'])->name('koreksi_pendapatan.simpan_edit');
            Route::post('hapus', [PenerimaanController::class, 'hapusKoreksi'])->name('koreksi_pendapatan.hapus');
        });
        // Penerimaan Non Pendapatan
        Route::group(['prefix' => 'non_pendapatan'], function () {
            Route::get('', [PenerimaanController::class, 'indexPenerimaanNonPendapatan'])->name('non_pendapatan.index');
            Route::post('load_data', [PenerimaanController::class, 'loadDataPenerimaanNonPendapatan'])->name('non_pendapatan.load_data');
            Route::get('tambah', [PenerimaanController::class, 'tambahPenerimaanNonPendapatan'])->name('non_pendapatan.tambah');
            Route::post('jenis', [PenerimaanController::class, 'jenisPenerimaanNonPendapatan'])->name('non_pendapatan.jenis');
            Route::post('simpan', [PenerimaanController::class, 'simpanPenerimaanNonPendapatan'])->name('non_pendapatan.simpan');
            Route::get('edit/{nomor?}', [PenerimaanController::class, 'editPenerimaanNonPendapatan'])->name('non_pendapatan.edit');
            Route::post('simpan_edit', [PenerimaanController::class, 'simpanEditPenerimaanNonPendapatan'])->name('non_pendapatan.simpan_edit');
            Route::post('hapus', [PenerimaanController::class, 'hapusPenerimaanNonPendapatan'])->name('non_pendapatan.hapus');
        });
    });

    Route::group(['prefix' => 'laporan_bendahara_umum_daerah'], function () {
        Route::get('laporan_bendahara_umum', [BendaharaUmumDaerahController::class, 'index'])->name('laporan_bendahara_umum.index');
        // CETAK REALISASI PENDAPATAN
        Route::get('realisasi_pendapatan', [BendaharaUmumDaerahController::class, 'realisasiPendapatan'])->name('laporan_bendahara_umum.realisasi_pendapatan');
        // CETAK BUKU KAS PEMBANTU PENERIMAAN
        Route::get('buku_kas_pembantu_penerimaan', [BendaharaUmumDaerahController::class, 'pembantuPenerimaan'])->name('laporan_bendahara_umum.buku_kas_pembantu_penerimaan');
        // BKU (B IX)
        Route::group(['prefix' => 'buku_kas_penerimaan_pengeluaran'], function () {
            // CETAK BKU (B IX) TANPA TANGGAL
            Route::get('tanpa_tanggal', [BendaharaUmumDaerahController::class, 'bkuTanpaTanggal'])->name('laporan_bendahara_umum.bku_tanpa_tanggal');
            // CETAK BKU (B IX) DENGAN TANGGAL
            Route::get('dengan_tanggal', [BendaharaUmumDaerahController::class, 'bkuDenganTanggal'])->name('laporan_bendahara_umum.bku_dengan_tanggal');
            // CETAK BKU (B IX) DENGAN TANGGAL (TANPA BLUD DAN JASPEL)
            Route::get('tanpa_blud', [BendaharaUmumDaerahController::class, 'bkuTanpaBlud'])->name('laporan_bendahara_umum.bku_tanpa_blud');
            // CETAK BKU (B IX) RINCIAN (SEMENTARA HANYA PERTANGGAL)
            Route::get('rincian', [BendaharaUmumDaerahController::class, 'bkuRincian'])->name('laporan_bendahara_umum.bku_rincian');
        });
        Route::get('penerimaan_pajak_daerah', [BendaharaUmumDaerahController::class, 'pajakDaerah'])->name('laporan_bendahara_umum.penerimaan_pajak_daerah');
        Route::get('rekap_gaji', [BendaharaUmumDaerahController::class, 'rekapGaji'])->name('laporan_bendahara_umum.rekap_gaji');
        Route::get('buku_besar_kasda', [BendaharaUmumDaerahController::class, 'rekapBBKasda'])->name('laporan_bendahara_umum.buku_besar_kasda');
    });
});

Route::get('dashboard', [HomeController::class, 'index'])->name('home')->middleware(['auth']);
Route::get('ubah_skpd/{id?}', [HomeController::class, 'ubahSkpd'])->where('id', '(.*)')->name('ubah_skpd');
Route::post('ubah_skpd/simpan', [HomeController::class, 'simpanUbahSkpd'])->name('ubah_skpd.simpan');
Route::get('ubah_password/{id?}', [HomeController::class, 'ubahPassword'])->where('id', '(.*)')->name('ubah_password');
Route::post('ubah_password/simpan', [HomeController::class, 'simpanUbahPassword'])->name('ubah_password.simpan');
Route::get('setting', [SettingController::class, 'index'])->name('setting');
Route::get('coba', [HomeController::class, 'coba'])->name('coba');
Route::get('', [LoginController::class, 'index'])->name('login.index');
Route::post('login', [LoginController::class, 'authenticate'])->name('login')->middleware(['throttle:3,1']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('403', function () {
    return abort(401);
})->name('403');

Route::get('/{any}', function () {
    return abort(404);
})->where('any', '.*');
