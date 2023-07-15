<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PunishmentsController;
use App\Http\Controllers\PunishmentTemplatesController;
use App\Http\Controllers\AnalyticsController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::controller(AuthenticationController::class)->group(function() {
    Route::get('/login', 'loginView')->name('auth.login');
    /*Route::get('/logincreatetest', 'logincreatetestView')->name('auth.logincreatetest');
    Route::post('/logincreatetest', 'logincreatetest')->name('logincreatetest');*/
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::resource('servers', ServersController::class);
Route::resource('announcements', AnnouncementsController::class);
Route::resource('punishments', PunishmentsController::class);
Route::resource('punishment_templates', PunishmentTemplatesController::class);
Route::resource('analytics', AnalyticsController::class);
