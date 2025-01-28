<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Laterality;
use App\Models\Project;

class LateralityController extends Controller
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
        $lateralitys = Laterality::query();
            if (request()->has('project_id') && request()->get('project_id')) {
                $lateralitys->where('project_id', request()->get('project_id'));
            }

        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $lateralitys->where(function ($query) {
                    $query->where('code', request('search'))
                        ->orWhere('description', request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $lateralitys->where(function ($query) {
                    $query->where('code', 'like', "%" . request('search'))
                        ->orWhere('description', 'like', "%" . request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $lateralitys->where(function ($query) {
                    $query->where('code', 'like', "%" . request('search'))
                        ->orWhere('description', 'like', "%" . request('search'));
                });
            } else {
                if ($request->get('search')) {
                    $lateralitys->where('id', 'like', '%' . $request->search . '%')
                        ->orWhere('code', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                }
            }
        }

        if ($request->get('from') && $request->get('to')) {
            $lateralitys->whereBetween('created_at', [\Carbon\carbon::parse($request->from)->format('Y-m-d'), \Carbon\Carbon::parse($request->to)->format('Y-m-d')]);
        }

        if ($request->get('asc')) {
            $lateralitys->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $lateralitys->orderBy($request->get('desc'), 'desc');
        }
        $projectName = Project::where('id', request()->get('project_id'))->first();
        $lateralitys = $lateralitys->latest()->paginate($length);

        if ($request->ajax()) {
            return view('admin.lateralitys.load', ['lateralitys' => $lateralitys])->render();
        }

        return view('admin.lateralitys.index', compact('lateralitys','projectName'));
    }
    
    public function print(Request $request){
            
        $lateralitys_arr = collect($request->records['data'])->pluck('id');
        $lateralitys = Laterality::whereIn('id', $lateralitys_arr)->get();
            return view('admin.lateralitys.print', ['lateralitys' => $lateralitys])->render();  
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.lateralitys.create');
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
            $laterality = Laterality::create($request->all());
            return redirect()->route('admin.lateralitys.index',['project_id'=> $request->project_id])->with('success', 'Laterality Created Successfully!');
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
    public function show(Laterality $laterality)
    {
        try {
            return view('admin.lateralitys.show', compact('lateralitys'));
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
    public function edit(Laterality $laterality)
    {
        try {
            // return $laterality;

            return view('admin.lateralitys.edit', compact('laterality'));
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
    public function update(Request $request, Laterality $laterality)
    {

        $this->validate($request, [
            'code'    => 'required',
            'description'  => 'required',

        ]);

        try {

            if ($laterality) {

                $chk = $laterality->update($request->all());

                return redirect()->route('admin.lateralitys.index',['project_id'=> $request->project_id])->with('success', 'Record Updated!');
            }
            return back()->with('error', 'Laterality not found')->withInput($request->all());
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
    public function destroy(Laterality $laterality)
    {
        try {
            if ($laterality) {

                $laterality->delete();
                return back()->with('success', 'Laterality deleted successfully');
            } else {
                return back()->with('error', 'Laterality not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request, Laterality $laterality)
    {
        // return $request->all();
        try {
            $ids = explode(',', $request->ids);
            foreach ($ids as $id) {
                if ($id != null) {

                    Laterality::where('id', $id)->delete();
                }
            }
            if ($ids == [""]) {
                return back()->with('error', 'There were no rows selected by you!');
            } else {
                return back()->with('success', 'Laterality Deleted Successfully!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function clearBulkAction(Request $request, Laterality $laterality)
    {
        try {
            if ($request->final_quote == 'delete permanently') {
              
                Laterality::whereNotNull('id')->delete();
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


        $laterality_obj = null;

        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;

            if ((trim($item[$Code]) != null || trim($item[$Code]) != '')) {
                $laterality_obj = Laterality::create([
                    'code' => $item[$Code],
                    'description' => $item[$Description],
                    'project_id' => $request->project_id?? null,


                ]);
            }
        }

        return back()->with('success', 'Record Created Successfully!');
    }
}
