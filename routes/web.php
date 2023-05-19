<?php
use App\Http\Controllers\DeleteTemporaryFileController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorePostController;
use App\Http\Controllers\UploadFileController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use function Termwind\render;

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

// ------ User Hompage---
Route::get('/home',function(){
    return Inertia::render('Home');
})->name('home');


Route::get('/dashboard', function(){
    return inertia::render('dashboard');
});
Route::get('/dashboard/doc',DocController::class);
Route::get('/dashboard/doc/file',[DocController::class,'showfile']);
// -----------------------Uppload File--------------

Route::post('/',StorePostController::class);
Route::post('/upload', UploadFileController::class);
Route::delete('/revert',DeleteTemporaryFileController::class);
Route::get('/',PostController::class)->name('post');

// ------------------View the file-------------------------
// Admin
Route::get('/admin/manage_users', function () {
    return Inertia::render('Admin/ManageUsers/Manage_Users');
})->middleware(['auth', 'verified'])->name('manage_users');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
