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

Route::get('/admin/printers', function () {
    return view('admin.printers');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.printers');

Route::get('/admin/reprint-qr', function () {
    return view('admin.reprint-qr');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.reprint');

Route::get('/admin/void-member', function () {
    return view('admin.void-member');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.void');

Route::get('/admin/members', function () {
    return view('admin.members');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.members');

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

Route::get('/admin/reports/overall-result', function () {
    return view('admin.reports.overall-result');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.reports.overall-result');

Route::get('/admin/upload', function () {
    return view('admin.upload');
 })
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.upload');

Route::get('/admin/reports/registered-members', function () {
    return view('admin.reports.registered-members');
 })
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.reports.registered-members');

Route::get('/admin/reports/voided-registration', function () {
    return view('admin.reports.voided-registration');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.reports.voided-registration');

Route::get('/admin/reports/voided-votes', function () {
    return view('admin.reports.voided-votes');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.reports.voided-votes');
Route::get('/admin/reports/voter-list', function () {
    return view('admin.reports.voter-list');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.reports.voter-list');

Route::get('/admin/reports/total-votes', function () {
    return view('admin.reports.total-votes');
})
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.reports.total-votes');

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

Route::get('registration/reports', function () {
    return view('registration.report');
})
    ->middleware(['auth', 'verified', 'role:registration'])
    ->name('registration.report');

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

Route::get('voting/voting-module/successful-voters', function () {
    return view('voting.voted');
})
    ->middleware(['auth', 'verified', 'role:voting'])
    ->name('voting.voted');

Route::get('voting/scan-qr-code', function () {
    return view('voting.scan-qr');
})
    ->middleware(['auth', 'verified', 'role:voting'])
    ->name('voting.scan-qr');

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
