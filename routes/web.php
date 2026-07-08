<?php

use App\Http\Controllers\Admin\InstallationActionController;
use App\Http\Controllers\Admin\InstallationPageController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\ForumController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/install')
    ->name('comco.install.')
    ->group(function (): void {
        Route::get('migrate', [InstallationActionController::class, 'migrate'])->name('migrate');
        Route::get('storage-link', [InstallationActionController::class, 'storageLink'])->name('storage-link');
        Route::get('optimize', [InstallationActionController::class, 'optimize'])->name('optimize');
        Route::get('seed-posts', [InstallationActionController::class, 'seedPosts'])->name('seed-posts');
        Route::get('seed-content', [InstallationActionController::class, 'seedContent'])->name('seed-content');
        Route::get('run-all', [InstallationActionController::class, 'runAll'])->name('run-all');
        Route::get('launch', [InstallationActionController::class, 'launch'])->name('launch');
        Route::post('save-configuration', [InstallationPageController::class, 'saveConfiguration'])->name('save-configuration');
        Route::post('create-super-admin', [InstallationPageController::class, 'createSuperAdmin'])->name('create-super-admin');
    });

Route::get('admin/site-installation', [InstallationPageController::class, 'show'])->name('comco.installation.show');

Route::prefix('public/admin')->group(function (): void {
    Route::get('site-installation', [InstallationPageController::class, 'show']);
    Route::prefix('install')->group(function (): void {
        Route::get('migrate', [InstallationActionController::class, 'migrate']);
        Route::get('storage-link', [InstallationActionController::class, 'storageLink']);
        Route::get('optimize', [InstallationActionController::class, 'optimize']);
        Route::get('seed-posts', [InstallationActionController::class, 'seedPosts']);
        Route::get('seed-content', [InstallationActionController::class, 'seedContent']);
        Route::get('run-all', [InstallationActionController::class, 'runAll']);
        Route::get('launch', [InstallationActionController::class, 'launch']);
        Route::post('save-configuration', [InstallationPageController::class, 'saveConfiguration']);
        Route::post('create-super-admin', [InstallationPageController::class, 'createSuperAdmin']);
    });
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{slug}', [ForumController::class, 'show'])->name('forum.show');
Route::get('/actualites/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::prefix('{section}')
    ->whereIn('section', array_keys(navigationSections()))
    ->name('sections.')
    ->group(function (): void {
        Route::get('{slug}', [PageController::class, 'showBySection'])->name('show');
    });
