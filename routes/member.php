<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\OrderController;
use App\Http\Controllers\Member\UserController;
use App\Http\Controllers\Member\BulkController;
use App\Http\Controllers\Member\WalletLogController;
use App\Http\Controllers\Member\RoleController;
use App\Http\Controllers\Member\PermissionController;
use App\Http\Controllers\Member\PayoutController;
use App\Http\Controllers\Member\WebsiteEnquiryController;
use App\Http\Controllers\Member\SupportTicketController;
use App\Http\Controllers\Member\ConversationController;
use App\Http\Controllers\Member\NewsLetterController;
use App\Http\Controllers\Member\EmailComposerController;
use App\Http\Controllers\Member\LeadController;
use App\Http\Controllers\Member\UserNoteController;
use App\Http\Controllers\Member\ContactController;
use App\Http\Controllers\Member\PayoutDetailController;
use App\Http\Controllers\Member\BlogController;
use App\Http\Controllers\Member\AgentController;
use App\Http\Controllers\Member\MailSmsTemplateController;
use App\Http\Controllers\Member\CategoryTypeController;
use App\Http\Controllers\Member\CategoryController;
use App\Http\Controllers\Member\SliderTypeController;
use App\Http\Controllers\Member\SliderController;
use App\Http\Controllers\Member\ParagraphContentController;
use App\Http\Controllers\Member\FaqController;
use App\Http\Controllers\Member\LocationController;
use App\Http\Controllers\Member\SettingController;
use App\Http\Controllers\Member\FeatureActivationController;
use App\Http\Controllers\Member\WebsitePageController;
use App\Http\Controllers\Member\GeneralController;
use App\Http\Controllers\Member\MailController;
use App\Http\Controllers\Member\NotificationController;
use App\Http\Controllers\Member\SeoTagController;
use App\Http\Controllers\Member\BroadcastController;
use App\Http\Controllers\Member\MediaController;
use App\Http\Controllers\Member\UserAddressController;
use App\Http\Controllers\Member\ZDeployerController;
use App\Http\Middleware\Authenticate;

Route::group(
    ['middleware' => ['auth','2fa','role:member'], 'prefix' => 'member', 'as' => 'panel.member.'],
    function () {
        

        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/agents','as' =>'agents.'], function () {
                Route::get('{item}/playground', ['uses' => 'AgentController@playground', 'as' => 'playground']);
                Route::get('push-wallet', ['uses' => 'AgentController@pushWallet', 'as' => 'push-wallet']);
                Route::get('{item}/stream-playground', ['uses' => 'AgentController@streamPlayground', 'as' => 'stream-playground']);
            }
        );
    
        Route::group(
            ['prefix' => 'dashboard', 'as' => 'dashboard.', 'controller' => DashboardController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('/logout-as', 'logoutAs')->name('logout-as');
                // Route::get('/module/create', 'createModule')->name('module.crud');
            }
        );
        Route::group(
            ['prefix' => 'profile', 'as' => 'profile.', 'controller' => ProfileController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::post('update/{id}', 'update')->name('update');
                Route::post('update/password/{id}', 'updatePassword')->name('update.password');
                Route::post('update/profile-img/{id}', 'updateProfileImg')->name('update.profile-img');
            }
        );
        Route::group(
            ['prefix' => 'users','as'=> 'users.','controller' => UserController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::any('print', 'print')->name('print');
                Route::get('create', 'create')->name('create')->middleware('permission:add_user');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{user}', 'edit')->name('edit')->middleware('permission:edit_user');
                Route::get('show/{user}', 'show')->name('show');
                Route::get('destroy/{user}', 'destroy')->name('destroy');
                Route::post('update/{user}', 'update')->name('update');
                Route::get('update/status/{id}/{s}', 'updateStatus')->name('update-status');
                Route::get('login-as/{id}/', 'loginAs')->name('login-as');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action');
                Route::post('/kyc-status', 'updateKycStatus')->name('update-kyc-status');
                Route::post('/user/update-password/{id}', 'updateUserPassword')->name('update-user-password');
                Route::get('get/users', 'getUsers')->name('get-users');
                Route::get('/user-delete', 'userDelete')->name('userDelete');
            }
        );
        
        Route::group(
            ['prefix' => 'wallet-logs', 'as' => 'wallet-logs.', 'controller' => WalletLogController::class],
            function () {
                Route::get('user/{id}/', 'index')->name('index')->middleware('permission:control_wallet');
                Route::get('status/{walletLog}/{status}', 'status')->name('status');
                Route::post('/update', 'update')->name('update');
            }
        );


        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/tts-register','as' =>'tts-register.'], function () {
                Route::get('', ['uses' => 'TTSRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'TTSRegisterController@update', 'as' => 'update']);
                Route::post('fetch-audio', ['uses' => 'TTSRegisterController@fetchAudio', 'as' => 'fetch-audio']);
                Route::get('delete/{id}', ['uses' => 'TTSRegisterController@destroy', 'as' => 'destroy']);      
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/ttp-register','as' =>'ttp-register.'], function () {
                Route::get('', ['uses' => 'TTPRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'TTPRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'TTPRegisterController@fetchImage', 'as' => 'fetch-image']);
                Route::get('delete/{id}', ['uses' => 'TTPRegisterController@destroy', 'as' => 'destroy']);      
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/greeting-post-register','as' =>'greeting-post.'],
            function ()
            {
                Route::get('', ['uses' => 'GreetingPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'GreetingPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'GreetingPostRegisterController@fetchImage', 'as' =>
                'fetch-image']);
                Route::get('delete/{id}', ['uses' => 'GreetingPostRegisterController@destroy', 'as' => 'destroy']);      

            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/hacks-register','as' =>'hacks-register.'], function () {
                Route::get('', ['uses' => 'HackRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'HackRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'HackRegisterController@fetchImage', 'as' => 'fetch-image']);
                Route::get('delete/{id}', ['uses' => 'HackRegisterController@destroy', 'as' => 'destroy']);      
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/hiring-register','as' =>'hiring-register.'],
            function () {
                Route::get('', ['uses' => 'HiringRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'HiringRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'HiringRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/employee-register','as' =>'employee-register.'],
            function () {
                Route::get('', ['uses' => 'EmployeeRegisterController@index', 'as' => 'index']);
                Route::post('fetch-image', ['uses' => 'EmployeeRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/client-register','as' =>'client-register.'],
            function () {
                Route::get('', ['uses' => 'ClientRegisterController@index', 'as' => 'index']);
                Route::post('fetch-image', ['uses' => 'ClientRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/id-card-register','as' =>'id-card-register.'],
            function () {
                Route::get('', ['uses' => 'IDCardRegisterController@index', 'as' => 'index']);
                Route::post('fetch-image', ['uses' => 'IDCardRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Member', 'prefix' => '/sensor-register','as' =>'sensor-register.'],
            function () {
                Route::get('', ['uses' => 'SensorRegisterController@index', 'as' => 'index']);
                Route::post('fetch-image', ['uses' => 'SensorRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

    
    }
);
