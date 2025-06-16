<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Admin\AdminController;
use Carbon\Language;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
});

Route::post('/currency/switch', [CurrencyController::class, 'switch'])->name('currency.switch');

Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register')->middleware('guest');
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password reset routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('guest');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    try {
        $user = \App\Models\User::find($id);
        
        if (!$user) {
            Log::error("User not found for verification: " . $id);
            return redirect()->route('show.login')->withErrors(['email' => 'Lietotājs nav atrasts.']);
        }
        
        // Verify the hash
        $expectedHash = sha1($user->getEmailForVerification());
        if (!hash_equals($expectedHash, $hash)) {
            Log::error("Invalid hash for user: " . $user->email);
            return redirect()->route('show.login')->withErrors(['email' => 'Nederīga apstiprināšanas saite.']);
        }
        
        if ($user->hasVerifiedEmail()) {
            Log::info("Email already verified for user: " . $user->email);
            return redirect()->route('show.login')->with('success', 'E-pasts jau ir apstiprināts!');
        }
        
        // Mark the email as verified
        $user->email_verified_at = now();
        $user->save();
        
        Log::info("Email verification successful for user: " . $user->email);
        
        return redirect()->route('show.login')->with('success', 'E-pasts veiksmīgi apstiprināts! Tagad varat ienākt sistēmā.');
    } catch (Exception $e) {
        Log::error("Email verification failed: " . $e->getMessage());
        return redirect()->route('show.login')->withErrors(['email' => 'E-pasta apstiprināšana neizdevās. Lūdzu, mēģiniet vēlreiz.']);
    }
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Apstiprinājuma e-pasts nosūtīts!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete')->middleware('auth');

Route::get('/cars', [CarsController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarsController::class, 'show'])->name('cars.show');
Route::post('/cars', [CarsController::class, 'store'])->name('cars.store');
Route::get('/cars/{car}/edit', [CarsController::class, 'edit'])->name('cars.edit');
Route::put('/cars/{car}', [CarsController::class, 'update'])->name('cars.update');
Route::delete('/cars/{car}', [CarsController::class, 'destroy'])->name('cars.destroy');
Route::post('/cars/{car}/report', [CarsController::class, 'report'])->name('cars.report');

Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update.avatar');

// Favorites routes
Route::middleware('auth')->group(function () {
    Route::get('/cars/{car}/favorite', [App\Http\Controllers\FavoritesController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [App\Http\Controllers\FavoritesController::class, 'index'])->name('favorites.index');
});

// Ratings routes
Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/rate', [App\Http\Controllers\RatingsController::class, 'store'])->name('ratings.store');
    Route::get('/users/{user}/ratings', [App\Http\Controllers\RatingsController::class, 'show'])->name('ratings.show');
    Route::delete('/ratings/{rating}', [App\Http\Controllers\RatingsController::class, 'destroy'])->name('ratings.destroy');
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/make-admin', [AdminController::class, 'makeAdmin'])->name('users.make-admin');
    Route::post('/users/{user}/remove-admin', [AdminController::class, 'removeAdmin'])->name('users.remove-admin');
    
    // Listings routes
    Route::get('/listings', [AdminController::class, 'listings'])->name('listings.index');
    Route::get('/listings/pending', [AdminController::class, 'pendingListings'])->name('listings.pending');
    Route::post('/listings/{car}/approve', [AdminController::class, 'approveListing'])->name('listings.approve');
    Route::delete('/listings/{car}', [AdminController::class, 'destroyListing'])->name('listings.destroy');
    Route::get('/listings/reported', [AdminController::class, 'reportedListings'])->name('listings.reported');
    Route::post('/listings/{car}/ignore-report', [AdminController::class, 'ignoreListing'])->name('listings.ignore');

    // Notifications
    Route::post('/notifications/mark-all-as-read', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.markAllAsRead');
});