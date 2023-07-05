<?php

use App\Http\Controllers\Api\PasienController;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RuangController;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/roles', [RoleController::class, 'index'])->name('api.role.index');
Route::get('/ruangan', [RuangController::class, 'index'])->name('api.ruang.index');
Route::get('/petugas', [PetugasController::class, 'index'])->name('api.petugas.index');
Route::get('/pasien', [PasienController::class, 'index'])->name('api.pasien.index');
