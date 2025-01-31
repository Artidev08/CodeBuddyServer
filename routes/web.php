<?php

use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\MFAController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\BlogController;
use App\Http\Controllers\Site\FaqController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\SiteMapController;
use App\Http\Controllers\WorldController;
use App\Http\Controllers\Site\SmartController;

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

Route::get('/qb', function () {
    return view('test');
    $logic = 0;
    foreach(App\Models\Agent::get() as $item){
        $item->update(['model_id' => 73]);
        ++$logic;
    }

    return "R: $logic";
});

Route::get('/reset-agent/{id}', function ($id) {
    //  Code Register
    App\Models\CodeRegister::whereId($id)->update([
        'status' => 0,
        'files' => 0
    ]);

    App\Models\CodeRegisterFile::whereCodeRegisterId($id)->update([
        'thread_id' => null,
        'output_content' => null,
        'status' => 0
    ]);

    App\Models\CodeRegisterFileLog::whereCodeRegisterId($id)->delete();

    return "Reseted";
});
// Auth




Route::get('{role}/login', [LoginController::class,'loginForm'])->name('login');
Route::post('{role}/login', [LoginController::class,'login']);
Route::post('logout', [LoginController::class,'logout'])->name('logout');
Route::post('/login-validate', [LoginController::class,'validateLoginByNumber'])->name('login-validate');
Route::get('/otp', [LoginController::class,'otp'])->name('otp-index');
Route::get('/auth-signup', [LoginController::class,'signup'])->name('signup');
Route::post('/signup-validate', [LoginController::class,'validateSignup'])->name('signup-validate');
Route::get('{role}/register', [RegisterController::class,'showRegistrationForm'])->name('register');
Route::post('/auth-otp-validate', [LoginController::class,'validateOTP'])->name('otp-validate');
Route::post('{role}/register', [RegisterController::class,'register']);

// Password
Route::get('password/forget', function () {
        return view('auth.passwords.forgot');
})->name('password.forget');
Route::post('password/email', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class,'reset'])->name('password.update');

// Country State
Route::get('get-states', [WorldController::class,'getStates'])->name('world.get-states');
Route::get('get-cities', [WorldController::class,'getCities'])->name('world.get-cities');


// Files

// Site Route
Route::get('/', [HomeController::class,'index'])->name('index');
Route::get('/about', [HomeController::class,'about'])->name('about');
Route::get('page-error', [HomeController::class,'notFound'])->name('error.index');
Route::post('/newsletter/store', [HomeController::class,'newsletterStore'])->name('newsletter.store');
Route::get('/blogs', [BlogController::class,'index'])->name('blogs');
Route::get('/blog/{slug}', [BlogController::class,'show'])->name('blog.show');
Route::get('/faqs', [FaqController::class,'index'])->name('faqs');

Route::get('/playground/{agent_id}', [HomeController::class,'playground'])->name('playground');
Route::get('push-wallet', [HomeController::class,'pushWallet'])->name('push-wallet');
Route::get('/member', [HomeController::class,'member'])->name('member');

// Contact
Route::get('/contact', [ContactController::class,'index'])->name('contact');
Route::post('/contact/store', [ContactController::class,'store'])->name('contact.store');

// Page
Route::get('/page/{slug}', [HomeController::class,'page'])->name('page.slug');
Route::get('/thank-you', [HomeController::class,'thankYou'])->name('page.custom');

// Sitemap
Route::get('sitemap.xml', [SiteMapController::class,'index'])->name('sitemap.index');
Route::get('smart/localCodeOptimizer/{code_register_id}', [SmartController::class,'localCodeOptimizer'])->name('smart.localCodeOptimizer');
Route::get('smart/scenarioRunner/{sceanrio_runner_id?}', [SmartController::class,'scenarioRunner'])->name('smart.scenarioRunner');

// MFA
// Route::group(['middleware' => '2fa'], function () {
    Route::get('/mfa-checkpoint', [MFAController::class,'index'])->name('mfa-index');
    Route::post('/mfa-checkpoint', [MFAController::class,'store'])->name('mfa-store');
    Route::post('/2fa', function () {
        return redirect(URL()->previous());
    })->name('2fa')->middleware('2fa');
    Route::get('/mfa-reset-form', [MFAController::class,'resetForm'])->name('mfa-reset-form');
    Route::post('/mfa-reset', [MFAController::class,'mfaReset'])->name('mfa-reset');
    Route::get('/mfa-enabled', [MFAController::class,'mfaEnabled'])->name('mfa-enabled');
// });


    Route::group(
        [],
        function () {
            include_once __DIR__ . '/user.php';
            include_once __DIR__ . '/admin.php';
            include_once __DIR__ . '/member.php';
            include_once __DIR__ . '/crudgen.php';
            require_once(__DIR__ . '/crons.php');
        }
    );
