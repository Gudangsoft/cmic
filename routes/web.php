<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\SdmController;
use App\Http\Controllers\PengalamanController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KontakController;

// Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [TentangController::class, 'index'])->name('tentang');
Route::get('/lingkup-layanan', [LayananController::class, 'index'])->name('layanan');
Route::get('/sdm', [SdmController::class, 'index'])->name('sdm');
Route::get('/pengalaman', [PengalamanController::class, 'index'])->name('pengalaman');
Route::get('/pengalaman/jenis/{jenisProyek}', [PengalamanController::class, 'showJenis'])->name('pengalaman.jenis');
Route::get('/pengalaman/{pengalamanProyek}', [PengalamanController::class, 'showItem'])->name('pengalaman.item');
Route::get('/pengalaman/proyek/{project}', [PengalamanController::class, 'show'])->name('pengalaman.show');
Route::get('/klien', [KlienController::class, 'index'])->name('klien');
Route::get('/klien/{client}', [KlienController::class, 'show'])->name('klien.show');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
Route::get('/kontak-kami', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak-kami', [KontakController::class, 'store'])->name('kontak.store')->middleware('throttle:5,1');

// Dynamic pages (must be before admin routes)
Route::get('/halaman/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

// Admin Auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'log.activity'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class)->except('show');
        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->except('show');
        Route::put('services-intro', [\App\Http\Controllers\Admin\ServiceController::class, 'updateIntro'])->name('services.updateIntro');
        Route::put('team/update-intro', [\App\Http\Controllers\Admin\TeamController::class, 'updateIntro'])->name('team.updateIntro');
        Route::resource('team', \App\Http\Controllers\Admin\TeamController::class)->except('show');
        Route::put('projects/update-intro', [\App\Http\Controllers\Admin\ProjectController::class, 'updateIntro'])->name('projects.updateIntro');
        Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class)->except('show');
        Route::get('projects/export', [\App\Http\Controllers\Admin\ProjectController::class, 'export'])->name('projects.export');
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class)->except('show');
        Route::post('clients/{client}/logo', [\App\Http\Controllers\Admin\ClientController::class, 'updateLogo'])->name('clients.updateLogo');
        Route::resource('client-types', \App\Http\Controllers\Admin\ClientTypeController::class)->except('show');
        Route::post('galleries/import-from-project', [\App\Http\Controllers\Admin\GalleryController::class, 'importFromProject'])->name('galleries.importFromProject');
        Route::post('galleries/album/rename', [\App\Http\Controllers\Admin\GalleryController::class, 'renameAlbum'])->name('galleries.albumRename');
        Route::delete('galleries/album', [\App\Http\Controllers\Admin\GalleryController::class, 'destroyAlbum'])->name('galleries.albumDestroy');
        Route::resource('galleries', \App\Http\Controllers\Admin\GalleryController::class)->except('show');
        Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class)->except('show');
        Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->except('show');
        Route::get('contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/export', [\App\Http\Controllers\Admin\ContactController::class, 'export'])->name('contacts.export');
        Route::get('contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::delete('contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
        Route::get('about', [\App\Http\Controllers\Admin\AboutController::class, 'index'])->name('about.index');
        Route::put('about', [\App\Http\Controllers\Admin\AboutController::class, 'update'])->name('about.update');
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::delete('activity-logs/clear', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('activity-logs.clear');
        Route::resource('legal-items', \App\Http\Controllers\Admin\LegalItemController::class)->except('show');
        Route::post('legal-items/{legalItem}/toggle', [\App\Http\Controllers\Admin\LegalItemController::class, 'toggleVisibility'])->name('legal-items.toggle');
        Route::resource('keunggulan', \App\Http\Controllers\Admin\KeunggulanController::class)->except('show');
        Route::post('keunggulan/{keunggulan}/toggle', [\App\Http\Controllers\Admin\KeunggulanController::class, 'toggleActive'])->name('keunggulan.toggle');
        // Account settings
        Route::get('account', [\App\Http\Controllers\Admin\AccountController::class, 'edit'])->name('account.edit');
        Route::put('account/profile', [\App\Http\Controllers\Admin\AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::put('account/password', [\App\Http\Controllers\Admin\AccountController::class, 'updatePassword'])->name('account.updatePassword');
        // Pengalaman Proyek (categories + items)
        Route::resource('jenis-proyek', \App\Http\Controllers\Admin\JenisProyekController::class)->except('show');
        Route::post('jenis-proyek/{jenisProyek}/toggle', [\App\Http\Controllers\Admin\JenisProyekController::class, 'toggleActive'])->name('jenis-proyek.toggle');
        // Static pengalaman-proyek routes BEFORE resource to avoid {pengalamanProyek} collision
        Route::put('pengalaman-proyek/update-intro', [\App\Http\Controllers\Admin\PengalamanProyekController::class, 'updateIntro'])->name('pengalaman-proyek.updateIntro');
        Route::post('pengalaman-proyek/quick-store', [\App\Http\Controllers\Admin\PengalamanProyekController::class, 'quickStore'])->name('pengalaman-proyek.quick-store');
        Route::resource('pengalaman-proyek', \App\Http\Controllers\Admin\PengalamanProyekController::class)->except('show');
        Route::post('pengalaman-proyek/{pengalamanProyek}/toggle', [\App\Http\Controllers\Admin\PengalamanProyekController::class, 'toggleActive'])->name('pengalaman-proyek.toggle');
        Route::delete('pengalaman-proyek/{pengalamanProyek}/quick-destroy', [\App\Http\Controllers\Admin\PengalamanProyekController::class, 'quickDestroy'])->name('pengalaman-proyek.quick-destroy');
    });
});
