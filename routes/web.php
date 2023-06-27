<?php

use App\Http\Controllers\ProfileController;
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
    return redirect()->route('login');
});


Route::get('/dashboard', function () {
    return view('admin.dashboard');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('dashboard');

Route::get('/users', function () {
    return view('admin.users');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('users');

Route::get('/elections', function () {
    return view('admin.elections');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('elections');

Route::get('/positions', function () {
    return view('admin.positions');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('positions');

Route::get('/candidates', function () {
    return view('admin.candidates');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('candidates');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
