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
use App\Http\Requests\SeoTagRequest;
use App\Models\SeoTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoTagController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Control SEO';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $seoTags = SeoTag::query();

        if ($request->get('search')) {
            $seoTags->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%');
        }
         
        if ($request->get('from') && $request->get('to')) {
            $seoTags->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        $seoTags= $seoTags->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.seo-tags.load', ['seoTags' => $seoTags])->render();
        }
        $label = $this->label;
        return view('admin.seo-tags.index', compact('seoTags', 'label'));
    }
    public function print(Request $request)
    {
        $seo_arr = collect($request->records['data'])->pluck('id');
        $seoTags = SeoTag::whereIn('id', $seo_arr)->get();
        return view('admin.seo-tags.print', ['seoTags' => $seoTags])->render();
    }

    /**
     * media
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = Str::singular($this->label);
        return view('admin.seo-tags.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeoTagRequest $request)
    {
        try {
            $seoTag = SeoTag::create(
                [
                'code' => $request->code,
                'title' => $request->title,
                'keyword' => $request->keyword,
                'description' => $request->description,
                'remark' => $request->remark,
                ]
            );
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Seo Tag created successfully'
                    ]
                );
            }
            return redirect(route('admin.seo-tags.index'))->with('success', "Seo Tag successfully!");
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SeoTag $seoTag
     * @return \Illuminate\Http\Response
     */
    public function show(SeoTag $seoTag)
    {
        return view('admin.seo-tags.show', compact('seoTag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SeoTag $seoTag
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $seoTag = SeoTag::whereId($id)->firstOrFail();
        $label = Str::singular($this->label);
        return view('admin.seo-tags.edit', compact('seoTag', 'label'));
    }
   
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
                    SeoTag::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
                return back()->with('success', 'Seo Tag Deleted Successfully!');
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
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\SeoTag       $seoTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SeoTag $seoTag)
    {
        try {
            $seoTag->code=$request->code;
            $seoTag->title=$request->title;
            $seoTag->keyword=$request->keyword;
            $seoTag->description=$request->description;
            $seoTag->remark=$request->remark;
            $seoTag->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Seo Tag updated successfully'
                    ]
                );
            }
            return redirect(route('admin.seo-tags.index'))->with('success', 'Seo Tag update successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SeoTag $seoTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeoTag $seoTag)
    {
        try {
            if ($seoTag) {
                $seoTag->delete();
                return back()->with('success', 'Seo Tag Deleted Successfully!');
            } else {
                return back()->with('error', 'Seo Tag not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
