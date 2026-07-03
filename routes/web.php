<?php

use App\Http\Controllers\ChatLogsController;
use App\Http\Controllers\CommandLogController;
use App\Http\Controllers\Modules\AnnouncementsController;
use App\Http\Controllers\Modules\CommandBlockerController;
use App\Http\Controllers\Modules\FilterController;
use App\Http\Controllers\Modules\HelpOPController;
use App\Http\Controllers\Modules\PermissionsController;
use App\Http\Controllers\Modules\Punishments\PunishmentsController;
use App\Http\Controllers\Modules\Punishments\PunishmentTemplatesController;
use App\Http\Controllers\Modules\ServersController;
use App\Http\Controllers\Modules\TagsController;
use App\Http\Controllers\Modules\TicketsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\MOTDController;
use App\Http\Controllers\PlayersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServerStatsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Webpanel\AccountsController;
use App\Http\Controllers\Webpanel\AuthenticationController;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthenticationController::class)->group(function () {
    Route::get('/login', 'loginView')->name('auth.login');
    /*Route::get('/logincreatetest', 'logincreatetestView')->name('auth.logincreatetest');
    Route::post('/logincreatetest', 'logincreatetest')->name('logincreatetest');*/
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    foreach ([
        'servers' => ServersController::class,
        'announcements' => AnnouncementsController::class,
        'punishments' => PunishmentsController::class,
        'punishment_templates' => PunishmentTemplatesController::class,
        'analytics' => AnalyticsController::class,
        'players' => PlayersController::class,
        'settings' => SettingsController::class,
        'languages' => LanguagesController::class,
        'profile' => ProfileController::class,
        'motd' => MOTDController::class,
        'filter' => FilterController::class,
        'commandblocker' => CommandBlockerController::class,
        'helpop' => HelpOPController::class,
        'accounts' => AccountsController::class,
        'chat' => ChatController::class,
        'tags' => TagsController::class,
        'chatlogs' => ChatLogsController::class,
        'commandlog' => CommandLogController::class,
        'serverstats' => ServerStatsController::class,
    ] as $uri => $controller) {
        Route::resource($uri, $controller);
    }

    Route::prefix('permissions')->controller(PermissionsController::class)->group(function () {
        Route::get('/', 'index')->name('permissions');
        Route::prefix('group/{group}')->whereNumber('group')->group(function () {
            Route::get('permissions', 'groupPermissions')->name('permissions.group.permissions');
            Route::get('prefixes', 'groupPrefixes')->name('permissions.group.prefixes');
            Route::get('suffixes', 'groupSuffixes')->name('permissions.group.suffixes');
            Route::get('parents', 'groupParents')->name('permissions.group.parents');
            Route::get('members', 'groupMembers')->name('permissions.group.members');
        });
        Route::prefix('player/{player}')->whereUuid('player')->group(function () {
            Route::get('permissions', 'playerPermissions')->name('permissions.player.permissions');
            Route::get('groups', 'playerGroups')->name('permissions.player.groups');
        });
    });

    Route::prefix('tickets')->controller(TicketsController::class)->group(function () {
        Route::get('/', 'index')->name('tickets');
        Route::get('/{ticket}', 'ticket')->whereNumber('ticket')->name('tickets.ticket');
    });
});
