<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PointRedeemController;
use App\Http\Controllers\SubDepartmentController;

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

Route::get('/', [Controller::class, 'ShowWelcome']);

Route::get('/dashboard', [Controller::class, 'ShowDashboard'])->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::prefix('Ticket')->group(function () {
    Route::get('New', [TicketController::class, 'ShowTicketForm'])->name('ShowNewTicketForm');
    Route::post('SubmitNewTicket', [TicketController::class, 'SubmitNewTicket'])->name('SubmitNewTicket');
    Route::get('Success', [TicketController::class, 'ShowSuccessTicketSubmit'])->name('ShowSuccessTicketSubmit');
    Route::get('Search', [TicketController::class, 'SearchTicketForm'])->name('ShowSearchTicketForm');
    Route::get('Search/Result/{status}', [TicketController::class, 'ShowTicketByStaffId'])->name('SearchTicketResult');
    Route::get('Search/Result/{status}/{ticket_id}', [TicketController::class, 'ShowTicketDetail'])->name('SearchTicketDetail');

    Route::middleware(['auth'])->group(function () {
        Route::get('List/{category}', [TicketController::class, 'ShowListTickets'])->name('ShowListTickets');
        Route::get('Select/{category}/{ticketId}', [TicketController::class, 'ShowSelectedTicket'])->name('ShowSelectedTicket');
        Route::post('SubmitApproverRespond', [TicketController::class, 'SubmitApproverRespond'])->name('SubmitApproverRespond');

        Route::get('/AllSubmissions', [TicketController::class, 'ShowAllSubmissions'])->name('ShowAllSubmissions');
        Route::get('/Detail/{ticketId}', [TicketController::class, 'ShowDetail'])->name('ShowDetail');

        Route::get('/Export', [TicketController::class, 'ShowExportPage'])->name('ShowExportPage');
        Route::get('/Export/Download', [TicketController::class, 'DownloadTicketsExport'])->name('DownloadTicketsExport');
    });
});

Route::get('/{staff_id}/redeem/{status}', [PointRedeemController::class, 'showRedeemPage'])->name('redeem.point');
Route::post('/{staff_id}/redeem/submit', [PointRedeemController::class, 'submitRedeemRequest'])->name('redeem.point.submit');

Route::middleware(['auth', 'AdminAuthorization'])->group(function () {
    Route::resource('User', UserController::class);
    Route::post('User/email-password-reset-link', [UserController::class, 'sendMailPasswordResetLink'])->name('sendMailPasswordResetLink');

    Route::resource('/Division', DivisionController::class);
    Route::prefix('/{div_id}')->group(function () {
        Route::resource('/Department', DepartmentController::class);
        Route::prefix('/{dept_id}')->group(function () {
            Route::resource('/SubDepartment', SubDepartmentController::class);
            Route::resource('/Plant', PlantController::class);
        });
    });

    Route::get('/PointRedeem/{status}', [PointRedeemController::class, 'showRedeemList'])->name('admin.redeem.list');
    Route::get('/PointRedeem/{status}/{staff_id}/{redeem_id}', [PointRedeemController::class, 'showRedeemDetail'])->name('admin.redeem.list.staff');
    Route::post('/PointRedeem/{status}/{staff_id}/{redeem_id}/approve', [PointRedeemController::class, 'approveRedeemRequest'])->name('admin.redeem.approve');
});
