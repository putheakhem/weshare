<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DeleteTemporaryFileController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Files\FavouriteItemsController;
use App\Http\Controllers\Files\HomePageController;
use App\Http\Controllers\Files\MyDocumentController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StorePostController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserController;
use App\Models\Items;
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

// Home Page Controller
Route::group([], function () {
    Route::get('/', [HomePageController::class, 'index'])->name('home');
    Route::get('/file_detail/{file}', [HomePageController::class, 'file_detail'])->name('file_detail');
    Route::get('/all_files', [HomePageController::class, 'files'])->name('all_files');
});
Route::get('/test',function(){
    $items = Items::all();
    return dd($items);
});
// Favorites

Route::group([], function () {
    Route::get('/favourite_items',[FavouriteItemsController::class, 'index'])->name('favourite_items');
    Route::post('/all_files/{itemId}', [FavouriteItemsController::class, 'toggleFavorite'])->name('items.favorite');
    Route::post('/favourite_items/{itemId}', [FavouriteItemsController::class, 'destroy'])->name('favorites.delete');
});

// My Document

Route::middleware('auth')->group(function () {
    Route::get('/my_documents', [MyDocumentController::class, 'index'])->name('my_documents');
    Route::post('/my_documents/{file}', [MyDocumentController::class, 'update'])->name('my_documents.update');
    Route::delete('/my_documents/{file}', [MyDocumentController::class, 'destroy'])->name('my_documents.delete');

});


// Searching

Route::get('/department/{id}',[SearchController::class,"departmentfilter"])->name('major');
Route::get('/search',SearchController::class);


// -----------------------Uppload File--------------

Route::post('/uploadfile',StorePostController::class);
Route::post('/upload', UploadFileController::class);
Route::delete('/revert',DeleteTemporaryFileController::class);
Route::get('/uploadfile',PostController::class)->name('upload');

// ------------------Admin Dashboard-------------------------
Route::group([], function () {
    // Users Management
    Route::middleware('auth')->group(function () {
        Route::get('/admin/manage_users', [UserController::class, 'index'])->name('manage_users');
        Route::patch('/admin/manage_users/{user}', [UserController::class, 'update'])->name('manage_users.update');
    });
    //Feedbacks Management
    Route::middleware('auth')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::patch('/admin/dashboard/{feedback}', [DashboardController::class, 'update'])->name('feedbacks.update');
        Route::delete('/admin/dashboard/{feedback}', [DashboardController::class, 'destroy'])->name('feedbacks.delete');
    });
    //Majors Management
    Route::middleware('auth')->group(function () {
        Route::get('/admin/manage_majors', [MajorController::class, 'index'])->name('manage_majors');
        Route::post('/admin/manage_majors', [MajorController::class, 'store'])->name('manage_majors.create');
        Route::patch('/admin/manage_majors/{major}', [MajorController::class, 'update'])->name('manage_majors.update');
        Route::delete('/admin/manage_majors/{major}', [MajorController::class, 'destroy'])->name('manage_majors.delete');
    });
    //Files Management
    Route::middleware('auth')->group(function () {
        Route::get('/admin/manage_files', [FileController::class, 'index'])->name('manage_files');
        Route::post('/admin/manage_files', [FileController::class, 'upload'])->name('manage_files.upload');
        Route::post('/admin/manage_files/{file}', [FileController::class, 'update'])->name('manage_files.update');
        Route::delete('/admin/manage_files/{file}', [FileController::class, 'destroy'])->name('manage_files.delete');
    });
    //Types Management
    Route::middleware('auth')->group(function () {
        Route::get('/admin/manage_types', [TypeController::class, 'index'])->name('manage_types');
        Route::post('/admin/manage_types', [TypeController::class, 'store'])->name('manage_types.create');
        Route::patch('/admin/manage_types/{type}', [TypeController::class, 'update'])->name('manage_types.update');
        Route::delete('/admin/manage_types/{type}', [TypeController::class, 'destroy'])->name('manage_types.delete');
    });
});

// Profile

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

