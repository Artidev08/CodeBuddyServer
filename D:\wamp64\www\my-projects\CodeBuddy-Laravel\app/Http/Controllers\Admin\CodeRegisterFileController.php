<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScenarioRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Scenario;
use App\Models\Agent;
use App\Models\CodeRegister;
use App\Models\CodeRegisterFile;

class CodeRegisterFileController extends Controller
{
    public function __construct()
    {
        $this->title = 'Code Register File';
        $this->view = 'panel.admin.code-register-file';
        $this->route = 'panel.admin.code-register-file';
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['agentRecord'] = CodeRegister::find($request->code_register_id);
        $title = isset($data['agentRecord']) ? $data['agentRecord']->title : '';
        $data['title'] = $title.' '.$this->title;
        $data['view'] = $this->view;
        $data['route'] = $this->route;
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $rows = CodeRegisterFile::query();
         
        if ($request->get('search')) {
            $rows->where('id', 'like', '%'.$request->search.'%');
        }
        if ($request->get('from') && $request->get('to')) {
            $rows->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->get('asc')) {
            $rows->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $rows->orderBy($request->get('desc'), 'desc');
        }
        if ($request->get('code_register_id')) {
            $rows->where('code_register_id', $request->get('code_register_id'));
        }
        $data['rows'] = $rows->latest()->paginate($length);

        if ($request->ajax()) {
            return view($this->view.'.load', $data)->render();
        }
 
        return view($this->view.'.index', $data);
    }

  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $data['agentRecord'] = Agent::find($request->code_register_id);
            $title = isset($data['agentRecord']) ? $data['agentRecord']->name : '';
            $data['title'] = $title.' '.$this->title;
            $data['view'] = $this->view;
            $data['route'] = $this->route;
            $data['agents'] = Agent::select('id', 'name')->get();
            return view($this->view.'.create', $data);
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
            $codeRegisterFile = CodeRegisterFile::create($request->all());
                
            if (request()->ajax()) {
                return response()->json(
                    [
                        'id'=>$codeRegisterFile->id,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Code Register File created successfully!'
                    ]
                );
            }
            return redirect()->back()->with('success', 'Code Register File Added successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    
    public function edit(Request $request, $id)
    {
        try {
            $data['title'] = $this->title;
            $data['view'] = $this->view;
            $data['route'] = $this->route;
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $data['item'] =CodeRegisterFile::whereId($id)->firstOrFail();
            $data['agents'] = Agent::select('id', 'name')->get();
            return view($this->view.'.edit', $data);
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $codeRegisterFile = CodeRegisterFile::find($id);
       
        try {
            if ($codeRegisterFile) {
                $chk = $codeRegisterFile->update($request->all());
                
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Code Register File created successfully!'
                        ]
                    );
                }
            }
            return redirect()->route($this->route.'.index')->with('success', 'Code Register File updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $codeRegisterFile =CodeRegisterFile::whereId($id)->firstOrFail();
            if ($codeRegisterFile) {
                $codeRegisterFile->delete();
                return back()->with('success', 'Code Register File deleted successfully');
            } else {
                return back()->with('error', 'Code Register File not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
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
                    CodeRegisterFile::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                
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
        
            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
}
