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

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsLetterRequest;
use App\Models\NewsLetter;
use App\Models\CampaginLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsLetterController extends Controller
{
   

    public $label;

    function __construct()
    {
        $this->label = 'Newsletters';
    }
    public function index(Request $request)
    {
        // return "s";
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $newsLetters = NewsLetter::query();
        if ($request->get('from') && $request->get('to')) {
            $newsLetters->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if (request()->has('type') && request()->get('type') != null) {
            $newsLetters->where('type', request()->get('type'));
        }
        if (request()->has('search') && request()->get('search')) {
            $newsLetters->where('name', 'like', '%'.request()->get('search').'%');
        }
        if ($request->get('asc')) {
            $newsLetters->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $newsLetters->orderBy($request->get('desc'), 'desc');
        }
        $newsLetters= $newsLetters->paginate($length);
        if ($request->ajax()) {
            return view('admin.news-letters.load', ['newsLetters' => $newsLetters])->render();
        }
        $label = $this->label;
        return view('admin.news-letters.index', compact('newsLetters', 'label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function print(Request $request)
    {
        $newsletter_arr = collect($request->records['data'])->pluck('id');
        $newsLetters = Newsletter::whereIn('id', $newsletter_arr)->get();
        return view('admin.news-letters.print', ['newsLetters' => $newsLetters])->render();
    }

    public function create(Request $request)
    {
       
        $categories = Category::where('category_type_id', 13)->get();
        $label = Str::singular($this->label);
        return view('admin.news-letters.create', compact('categories', 'label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsLetterRequest $request)
    {
        try {
            $news_letter = NewsLetter::create($request->all());
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Record Created!'
                    ]
                );
            }
            return redirect()->route('admin.news-letters.index')->with('success', 'Record Created!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NewsLetter $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function launchcampaign(Request $request)
    {
        return view('admin.news-letters.campaign');
    }
    public function runcampaign(Request $request)
    {
        // return $request->all();
        try {
            $subject = $request->title;
            $body = $request->body;
            if ($request->group_id == null) {
                $users = NewsLetter::whereType(1)->get();
                $emails = $users->pluck('value');

                $camp = CampaginLog::create(
                    [
                    'subject' => $subject,
                    'user_id' => auth()->id(),
                    'email' => json_encode($emails),
                    'body' => $body,
                    'status' => 0,
                    ]
                );
            } else {
                   // Get all newsletter users of a particular group
                   $users = NewsLetter::whereType(1)->whereGroupId($request->group_id)->get();
                   $emails = $users->pluck('value');
   
                $camp = CampaginLog::create(
                    [
                       'subject' => $subject,
                       'user_id' => auth()->id(),
                       'email' => json_encode($emails),
                       'body' => $body,
                       'status' => 0,
                       ]
                );
            }
                $count = 0;
            foreach ($users as $user) {
                if (StaticMail($user->name, $user->value, $subject, 'Lauching Campaign', $body, null, null, null, null) == "done") {
                    $count++;
                }
            }
            if ($emails->count() == $count) {
                $camp->update(
                    [
                    'status' => 1
                    ]
                );
            }
                return redirect()->back()->with('success', 'Campaign run successfully');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function show(NewsLetter $newsLetter)
    {
        try {
            return view('admin.news-letters.show', compact('newsLetter'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NewsLetter $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //  return $newsLetter;
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $newsLetter = NewsLetter::whereId($id)->firstOrFail();
            $label = Str::singular($this->label);
            return view('admin.news-letters.edit', compact('newsLetter', 'label'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\NewsLetter   $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function update(NewsLetterRequest $request, NewsLetter $newsLetter)
    {
    
         
        try {
            if ($newsLetter) {
                $chk = $newsLetter->update($request->all());
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Record Updated!'
                        ]
                    );
                }
                return redirect()->route('admin.news-letters.index')->with('success', 'Record Updated!');
            }
            return back()->with('error', 'NewsLetter not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NewsLetter $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsLetter $newsLetter)
    {
        try {
            if ($newsLetter) {
                $newsLetter->delete();
                return back()->with('success', 'News Letter Deleted Successfully!');
            } else {
                return back()->with('error', 'News Letter not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function BulkAction(Request $request)
    {
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json(
                    [
                        'status'=>'error',
                    ]
                );
                return back()->with('error', 'Hands Up!","Atleast one row should be selected');
            }
            switch ($request->action) {
                // Delete
                case ('delete'):
                    NewsLetter::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
                $type = "error";
                $title = 'No action selected!';
            }
        
            if (request()->ajax()) {
                return response()->json(
                    [
                    'status'=>'success',
                    'column'=>$request->column,
                    'action'=>$request->action,
                    'data' => $request->ids,
                    'title' => $title,
                    ]
                );
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
