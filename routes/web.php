<?php
use App\Http\Controllers\DeleteTemporaryFileController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StorePostController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserController;
use App\Models\Majors;
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
    $major = Majors::all();
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'majors' => $major
    ]);
})->name('home');

Route::get('/aboutus', function () {
    return Inertia::render('AboutUs');
})->name('aboutus');

// add to favorites

Route::post('/favorite', [FavoritesController::class, 'addToFavorites']);
Route::post('/unfavorite', [FavoritesController::class, 'removeFromFavorites']);


// show favorites
Route::get('/fav',function(){
    return inertia::render('Favorite');
})->name('fav');

// Searching

Route::get('/department/{id}',[SearchController::class,"departmentfilter"])->name('major');
Route::get('/search',SearchController::class);


// -------User dashboard----------------


Route::get('/dashboard/doc',DocController::class)->name('document');
Route::get('/dashboard/savedoc',DocController::class)->name('save');
Route::get('/dashboard/doc/file',[DocController::class,'showfile']);

// -----------------------Uppload File--------------

Route::post('/upload',StorePostController::class);
Route::post('/uploadfile', UploadFileController::class);
Route::delete('/revert',DeleteTemporaryFileController::class);
Route::get('/upload',PostController::class)->name('upload');

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
    Route::get('/admin/manage_files', [FileController::class, 'index'])->name('manage_files');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/manage_types', [TypeController::class, 'index'])->name('manage_types');
    Route::post('/admin/manage_types', [TypeController::class, 'store'])->name('manage_types.create');
    Route::patch('/admin/manage_types/{type}', [TypeController::class, 'update'])->name('manage_types.update');
    Route::delete('/admin/manage_types/{type}', [TypeController::class, 'destroy'])->name('manage_types.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
