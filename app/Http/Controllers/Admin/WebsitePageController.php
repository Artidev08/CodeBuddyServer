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
use App\Http\Requests\WebsitePageRequest;
use App\Models\WebsitePage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebsitePageController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Website Pages';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $websitePages = WebsitePage::query();
        if ($request->get('search')) {
            $websitePages->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('slug', 'like', '%'.$request->search.'%');
        };
        if ($request->get('from') && $request->get('to')) {
            $websitePages->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
            $websitePages= $websitePages->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.website-setup.load', ['websitePages' => $websitePages])->render();
        }
            $label = $this->label;
        return view('admin.website-setup.index', compact('websitePages', 'label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = Str::singular($this->label);
        return view('admin.website-setup.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebsitePageRequest $request)
    {
        // try {
            $meta = [
                'title' => $request->page_meta_title??"",
                'description' => $request->page_meta_description??"",
                'keywords' => $request->page_keywords??"",
            ];
            $websitePage = new WebsitePage();
            $websitePage->title=$request->title;
            $websitePage->slug=$request->slug;
            $websitePage->content=$request->content;
            $websitePage->status=$request->status?? 0;
            $websitePage->meta = $meta;

            if ($request->hasFile('page_meta_image')) {
                $fileAdders = $websitePage->addMultipleMediaFromRequest(['page_meta_image'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('page_meta_image');
                });
            }

            // if($request->hasFile('page_meta_image')) {
            //     $websitePage->addMediaFromRequest('page_meta_image')->toMediaCollection('page_meta_image');
            // }
            $websitePage->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                       'status'=>'success',
                       'message' => 'Success',
                       'title' => 'Page Added Successfully'
                    ]
                );
            }
            return redirect()->route('admin.website-pages.index')->with('success', 'Page Added Successfully');
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WebsitePage $websitePage
     * @return \Illuminate\Http\Response
     */
    public function appearance()
    {
        $label = $this->label;
        return view('admin.appearance.index', compact('label'));
    }

    // public function show($slug)
    // {
    //     $websitePage = WebsitePage::where('slug',$slug)->with('admin')->first();
    //     $relatedPosts = WebsitePage::where('id','!=',$websitePage->id)->latest()->take(2)->get();
    //     return view('admin.website-setup.show',compact('websitePage','relatedPosts'));
    // }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebsitePage $websitePage
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'encrypt');
        }
         $websitePage = WebsitePage::whereId($id)->firstOrFail();
        $label = Str::singular($this->label);
        return view('admin.website-setup.edit', compact('websitePage', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\WebsitePage  $websitePage
     * @return \Illuminate\Http\Response
     */
    public function update(WebsitePageRequest $request, WebsitePage $websitePage)
    { 

         
        try {
            if (!$request->has('status')) {
                $request->status = 0;
            }
            $meta = [
                'title' => $request->page_meta_title??"",
                'description' => $request->page_meta_description??"",
                'keywords' => $request->page_keywords??"",
            ];
            $websitePage->title=$request->title;
            $websitePage->slug=$request->slug;
            $websitePage->content=$request->content;
            $websitePage->status=$request->status ?? 0;
            $websitePage->meta=$meta;
            $websitePage->save();

            if ($request->hasFile('page_meta_image')) {
                $fileAdders = $websitePage->addMultipleMediaFromRequest(['page_meta_image'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('page_meta_image');
                });
            }

            // if($request->hasFile('page_meta_image') && $request->file('page_meta_image')->isValid()){
            //     if ($websitePage->getMedia('page_meta_image')->count()) {
            //         $websitePage->clearMediaCollection('page_meta_image');
            //     }
            //     $websitePage->addMediaFromRequest('page_meta_image')->toMediaCollection('page_meta_image');
            // }
            $websitePage->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Page Updated Successfully'
                    ]
                );
            }
            return redirect()->route('admin.website-pages.index')->with('success', 'Page Updated Successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebsitePage $websitePage
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebsitePage $websitePage)
    {
        try {
            if ($websitePage) {
                $websitePage->delete();
                return back()->with('success', 'Page Deleted Successfully!');
            } else {
                return back()->with('error', 'Page not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function destroyMedia(WebsitePage $websitePage, Request $request)
    {
        try {
            if ($websitePage) {
                if ($websitePage->getMedia($request->media)->count()) {
                    $websitePage->clearMediaCollection($request->media);
                }
                return back()->with('success', 'Media deleted successfully');
            } else {
                return back()->with('error', 'Media not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    // case('columnUpdate'):
    //     WebsitePage::whereIn('id',$request->ids)->update([
    //         $request->column => $request->value
    //     ]);

    //     switch($request->column) {

    //         // Column Status Output Generation
    //         case('is_publish'):
               
    //             $html['badge_color'] = $request->value != 0 ? "success" : "danger";
    //             $html['badge_label'] = $request->value != 0 ? "Publish" : "Unpublish";

    //             $title = "Updated ".count($request->ids)." records successfully!";
    //         break;
    //         default:
    //         $type = "error";
    //         $title = 'No action selected!';
    //     }
    public function bulkAction(Request $request)
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
                    $pages =  WebsitePage::whereIn('id', $request->ids)->get();
                    foreach ($pages as $key => $page) {
                        $this->destroy($page);
                    }

                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                case ('columnUpdate'):
                    WebsitePage::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
    
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('status'):
                            $html['badge_color'] =  WebsitePage::STATUSES[$request->value]['color'];
                            $html['badge_label'] =  WebsitePage::STATUSES[$request->value]['label'];
    
                            $title = "Updated ".count($request->ids)." records successfully!";
                            break;
                        default:
                            $type = "error";
                            $title = 'No action selected!';
                    }
                    
                    break;
                default:
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
                        'html' => $html,
    
                    ]
                );
            }
        
            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
}
