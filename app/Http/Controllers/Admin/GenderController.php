<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Gender;
use App\Models\Project;

class GenderController extends Controller
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
        $genders = Gender::query();
        if (request()->has('project_id') && request()->get('project_id')) {
            $genders->where('project_id', request()->get('project_id'));
        }

        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $genders->where(function ($query) {
                    $query->where('name', request('search'))
                    ->orWhere('code', 'like', '%' . request('search') . '%');
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $genders->where(function ($query) {
                    $query->where('name', 'like', "%" . request('search'))
                    ->orWhere('code', 'like', '%' . request('search') . '%'); 
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $genders->where(function ($query) {
                    $query->where('name', 'like', "%" . request('search'))
                    ->orWhere('code', 'like', '%' . request('search') . '%');
                });
            } else {
                if ($request->get('search')) {
                    $genders->where('id', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('code', 'like', '%' . $request->search . '%');
                }
            }
        }

        if ($request->get('from') && $request->get('to')) {
            $genders->whereBetween('created_at', [\Carbon\carbon::parse($request->from)->format('Y-m-d'), \Carbon\Carbon::parse($request->to)->format('Y-m-d')]);
        }

        if ($request->get('asc')) {
            $genders->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $genders->orderBy($request->get('desc'), 'desc');
        }
        $projectName = Project::where('id', request()->get('project_id'))->first();
        $genders = $genders->latest()->paginate($length);

        if ($request->ajax()) {
            return view('admin.genders.load', ['genders' => $genders])->render();
        }

        return view('admin.genders.index', compact('genders','projectName'));
    }


    // public function print(Request $request)
    // {
    //     $genders = collect($request->records['data']);
    //     return view('admin.genders.print', ['genders' => $genders])->render();
    // }
    public function print(Request $request){
            
        $gender_arr = collect($request->records['data'])->pluck('id');
        $genders = Gender::whereIn('id', $gender_arr)->get();
            return view('admin.genders.print', ['genders' => $genders])->render();  
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.genders.create');
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
            'name'  => 'required',
            

        ]);

        try {
            $gender = Gender::create($request->all());
            return redirect()->route('admin.genders.index',['project_id'=> $request->project_id])->with('success', ' Gender Created Successfully!');
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
    public function show(Gender $gender)
    {
        try {
            return view('admin.genders.show', compact('genders'));
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
    public function edit(Gender $gender)
    {
        try {
            // return $gender;

            return view('admin.genders.edit', compact('gender'));
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
    public function update(Request $request, Gender $gender)
    {

        $this->validate($request, [
            'name'  => 'required',
          

        ]);

        try {

            if ($gender) {

                $chk = $gender->update($request->all());

                return redirect()->route('admin.genders.index',['project_id'=> $request->project_id])->with('success', 'Record Updated!');
            }
            return back()->with('error', ' Gender not found')->withInput($request->all());
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
    public function destroy(Gender $gender)
    {
        try {
            if ($gender) {

                $gender->delete();
                return back()->with('success', ' Gender deleted successfully');
            } else {
                return back()->with('error', ' Gender not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request, Gender $gender)
    {
        // return $request->all();
        try {
            $ids = explode(',', $request->ids);
            foreach ($ids as $id) {
                if ($id != null) {

                    Gender::where('id', $id)->delete();
                }
            }
            if ($ids == [""]) {
                return back()->with('error', 'There were no rows selected by you!');
            } else {
                return back()->with('success', ' Gender Deleted Successfully!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function clearBulkAction(Request $request, Gender $gender)
    {
        try {
            if ($request->final_quote == 'delete permanently') {

                Gender::whereNotNull('id')->delete();
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
        $Name = 0;
        $Code=1;


        $genders_obj = null;

        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;

            if ((trim($item[$Name]) != null || trim($item[$Name]) != '')) {
                $genders_obj = Gender::create([
                    'name' => $item[$Name],
                    'code' => $item[$Code],
                    'project_id' => $request->project_id?? null,
                  


                ]);
            }
        }

        return back()->with('success', 'Record Created Successfully!');
    }
}
