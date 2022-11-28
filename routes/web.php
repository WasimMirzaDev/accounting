<?php

use Illuminate\Support\Facades\Route;

// custom
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DojoController;
use App\Http\Controllers\GradingPolicyController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\VoucherHeadTypeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::prefix('admin')->middleware('isAdmin', 'auth')->group(function(){
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('profile',   [App\Http\Controllers\AdminController::class, 'profile'])->name('admin.profile');
    Route::post('update-profile',   [App\Http\Controllers\AdminController::class, 'update_profile'])->name('admin.update-profile');
    Route::get('settings',   'AdminController@settings')->name('admin.settings');
});



Route::prefix('attendance')->name('attendance.')->middleware('isAdminOrDojo', 'auth')->group(function(){
    Route::get('/upload', [App\Http\Controllers\AttendanceController::class, 'index'])->name('upload');
    Route::post('/save', [App\Http\Controllers\AttendanceController::class, 'store'])->name('save');
    Route::get('/daily', [App\Http\Controllers\AttendanceController::class, 'daily'])->name('daily');
    Route::post('/daily-report', [App\Http\Controllers\AttendanceController::class, 'daily_report'])->name('daily-report');
    Route::get('/summary', [App\Http\Controllers\AttendanceController::class, 'attendance_summary'])->name('attendance-summary');
    Route::post('/summary', [App\Http\Controllers\AttendanceController::class, 'attendance_summary_report'])->name('attendance-summary-report');
    Route::get('/student-attendance', [App\Http\Controllers\AttendanceController::class, 'student_attendance'])->name('student');
    Route::post('/student_attendance', [App\Http\Controllers\AttendanceController::class, 'save_student_attendance'])->name('student_attendance');
});


Route::prefix('grading-policy')->name('grading-policy.')->middleware('isAdminOrDojo', 'auth')->group(function () {
    Route::get('/show', [App\Http\Controllers\GradingPolicyController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\GradingPolicyController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\GradingPolicyController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\GradingPolicyController::class, 'store'])->name('save');
});

Route::prefix('programs')->name('programs.')->middleware('isAdmin', 'auth')->group(function () {
    Route::get('/show', [App\Http\Controllers\ProgramController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\ProgramController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\ProgramController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\ProgramController::class, 'store'])->name('save');
});


Route::prefix('dojo')->middleware('isDojo', 'auth')->group(function(){
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dojo.dashboard');
    Route::get('profile',   'DojoController@profile')->name('dojo.profile');
    Route::get('settings',  'DojoController@settings')->name('dojo.settings');
});

Route::prefix('isStudent', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->group(function(){
    Route::get('dashboard', 'StudentController@index')->name('student.dashboard');
    Route::get('profile',   'StudentController@profile')->name('student.profile');
    Route::get('settings',  'StudentController@settings')->name('student.settings');
});



















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
//     return view('auth.login');
// });
//
//
//
//
// Auth::routes();
// Route::middleware('auth')->group(function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/logout', function(){
  Session::flush();
       Auth::logout();
       return redirect('login');
})->name('auth.logout');

Route::prefix('dojos')->name('dojos.')->middleware('isAdmin', 'auth')->group(function () {
    Route::get('/show', [App\Http\Controllers\DojoController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\DojoController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\DojoController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\DojoController::class, 'store'])->name('save');
});

Route::prefix('events')->name('events.')->middleware('isAdminOrDojo', 'auth')->group(function () {
    Route::post('/add', [App\Http\Controllers\EventController::class, 'add'])->name('add');
    Route::get('/show', [App\Http\Controllers\EventController::class, 'index'])->name('show');
    Route::post('/create', [App\Http\Controllers\EventController::class, 'create'])->name('create');
    Route::post('/update', [App\Http\Controllers\EventController::class, 'update']);
    Route::post('/delete', [App\Http\Controllers\EventController::class, 'destroy']);
});

Route::prefix('students')->name('students.')->middleware('isAdminOrDojo', 'auth')->group(function () {
    Route::get('/show', [App\Http\Controllers\StudentController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\StudentController::class, 'edit'])->name('edit');
    Route::get('/copy/{id?}', [App\Http\Controllers\StudentController::class, 'copy'])->name('copy');
    Route::get('/delete/{id?}', [App\Http\Controllers\StudentController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\StudentController::class, 'store'])->name('save');
    Route::post('/add_note', [App\Http\Controllers\StudentController::class, 'add_note'])->name('add_note');
    Route::post('/edit_students', [App\Http\Controllers\StudentController::class, 'edit_students'])->name('edit_students');
});




// });
//
//
// Route::get('login', function () {
//     return view('auth.login');
// })->name('login');





// accounting start

Route::prefix('account-type')->name('account-type.')->middleware('isAdmin', 'auth')->group(function(){

    Route::get('/show', [App\Http\Controllers\AccountTypeController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\AccountTypeController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\AccountTypeController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\AccountTypeController::class, 'store'])->name('save');
});

Route::prefix('account')->name('account.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\AccountController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\AccountController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\AccountController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\AccountController::class, 'store'])->name('save');
});


Route::prefix('vouchertype')->name('vouchertype.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\AccountController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\AccountController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\AccountController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\AccountController::class, 'store'])->name('save');
});


Route::prefix('vouchertype')->name('vouchertype.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\VoucherTypeController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\VoucherTypeController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\VoucherTypeController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\VoucherTypeController::class, 'store'])->name('save');
});

Route::prefix('voucherheadtype')->name('voucherheadtype.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\VoucherHeadTypeController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\VoucherHeadTypeController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\VoucherHeadTypeController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\VoucherHeadTypeController::class, 'store'])->name('save');
});


Route::prefix('voucher')->name('voucher.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\VoucherController::class, 'index'])->name('show');
    Route::get('/edit/{id?}', [App\Http\Controllers\VoucherController::class, 'edit'])->name('edit');
    Route::get('/delete/{id?}', [App\Http\Controllers\VoucherController::class, 'destroy'])->name('delete');
    Route::post('/save', [App\Http\Controllers\VoucherController::class, 'store'])->name('save');
    Route::post('/change', [App\Http\Controllers\VoucherController::class, 'change_voucher_number']);
    Route::post('/save-headtype', [App\Http\Controllers\VoucherController::class, 'store_headtype'])->name('headtype.save');
});

Route::prefix('ledger')->name('ledger.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\VoucherController::class, 'get_ledger'])->name('show');
    Route::post('/show', [App\Http\Controllers\VoucherController::class, 'show_ledger'])->name('show');
});
Route::prefix('trial')->name('trial.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\TrialBalanceController::class, 'trial_balance'])->name('show');
    Route::post('/show', [App\Http\Controllers\TrialBalanceController::class, 'show_trialbalance'])->name('show');
});
Route::prefix('profitloss')->name('profitloss.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\ProfitLossController::class, 'profitloss'])->name('show');
    Route::post('/show', [App\Http\Controllers\ProfitLossController::class, 'show_profitloss'])->name('show');
});
Route::prefix('balancesheet')->name('balancesheet.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\BalanceSheetController::class, 'balancesheet'])->name('show');
    Route::post('/show', [App\Http\Controllers\BalanceSheetController::class, 'show_balancesheet'])->name('show');
});
Route::prefix('advancetrial')->name('advancetrial.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\AdvanceTrialBalanceController::class, 'advancetrial'])->name('show');
    Route::post('/show', [App\Http\Controllers\AdvanceTrialBalanceController::class, 'show_advancetrial'])->name('show');
});
Route::prefix('ledgertotal')->name('ledgertotal.')->middleware('isAdmin', 'auth')->group(function(){
    // accounting start
    Route::get('/show', [App\Http\Controllers\LedgerTotalController::class, 'ledgertotal'])->name('show');
    Route::post('/show', [App\Http\Controllers\LedgerTotalController::class, 'show_ledgertotal'])->name('show');
});

    // accounting end
