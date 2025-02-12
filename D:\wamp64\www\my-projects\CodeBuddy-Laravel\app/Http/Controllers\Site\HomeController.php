<?php
/**
 *
 * @category Hq.ai
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <Hq.ai: 202309-V1.2>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Slider;
use App\Models\NewsLetter;
use App\Http\Requests\NewsLetterRequest;
use App\Models\WebsitePage;
use App\Models\ParagraphContent;
use App\Models\Agent;
use App\Models\Thread;
use Illuminate\Support\Facades\Hash;
use App\Models\UserAgent;

class HomeController extends Controller
{
        
    public function index()
    {
        return redirect(route('panel.admin.dashboard.index'));
        $metas = getSeoData('home');
        $agents = Agent::query();

         $agents = $agents->where('status',Agent::STATUS_INSERVICE)->latest()->take(8)->get();

        $contents = getParagraphContent(['home_title','home_description']);
        return view('site.home.index', compact('metas', 'contents','agents'));
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
                return redirect()->route('panel.admin.dashboard.index');
                return $request->all();
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

            if (auth()->user()->hasRole('user')) {
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

    public function playground($id)
    {
        try {
            $data['item'] = Agent::find($id);
            if (!$data['item']) {
                return redirect('/')->with('error', 'Agent does not exist');
            }
            if (request()->has('email') && request()->get('email')) {
                $user   = User::where('email', request()->get('email'))->first();

                if ($user) {
                    auth()->logout();
                    auth()->loginUsingId($user->id);
                    session(['admin_user_id' => auth()->id()]);
                    session(['admin_user_name' => auth()->user()->full_name]);
                    session(['temp_user_id' => $user->id]);
                    // Login.
                } else {
                    $email = request()->get('email');
                    list($name, $domain) = explode('@', $email);
                    $user = User::create(
                        [
                        'first_name'     => $name,
                        'email'    => request()->email,
                        'wallet'    => 20,
                        'password' => Hash::make(1234),
                        ]
                    );
                    $user->syncRoles(['member']);
                    session(['admin_user_id' => auth()->id()]);
                    session(['admin_user_name' => auth()->user()->full_name]);
                    session(['temp_user_id' => $user->id]);
                    auth()->logout();
                    // Login.
                    auth()->loginUsingId($user->id);
                }
            }
            $user = auth()->user();
           
            $userAgent = UserAgent::where('user_id', $user->id)->where('agent_id', $id)->first();
            if (!$userAgent) {
                return redirect()->route('panel.member.dashboard.index')->with('error', 'Selected Agent not assigned to you');
            }
            // auth()->logout();
           
            // find assistant
            $assistantDetails = $this->findAssistant($data['item']->gpt_code);
            $responseArray = json_decode($assistantDetails, true);
            if (isset($responseArray['error'])) {
                return back()->with('error', $responseArray['error']['message']);
            }
            if (request()->get('thread_id')) {
                $thread = Thread::where('id', request()->get('thread_id'))->first();
            } else {
                $thread = Thread::where('agent_id', $data['item']->id)->latest()->first();
            }
            if (!$thread) {
                //create thread in AI
                $threadResponse = $this->createThread();
                $threadresponseArray = json_decode($threadResponse, true);
                $thread = new Thread;
                $thread->agent_id = $data['item']->id;
                $thread->thread_id = $threadresponseArray['id'];
                $thread->user_id = auth()->id();
                $thread->name = 'New Thread';
                $thread->save();
            }
            if (!request()->get('thread_id')) {
                $redirectUrl = route('playground', $id).'?thread_id='.$thread->id;
                return redirect($redirectUrl);
            }
            $data['threadId'] = $thread->thread_id;
            $data['user'] = auth()->user();
            $data['threads'] = Thread::where('agent_id', $data['item']->id)->latest()->get();
            return view('panel.admin.agents.playground', $data);
            // return view('site.playground',$data);
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function member()
    {
        try {
            if (request()->has('email') && request()->get('email')) {
                $user   = User::where('email', request()->get('email'))->first();

                if ($user) {
                    auth()->logout();
                    auth()->loginUsingId($user->id);
                    session(['admin_user_id' => auth()->id()]);
                    session(['admin_user_name' => $user->full_name]);
                    session(['temp_user_id' => $user->id]);
                    // Login.
                } else {
                    $email = request()->get('email');
                    list($name, $domain) = explode('@', $email);
                    $user = User::create(
                        [
                        'first_name'     => $name,
                        'email'    => request()->email,
                        'wallet'    => 20,
                        'password' => Hash::make(1234),
                        ]
                    );
                    $user->syncRoles(['member']);
                    session(['admin_user_id' => auth()->id()]);
                    session(['admin_user_name' => auth()->user()->full_name]);
                    session(['temp_user_id' => $user->id]);
                    auth()->logout();
                    // Login.
                    auth()->loginUsingId($user->id);
                }
            }
            $user = auth()->user();
           
            return redirect()->route('panel.member.dashboard.index');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function pushWallet(Request $request)
    {
        pushWalletLog('debit', '1', 'Your wallet has been deducted by 1 point because you used the playground.',$request->agent_id);
        $user = User::find(auth()->user()->id);
        $user->wallet = $user->wallet - 1;
        $user->save();
        return $user->wallet;
    }
}
