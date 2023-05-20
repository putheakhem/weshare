<?php
use App\Http\Controllers\DeleteTemporaryFileController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorePostController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
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

// ------ User Hompage--------------
Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');
Route::get('/aboutus', function () {
    return Inertia::render('AboutUs');
})->name('aboutus');

// -------User dashboard----------------
Route::get('/dashboard', function(){
    return inertia::render('Dashboard');
})->name('dashboard');
Route::get('/dashboard/doc',DocController::class)->name('document');
Route::get('/dashboard/savedoc',DocController::class)->name('save');
Route::get('/dashboard/doc/file',[DocController::class,'showfile']);
// -----------------------Uppload File--------------

Route::post('/dashboard/upload',StorePostController::class);
Route::post('/upload', UploadFileController::class);
Route::delete('/revert',DeleteTemporaryFileController::class);
Route::get('/dashboard/upload',PostController::class)->name('upload');

// ------------------Admin Dashboard-------------------------
Route::middleware('auth')->group(function () {
    Route::get('/admin/manage_users', [UserController::class, 'index'])->name('manage_users');
    Route::patch('/admin/manage_users/{user}', [UserController::class, 'update'])->name('manage_users.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/manage_majors', [MajorController::class, 'index'])->name('manage_majors');
    Route::post('/admin/manage_majors', [MajorController::class, 'store'])->name('manage_majors.create');
    Route::patch('/admin/manage_majors/{major}', [MajorController::class, 'update'])->name('manage_majors.update');
    Route::delete('/admin/manage_majors/{major}', [MajorController::class, 'destroy'])->name('manage_majors.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
