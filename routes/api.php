<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function () {
        return auth()->user();
    })->name('profile');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('user', UserController::class);

    Route::post('/be-farmer', [UserController::class, 'beFarmer']);
    Route::post('/email/resend',[EmailVerificationController::class, 'resend'])->name('verification.resend');
});

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class,'verify'])->middleware('signed')->name('verification.verify');

Route::apiResource('post', PostController::class);

Route::prefix('post/{post}')->group(function () {
    Route::apiResource('comment', CommentController::class)->except('show');
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found.'
    ], 404);
});
