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
use App\Http\Requests\ParagraphContentRequest;
use App\Models\ParagraphContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParagraphContentController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Paragraph Contents';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $paragraphContents = ParagraphContent::query();
        if ($request->get('search')) {
            $paragraphContents->where('code', 'like', '%'.$request->search.'%')
                ->orWhere('value', 'like', '%'.$request->search.'%')
                ->orWhere('remark', 'like', '%'.$request->search.'%');
        }
        if ($request->get('group')) {
            $paragraphContents->where('group', '=', $request->group);
        }
        $paragraphContents->get();
        $paragraphContents= $paragraphContents->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.paragraph-contents.load', ['paragraphContents' => $paragraphContents])->render();
        }
        $label = $this->label;
        // $groups = ParagraphContent::where('group', '!=', null)->where('group', '!=', ' ')->groupBy('group')->get();
        $groups = getCategoriesByCode('ParagraphContentGroup');
        return view('admin.paragraph-contents.index', compact('paragraphContents', 'label', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $label = Str::singular($this->label);
            return view('admin.paragraph-contents.create', compact('label'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ParagraphContentRequest $request)
    {
        try {
             $paragraphContent = ParagraphContent::create($request->all());
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Record Created'
                    ]
                );
            }
            return redirect()->route('admin.paragraph-contents.edit', $paragraphContent->id)->with('success', 'Record Created!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ParagraphContent $pagagraphContent
     * @return \Illuminate\Http\Response
     */
    public function show(ParagraphContent $paragraphContent)
    {
        try {
            return view('admin.paragraph-contents.show', compact('paragraphContent'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function customUpdate(ParagraphContentRequest $request){

        $paragraphContent = ParagraphContent::where('id', '=', $request->id)->first();
        if($paragraphContent){
            $paragraphContent->value = $request->value;
            $paragraphContent->save();

            return response()->json(
                [
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record Updated'
                ]
            );
        }else{
            return response()->json(
                [
                    'status'=>'error',
                    'message' => 'error',
                    'title' => 'Record not found!'
                ]
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParagraphContent $paragraphContent
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $paragraphContent = ParagraphContent::whereId($id)->firstOrFail();
            $label = Str::singular($this->label);
            return view('admin.paragraph-contents.edit', compact('paragraphContent', 'label'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\ParagraphContent $paragraphContent
     * @return \Illuminate\Http\Response
     */
    public function update(ParagraphContentRequest $request, ParagraphContent $paragraphContent)
    {
        $this->validate(
            $request,
            [
            'code'     => 'required',
            ]
        );
        try {
            if ($paragraphContent) {
                $chk = $paragraphContent->update($request->all());
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Record Updated'
                        ]
                    );
                }
                return redirect()->route('admin.paragraph-contents.index')->with('success', 'Record Updated!');
            }
            return back()->with('error', 'ParagraphContent not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParagraphContent $paragraphContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParagraphContent $paragraphContent)
    {
        try {
            if ($paragraphContent) {
                $paragraphContent->delete();
                return back()->with('success', 'Record Deleted!');
            }
            return back()->with('error', 'Paragraph Content not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request)
    {
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
                ParagraphContent::whereIn('id', $request->ids)->delete();
                $msg = 'Bulk delete!';
                $title = "Deleted ".count($request->ids)." records successfully!";
                break;
            return back()->with('success', 'Paragraph Content Deleted Successfully!');
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
    }
}
