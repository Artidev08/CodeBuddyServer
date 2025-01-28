<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\CriticalDiagnosis;
use App\Models\Project;

class CriticalDiagnosisController extends Controller
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
        $cricital_diagnosiss = CriticalDiagnosis::query();
        if (request()->has('project_id') && request()->get('project_id')) {
            $cricital_diagnosiss->where('project_id', request()->get('project_id'));
        }

        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $cricital_diagnosiss->where(function ($query) {
                    $query->where('code', request('search'))
                        ->orWhere('description', request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $cricital_diagnosiss->where(function ($query) {
                    $query->where('code', 'like', "%" . request('search'))
                        ->orWhere('description', 'like', "%" . request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $cricital_diagnosiss->where(function ($query) {
                    $query->where('code', 'like', "%" . request('search'))
                        ->orWhere('description', 'like', "%" . request('search'));
                });
            } else {
                if ($request->get('search')) {
                    $cricital_diagnosiss->where('id', 'like', '%' . $request->search . '%')
                        ->orWhere('code', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                }
            }
        }

        if ($request->get('from') && $request->get('to')) {
            $cricital_diagnosiss->whereBetween('created_at', [\Carbon\carbon::parse($request->from)->format('Y-m-d'), \Carbon\Carbon::parse($request->to)->format('Y-m-d')]);
        }

        if ($request->get('asc')) {
            $cricital_diagnosiss->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $cricital_diagnosiss->orderBy($request->get('desc'), 'desc');
        }
        $projectName = Project::where('id', request()->get('project_id'))->first();
        $cricital_diagnosiss = $cricital_diagnosiss->latest()->paginate($length);

        if ($request->ajax()) {
            return view('admin.cricital_diagnosiss.load', ['cricital_diagnosiss' => $cricital_diagnosiss])->render();
        }

        return view('admin.cricital_diagnosiss.index', compact('cricital_diagnosiss','projectName'));
    }


    public function print(Request $request){
            
        $cricital_diagnosiss_arr = collect($request->records['data'])->pluck('id');
        $cricital_diagnosiss = CriticalDiagnosis::whereIn('id', $cricital_diagnosiss_arr)->get();
            return view('admin.cricital_diagnosiss.print', ['cricital_diagnosiss' => $cricital_diagnosiss])->render();  
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.cricital_diagnosiss.create');
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
            'code'     => 'required',
            'description'     => 'required',

        ]);

        try {
            $cricital_diagnosis = CriticalDiagnosis::create($request->all());
            return redirect()->route('admin.cricital-diagnosis.index',['project_id'=> $request->project_id])->with('success', 'Critical Diagnosis Created Successfully!');
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
    public function show(CriticalDiagnosis $cricital_diagnosis)
    {
        try {
            return view('admin.cricital-diagnosis.show', compact('cricital_diagnosiss'));
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
    public function edit(CriticalDiagnosis $cricital_diagnosis)
    {
        try {
            // return $cricital_diagnosis;

            return view('admin.cricital_diagnosiss.edit', compact('cricital_diagnosis'));
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
    public function update(Request $request, CriticalDiagnosis $cricital_diagnosis)
    {

        $this->validate($request, [
            'code'    => 'required',
            'description'  => 'required',

        ]);

        try {

            if ($cricital_diagnosis) {

                $chk = $cricital_diagnosis->update($request->all());

                return redirect()->route('admin.cricital-diagnosis.index',['project_id'=> $request->project_id])->with('success', 'Record Updated!');
            }
            return back()->with('error', 'Critical Diagnosis not found')->withInput($request->all());
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
    public function destroy(CriticalDiagnosis $cricital_diagnosis)
    {
        try {
            if ($cricital_diagnosis) {

                $cricital_diagnosis->delete();
                return back()->with('success', 'Critical Diagnosis deleted successfully');
            } else {
                return back()->with('error', 'Critical Diagnosis not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request, CriticalDiagnosis $cricital_diagnosis)
    {
        // return $request->all();
        try {
            $ids = explode(',', $request->ids);
            foreach ($ids as $id) {
                if ($id != null) {

                    CriticalDiagnosis::where('id', $id)->delete();
                }
            }
            if ($ids == [""]) {
                return back()->with('error', 'There were no rows selected by you!');
            } else {
                return back()->with('success', 'Critical Diagnosis Deleted Successfully!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function clearBulkAction(Request $request, CriticalDiagnosis $cricital_diagnosis)
    {
        try {
            if ($request->final_quote == 'delete permanently') {
              
                CriticalDiagnosis::whereNotNull('id')->delete();
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
        $Code = 0;
        $Description = 1;


        $cricital_diagnosiss_obj = null;

        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;

            if ((trim($item[$Code]) != null || trim($item[$Code]) != '')) {
                $cricital_diagnosiss_obj = CriticalDiagnosis::create([
                    'code' => $item[$Code],
                    'description' => $item[$Description],


                ]);
            }
        }

        return back()->with('success', 'Record Created Successfully!');
    }
}
