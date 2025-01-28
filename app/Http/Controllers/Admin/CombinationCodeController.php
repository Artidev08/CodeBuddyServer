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
use App\Http\Requests\MailSmsTemplateRequest;
use App\Models\CombinationCode;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CombinationCodeController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Combination Codes';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $combinationCodes = CombinationCode::query();
        if (request()->has('project_id') && request()->get('project_id')) {
            $combinationCodes->where('project_id', request()->get('project_id'));
        }
        if ($request->get('from') && $request->get('to')) {
            $combinationCodes->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        $search_keywords = explode(' ', request('search'));
        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $combinationCodes->where(function ($query) {
                    $query->where('description', request('search'))
                        ->orWhere('codes', request('search'))
                        ->orWhere('id', request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $combinationCodes->where(function ($query) {
                    $query->where('description', 'like', "%" . request('search'))
                        ->orWhere('codes', 'like', "%" . request('search'))
                        ->orWhere('id', 'like', "%" . request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $combinationCodes->where(function ($query) {
                    $query->where('description', 'like', request('search') . '%')
                        ->orWhere('codes', 'like', request('search') . '%')
                        ->orWhere('id', 'like', request('search') . '%');
                });
            } else {
                if (count($search_keywords) == 1) {
                    $keyword = $search_keywords[0];
                    $combinationCodes->where(function ($query) use ($keyword) {
                        $query->where('description', 'like', '%' . $keyword . '%')
                            ->orWhere('codes', 'like', '%' . $keyword . '%')
                            ->orWhere('id', 'like', '%' . $keyword . '%');
                    });
                } else {

                    foreach ($search_keywords as $index => $term) {
                        if ($index == 0) {
                            $combinationCodes->where('description', 'LIKE', "%" . $term . "%")
                                ->orWhere('codes', 'LIKE', "%" . $term . "%");
                        } else {
                            $combinationCodes->orWhere('description', 'LIKE', "%" . $term . "%");
                            $combinationCodes->orWhere('codes', 'LIKE', "%" . $term . "%");
                            $combinationCodes->orWhere('id', 'LIKE', "%" . $term);
                       
                        }
                    }
                }
            }
        }
        $projectName = Project::where('id', request()->get('project_id'))->first();
        $combinationCodes = $combinationCodes->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.code_combinations.load', ['combinationCodes' => $combinationCodes])->render();
        }
        $label = $this->label;
        return view('admin.code_combinations.index', compact('combinationCodes', 'label','projectName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = Str::singular($this->label);
         return view('admin.code_combinations.create', compact('label'));
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
            if(!$request->codes[0]){
                return response()->json(
                    [
                        'status'=>'error',
                        'message' => 'Success',
                        'title' => 'Please Enter Codes'
                    ]
                );
            }
               
            $combinationCode = new CombinationCode();
            $combinationCode->description=$request->description;
            $combinationCode->codes=$request->codes;
            $combinationCode->project_id=$request->project_id;
            // $combinationCode->user_id=auth()->id();
            $combinationCode->save();

            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Code Combinations created successfully'
                    ]
                );
            }
            return redirect()->route('admin.code-combinations.index',['project_id'=>$request->project_id])->with('success', 'Combination Code created successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CombinationCode $combinationCode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $combinationCode = CombinationCode::whereId($id)->firstOrFail();
        return view('admin.code_combinations.show', compact('combinationCode'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CombinationCode $combinationCode
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $combinationCode = CombinationCode::whereId($id)->firstOrFail();
        $label = Str::singular($this->label);
        return view('admin.code_combinations.edit', compact('combinationCode', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  \App\Models\CombinationCode $CombinationCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CombinationCode $combinationCode ,$id)
    {
        try {
            if(!$request->codes[0]){
                return response()->json(
                    [
                        'status'=>'error',
                        'message' => 'Success',
                        'title' => 'Please Enter code'
                    ]
                );
            }
            $combinationCode = CombinationCode::find($id);
            $combinationCode->description=$request->description;
            
            $combinationCode->codes=$request->codes;
            // $combinationCode->user_id=auth()->id();
            $combinationCode->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'code combinations updated successfully'
                    ]
                );
            }
            return redirect(route('admin.code-combinations.index',['project_id'=>$request->project_id]))->with('success', 'Combination Code update successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CombinationCode $CombinationCode
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if ($id) {
               $combinationCode = CombinationCode::find($id);
                $combinationCode->delete();
                return back()->with('success', 'Combination Code Deleted Successfully!');
            } else {
                return back()->with('error', 'Combination Code not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request)
    {
try {
    $ids = explode(',', $request->ids);
    foreach ($ids as $id) {
        if ($id != null) {

            CombinationCode::where('id', $id)->delete();
        }
    }
    if ($ids == [""]) {
        return back()->with('error', 'There were no rows selected by you!');
    } else {
        return back()->with('success', 'Combination Code Deleted Successfully!');
    }
} catch (Exception $e) {
    return back()->with('error', 'There was an error: ' . $e->getMessage());
}
}
public function clearBulkAction(Request $request)
    {
        try {
            if ($request->final_quote == 'delete permanently') {
              
                CombinationCode::whereNotNull('id')->delete();
                return back()->with('success', 'All Record Deleted Successfully!');
            } else {
                return back()->with('error', 'Incorrect input. Please type "delete permanently" to confirm!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
