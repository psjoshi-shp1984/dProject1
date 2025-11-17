<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\StaticPageController;
use App\Http\Controllers\Backend\CKEditorController;
use App\Http\Controllers\Backend\ContactUsController;
 use App\Http\Controllers\Backend\FaqController;



Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login']);
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Route::middleware('auth')->group(function () {
//    // Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  
// });
Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')
->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/sliders', SliderController::class)->only(['index', 'create', 'store', 'edit', 'update','destroy']);
    Route::get('sliders/check-sort-order', [SliderController::class, 'checkSortOrder'])
    ->name('sliders.checkSortOrder');
    Route::get('/sliders/restore/{id}', [SliderController::class, 'restore'])->name('sliders.restore');
    
    Route::delete('/sliders/force-delete/{id}', [SliderController::class, 'forceDelete'])->name('sliders.forceDelete');  
    Route::resource('static_pages', StaticPageController::class)->only(['index', 'create', 'store', 'edit', 'update','destroy']);
    Route::get('static_pages/check-slug', [StaticPageController::class, 'checkSlug'])->name('static_pages.checkSlug');
    
    Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');
    Route::get('/ckeditor/browse', [CKEditorController::class, 'browse'])->name('ckeditor.browse');

    Route::get('contact-us', [ContactUsController::class, 'index'])->name('contact_us.index');

    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/faq/create', [FaqController::class, 'create'])->name('faq.create');
    Route::post('/faq/store', [FaqController::class, 'store'])->name('faq.store');
    Route::get('/faq/edit/{id}', [FaqController::class, 'edit'])->name('faq.edit');
    Route::put('/faq/update/{id}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('/faq/delete/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');


});




require __DIR__.'/auth.php';
