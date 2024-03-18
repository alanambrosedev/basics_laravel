<?php

use App\Events\UserRegistered;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Jobs\SendMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('user-data', function () {
    return auth()->user()->name;
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/posts/trash', [PostController::class, 'trashed'])->name('posts.trashed');
    Route::get('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
    Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])->name('posts.force_delete');

    Route::resource('posts', PostController::class);
});

Route::get('send-mail', function () {
    SendMail::dispatch();
    dd('Mail sent!');
});

Route::get('user-register', function () {
    $email = 'user@gmail.com';
    event(new UserRegistered($email));
    dd('Send Mail');
});

//en ,hi
Route::get('greeting/{locale}', function ($locale) {
    App::setLocale($locale);

    return view('greeting');
})->name('greeting');
