<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminPagesController;
use App\Http\Controllers\CreateLoanController;
use App\Http\Controllers\ComputeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [ClientInfoController::class, 'index'])->name('main_page');





// ADMIN ROUTES
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
  Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.borrowers.list');
  Route::get('/main', [DashboardController::class, 'index'])->name('admin.dashboard');
  Route::get('/search', [AdminController::class, 'search'])->name('admin.search');
  Route::get('/add_client', [AdminController::class, 'create'])->name('admin.add_client_page');
  Route::post('/store', [AdminController::class,'store'])->name('admin.store_client');
  Route::get('edit_client/{id}', [AdminController::class, 'edit'])->name('admin.edit_client_data');
  Route::put('update_client/{id}', [AdminController::class, 'update'])->name('admin.update');
  Route::delete('delete_client/{id}', [AdminController::class, 'destroy'])->name('admin.delete_client');
  Route::get('/attachment/{id}', [AdminController::class, 'attachment'])->name('admin.attachment');
  Route::delete('delete_attachment/{id}', [AdminController::class, 'delete_attachment'])->name('admin.delete_attachment');
  
  Route::post('/store_files', [AdminController::class, 'store_file'])->name('admin.add_files.post');

  Route::get('/LoanHistory/{id}', [AdminController::class, 'loanHistory'])->name('admin.LoanHistory');
  Route::get('/MemberHistory', [AdminController::class, 'memberHistory'])->name('admin.MemberHistory');

  Route::get('/LoanHistory1/{id}', [AdminController::class, 'loanHistory1'])->name('admin.LoanHistory1');
  Route::get('/MemberHistory1', [AdminController::class, 'memberHistory1'])->name('admin.MemberHistory1');

  Route::get('/LoanManagement', [AdminPagesController::class, 'LoanManagement'])->name('admin.LoanManagement');
  Route::get('/LoanApplicants', [AdminPagesController::class, 'loan_applicants'])->name('admin.loan_applicants');
  Route::get('/LoanOverdue', [AdminPagesController::class, 'loan_overdue'])->name('admin.loan_overdue');
  Route::get('/CreateLoan', [CreateLoanController::class, 'create'])->name('admin.create_loan_page');
  Route::post('/StoreLoan', [CreateLoanController::class, 'store'])->name('admin.create_loan.post');
  Route::get('/SearchLoan', [AdminPagesController::class, 'search'])->name('admin.search.loan');
  Route::get('/release/{id}', [AdminPagesController::class, 'release'])->name('admin.loan.release');
  Route::post('/release_update/{id}', [AdminPagesController::class, 'update'])->name('admin.released.update');
  Route::get('/delinquent/{id}', [AdminPagesController::class, 'delinquent'])->name('admin.loan.delinquent');
  Route::post('/delinquent_update/{id}', [AdminPagesController::class, 'update_delinquent'])->name('admin.delinquent.update');
  Route::get('/complete/{id}', [AdminPagesController::class, 'complete'])->name('admin.loan.complete');
  Route::post('/complete_update/{id}', [AdminPagesController::class, 'complete_update'])->name('admin.complete.update');
  Route::get('/loanpayment/{id}', [AdminPagesController::class, 'loan_payment'])->name('admin.loan.loan_payment');
  Route::post('/store_loanpayment/{id}', [AdminPagesController::class, 'store_loan_payment'])->name('admin.store.loan_payment');
  Route::get('/create_user/{id}', [AdminPagesController::class, 'create_user'])->name('admin.customer.create_user');
  Route::post('/store_create_user/{id}', [AdminPagesController::class, 'store_link_user'])->name('admin.customer.store_link_user');
  Route::get('/account_setting', [AdminController::class, 'account_setting'])->name('admin.account_setting');
  Route::get('/adminEmail', [AdminController::class, 'adminEmail'])->name('admin.adminEmail');
  Route::post('/changeadminEmail', [AdminController::class, 'changeadminEmail'])->name('admin.changeadminEmail');
  Route::get('/updateadminPassword', [AdminController::class, 'updateadminPassword'])->name('admin.updateadminPassword');
  Route::post('/changeadminPassword', [AdminController::class, 'changeadminPassword'])->name('admin.changePassword');
  Route::get('/viewReports', [AdminController::class, 'viewReports'])->name('admin.viewReports');
  Route::get('/approved/{id}', [AdminPagesController::class, 'approved'])->name('admin.approved');
  Route::get('/denied/{id}', [AdminPagesController::class, 'denied'])->name('admin.loan.denied');
  Route::post('/denied_update/{id}', [AdminPagesController::class, 'update_denied'])->name('admin.denied.update');

  // Route::post('/store_id/{id}', [AdminPagesController::class, 'store_id'])->name('admin.store_id');

  Route::get('/searchToInspect', [DashboardController::class, 'searchToInspect'])->name('admin.searchToInspect');
  Route::get('/specClient/{id}', [DashboardController::class, 'showSpecificGrpah'])->name('admin.showSpecificClientGraph');
  
});

// MEMBER ROUTES
Route::prefix('member')->group(function () {
  Route::get('/dashboard', [HomeController::class, 'index'])->name('member.dashboard');
  Route::get('/account_Setting', [HomeController::class, 'account_setting'])->name('member.account_setting');
  Route::get('/updatePhoto', [HomeController::class, 'updatePhoto'])->name('member.updatePhoto');
  Route::post('/changePhoto', [HomeController::class, 'changePhoto'])->name('member.changePhoto');
  Route::get('/contactNo', [HomeController::class, 'contactNo'])->name('member.contactNo');
  Route::post('/changeContactNo', [HomeController::class, 'changeContactNo'])->name('member.changeContactNo');
  Route::get('/myemail', [HomeController::class, 'myemail'])->name('member.myemail');
  Route::post('/changeEmail', [HomeController::class, 'changeEmail'])->name('member.changeEmail');
  Route::get('/updatePassword', [HomeController::class, 'updatePassword'])->name('member.updatePassword');
  Route::post('/changePassword', [HomeController::class, 'changePassword'])->name('member.changePassword');
  Route::get('/faqs', [HomeController::class, 'faqs'])->name('member.faqs');
  Route::get('/reports', [HomeController::class, 'reports'])->name('member.reports');
  Route::delete('/Delreports/{id}', [HomeController::class, 'reports_destroy'])->name('member.reports.destroy');
  Route::post('/sendReport', [HomeController::class, 'sendReport'])->name('member.sendReport');
  Route::get('/receipt', [HomeController::class, 'receipt'])->name('member.receipt');
  Route::get('/downloadPDF', [HomeController::class, 'downloadPDF'])->name('member.downloadPDF');
  Route::get('/apply_loan', [HomeController::class, 'apply_loan'])->name('member.apply_loan');
  Route::post('/applied_loan', [HomeController::class, 'applied_loan'])->name('member.applied_loan');
  Route::get('/transactionHistory/{id}', [HomeController::class, 'transactionHistory'])->name('member.transactionHistory');
  Route::get('/membHistory', [HomeController::class, 'membHistory'])->name('member.membHistory');
  Route::get('/transactionHistory1/{id}', [HomeController::class, 'transactionHistory1'])->name('member.transactionHistory1');
  Route::get('/membHistory1', [HomeController::class, 'membHistory1'])->name('member.membHistory1');
});

Route::get('/sign_up', [AdminController::class, 'sign_up'])->name('sign_up');




Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/', function () {
  return view('auth.login');
});

  Auth::routes();

// // Authentication Routes...
// Route::get('login', '\App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', '\App\Http\Controllers\Auth\LoginController@login');
// Route::post('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// // Registration Routes...
// Route::get('register', '\App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', '\App\Http\Controllers\Auth\RegisterController@register');

// // Password Reset Routes...
// Route::get('password/reset', '\App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', '\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', '\App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');

// // Confirm Password (added in v6.2)
// Route::get('password/confirm', '\App\Http\Controllers\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
// Route::post('password/confirm', '\App\Http\Controllers\Auth\ConfirmPasswordController@confirm');

// // Email Verification Routes...
// Route::get('email/verify', '\App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', '\App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify'); // v6.x
// /* Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify'); // v5.x */
// Route::get('email/resend', '\App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');
 

