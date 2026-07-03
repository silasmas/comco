<?php

use App\Http\Controllers\Admin\InstallationActionController;
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
    Route::get('run-all', [InstallationActionController::class, 'runAll'])->name('run-all');
    Route::get('launch', [InstallationActionController::class, 'launch'])->name('launch');
  });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/contact', 'public.pages.contact')->name('contact');
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{slug}', [ForumController::class, 'show'])->name('forum.show');
Route::get('/actualites/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::prefix('{section}')
  ->whereIn('section', array_keys(config('navigation.sections')))
  ->name('sections.')
  ->group(function (): void {
    Route::get('{slug}', [PageController::class, 'showBySection'])->name('show');
  });
