<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mentor\DashboardController;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\Mentor\PayoutController;
use App\Http\Controllers\Mentor\WalletLogController;
use App\Http\Controllers\Mentor\SupportTicketController;
use App\Http\Controllers\Mentor\ProfileController;
use App\Http\Controllers\Mentor\CalendarController;
use App\Http\Controllers\Mentor\ConversationController;

Route::group(
    ['middleware' =>  ['auth','role:mentor','check-mentor-step'],'prefix' => 'mentor', 'as' => 'mentor.'], function () {

        Route::group(
            ['prefix' => 'dashboard', 'as' => 'dashboard.', 'controller' => DashboardController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/logout-as', 'logoutAs')->name('logout-as');
            }
        );
        Route::group(
            ['controller' => MentorController::class], function () {
                Route::get('appointment', ['uses' => 'appointment', 'as' => 'appointment']);
                Route::get('appointment/{id?}', ['uses' => 'appointmentShow', 'as' => 'appointment.show']);
                Route::get('appointment/{id?}/cancel', ['uses' => 'cancelAppointment', 'as' => 'appointment.cancel']);
                Route::get('appointment/invoice/{id?}', ['uses' => 'invoice', 'as' => 'appointment.invoice']);
                Route::get('appointment/get-recording/{id}', ['uses' => 'getRecording', 'as' => 'appointment.get-recording']);

                Route::get('schedules', ['uses' => 'schedule', 'as' => 'schedules']);
                Route::post('schedule/store', ['uses' => 'scheduleStore', 'as' => 'schedule.store']);
                Route::get('schedule/destroy', ['uses' => 'destroySchedule', 'as' => 'schedule.destroy']);

                Route::get('profile', ['uses' => 'settingProfile', 'as' => 'profile']);
                Route::get('change-password', ['uses' => 'settingChangePassword', 'as' => 'change-password']);
                Route::post('update-meeting-link', ['uses' => 'updateMeetingLink', 'as' => 'update.meeting-link']);
                Route::get('appointment/sign-in/{id?}', ['uses' => 'appointmentSignIn', 'as' => 'appointment.sign-in']);
                Route::post('appointment/sign-off/{id?}', ['uses' => 'appointmentSignOff', 'as' => 'appointment.sign-off']);
                Route::post('update/duration', ['uses' => 'updateDuration', 'as' => 'update.duration']);
                Route::get('fee', ['uses' => 'manageFee', 'as' => 'fee']);
                Route::post('update/fee', ['uses' => 'updateFee', 'as' => 'update.fee']);
            }
        );

        Route::group(
            ['prefix' => 'payout', 'as' => 'payout.', 'controller' => PayoutController::class], function () {
                Route::get('/', ['uses' => 'index', 'as' => 'index']);
            }
        );
        Route::group(
            ['prefix' => 'wallet-statements', 'as' => 'wallet-logs.', 'controller' => WalletLogController::class], function () {
                Route::get('/', ['uses' => 'index', 'as' => 'index']);
            }
        );
        Route::group(
            ['prefix' => 'calendar', 'as' => 'calendar.', 'controller' => CalendarController::class], function () {
                Route::get('/', ['uses' => 'index', 'as' => 'index']);
            }
        );

        Route::group(
            ['prefix' => 'support-ticket', 'as' => 'support-ticket.', 'controller' => SupportTicketController::class], function () {
                Route::get('/', ['uses' => 'index', 'as' => 'index']);
                Route::post('support-ticket/store', ['uses' => 'supportTicketStore', 'as' => 'store']);
                Route::get('/{id?}', ['uses' => 'show', 'as' => 'show']);
            }
        );

        Route::group(
            ['prefix' => 'conversations', 'as' => 'conversations.', 'controller' => ConversationController::class], function () {
                Route::post('store', ['uses' => 'store', 'as' => 'store']);
                Route::get('destroy', ['uses' => 'destroy', 'as' => 'destroy']);
            }
        );

        Route::group(
            ['prefix' => 'setting', 'as' => 'setting.', 'controller' => ProfileController::class], function () {
                Route::get('profile', ['uses' => 'index', 'as' => 'profile']);
                Route::get('security', ['uses' => 'showSecurity', 'as' => 'security']);
                Route::get('change-password', ['uses' => 'settingChangePassword', 'as' => 'change-password']);
                Route::post('update-password/{id}', ['uses' => 'updatePassword', 'as' => 'update-password']);
                Route::post('profile/{id}', ['uses' => 'update', 'as' => 'update.profile']);
            }
        );
    }
);
