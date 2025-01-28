<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Exports\ProjectExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CombinationCode;
use App\Models\CriticalDiagnosis;
use App\Models\Exclude;
use App\Models\Gender;
use App\Models\Laterality;
use App\Models\MoreSpecific;
use App\Models\ProjectEntry;

class ProjectController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $projects = Project::query();

        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $projects->where(function ($query) {
                    $query->where('name', request('search'))
                        ->orWhere('client_name', request('search'))
                        ->orWhere('remark', request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $projects->where(function ($query) {
                    $query->where('name', 'like', "%" . request('search'))
                        ->orWhere('client_name', request('search'))
                        ->orWhere('remark', 'like', "%" . request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $projects->where(function ($query) {
                    $query->where('name', 'like', request('search') . '%')
                        ->orWhere('client_name', request('search'))
                        ->orWhere('remark', 'like', request('search') . '%');
                });
            } else {
                if ($request->get('search')) {
                    $projects->where('id', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('client_name', request('search'))
                        ->orWhere('remark', 'like', '%' . $request->search . '%');
                }
            }
        }

        if ($request->get('from') && $request->get('to')) {
            $projects->whereBetween('created_at', [\Carbon\carbon::parse($request->from)->format('Y-m-d'), \Carbon\Carbon::parse($request->to)->format('Y-m-d')]);
        }

        if ($request->get('asc')) {
            $projects->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $projects->orderBy($request->get('desc'), 'desc');
        }
        $projects = $projects->paginate($length);

        if ($request->ajax()) {
            return view('admin.projects.load', ['projects' => $projects])->render();
        }

        return view('admin.projects.index', compact('projects'));
    }

    public function print(Request $request)
    {
        // return $request->all();
        $projects_arr = collect($request->records['data'])->pluck('id');
        $projects = Project::whereIn('id', $projects_arr)->get();
        return view('admin.projects.print', ['projects' => $projects])->render();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.projects.create');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'     => 'required',
            'remark'     => 'required',
            // 'client_name'     => 'required',

        ]);

        try {
            $project = Project::create($request->all());
            return redirect()->route('admin.projects.index')->with('success', 'Projects Created Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        try {
            if (!is_numeric($project->id)) {
                $id = secureToken($project->id, 'decrypt');
            }
            $project = Project::whereId($project->id)->firstOrFail();
            $label = 'Project';
            $stats['excludesCount'] = Exclude::where('project_id', $project->id)->count();
            $stats['lateralityCount'] = Laterality::where('project_id', $project->id)->count();
            $stats['criticalDiagnosisCount'] = CriticalDiagnosis::where('project_id', $project->id)->count();
            $stats['moreSpecificCount'] = MoreSpecific::where('project_id', $project->id)->count();
            $stats['combinationCodeCount'] = CombinationCode::where('project_id', $project->id)->count();
            $stats['projectEnteriesCount'] = ProjectEntry::where('project_id', $project->id)->count();
            $stats['genderCount'] = Gender::where('project_id', $project->id)->count();
            return view('admin.projects.show', compact('project', 'label', 'stats'));
            // return view('admin.projects.show',compact('project'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        try {
            // return $project;

            return view('admin.projects.edit', compact('project'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {

        $this->validate($request, [
            'name'     => 'required',
            'remark'     => 'required',

        ]);

        try {

            if ($project) {

                $chk = $project->update($request->all());

                return redirect()->route('admin.projects.index')->with('success', 'Record Updated!');
            }
            return back()->with('error', 'Projects not found')->withInput($request->all());
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        try {
            if ($project) {
                if (($project->excludes->count() > 0) || ($project->lateralities->count() > 0) || ($project->cricitalDiagnosis->count() > 0) || ($project->moreSpecific->count() > 0) || ($project->projectEntry->count() > 0) || ($project->gender->count() > 0) || ($project->combinationCodes->count() > 0)) {
                    return back()->with('error', 'Project can not be deleted because its associated with other resource');
                }
                // $chk = Exclude::where('project_id', $project->id)->first();
                // if($chk){
                //     return back()->with('error', 'This item Can nopt be delete bjhfgjsfjwfwjh');
                // }
                $project->delete();
                return back()->with('success', 'Project deleted successfully');
            } else {
                return back()->with('error', 'Project not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request, Project $project)
    {
        // return $request->all();
        try {
            $ids = explode(',', $request->ids);
            foreach ($ids as $id) {
                if ($id != null) {

                    Project::where('id', $id)->delete();
                }
            }
            if ($ids == [""]) {
                return back()->with('error', 'There were no rows selected by you!');
            } else {
                return back()->with('success', 'Project Deleted Successfully!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function clearBulkAction(Request $request, Project $project)
    {
        try {
            if ($request->final_quote == 'delete permanently') {
                // CodeBookmark::whereType('Supporting Diagnosis')->delete();
                Project::whereNotNull('id')->delete();
                return back()->with('success', 'All Record Deleted Successfully!');
            } else {
                return back()->with('error', 'Incorrect input. Please type "delete permanently" to confirm!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkUpload(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required'
        ]);
        $count = 0;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            $rows[] = $cells;
        }
        $head = array_shift($rows);
        $master = $rows;
        // Index
        $Title = 0;
        $clientName = 1;
        $Remark = 2;


        $projects_obj = null;

        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;

            if ((trim($item[$Title]) != null || trim($item[$Title]) != '')) {
                $projects_obj = Project::create([
                    'name' => $item[$Title],
                    'client_name' => $item[$clientName],
                    'remark' => $item[$Remark],


                ]);
            }
        }

        return back()->with('success', 'Record Created Successfully!');
    }
    public function exportEntries($id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt'); 
        }
        $project = Project::findOrFail($id);
        return Excel::download(new ProjectExport($project), $project->name . '-' . time() . '.xlsx');
    }
}
