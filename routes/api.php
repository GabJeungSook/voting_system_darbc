<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiMemberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('member-details/{id}', [ApiMemberController::class ,'getMemberDetails'])->name('api.getMemberDetails');
Route::get('member-details-darbc-id/{darbc_id}', [ApiMemberController::class ,'getMemberDetailsDarbcId'])->name('api.getMemberDetailsDarbcId');
