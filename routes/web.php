<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\Roadmap\DestroyRoadmapItemController;
use App\Http\Controllers\Admin\Roadmap\DestroyRoadmapPhaseController;
use App\Http\Controllers\Admin\Roadmap\IndexRoadmapController;
use App\Http\Controllers\Admin\Roadmap\MoveRoadmapItemController;
use App\Http\Controllers\Admin\Roadmap\MoveRoadmapPhaseController;
use App\Http\Controllers\Admin\Roadmap\StoreRoadmapItemController;
use App\Http\Controllers\Admin\Roadmap\StoreRoadmapPhaseController;
use App\Http\Controllers\Admin\Roadmap\UpdateRoadmapItemController;
use App\Http\Controllers\Admin\Roadmap\UpdateRoadmapPhaseController;
use App\Http\Controllers\Admin\Users\IndexUserController as AdminIndexUserController;
use App\Http\Controllers\Admin\Users\UpdateUserRolesController;
use App\Http\Controllers\Auth\GoogleCallbackController;
use App\Http\Controllers\Auth\GoogleRedirectController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Categories\DestroyCategoryController;
use App\Http\Controllers\Categories\IndexCategoryController;
use App\Http\Controllers\Categories\StoreCategoryController;
use App\Http\Controllers\Categories\UpdateCategoryController;
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
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\Tags\DestroyTagController;
use App\Http\Controllers\Tags\IndexTagController;
use App\Http\Controllers\Tags\StoreTagController;
use App\Http\Controllers\Tags\UpdateTagController;
use App\Http\Middleware\PreventRoadmapAdministrationInProduction;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::get('/roadmap', RoadmapController::class)->name('roadmap');

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

    Route::prefix('admin')->name('admin.')->middleware('can:access-admin')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('/users', AdminIndexUserController::class)->name('users.index');
        Route::patch('/users/{user}/roles', UpdateUserRolesController::class)->name('users.roles.update');

        Route::middleware(PreventRoadmapAdministrationInProduction::class)->group(function () {
            Route::get('/roadmap', IndexRoadmapController::class)->name('roadmap.index');
            Route::post('/roadmap/phases', StoreRoadmapPhaseController::class)->name('roadmap.phases.store');
            Route::patch('/roadmap/phases/{roadmapPhase}', UpdateRoadmapPhaseController::class)->whereNumber('roadmapPhase')->name('roadmap.phases.update');
            Route::delete('/roadmap/phases/{roadmapPhase}', DestroyRoadmapPhaseController::class)->whereNumber('roadmapPhase')->name('roadmap.phases.destroy');
            Route::post('/roadmap/phases/{roadmapPhase}/move', MoveRoadmapPhaseController::class)->whereNumber('roadmapPhase')->name('roadmap.phases.move');
            Route::post('/roadmap/phases/{roadmapPhase}/items', StoreRoadmapItemController::class)->whereNumber('roadmapPhase')->name('roadmap.items.store');
            Route::patch('/roadmap/items/{roadmapItem}', UpdateRoadmapItemController::class)->whereNumber('roadmapItem')->name('roadmap.items.update');
            Route::delete('/roadmap/items/{roadmapItem}', DestroyRoadmapItemController::class)->whereNumber('roadmapItem')->name('roadmap.items.destroy');
            Route::post('/roadmap/items/{roadmapItem}/move', MoveRoadmapItemController::class)->whereNumber('roadmapItem')->name('roadmap.items.move');
        });
    });

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

    Route::get('/categories', IndexCategoryController::class)->name('categories.index');
    Route::post('/categories', StoreCategoryController::class)->name('categories.store');
    Route::match(['put', 'patch'], '/categories/{category}', UpdateCategoryController::class)->name('categories.update');
    Route::delete('/categories/{category}', DestroyCategoryController::class)->name('categories.destroy');

    Route::get('/tags', IndexTagController::class)->name('tags.index');
    Route::post('/tags', StoreTagController::class)->name('tags.store');
    Route::match(['put', 'patch'], '/tags/{tag}', UpdateTagController::class)->name('tags.update');
    Route::delete('/tags/{tag}', DestroyTagController::class)->name('tags.destroy');
});
