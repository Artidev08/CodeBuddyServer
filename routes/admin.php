<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BulkController;
use App\Http\Controllers\Admin\WalletLogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\WebsiteEnquiryController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\NewsLetterController;
use App\Http\Controllers\Admin\EmailComposerController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\UserNoteController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PayoutDetailController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\MailSmsTemplateController;
use App\Http\Controllers\Admin\CategoryTypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SliderTypeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ParagraphContentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FeatureActivationController;
use App\Http\Controllers\Admin\WebsitePageController;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SeoTagController;
use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\UserAddressController;
use App\Http\Controllers\Admin\ZDeployerController;
use App\Http\Controllers\Admin\TestModelController;
use App\Http\Middleware\Authenticate;

Route::group(
    ['middleware' => 'web', 'prefix' => 'deployer', 'as' => 'deployer.', 'controller' => ZDeployerController::class], function () {
        Route::get('/', 'handle')->name('index');
    }
);
Route::group(
    ['middleware' => ['auth','2fa','role:admin'], 'prefix' => 'admin', 'as' => 'panel.admin.'], function () {
    
        Route::get('/cron/diagnosis', [DashboardController::class, 'cronDiagnosis'])->name('cron.system-diagnosis');
    
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/agents','as' =>'agents.'], function () {
                Route::post('export', 'AgentController@export')->name('export');
                Route::get('', ['uses' => 'AgentController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'AgentController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'AgentController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'AgentController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'AgentController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'AgentController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::get('clone/{item}', ['uses' => 'AgentController@clone', 'as' => 'clone']);
                Route::get('{item}/show', ['uses' => 'AgentController@show', 'as' => 'show']);
                Route::get('{item}/teach', ['uses' => 'AgentController@teach', 'as' => 'teach']);
                Route::get('{item}/playground', ['uses' => 'AgentController@playground', 'as' => 'playground']);
                Route::post('update/{item}', ['uses' => 'AgentController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'AgentController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'AgentController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'AgentController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/agents', ['uses' => 'AgentController@getagents', 'as' => 'get-agents']);
                Route::post('bulk', [BulkController::class,'agents'])->name('bulk');
                Route::get('push-wallet', ['uses' => 'AgentController@pushWallet', 'as' => 'push-wallet']);
                Route::get('{item}/stream-playground', ['uses' => 'AgentController@streamPlayground', 'as' => 'stream-playground']);
                Route::get('{item}/form-filler', ['uses' => 'AgentController@formFiller', 'as' => 'form-filler']);

            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Api\Scenario', 'prefix' => '/scenario-runner','as' =>'scenario-runner.'], function () {
                Route::get('', ['uses' => 'ScenarioRunnerController@runner', 'as' => 'runner']);
                Route::get('{runner_id}/step/{runner_log_id}', ['uses' => 'ScenarioRunnerController@step', 'as' => 'step']);
            }
        );
        
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/scenario','as' =>'scenario.'], function () {
                Route::get('', ['uses' => 'ScenarioController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'ScenarioController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'ScenarioController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'ScenarioController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'ScenarioController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'ScenarioController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'ScenarioController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ScenarioController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'ScenarioController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'ScenarioController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'ScenarioController@getscenario', 'as' => 'get-scenario']);
                 Route::post('export', 'ScenarioController@export')->name('export');
                Route::post('bulk', [BulkController::class,'agents'])->name('bulk');
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/scenario-agents','as' =>'scenario-agents.'], function () {
                Route::get('', ['uses' => 'ScenarioAgentController@index', 'as' => 'index']);
                Route::get('status-update', ['uses' => 'ScenarioAgentController@statusUpdate', 'as' => 'status-update']);
                Route::get('sequence-update', ['uses' => 'ScenarioAgentController@sequenceUpdate', 'as' => 'sequence-update']);
                Route::any('/print', ['uses' => 'ScenarioAgentController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'ScenarioAgentController@create', 'as' => 'create']);
                Route::post('store', ['uses' => 'ScenarioAgentController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'ScenarioAgentController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'ScenarioAgentController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'ScenarioAgentController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ScenarioAgentController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'ScenarioAgentController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'ScenarioAgentController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'ScenarioAgentController@getscenario', 'as' => 'get-scenario']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/scenario-logs','as' =>'scenario-logs.'], function () {
                Route::get('', ['uses' => 'ScenarioLogController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'ScenarioLogController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'ScenarioLogController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'ScenarioLogController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'ScenarioLogController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'ScenarioLogController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'ScenarioLogController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ScenarioLogController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'ScenarioLogController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'ScenarioLogController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'ScenarioLogController@getscenario', 'as' => 'get-scenario']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/content-base','as' =>'content-base.'], function () {
                Route::get('', ['uses' => 'ContentBaseController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'ContentBaseController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'ContentBaseController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'ContentBaseController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'ContentBaseController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'ContentBaseController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'ContentBaseController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ContentBaseController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'ContentBaseController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'ContentBaseController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'ContentBaseController@getscenario', 'as' => 'get-scenario']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/companies','as' =>'companies.'], function () {
                Route::get('', ['uses' => 'CompanyController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'CompanyController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'CompanyController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'CompanyController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'CompanyController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'CompanyController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'CompanyController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'CompanyController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'CompanyController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'CompanyController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'CompanyController@getscenario', 'as' => 'get-scenario']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/platforms','as' =>'platforms.'], function () {
                Route::get('', ['uses' => 'PlatformController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'PlatformController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'PlatformController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'PlatformController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'PlatformController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'PlatformController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'PlatformController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'PlatformController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'PlatformController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'PlatformController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'PlatformController@getscenario', 'as' => 'get-scenario']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/events','as' =>'events.'], function () {
                Route::get('', ['uses' => 'EventController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'EventController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'EventController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'EventController@store', 'as' => 'store']);
                Route::get('edit/{item}', ['uses' => 'EventController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'EventController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'EventController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'EventController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'EventController@bulkAction', 'as' => 'bulk-action']);
                Route::get('update/status/{id}', 'EventController@updateStatus')->name('status-update');
                Route::get('get-files', ['uses' => 'EventController@getFiles', 'as' => 'get-files']);
                Route::get('/post/facebook/{id}', ['uses' => 'EventController@postToFacebook', 'as' => 'post.facebook']);
                Route::get('/post/blog/{id}', ['uses' => 'EventController@postBlog', 'as' => 'post.blog']);
                Route::get('/run/scenario/{id}', ['uses' => 'EventController@runProcess', 'as' => 'run.scenario']);
                Route::get('/generate-image/{id}', ['uses' => 'EventController@generateBlogImage', 'as' => 'generate-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/threads','as' =>'threads.'], function () {
                Route::get('', ['uses' => 'ThreadController@index', 'as' => 'index']);
                Route::get('get-messages', ['uses' => 'ThreadController@getMessages', 'as' => 'get-messages']);
                Route::any('/print', ['uses' => 'ThreadController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'ThreadController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'ThreadController@store', 'as' => 'store'])->withoutMiddleware(['role:admin']);
                Route::get('/{item}', ['uses' => 'ThreadController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'ThreadController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'ThreadController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ThreadController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'ThreadController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'ThreadController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'ThreadController@getscenario', 'as' => 'get-scenario']);
            }
        );
        
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/user-agents','as' =>'user-agents.'], function () {
                Route::get('', ['uses' => 'UserAgentController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'UserAgentController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'UserAgentController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'UserAgentController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'UserAgentController@restore', 'as' => 'restore']);
                Route::get('edit/{item}', ['uses' => 'UserAgentController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'UserAgentController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'UserAgentController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'UserAgentController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'UserAgentController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'UserAgentController@getscenario', 'as' => 'get-scenario']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/agent-versions','as' =>'agent-versions.'], function () {
                Route::get('', ['uses' => 'AgentVersionController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'AgentVersionController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'AgentVersionController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'AgentVersionController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'AgentVersionController@restore', 'as' => 'restore']);
                Route::get('show/{item}', ['uses' => 'AgentVersionController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'AgentVersionController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'AgentVersionController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'AgentVersionController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'AgentVersionController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'AgentVersionController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'AgentVersionController@getscenario', 'as' => 'get-scenario']);
            }
        );

         Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/code-register','as' =>'code-register.'], function () {
                Route::get('', ['uses' => 'CodeRegisterController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'CodeRegisterController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'CodeRegisterController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'CodeRegisterController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'CodeRegisterController@restore', 'as' => 'restore']);
                Route::get('edit/{item}', ['uses' => 'CodeRegisterController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'CodeRegisterController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'CodeRegisterController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'CodeRegisterController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'CodeRegisterController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'CodeRegisterController@getscenario', 'as' => 'get-scenario']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/code-register-file','as' =>'code-register-file.'], function () {
                Route::get('', ['uses' => 'CodeRegisterFileController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'CodeRegisterFileController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'CodeRegisterFileController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'CodeRegisterFileController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'CodeRegisterFileController@restore', 'as' => 'restore']);
                Route::get('edit/{item}', ['uses' => 'CodeRegisterFileController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'CodeRegisterFileController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'CodeRegisterFileController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'CodeRegisterFileController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'CodeRegisterFileController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'CodeRegisterFileController@getscenario', 'as' => 'get-scenario']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/code-register-file-logs','as' =>'code-register-file-logs.'], function () {
                Route::get('', ['uses' => 'CodeRegisterFileLogController@index', 'as' => 'index']);
                Route::any('/print', ['uses' => 'CodeRegisterFileLogController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'CodeRegisterFileLogController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'CodeRegisterFileLogController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'CodeRegisterFileLogController@restore', 'as' => 'restore']);
                Route::get('edit/{item}', ['uses' => 'CodeRegisterFileLogController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'CodeRegisterFileLogController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'CodeRegisterFileLogController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'CodeRegisterFileLogController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'CodeRegisterFileLogController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/scenario', ['uses' => 'CodeRegisterFileLogController@getscenario', 'as' => 'get-scenario']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/tts-register','as' =>'tts-register.'], function () {
                Route::get('', ['uses' => 'TTSRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'TTSRegisterController@update', 'as' => 'update']);
                Route::post('fetch-audio', ['uses' => 'TTSRegisterController@fetchAudio', 'as' => 'fetch-audio']);
                Route::get('delete/{id}', ['uses' => 'TTSRegisterController@destroy', 'as' => 'destroy']);      
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/ttp-register','as' =>'ttp-register.'], function () {
                Route::get('', ['uses' => 'TTPRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'TTPRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'TTPRegisterController@fetchImage', 'as' => 'fetch-image']);
                Route::get('delete/{id}', ['uses' => 'TTPRegisterController@destroy', 'as' => 'destroy']);      
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/greeting-post-register','as' =>'greeting-post.'],
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
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/certificate-register','as' =>'certificate.'],
            function
            () {
                Route::get('', ['uses' => 'CertificateRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'CertificateRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'CertificateRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/hacks-register','as' =>'hacks-register.'], function () {
                Route::get('', ['uses' => 'HackRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'HackRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'HackRegisterController@fetchImage', 'as' => 'fetch-image']);
                Route::get('delete/{id}', ['uses' => 'HackRegisterController@destroy', 'as' => 'destroy']);      
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/announcement-register','as'
            =>'announcement-register.'], function () {
                Route::get('', ['uses' => 'AnnouncementRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'AnnouncementRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'AnnouncementRegisterController@fetchImage', 'as' =>
                'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/hiring-register','as' =>'hiring-register.'],
            function () {
                Route::get('', ['uses' => 'HiringRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'HiringRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'HiringRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/cover-post-register','as' =>'cover-post-register.'],
            function () {
                Route::get('', ['uses' => 'CoverPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'CoverPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'CoverPostRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/employee-register','as' =>'employee-register.'],
            function () {
                Route::get('', ['uses' => 'EmployeeRegisterController@index', 'as' => 'index']);
                Route::post('fetch-image', ['uses' => 'EmployeeRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/client-register','as' =>'client-register.'],
            function () {
                Route::get('', ['uses' => 'ClientRegisterController@index', 'as' => 'index']);
                Route::post('fetch-image', ['uses' => 'ClientRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/id-card-register','as' =>'id-card-register.'],
            function () {
                Route::get('', ['uses' => 'IDCardRegisterController@index', 'as' => 'index']);
                Route::post('fetch-image', ['uses' => 'IDCardRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/sensor-register','as' =>'sensor-register.'],
            function () {
                Route::get('', ['uses' => 'SensorRegisterController@index', 'as' => 'index']);
                Route::post('fetch-image', ['uses' => 'SensorRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/insight-register','as' =>'insight-register.'],
            function () {
                Route::get('', ['uses' => 'InsightRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'InsightRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'InsightRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/testimonial-register','as'
            =>'testimonial-register.'],
            function () {
                Route::get('', ['uses' => 'TestimonialRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'TestimonialRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'TestimonialRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/product-post-register','as' =>'product-post-register.'],
            function () {
                Route::get('', ['uses' => 'ProductPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'ProductPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'ProductPostRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/project-post-register','as' =>'project-post-register.'],
            function () {
                Route::get('', ['uses' => 'ProjectPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'ProjectPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'ProjectPostRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/charity-post-register','as' =>'charity-post-register.'],
            function () {
                Route::get('', ['uses' => 'CharityPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'CharityPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'CharityPostRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/collaboration-post-register','as' =>'collaboration-post-register.'],
            function () {
                Route::get('', ['uses' => 'CollaborationPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'CollaborationPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'CollaborationPostRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
       
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/thought-post-register','as' =>'thought-post-register.'],
            function () {
                Route::get('', ['uses' => 'ThoughtPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'ThoughtPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'ThoughtPostRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/achievement-post-register','as' =>'achievement-post-register.'],
            function () {
                Route::get('', ['uses' => 'AchievementPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'AchievementPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'AchievementPostRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/upcoming-post-register','as' =>'upcoming-post-register.'],
            function () {
                Route::get('', ['uses' => 'UpcomingPostRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'UpcomingPostRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'UpcomingPostRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/cyber-security-register','as' =>'cyber-security-register.'],
            function () {
                Route::get('', ['uses' => 'CyberSecurityRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'CyberSecurityRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'CyberSecurityRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/client-testimonal-register','as' =>'client-testimonal-register.'],
            function () {
                Route::get('', ['uses' => 'ClientTestimonalRegisterController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'ClientTestimonalRegisterController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'ClientTestimonalRegisterController@fetchImage', 'as' => 'fetch-image']);
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/service-post-maker','as' =>'service-post-maker.'],
            function () {
                Route::get('', ['uses' => 'ServicePostMakerController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'ServicePostMakerController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'ServicePostMakerController@fetchImage', 'as' => 'fetch-image']);
            }
        );
        
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/celebration-collage-maker','as' =>'celebration-collage-maker.'],
            function () {
                Route::get('', ['uses' => 'CelebrationCollageMakerController@index', 'as' => 'index']);
                Route::post('update/{item}', ['uses' => 'CelebrationCollageMakerController@update', 'as' => 'update']);
                Route::post('fetch-image', ['uses' => 'CelebrationCollageMakerController@fetchImage', 'as' => 'fetch-image']);
            }
        );


        Route::group(
            ['prefix' => 'media', 'as' => 'media.', 'controller' => MediaController::class], function () {
                Route::get('/destroy', 'destroy')->name('destroy');
                Route::get('/destroy/{id}', 'destroyById')->name('single-destroy');
                Route::post('ckeditor/upload', 'ckeditorUpload')->name('ckeditor.upload');
                // Route::get('/module/create', 'createModule')->name('module.crud');
            }
        );
        Route::group(
            ['prefix' => 'dashboard', 'as' => 'dashboard.', 'controller' => DashboardController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/logout-as', 'logoutAs')->name('logout-as');
                // Route::get('/module/create', 'createModule')->name('module.crud');
            }
        );
        Route::group(
            ['prefix' => 'broadcast', 'as' => 'broadcast.', 'controller' => BroadcastController::class], function () {
                Route::post('/', 'index')->name('index');
                Route::post('role/record', 'roleWiseRecord');
            }
        );
        Route::group(
            ['prefix' => 'profile', 'as' => 'profile.', 'controller' => ProfileController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::post('update/{id}', 'update')->name('update');
                Route::post('update/password/{id}', 'updatePassword')->name('update.password');
                Route::post('update/profile-img/{id}', 'updateProfileImg')->name('update.profile-img');
            }
        );
        Route::group(
            ['prefix' => 'orders', 'as' => 'orders.', 'controller' => OrderController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_orders');
                Route::any('print', 'print')->name('print');
                Route::get('create', 'create')->name('create')->middleware('permission:add_order');
                Route::post('store', 'store')->name('store');
                Route::get('show/{id}', 'show')->name('show')->middleware('permission:show_order');
                Route::get('update/status/{order}', 'updateStatus')->name('status-update');
                Route::get('update/payment-status/{order}', 'updatePaymentStatus')->name('payment-status-update');
                Route::get('invoice/{order}', 'invoice')->name('invoice');
                Route::get('delivery-receipt', 'deliveryReceipt')->name('delivery-receipt');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action');
                Route::get('/getUser', 'getUser')->name('getUser');
                Route::post('/get-user-address', 'getUserAddress')->name('getUserAddress');
                Route::post('/get-seller-address', 'getSellerAddress')->name('getSellerAddress');
            }
        );
        Route::group(
            ['prefix' => 'users','as'=> 'users.','controller' => UserController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::any('print', 'print')->name('print');
                Route::get('create', 'create')->name('create')->middleware('permission:add_user');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{user}', 'edit')->name('edit')->middleware('permission:edit_user');
                Route::get('show/{user}', 'show')->name('show');
                Route::get('destroy/{id}', 'destroy')->name('destroy');
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
            ['prefix' => 'addresses','as'=> 'addresses.','controller' => UserAddressController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::post('store', 'store')->name('store');
                Route::post('update', 'update')->name('update');
                Route::get('destroy/{userAddress}', 'destroy')->name('destroy');
            }
        );
        Route::group(
            ['prefix' => 'payout-details', 'as' => 'payout-details.', 'controller' => PayoutDetailController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::get('/destroy/{payoutDetail}', 'destroy')->name('destroy');
            }
        );
        Route::group(
            ['prefix' => 'roles', 'as' => 'roles.','controller' => RoleController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_roles');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{role}', 'edit')->name('edit')->middleware('permission:edit_role');
                Route::post('update/{id}', 'update')->name('update');
                Route::get('destroy/{role}', 'destroy')->name('destroy');
            }
        );
        Route::group(
            ['prefix' =>'permissions','as' => 'permissions.','controller' => PermissionController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_permissions');
                Route::post('store', 'store')->name('store');
                Route::get('destroy/{id}', 'destroy')->name('destroy');
            }
        );
        Route::group(
            ['prefix' => 'bulk', 'as' => 'bulk.', 'controller' => BulkController::class], function () {
                Route::post('user', 'user')->name('user');
                Route::post('events', 'events')->name('events');
            }
        );
        Route::group(
            ['prefix' => 'wallet-logs', 'as' => 'wallet-logs.', 'controller' => WalletLogController::class], function () {
                Route::get('user/{id}/', 'index')->name('index')->middleware('permission:control_wallet');
                Route::get('status/{walletLog}/{status}', 'status')->name('status');
                Route::post('/update', 'update')->name('update');
            }
        );
    
        Route::group(
            ['prefix' => 'website-pages', 'as' => 'website-pages.', 'controller' => WebsitePageController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_pages');
                Route::get('/create', 'create')->name('create')->middleware('permission:add_page');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_page');
                Route::get('/show/{websitePage}', 'show')->name('show');
                Route::post('/update/{websitePage}', 'update')->name('update');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_page');
                Route::get('/delete-media/{websitePage}', 'destroyMedia')->name('destroy-media');
                Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
            }
        );
        Route::group(
            ['prefix' => 'payouts', 'as' => 'payouts.', 'controller' => PayoutController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_payouts');
                Route::get('/create', 'create')->name('create')->middleware('permission:add_payouts');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{payout}', 'show')->name('show')->middleware('permission:show_payout');
                Route::get('/delete/{id}', 'destroy')->name('delete');
                Route::post('/status/{payout}', 'status')->name('status');
                Route::any('/print', 'print')->name('print');
                Route::any('/bulk-action', 'bulkAction')->name('bulk-action');
            }
        );
        Route::group(
            ['prefix' => 'support-tickets', 'as' => 'support-tickets.', 'controller' => SupportTicketController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_tickets');
                Route::get('/create', 'create')->name('create')->middleware('permission:add_ticket');
                Route::post('/store', 'store')->name('store');
                Route::post('/bulk-delete', 'bulkAction')->name('bulk-delete');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_ticket');
                Route::get('/show/{supportTicket}', 'show')->name('show')->middleware('permission:show_ticket');
                Route::post('/add-attachment/{supportTicket}', 'addAttachment')->name('add-attachment');
                Route::get('/status/{supportTicket}/{status}', 'status')->name('status');
                Route::post('/update/{supportTicket}', 'update')->name('update');
                Route::get('/reply', 'reply')->name('reply');
                Route::any('/print', 'print')->name('print');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_ticket');
            }
        );
        Route::group(
            ['prefix' => 'conversations', 'as' => 'conversations.', 'controller' => ConversationController::class], function () {
                Route::post('/store', 'store')->name('store');
                Route::any('/destroy', 'destroy')->name('destroy');
            }
        );
        Route::group(
            ['prefix' => 'news-letters', 'as' => 'news-letters.', 'controller' => NewsLetterController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_newsletters');
                Route::get('/create', 'create')->name('create')->middleware('permission:add_newsletter');
                Route::post('/store', 'store')->name('store')->middleware('permission:add_newsletter');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_newsletter');
                Route::get('/show/{newsLetter}', 'show')->name('show')->middleware('permission:show_newsletter');
                Route::post('/update/{newsLetter}', 'update')->name('update');
                Route::any('/print', 'print')->name('print');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_newsletter');
                Route::get('/launchcampaign', 'launchcampaign')->name('launchcampaign');
                Route::post('/runcampaign', 'runcampaign')->name('runcampaign');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action');
            }
        );
        Route::group(
            ['prefix' => 'compose-emails', 'as' => 'compose-emails.', 'controller' => EmailComposerController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::post('/get-template', 'getTemplate')->name('get-template');
                Route::post('/send', 'send')->name('send');
            }
        );
        Route::group(
            ['prefix' => 'website-enquiries', 'as' => 'website-enquiries.', 'controller' => WebsiteEnquiryController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_enquiries');
                Route::get('/create', 'create')->name('create')->middleware('permission:add_enquiry');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_enquiry');
                Route::get('/show/{websiteEnquiry}', 'show')->name('show')->middleware('permission:show_enquiry');
                Route::post('/update/{websiteEnquiry}', 'update')->name('update');
                Route::any('/print', 'print')->name('print');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_enquiry');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action');
                Route::get('update/status/{websiteEnquiry}', 'status')->name('status-update');
            }
        );
        Route::group(
            ['prefix' => 'leads', 'as' => 'leads.', 'controller' => LeadController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_leads');
                Route::get('/create', 'create')->name('create')->middleware('permission:add_lead');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{lead}', 'edit')->name('edit')->middleware('permission:edit_lead');
                Route::get('/show/{lead}', 'show')->name('show');
                Route::post('/update/{lead}', 'update')->name('update');
                Route::any('/print', 'print')->name('print');
                Route::get('/destroy/{lead}', 'destroy')->name('destroy')->middleware('permission:delete_lead');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action');
                Route::post('/bulk-delete', 'bulkAction')->name('bulk-delete');
            }
        );
        Route::group(
            ['prefix' => 'user-notes', 'as' => 'user-notes.', 'controller' => UserNoteController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{userNote}', 'edit')->name('edit');
                Route::get('/show/{userNote}', 'show')->name('show');
                Route::post('/update/{userNote}', 'update')->name('update');
                Route::any('/print', 'print')->name('print');
                Route::get('/destroy/{userNote}', 'destroy')->name('destroy');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action');
            }
        );
        Route::group(
            ['prefix' => 'contacts', 'as' => 'contacts.', 'controller' => ContactController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{contact}', 'edit')->name('edit');
                Route::get('/show/{contact}', 'show')->name('show');
                Route::post('/update/{contact}', 'update')->name('update');
                Route::any('/print', 'print')->name('print');
                Route::get('/destroy/{contact}', 'destroy')->name('destroy');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action');
            }
        );
        Route::group(
            ['prefix' => 'blogs', 'as' => 'blogs.', 'controller' => BlogController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_blogs');
                Route::get('/create', 'create')->name('create')->middleware('permission:add_blog');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{blog}', 'edit')->name('edit')->middleware('permission:edit_blog');
                Route::get('/show/{blog}', 'show')->name('show')->middleware('permission:edit_blog');
                Route::post('/update/{blog}', 'update')->name('update');
                Route::any('/print', 'print')->name('print');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_blog');
                Route::get('/delete-media/{blog}', 'destroyMedia')->name('destroy-media');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action');
                Route::post('ckeditor/upload', 'ckeditorUpload')->name('ckeditor.upload');
            }
        );
      
    Route::group(['prefix' => 'mail-sms-templates', 'as' => 'mail-sms-templates.', 'controller' => MailSmsTemplateController::class],function () {
        Route::get('/', 'index')->name('index')->middleware('permission:view_mail_templates');
        Route::get('/create', 'create')->name('create')->middleware('permission:add_mail_template');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_mail_template');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{mailSmsTemplate}', 'update')->name('update');
        Route::any('/print','print')->name('print');
        Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_mail_template');
        Route::post('bulk-action', 'bulkAction')->name('bulk-action');
    });
    Route::group(['prefix' => 'category-types', 'as' => 'category-types.', 'controller' => CategoryTypeController::class],function () {
        Route::get('/', 'index')->name('index')->middleware('permission:view_categories');
        Route::get('/create', 'create')->name('create')->middleware('permission:add_category');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_category');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/update/{categoryType}', 'update')->name('update');
        Route::post('more-action', 'moreAction',)->name('more-action');
        Route::any('/print','print')->name('print');
        Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_category');
        Route::post('bulk-action', 'bulkAction')->name('bulk-action');
    });
    Route::group(['prefix' => 'categories', 'as' => 'categories.', 'controller' => CategoryController::class],function () {
        Route::get('/{categoryTypeId}', 'index')->name('index');
        Route::get('/create/{categoryTypeId}/{level?}/{parent_id?}', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/show/{category}', 'show')->name('show');
        Route::post('/update/{category}', 'update')->name('update');
        Route::post('more-action', 'moreAction',)->name('more-action');
        Route::any('/print','print')->name('print');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::post('bulk-action', 'bulkAction')->name('bulk-action');
        Route::post('get-category', 'getCategory')->name('get-category');
    });
    Route::group(['prefix' => 'slider-types', 'as' => 'slider-types.', 'controller' => SliderTypeController::class],function () {
        Route::get('/', 'index')->name('index')->middleware('permission:view_sliders');
        Route::get('/create', 'create')->name('create')->middleware('permission:add_slider');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_slider');
        Route::get('/show/{sliderType}', 'show')->name('show');
        Route::post('/update/{sliderType}', 'update')->name('update');
        Route::any('/print','print')->name('print');
        Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_slider');
        Route::post('bulk-action', 'bulkAction')->name('bulk-action');
    });
    Route::group(['prefix' => 'sliders', 'as' => 'sliders.', 'controller' => SliderController::class],function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{slider}', 'edit')->name('edit');
        Route::get('/show/{slider}', 'show')->name('show');
        Route::post('/update/{slider}', 'update')->name('update');
        Route::any('/print','print')->name('print');
        Route::get('/destroy/{id}', 'destroy')->name('destroy');
        Route::get('/delete-media/{slider}', 'destroyMedia')->name('destroy-media');
        Route::post('bulk-action', 'bulkAction')->name('bulk-action');
    });
    Route::group(['prefix' => 'paragraph-contents', 'as' => 'paragraph-contents.', 'controller' => ParagraphContentController::class],function () {
        Route::get('/', 'index')->name('index')->middleware('permission:view_paragraph_contents');
        Route::get('/create', 'create')->name('create')->middleware('permission:add_paragraph_content');
        Route::post('/store', 'store')->name('store');
        Route::post('/custom-update', 'customUpdate')->name('custom-update');
        Route::get('/edit/{ParagraphContent}', 'edit')->name('edit')->middleware('permission:edit_paragraph_content');
        Route::get('/show/{ParagraphContent}', 'show')->name('show');
        Route::post('/update/{paragraphContent}', 'update')->name('update');
        Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_paragraph_content');
        Route::post('bulk-action', 'bulkAction')->name('bulk-action');
    });
    Route::group(['prefix' => 'faqs', 'as' => 'faqs.', 'controller' => FaqController::class],function () {
        Route::get('/', 'index')->name('index')->middleware('permission:view_faqs');
        Route::get('/create', 'create')->name('create')->middleware('permission:add_faq');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_faq');
        Route::get('/show/{faq}', 'show')->name('show');
        Route::post('/update/{faq}', 'update')->name('update');
        Route::any('/print','print')->name('print');
        Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_faq');
        Route::post('bulk-action', 'bulkAction')->name('bulk-action');
    });
    Route::group(['prefix' => 'locations', 'as' => 'locations.', 'controller' => LocationController::class],function () {
        Route::get('/country','country')->name('country')->middleware('permission:view_locations');
        Route::get('/country/create','create')->name('country.create')->middleware('permission:add_location');
        Route::post('/country/store','store')->name('country.store');
        Route::get('/country/edit/{id}','edit')->name('country.edit')->middleware('permission:edit_location');
        Route::post('/country/update/{id}','update')->name('country.update');
        Route::get('/state','state')->name('state');
        Route::post('/state/store','stateStore')->name('state.store');
        Route::post('/state/update','stateUpdate')->name('state.update');
        Route::get('/city','city')->name('city');
        Route::post('/city/store','cityStore')->name('city.store');
        Route::post('/city/update','cityUpdate')->name('city.update');
    });
    Route::group(['prefix' => 'setting', 'as' => 'setting.', 'controller' => SettingController::class],function () {
        Route::get('/','index')->name('index')->middleware('permission:control_basic_details');
        Route::post('/store','store')->name('store');
    });
    Route::group(['prefix' => 'setting', 'as' => 'setting.', 'controller' => FeatureActivationController::class],function () {
            Route::get('/features-activation', 'featuresActivationIndex')->name('features-activation');
            Route::post('/features-activation/store', 'featuresActivationStore')->name('features-activation.store');
        }
    );
        Route::group(
            ['prefix' => 'website-pages', 'as' => 'website-pages.', 'controller' => WebsitePageController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_pages');
                Route::get('/create', 'create')->name('create')->middleware('permission:add_page');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{websitePage}', 'edit')->name('edit')->middleware('permission:edit_page');
                Route::get('/show/{websitePage}', 'show')->name('show');
                Route::post('/update/{websitePage}', 'update')->name('update');
                Route::get('/destroy/{websitePage}', 'destroy')->name('destroy')->middleware('permission:delete_page');
                Route::get('/delete-media/{websitePage}', 'destroyMedia')->name('destroy-media');
                Route::get('/appearance', 'appearance')->name('appearance');
                Route::get('/social-login', 'socialLogin')->name('social-login')->middleware('permission:control_social_logins_details');
                Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
            }
        );
        Route::group(
            ['prefix' => 'general', 'as' => 'general.', 'controller' => GeneralController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:access_general_setting');
                Route::get('/storage-link', 'storageLink')->name('storage-link');
                Route::get('/optimize-clear', 'optimizeClear')->name('optimize-clear');
                Route::get('/session-clear', 'sessionClear')->name('session-clear');
                Route::get('/content-group', 'contentGroup')->name('content-group');

            }
        );
        Route::group(
            ['prefix' => 'notifications', 'as' => 'notifications.', 'controller' =>NotificationController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::any('print', 'print')->name('print');
                Route::get('/update/{notification}', 'update')->name('update');
            }
        );
        Route::group(
            ['prefix' => 'mail-sms-configuration', 'as' => 'mail-sms-configuration.', 'controller' => MailController::class], function () {
                Route::get('/', 'index')->name('index');
                Route::post('/mail', 'storeMail')->name('mail.store');
                Route::post('/sms', 'storeSMS')->name('sms.store');
                Route::post('/notification', 'storePushNotification')->name('notification.store');
                Route::post('test', 'testSend')->name('test.send');

            }
        );
        Route::group(
            ['prefix' => 'seo-tags', 'as' => 'seo-tags.', 'controller' => SeoTagController::class], function () {
                Route::get('/', 'index')->name('index')->middleware('permission:view_seo_tags');
                Route::any('print', 'print')->name('print');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:edit_seo_tag');
                Route::get('/show/{seoTag}', 'show')->name('show');
                Route::post('/update/{seoTag}', 'update')->name('update');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:delete_seo_tag');
                Route::post('bulk-action', 'bulkAction')->name('bulk-delete');
            }
        );

        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/items','as' =>'items.'], function () {
                Route::get('', ['uses' => 'ItemController@index', 'as' => 'index'])->middleware('permission:view_items');
                Route::any('/print', ['uses' => 'ItemController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'ItemController@create', 'as' => 'create'])->middleware('permission:add_item');
                Route::post('store', ['uses' => 'ItemController@store', 'as' => 'store']);
                Route::get('/{item}', ['uses' => 'ItemController@show', 'as' => 'show']);
                Route::get('edit/{item}', ['uses' => 'ItemController@edit', 'as' => 'edit'])->middleware('permission:edit_item');
                Route::post('update/{item}', ['uses' => 'ItemController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ItemController@destroy', 'as' => 'destroy'])->middleware('permission:delete_item');      
                Route::get('delete-media/{item}', ['uses' => 'ItemController@destroyMedia', 'as' => 'destroy-media']);
                Route::post('bulk-action', ['uses' => 'ItemController@bulkAction', 'as' => 'bulk-action']);
                Route::get('get/items', ['uses' => 'ItemController@getItems', 'as' => 'get-items']);
            }
        );
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'subscriptions','as' =>'subscriptions.'], function () {
                Route::get('', ['uses' => 'SubscriptionController@index', 'as' => 'index'])->middleware('permission:view_subscription_plans');
                Route::any('/print', ['uses' => 'SubscriptionController@print', 'as' => 'print']);
                Route::get('create', ['uses' => 'SubscriptionController@create', 'as' => 'create'])->middleware('permission:add_subscription_plan');
                Route::post('store', ['uses' => 'SubscriptionController@store', 'as' => 'store'])->middleware('permission:add_subscription_plan');
                Route::get('/{subscription}', ['uses' => 'SubscriptionController@show', 'as' => 'show']);
                Route::get('edit/{subscription}', ['uses' => 'SubscriptionController@edit', 'as' => 'edit'])->middleware('permission:edit_subscription_plan');
                Route::post('update/{subscription}', ['uses' => 'SubscriptionController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'SubscriptionController@destroy', 'as' => 'destroy'])->middleware('permission:delete_subscription_plan');;
                Route::post('/bulk-action', ['uses' => 'SubscriptionController@bulkAction', 'as' => 'bulk-action']);
            }
        ); 

        Route::group(['namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'user-subscriptions','as' =>'user-subscriptions.'], function () {
            Route::get('', ['uses' => 'UserSubscriptionController@index', 'as' => 'index']);
            Route::any('/print', ['uses' => 'UserSubscriptionController@print', 'as' => 'print']);
            Route::get('create', ['uses' => 'UserSubscriptionController@create', 'as' => 'create']);
            Route::post('store', ['uses' => 'UserSubscriptionController@store', 'as' => 'store']);
            Route::get('/{user_subscription}', ['uses' => 'UserSubscriptionController@show', 'as' => 'show']);
            Route::get('edit/{id}', ['uses' => 'UserSubscriptionController@edit', 'as' => 'edit']);
            Route::post('update/{user_subscription}', ['uses' => 'UserSubscriptionController@update', 'as' => 'update']);
            Route::get('delete/{id}', ['uses' => 'UserSubscriptionController@destroy', 'as' => 'destroy']);
            Route::get('get/user-subscription-data', ['uses' => 'UserSubscriptionController@getUserSubscriptionData', 'as' => 'get-user-subscription-data']);
            }
        ); 

         Route::group(
         ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/scenario-runner','as' =>'scenario-runner.'],
         function ()
         {
         Route::get('', ['uses' => 'ScenarioRunnerController@index', 'as' => 'index']);
         Route::get('delete/{id}', ['uses' => 'ScenarioRunnerController@destroy', 'as' => 'destroy']);
             
         }
         );
         Route::group(
         ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/scenario-runner-logs','as' =>'scenario-runner-logs.'],
         function ()
         {
         Route::get('', ['uses' => 'ScenarioRunnerLogController@index', 'as' => 'index']);
         Route::get('delete/{id}', ['uses' => 'ScenarioRunnerLogController@destroy', 'as' => 'destroy']);
             
         }
         );

    
    
    }
);
    
    
    
   
    
    
    
    
