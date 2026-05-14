<?php

use App\Http\Controllers\Auth\GoogleCallbackController;
use App\Http\Controllers\Auth\GoogleRedirectController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Documents\ArchiveDocumentController;
use App\Http\Controllers\Documents\CreateDocumentController;
use App\Http\Controllers\Documents\DestroyDocumentController;
use App\Http\Controllers\Documents\DownloadDocumentController;
use App\Http\Controllers\Documents\EditDocumentController;
use App\Http\Controllers\Documents\IndexDocumentController;
use App\Http\Controllers\Documents\PreviewDocumentController;
use App\Http\Controllers\Documents\RestoreDocumentController;
use App\Http\Controllers\Documents\ShowDocumentController;
use App\Http\Controllers\Documents\StoreDocumentController;
use App\Http\Controllers\Documents\UpdateDocumentController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', GoogleRedirectController::class)->name('auth.google.redirect');
    Route::get('/auth/google/callback', GoogleCallbackController::class)->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/documents', IndexDocumentController::class)->name('documents.index');
    Route::get('/documents/create', CreateDocumentController::class)->name('documents.create');
    Route::post('/documents', StoreDocumentController::class)->name('documents.store');
    Route::get('/documents/{document}', ShowDocumentController::class)->name('documents.show');
    Route::get('/documents/{document}/edit', EditDocumentController::class)->name('documents.edit');
    Route::match(['put', 'patch'], '/documents/{document}', UpdateDocumentController::class)->name('documents.update');
    Route::delete('/documents/{document}', DestroyDocumentController::class)->name('documents.destroy');
    Route::post('/documents/{document}/archive', ArchiveDocumentController::class)->name('documents.archive');
    Route::post('/documents/{document}/restore', RestoreDocumentController::class)->name('documents.restore');
    Route::get('/documents/{document}/preview', PreviewDocumentController::class)->name('documents.preview');
    Route::get('/documents/{document}/download', DownloadDocumentController::class)->name('documents.download');
});
