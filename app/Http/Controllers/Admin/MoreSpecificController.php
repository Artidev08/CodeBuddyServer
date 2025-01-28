<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\MoreSpecific;
use App\Models\Project;

class MoreSpecificController extends Controller
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
        $more_specifics = MoreSpecific::query();
        if (request()->has('project_id') && request()->get('project_id')) {
            $more_specifics->where('project_id', request()->get('project_id'));
        }

        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $more_specifics->where(function ($query) {
                    $query->where('code', request('search'))
                        ->orWhere('description', request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $more_specifics->where(function ($query) {
                    $query->where('code', 'like', "%" . request('search'))
                        ->orWhere('description', 'like', "%" . request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $more_specifics->where(function ($query) {
                    $query->where('code', 'like', "%" . request('search'))
                        ->orWhere('description', 'like', "%" . request('search'));
                });
            } else {
                if ($request->get('search')) {
                    $more_specifics->where('id', 'like', '%' . $request->search . '%')
                        ->orWhere('code', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                }
            }
        }

        if ($request->get('from') && $request->get('to')) {
            $more_specifics->whereBetween('created_at', [\Carbon\carbon::parse($request->from)->format('Y-m-d'), \Carbon\Carbon::parse($request->to)->format('Y-m-d')]);
        }

        if ($request->get('asc')) {
            $more_specifics->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $more_specifics->orderBy($request->get('desc'), 'desc');
        }
        $projectName = Project::where('id', request()->get('project_id'))->first();
        $more_specifics = $more_specifics->latest()->paginate($length);

        if ($request->ajax()) {
            return view('admin.more_specifics.load', ['more_specifics' => $more_specifics])->render();
        }

        return view('admin.more_specifics.index', compact('more_specifics','projectName'));
    }

    public function print(Request $request)
    {
        // return $request->all();
        $more_specifics_arr = collect($request->records['data'])->pluck('id');
        $more_specifics = MoreSpecific::whereIn('id', $more_specifics_arr)->get();
        return view('admin.more_specifics.print', ['more_specifics' => $more_specifics])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.more_specifics.create');
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
            $more_specific = MoreSpecific::create($request->all());
            return redirect()->route('admin.more-specifics.index',['project_id'=> $request->project_id])->with('success', ' More Specific Created Successfully!');
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
    public function show(MoreSpecific $more_specific)
    {
        try {
            return view('admin.more-specifics.show', compact('more_specifics'));
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
    public function edit(MoreSpecific $more_specific)
    {
        try {
            // return $more_specific;

            return view('admin.more_specifics.edit', compact('more_specific'));
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
    public function update(Request $request, MoreSpecific $more_specific)
    {

        $this->validate($request, [
            'code'    => 'required',
            'description'  => 'required',

        ]);

        try {

            if ($more_specific) {

                $chk = $more_specific->update($request->all());

                return redirect()->route('admin.more-specifics.index',['project_id'=> $request->project_id])->with('success', 'Record Updated!');
            }
            return back()->with('error', ' More Specific not found')->withInput($request->all());
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
    public function destroy(MoreSpecific $more_specific)
    {
        try {
            if ($more_specific) {

                $more_specific->delete();
                return back()->with('success', ' More Specific deleted successfully');
            } else {
                return back()->with('error', ' More Specific not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request, MoreSpecific $more_specific)
    {
        // return $request->all();
        try {
            $ids = explode(',', $request->ids);
            foreach ($ids as $id) {
                if ($id != null) {

                    MoreSpecific::where('id', $id)->delete();
                }
            }
            if ($ids == [""]) {
                return back()->with('error', 'There were no rows selected by you!');
            } else {
                return back()->with('success', ' More Specific Deleted Successfully!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function clearBulkAction(Request $request, MoreSpecific $more_specific)
    {
        try {
            if ($request->final_quote == 'delete permanently') {

                MoreSpecific::whereNotNull('id')->delete();
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


        $more_specifics_obj = null;

        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;

            if ((trim($item[$Code]) != null || trim($item[$Code]) != '')) {
                $more_specifics_obj = MoreSpecific::create([
                    'code' => $item[$Code],
                    'description' => $item[$Description],
                    'project_id' => $request->project_id?? null,

                ]);
            }
        }

        return back()->with('success', 'Record Created Successfully!');
    }
}
