<?php

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

Route::permanentRedirect('/', 'login');

Auth::routes();
Route::get('email/verify', '\App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', '\App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', '\App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::post('user/change-password', [\App\Http\Controllers\UserController::class, 'changePassword'])->name('users.changePassword');
    Route::get('user/ask-change-password', [\App\Http\Controllers\UserController::class, 'askToChangePassword'])->name('users.askToChangePassword');
    Route::resource('organizations', \App\Http\Controllers\OrganizationController::class);
    Route::get('/organizations/invite/{organization}', [\App\Http\Controllers\OrganizationController::class, 'inviteUser'])->name('organizations.invite');
    Route::get('/organizations/handle-invitation/{code}', [\App\Http\Controllers\OrganizationController::class, 'handleInvitation'])->name('organizations.handle-invitation');
    Route::get('/organizations/accept-invitation/{code}', [\App\Http\Controllers\OrganizationController::class, 'acceptInvitation'])->name('organizations.acceptInvitation');
    Route::get('/organizations/reject-invitation/{code}', [\App\Http\Controllers\OrganizationController::class, 'rejectInvitation'])->name('organizations.rejectInvitation');
    Route::delete('/organizations/remove-user/{organization}/{user}', [\App\Http\Controllers\OrganizationController::class, 'removeUser'])->name('organizations.removeUser');
    Route::delete('/organizations/leave/{organization}', [\App\Http\Controllers\OrganizationController::class, 'leave'])->name('organizations.leave');
    //Route::post('organizations/set-current/{organization}', [\App\Http\Controllers\OrganizationController::class, 'setCurrentOrganization'])->name('organizations.setCurrent');





    Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    Route::get('/tasks/invite/{task}', [\App\Http\Controllers\TaskController::class, 'inviteUser'])->name('tasks.invite');
    Route::get('/tasks/handle-invitation/{code}', [\App\Http\Controllers\TaskController::class, 'handleInvitation'])->name('tasks.handle-invitation');
    Route::get('/tasks/accept-invitation/{code}', [\App\Http\Controllers\TaskController::class, 'acceptInvitation'])->name('tasks.acceptInvitation');
    Route::get('/tasks/reject-invitation/{code}', [\App\Http\Controllers\TaskController::class, 'rejectInvitation'])->name('tasks.rejectInvitation');
    Route::get('/tasks/add-subtask/{task}', [\App\Http\Controllers\TaskController::class, 'addSubtask'])->name('tasks.addSubtask');
    Route::get('/tasks/add-assignee/{task}', [\App\Http\Controllers\TaskController::class, 'addAssignee'])->name('tasks.addAssignee');
    Route::post('/tasks/invite-assignee', [\App\Http\Controllers\TaskController::class, 'inviteAssignee'])->name('tasks.inviteAssignee');
    Route::delete('/tasks/remove-assignee/{task}/{user}', [\App\Http\Controllers\TaskController::class, 'removeAssignee'])->name('tasks.removeAssignee');


    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::put('/{notification}', [\App\Http\Controllers\NotificationController::class, 'update'])->name('update');
        Route::delete('/destroy', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'media', 'as' => 'media.'], function () {
        Route::post('{model}/{id}/upload', [\App\Http\Controllers\MediaController::class, 'store'])->name('upload');
        Route::get('{mediaItem}/download', [\App\Http\Controllers\MediaController::class, 'download'])->name('download');
        Route::delete('{model}/{id}/{mediaItem}/delete', [\App\Http\Controllers\MediaController::class, 'destroy'])->name('delete');
    });

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/change-password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.changePassword');

    //Route::get('token', function () {
    //return auth()->user()->createToken('OTM')->plainTextToken;
    //});
});

//Route::get('terms', [\App\Http\Controllers\TermsController::class, 'index'])->middleware('auth')->name('terms.index');
//Route::post('terms', [\App\Http\Controllers\TermsController::class, 'store'])->middleware('auth')->name('terms.store');
