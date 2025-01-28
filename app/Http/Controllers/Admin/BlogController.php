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
use App\Http\Requests\BlogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\Category;

class BlogController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Blogs';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
            $blogs = Blog::query();
        if ($request->get('from') && $request->get('to')) {
            $blogs->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->get('type')) {
            $blogs->where('category_id', '=', $request->type);
        }
        if ($request->get('search')) {
            $blogs->where('title', 'like', '%'.$request->get('search').'%')
                ->orWhere('slug', 'like', '%'.$request->get('search').'%');
        }
            $blogs= $blogs->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.blogs.load', ['blogs' => $blogs])->render();
        }
            $categories = Category::where('category_type_id', 1)->get();
            $label = $this->label;
            return view('admin.blogs.index', compact('blogs', 'categories', 'label'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = getCategoriesByCode('BlogCateogry');
        $label = Str::singular($this->label);
        return view('admin.blogs.create', compact('categories', 'label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequest $request)
    {
        try {
            $meta = [
                'title' => $request->seo_title,
                'keyword' => $request->seo_keywords,
                'description' => $request->seo_description,
            ];
            $blog = new Blog();
            $blog->title=$request->title;
            $blog->slug = \Str::slug($request->title);
            $blog->user_id=$request->user_id;
            ;
            $blog->category_id=$request->category_id;
            $blog->meta=$meta ?? [];
            $blog->is_publish=$request->is_publish;
            $blog->short_description=$request->short_description;
            $blog->description=$request->description;
            
            if ($request->hasFile('description_banner')) {
                $fileAdders = $blog->addMultipleMediaFromRequest(['description_banner'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('description_banner');
                });
            }

            // if ($request->hasFile('description_banner') && $request->file('description_banner')->isValid()) {
            //     $blog->addMediaFromRequest('description_banner')->toMediaCollection('description_banner');
            // }
            $blog->save();

            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Blog Created Successfully'
                    ]
                );
            }
            return redirect(route('admin.blogs.index'))->with('success', 'Blog created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->with('user')->first();
        $relatedPosts = Blog::where('id', '!=', $blog->id)->where('category_id', $blog->category_id)->latest()->take(2)->get();
        return view('site.blog.show', compact('blog', 'relatedPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $blog= Blog::whereId($id)->firstOrFail();
        $categories = Category::where('category_type_id', 1)->get();
        $label = Str::singular($this->label);
        return view('admin.blogs.edit', compact('blog', 'categories', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request $request
     * @param  \App\Models\Blog           $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        try {
            $meta = [
                'title' => $request->seo_title,
                'keyword' => $request->seo_keywords,
                'description' => $request->seo_description,
            ];
            $blog->title=$request->title;
            $blog->slug = \Str::slug($request->title);
            $blog->user_id=auth()->id();
            $blog->category_id=$request->category_id;
            $blog->meta=$meta;
            $blog->short_description=$request->short_description;
            $blog->description=$request->description;

            if ($request->hasFile('description_banner')) {
                $fileAdders = $blog->addMultipleMediaFromRequest(['description_banner'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('description_banner');
                });
//                if ($blog->getMedia('description_banner')->count() > 0) {
//                     $blog->clearMediaCollection('description_banner');
//                 }
//                 $fileAdders = $blog->addMultipleMediaFromRequest(['description_banner'])->each(
//                     function ($fileAdder) {
//                         $fileAdder->toMediaCollection('description_banner');
//                     }
//                 );
            }

            // if ($request->hasFile('description_banner')) {
            //     if($blog->getMedia('description_banner')->count() > 0)
            //     {
            //         $blog->clearMediaCollection('description_banner');
            //     } 
            //     $fileAdders = $blog->addMultipleMediaFromRequest(['description_banner'])->each(function ($fileAdder) {
            //         $fileAdder->toMediaCollection('description_banner');
            //     });
            // }
            if(!$request->has('is_publish')){
                $blog->is_publish = 0;
            } else {
                $blog->is_publish = 1;
            }
            $blog->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Blog Update Successfully'
                    ]
                );
            }
            return redirect(route('admin.blogs.index'))->with('success', 'Blog Update successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        try {
            if ($blog) {
                if ($blog->getMedia('description_banner')->count()) {
                    $blog->clearMediaCollection('description_banner');
                }
                $blog->delete();
                return back()->with('success', 'Blog Deleted Successfully!');
            } else {
                return back()->with('error', 'Blog not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function destroyMedia(Blog $blog, Request $request)
    {
        try {
            if ($blog) {
                if(!empty($request->id)){
                    $blog->deleteMedia($request->id);
                }else{
                    if ($blog->getMedia($request->media)->count()) {
                        $blog->clearMediaCollection($request->media);
                    }
                }
                return back()->with('success', 'Media deleted successfully');
            } else {
                return back()->with('error', 'Media not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function bulkAction(Blog $blog, Request $request)
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
                    Blog::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
        
                // Column Update
                case ('columnUpdate'):
                    Blog::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
        
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('is_publish'):
                            $html['badge_color'] = $request->value != 0 ? "success" : "danger";
                            $html['badge_label'] = $request->value != 0 ? "Publish" : "Unpublish";
        
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
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function ckeditorUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
      
            $request->file('upload')->move(public_path('media'), $fileName);
      
            $url = asset('media/' . $fileName);
  
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }
}
