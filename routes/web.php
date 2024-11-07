<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UmumC;
use App\Http\Controllers\AdminC;

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

// Route::get('/', function () {
//     return view('home');
// });

// umum
Route::get('/', [UmumC::class, 'Home']);
Route::post('submit-peserta', [UmumC::class, 'SubmitPeserta']);
Route::get('lacak', [UmumC::class, 'Lacak']);


// admin
Route::get('admin/login', [AdminC::class, 'Login']);
Route::post('admin/login', [AdminC::class, 'LoginSubmit']);
Route::group(['prefix' => 'admin', 'middleware' => 'adminMid'], function () {

    Route::get('logout', [AdminC::class, 'Logout']);
    Route::get('home', [AdminC::class, 'Home']);
    Route::post('home/filter', [AdminC::class, 'HomeFilter']);
    Route::get('profil', [AdminC::class, 'Profil']);
    Route::post('profil/update', [AdminC::class, 'ProfilUpdate']);
    Route::post('profil/password/update', [AdminC::class, 'ProfilUpdatePassword']);

    // tiket : cari, filter, unduh file, detail tiket, update progres tiket, load more
    Route::get('tiket', [AdminC::class, 'Tiket']);
    // API KELOLA TIKET
    Route::get('api/get-tikets', [AdminC::class, 'GetTikets']);
    Route::get('api/more-tikets/{skip}', [AdminC::class, 'MoreTikets']);
    // Route::post('api/filter-tikets/{skip?}', [AdminC::class,'FilterTikets']);
    Route::get('api/files-tiket/{id}', [AdminC::class, 'FilesTiket']);
    Route::get('api/detail-tiket/{id}', [AdminC::class, 'DetailTiket']);
    Route::post('api/verifikasi-tiket', [AdminC::class, 'VerifikasiTiket']);
    Route::get('api/riwayat-tiket/{id}', [AdminC::class, 'RiwayatTiket']);
    Route::get('api/hapus-tiket/{id}', [AdminC::class, 'HapusTiket']);
});
