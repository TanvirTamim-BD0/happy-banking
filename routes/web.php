<?php

use Backend\PaymentTypeController;
use Illuminate\Support\Facades\Route;
use Backend\UserRole\UserController;
use Backend\UserRole\RoleController;
use Backend\BlogCategoryController;
use Backend\BlogController;
use Backend\UserProfessionController;
use Backend\ContactController;
use Backend\PushNotificationController;
use Backend\MobileWalletController;
use Backend\BankController;
use Backend\TransactionCategoryController;
use Backend\ActiveSessionController;
use Illuminate\Support\Facades\Auth;
use Backend\DocumentationCategoryController;
use Backend\DocumentationController;
use Backend\FrontendNoteController;





Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [App\Http\Controllers\HomePageController::class, 'index'])->name('welcome');
Route::get('/privacy/policy', [App\Http\Controllers\HomePageController::class, 'privacyPolicy'])->name('privacy-policy');


Auth::routes();

Route::get('/login',       [App\Http\Controllers\Auth\LoginController::class, 'getLoginPageForAdmin'])->name('login');
Route::get('/admin-login',       [App\Http\Controllers\Auth\LoginController::class, 'getLoginPage'])->name('admin.login');
Route::post('/admin-login',   [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

//All the backend route list here...
Route::middleware(['auth'])->prefix('admin')->group(function () {

	Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');

	//UserRolePermission System Controller...
	Route::resource('users', UserController::class);
	Route::get('users-active/{id}', [App\Http\Controllers\Backend\UserRole\UserController::class, 'userActive'])->name('user-active');
    Route::get('users-inactive/{id}', [App\Http\Controllers\Backend\UserRole\UserController::class, 'userInactive'])->name('user-inactive');
    Route::get('users-profile/{id}', [App\Http\Controllers\Backend\UserRole\UserController::class, 'userProfile'])->name('users-profile');
    Route::post('users-activity-filter/{id}', [App\Http\Controllers\Backend\UserRole\UserController::class, 'userActivityFilter'])->name('users-activity-filter');
    Route::get('users-security/{id}', [App\Http\Controllers\Backend\UserRole\UserController::class, 'userSecurity'])->name('users-security');
    Route::post('users-password-update/{id}', [App\Http\Controllers\Backend\UserRole\UserController::class, 'userPassword'])->name('user-password-update');
	Route::resource('roles', RoleController::class);
	
	//For BlogCategoryController, BlogController, UserProfessionController & ContactCntroller......
	Route::resource('blog-category', BlogCategoryController::class);
	Route::get('/blog-category/destroy/{id}', [App\Http\Controllers\Backend\BlogCategoryController::class, 'destroy'])->name('blog-category.destroy');

	Route::resource('blog', BlogController::class);
	Route::get('/blog/destroy/{id}', [App\Http\Controllers\Backend\BlogController::class, 'destroy'])->name('blog.destroy');

	//For DocumentationCategory & DocumentationController...
	Route::resource('documentation-category', DocumentationCategoryController::class);
	Route::get('/documentation-category/destroy/{id}', [App\Http\Controllers\Backend\DocumentationCategoryController::class, 'destroy'])->name('documentation-category.destroy');
	Route::resource('documentation', DocumentationController::class);
	Route::get('/documentation/destroy/{id}', [App\Http\Controllers\Backend\DocumentationController::class, 'destroy'])->name('documentation.destroy');
	// Documentation section end 

	// payment type start
	Route::resource('payment-type', PaymentTypeController::class);
	Route::get('/payment-type/destroy/{id}', [App\Http\Controllers\Backend\PaymentTypeController::class, 'destroy'])->name('payment-type.destroy');
	// payment type end

	// frontend note start
	Route::resource('frontend-note', FrontendNoteController::class);
	Route::get('/frontend-note/destroy/{id}', [App\Http\Controllers\Backend\FrontendNoteController::class, 'destroy'])->name('frontend-note.destroy');
	// frontend note end 

	Route::resource('profession', UserProfessionController::class);
	Route::get('/profession/destroy/{id}', [App\Http\Controllers\Backend\UserProfessionController::class, 'destroy'])->name('profession.destroy');

	Route::resource('contact', ContactController::class);
	Route::resource('push-notification', PushNotificationController::class);
	
	//To Profile Update...
	Route::get('/user-profile', [App\Http\Controllers\Backend\ProfileController::class, 'userProfile'])->name('user-profile');
	Route::post('/user-profile-update', [App\Http\Controllers\Backend\ProfileController::class, 'updateUserProfile'])->name('user-profile.update');
	Route::get('/user-security', [App\Http\Controllers\Backend\ProfileController::class, 'userSecurity'])->name('user-security');
	Route::post('/user-security-update', [App\Http\Controllers\Backend\ProfileController::class, 'userSecurityUpdate'])->name('user-security-update');
	
	//For logo update...
	Route::get('/logo', [App\Http\Controllers\Backend\SettingController::class, 'logo'])->name('logo');
	Route::post('/logo/update', [App\Http\Controllers\Backend\SettingController::class, 'logoUpdate'])->name('logo.update');
	
	//For Mobile Wallet, Bank, Transaction Category & Active Session Controller...
	Route::resource('mobile-wallet', MobileWalletController::class);
	Route::get('/mobile-wallet/destroy/{id}', [App\Http\Controllers\Backend\MobileWalletController::class, 'destroy'])->name('mobile-wallet.destroy');
	Route::resource('banks', BankController::class);
	Route::get('/banks/destroy/{id}', [App\Http\Controllers\Backend\BankController::class, 'destroy'])->name('banks.destroy');
	Route::resource('transaction-category', TransactionCategoryController::class);
	Route::get('/transaction-category/destroy/{id}', [App\Http\Controllers\Backend\TransactionCategoryController::class, 'destroy'])->name('transaction-category.destroy');
	Route::resource('active-session', ActiveSessionController::class);
	Route::get('/active-session/destroy/{id}', [App\Http\Controllers\Backend\ActiveSessionController::class, 'destroy'])->name('active-session.destroy');
	Route::get('/active-session-active/{id}', [App\Http\Controllers\Backend\ActiveSessionController::class, 'activeSession'])->name('active-session.active');
	Route::get('/active-session-inactive/{id}', [App\Http\Controllers\Backend\ActiveSessionController::class, 'inActiveSession'])->name('active-session.inactive');
	
	Route::fallback(function () {
		return view('error.404');
	});

});

Route::fallback(function () {
    return view('error.404');
});


