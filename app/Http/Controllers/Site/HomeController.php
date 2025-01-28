<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <zStarter: 202306-V1.0>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Slider;
use App\Models\SliderType;
use App\Models\NewsLetter;
use App\Http\Requests\NewsLetterRequest;
use App\Models\WebsitePage;
use App\Models\ParagraphContent;

class HomeController extends Controller
{
        
    public function index()
    {
        $metas = getSeoData('home');
        $app_settings = getSetting(['app_core']);
        $contents = getParagraphContent(['home_title','home_description']);
        return view('site.home.index', compact('metas', 'contents', 'app_settings'));
    }
    
    public function notFound()
    {
        return view('global.error');
    }

    public function page($slug = null)
    {
        if ($slug != null) {
            $page = WebsitePage::where('slug', '=', $slug)->whereStatus(1)->first();
            if (!$page) {
                abort(404);
            }
        } else {
            $page = null;
        }
        return view('site.page.index', compact('page'));
    }

    public function about(Request $request)
    {
        $metas = getSeoData('about');
        $app_settings = getSetting(['app_core']);
        $contents = getParagraphContent(['about_title','about_description']);
        return view('site.about.index', compact('metas', 'contents', 'app_settings'));
    }
    public function newsletterStore(NewsLetterRequest $request)
    {
        // return $request->all();
        if (!auth()->check()) {
            return back()->with('error', "You must be logged in to send the Newsletter!");
        }
        $request['name'] = auth()->user()->full_name;
        $news = NewsLetter::create($request->all());
        return back()->with('success', "Subscribed Successfully!");
    }

   
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function smsVerification(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            $user = User::where('phone', $request->phone)->first();
        }
        
        if ($user->temp_otp != null) {
            if ($user->temp_otp = $request->verification_code) {
                $user->update(['is_verified' => 1,'temp_otp'=>null ]);
                return redirect()->route('admin.dashboard.index');
                // return $request->all();
            } else {
                return back()->with('error', 'OTP Mismatch');
            }
        } else {
            return back()->with('error', 'Try Again');
        }
    }
    public function thankYou(Request $request)
    {
        return view('site.custom-page', compact('request'));
    }
    public function logoutAs()
    {
        // If for some reason route is getting hit without someone already logged in
        if (! auth()->user()) {
            return redirect()->url('/');
        }
        
        // If admin id is set, relogin
        if (session()->has('admin_user_id') && session()->has('temp_user_id')) {
            // Save admin id

            if (authRole() == "User") {
                $role = "?role=User";
            } else {
                $role = "?role=Admin";
            }
            $admin_id = session()->get('admin_user_id');

            session()->forget('admin_user_id');
            session()->forget('temp_user_id');
            session()->forget('admin_user_name');

            // Re-login admin
            auth()->loginUsingId((int) $admin_id);

            // Redirect to backend user page
            return redirect(route('panel.users.index').$role);
        } else {
            // return 'f';
            session()->forget('admin_user_id');
            session()->forget('temp_user_id');
            session()->forget('admin_user_name');

            // Otherwise logout and redirect to login
            auth()->logout();

            return redirect('/');
        }
    }

    public function accessDenied()
    {
        return view('access-denied');
    }
}
