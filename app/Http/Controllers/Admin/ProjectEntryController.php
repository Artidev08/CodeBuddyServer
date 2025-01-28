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
use App\Http\Requests\projectEnterieRequest;
use App\Models\Project;
use App\Models\ProjectEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectEntryController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Project Enteries';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $projectEnteries = ProjectEntry::query();
        if (request()->has('project_id') && request()->get('project_id')) {
            $projectEnteries->where('project_id', request()->get('project_id'));
        }
        if ($request->get('from') && $request->get('to')) {
            $projectEnteries->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        $search_keywords = explode(' ', request('search'));
        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $projectEnteries->where(function ($query) {
                    $query->where('group', request('search'))
                        ->orWhere('group', request('search'))
                        ->orWhere('id', request('search'))
                        ->orWhere('input_content', request('search'))
                        ->orWhere('remark', request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $projectEnteries->where(function ($query) {
                    $query->where('group', 'like', "%" . request('search'))
                        ->orWhere('group', 'like', "%" . request('search'))
                        ->orWhere('id', 'like', "%" . request('search'))
                        ->orWhere('input_content', 'like', "%" . request('search'))
                        ->orWhere('remark', 'like', "%" . request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $projectEnteries->where(function ($query) {
                    $query->where('group', 'like', request('search') . '%')
                        ->orWhere('group', 'like', request('search') . '%')
                        ->orWhere('id', 'like', request('search') . '%')
                        ->orWhere('input_content', 'like', request('search') . '%')
                        ->orWhere('remark', 'like', request('search') . '%');
                });
            } else {
                if (count($search_keywords) == 1) {
                    $keyword = $search_keywords[0];
                    $projectEnteries->where(function ($query) use ($keyword) {
                        $query->where('group', 'like', '%' . $keyword . '%')
                            ->orWhere('group', 'like', '%' . $keyword . '%')
                            ->orWhere('id', 'like', '%' . $keyword . '%')
                            ->orWhere('input_content', 'like', '%' . $keyword . '%')
                            ->orWhere('remark', 'like', '%' . $keyword . '%');
                    });
                } else {

                    foreach ($search_keywords as $index => $term) {
                        if ($index == 0) {
                            $projectEnteries->where('group', 'LIKE', "%" . $term . "%")
                                ->orWhere('group', 'LIKE', "%" . $term . "%");
                        } else {
                            $projectEnteries->orWhere('id', 'LIKE', "%" . $term . "%");
                            $projectEnteries->orWhere('input_content', 'LIKE', "%" . $term . "%");
                            $projectEnteries->orWhere('remark', 'LIKE', "%" . $term);
                            $projectEnteries->orWhere('group', 'LIKE', $term . "%");
                        }
                    }
                }
            }
        }
        $projectName = Project::where('id', request()->get('project_id'))->first();
        $projectEnteries = $projectEnteries->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.project-enteries.load', ['projectEnteries' => $projectEnteries])->render();
        }
        $label = $this->label;
        return view('admin.project-enteries.index', compact('projectEnteries','projectName', 'label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = Str::singular($this->label);
         return view('admin.project-enteries.create', compact('label'));
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
            $projectEntry = new ProjectEntry();
            // $projectEntry->code_id = $request->code_id;
          $projectEntry->group =generateUniqueGroupId();
            $projectEntry->input_content = $request->input_content;
            $projectEntry->user_id = auth()->id();
            $projectEntry->project_id = $request->project_id;
            $projectEntry->medical_condition_id = $request->medical_condition_id;
            // $projectEntry->folder_id = $request->folder_id;
            $projectEntry->criteria_payload = $request->criteria_payload;
            $projectEntry->remark = $request->remark;
            $projectEntry->status = $request->status;
            $projectEntry->save();
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Success',
                        'title' => 'Project Entries created successfully'
                    ]
                );
            }

            return redirect()->route('admin.project-enteries.index',['project_id'=>$request->project_id])->with('success', 'Project Entries created successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectEntry $projectEntry
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $projectEntry = ProjectEntry::whereId($id)->firstOrFail();
        return view('admin.project-enteries.show', compact('projectEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectEntry $projectEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
    
        $projectEntry = ProjectEntry::whereId($id)->firstOrFail();
        $statuses = ProjectEntry::STATUSES; 
        $label = Str::singular($this->label);
        return view('admin.project-enteries.edit', compact('projectEntry', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  \App\Models\ProjectEntry $projectEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $projectEntry = ProjectEntry::find($id);
            // $projectEntry->code_id = $request->code_id;
            // $projectEntry->group = $request->group;
            $projectEntry->input_content = $request->input_content;
            $projectEntry->user_id = auth()->id();
            $projectEntry->project_id = $request->project_id;
            $projectEntry->medical_condition_id = $request->medical_condition_id;
            // $projectEntry->folder_id = $request->folder_id;
            $projectEntry->criteria_payload = $request->criteria_payload;
            $projectEntry->remark = $request->remark;
            $projectEntry->status = $request->status;
            $projectEntry->save();

            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'project enteries updated successfully'
                    ]
                );
            }
            return redirect(route('admin.project-enteries.index',['project_id'=> $request->project_id]))->with('success', 'Project Entry update successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectEntry $projectEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectEntry $id)
    {
        try {
            if ($id) {
                $id->delete();
                return back()->with('success', 'Project Entry Deleted Successfully!');
            } else {
                return back()->with('error', 'Project Entry not found');
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

            ProjectEntry::where('id', $id)->delete();
        }
    }
    if ($ids == [""]) {
        return back()->with('error', 'There were no rows selected by you!');
    } else {
        return back()->with('success', 'Project Entry Deleted Successfully!');
    }
} catch (Exception $e) {
    return back()->with('error', 'There was an error: ' . $e->getMessage());
}
}
public function clearBulkAction(Request $request)
    {
        try {
            if ($request->final_quote == 'delete permanently') {
              
                ProjectEntry::whereNotNull('id')->delete();
                return back()->with('success', 'All Record Deleted Successfully!');
            } else {
                return back()->with('error', 'Incorrect input. Please type "delete permanently" to confirm!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
