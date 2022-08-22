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
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/simakda_2023', function () {
    return view('welcome');
});

Route::get('coba', [PenerimaController::class, 'coba'])->name('penerima.coba');

// Auth::routes();
Route::group(['prefix' => 'simakda_2023'], function () {
    Route::group(['prefix' => 'kelola-akses'], function () {
        Route::resource('hak-akses', PermissionController::class);
        Route::resource('peran', RoleController::class);
        Route::resource('user', UserController::class);
    });
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
        Route::resource('penagihan', PenagihanController::class);
        Route::post('cek_status_ang_new', [PenagihanController::class, 'cekStatusAngNew'])->name('penagihan.cek_status_ang_new');
        Route::post('cek_status_ang', [PenagihanController::class, 'cekStatusAng'])->name('penagihan.cek_status_ang');
        Route::post('cari_rekening', [PenagihanController::class, 'cariRekening'])->name('penagihan.cari_rekening');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/coba', [HomeController::class, 'coba'])->name('coba');
    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
