<?php

use App\Http\Controllers\ProfileController;
use App\Models\RegisteredMember;
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
    if(auth()->user()->role_id == 1)
    {
        return redirect()->route('admin.dashboard');
    }elseif(auth()->user()->role_id == 2)
    {
        return redirect()->route('registration.dashboard');
    }elseif(auth()->user()->role_id == 3)
    {
        return redirect()->route('voting.dashboard');
    }

});

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.dashboard');

Route::get('/admin/users', function () {
    return view('admin.users');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.users');

Route::get('/admin/elections', function () {
    return view('admin.elections');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.elections');

Route::get('/admin/positions', function () {
    return view('admin.positions');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.positions');

Route::get('/admin/candidates', function () {
    return view('admin.candidates');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.candidates');

Route::get('/admin/live-result', function () {
    return view('admin.live-result');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.live-result');

Route::get('/admin/reports/official-result', function () {
       return view('admin.reports.official-result');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.reports.official-result');

Route::get('/admin/upload', function () {
    return view('admin.upload');
 })
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.upload');

Route::get('registration/dashboard', function () {
    return view('registration.dashboard');
})
    ->middleware(['auth', 'verified', 'role:registration'])
    ->name('registration.dashboard');

Route::get('registration/members', function () {
    return view('registration.members');
})
    ->middleware(['auth', 'verified', 'role:registration'])
    ->name('registration.members');

Route::get('voting/dashboard', function () {
    return view('voting.dashboard');
})
    ->middleware(['auth', 'verified', 'role:voting'])
    ->name('voting.dashboard');

Route::get('voting/voting-module', function () {
    return view('voting.voting-module');
})
    ->middleware(['auth', 'verified', 'role:voting'])
    ->name('voting.voting-module');

Route::get('/cast-vote/{record}', function ($record) {
    $voteRecord = RegisteredMember::findOrFail($record);

    return view('voting.cast-vote', ['record' => $voteRecord]);
})
    ->middleware(['auth', 'verified', 'role:voting'])
    ->name('voting.cast-vote');

Route::get('/view-vote-ballot/{record}', function ($record) {
    $voteBallotRecord = RegisteredMember::findOrFail($record);

    return view('voting.view-ballot', ['record' => $voteBallotRecord]);
})
    ->middleware(['auth', 'verified', 'role:voting'])
    ->name('voting.view-ballot');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
