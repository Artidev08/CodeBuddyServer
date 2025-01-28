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
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'FAQS';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $faqs = Faq::query();
        if (request()->has('search') && request()->get('search')) {
            $faqs->where('title', 'like', '%'.request()->get('search').'%')
                ->orWhereHas(
                    'category',
                    function ($q) {
                        $q->where('name', 'like', '%'.request()->get('search').'%');
                    }
                );
        }
        if ($request->get('asc')) {
            $faqs->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $faqs->orderBy($request->get('desc'), 'desc');
        }
        $faqs= $faqs->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.faqs.load', ['faqs' => $faqs])->render();
        }
        $categories = getCategoriesByCode('FaqCategories');
        $label = $this->label;
        return view('admin.faqs.index', compact('faqs', 'label', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $categories = getCategoriesByCode('FaqCategories');
            $label = Str::singular($this->label);
            return view('admin.faqs.create', compact('categories', 'label'));
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
    public function store(Request $request)
    {
        try {
            if (!$request->has('is_publish')) {
                $request['is_publish'] = 0;
            }
            $faq = Faq::create($request->all());
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Record Created'
                    ]
                );
            }
            return redirect()->route('admin.faqs.index')->with('success', 'Record Created!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        try {
            return view('admin.faqs.show', compact('faq'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $faq =Faq::whereId($id)->firstOrFail();
            $categories = getCategoriesByCode('FaqCategories');
            $label = Str::singular($this->label);
            return view('admin.faqs.edit', compact('faq', 'categories', 'label'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Faq          $faq
     * @return \Illuminate\Http\Response
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        // return $request->all();
        try {
            if ($faq) {
                if (!$request->has('is_publish')) {
                    $request['is_publish'] = 0;
                }
                $chk = $faq->update($request->all());
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Record Created'
                            ]
                    );
                }
            }
            return back()->with('error', 'Faq not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        try {
            if ($faq) {
                $faq->delete();
                return back()->with('success', 'Record Deleted!');
            }
            return back()->with('error', 'Faq not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request)
    {
        $html = [];
        $type = "success";
        if (!isset($request->ids)) {
            if($request->ajax())
            return response()->json(
                [
                    'status'=>'error',
                ]
            );
            else
            return back()->with('error', 'Hands Up!","Atleast one row should be selected');
        }
        switch ($request->action) {
            // Delete
            case ('delete'):
                Faq::whereIn('id', $request->ids)->delete();
                $msg = 'Bulk delete!';
                $title = "Deleted ".count($request->ids)." records successfully!";
                break;
            //Column Update
            case ('columnUpdate'):
                Faq::whereIn('id', $request->ids)->update(
                    [
                    $request->column => $request->value
                    ]
                );

                switch ($request->column) {
                    // Column Status Output Generation
                    case ('is_publish'):
                        $html['badge_color'] = $request->value != 0 ? "success" : "danger";
                        $html['badge_label'] = $request->value != 0 ? "Published" : "Unpublished";

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
    }
}
