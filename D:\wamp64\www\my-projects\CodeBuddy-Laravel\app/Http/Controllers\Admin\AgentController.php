<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Agent;
use Exception;

class AgentController extends Controller
{
    
    /**start-hqai-m-index**/
    public function index(Request $request)
    {
        $length = 32;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $agents = Agent::query();
         
        if ($request->get('search')) {
            $agents->where('id', 'like', '%'.$request->search.'%')
                ->orWhere('name', 'like', '%'.$request->search.'%')
                ->orWhere('bio', 'like', '%'.$request->search.'%')
                ->orWhere('gpt_code', 'like', '%'.$request->search.'%')
                ->orWhereHas(
                    'department',
                    function ($q) use ($request) {
                        $q->where('name', 'like', '%'.$request->search.'%');
                    }
                )
                ->orWhereHas(
                    'designation',
                    function ($q) use ($request) {
                        $q->where('name', 'like', '%'.$request->search.'%');
                    }
                );
        }
        if ($request->get('from') && $request->get('to')) {
            $agents->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->has('status') && $request->get('status') != null) {
            $agents->where('status', $request->get('status'));
        }
        if ($request->has('model_id') && $request->get('model_id') != null) {
            $agents->where('model_id', $request->get('model_id'));
        }

        $agents = $agents->latest()->paginate($length);

        if ($request->ajax()) {
            return view('panel.admin.agents.load', ['agents' => $agents])->render();
        }
 
        return view('panel.admin.agents.index', compact('agents'));
    }
    /**end-hqai-m-index**/
      
    /**start-hqai-m-print**/
    public function print(Request $request)
    {
        $length = @$request->limit ?? 5000;
        $print_mode = true;
        $items_arr = collect($request->records['data'])->pluck('id');
        $items = Item::whereIn('id', $items_arr)->latest()->get();
        return view('panel.admin.agents.print', compact('items', 'print_mode'))->render();
    }
    /**end-hqai-m-print**/
    
    public function create()
    {
        try {
            $scope_categories = getCategoriesByCode('ScopeCategories');
            $handle_types = Agent::HANDLE_TYPES;
            return view('panel.admin.agents.create',compact('scope_categories','handle_types'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    
     /**start-hqai-m-store**/
    public function store(AgentRequest $request)
    {
            $this->validate(
                $request,
                [
                'name'     => 'required',
                ]
            );
            
        // try {
            //check if gpt_code is entered and match in AI
            if ($request->gpt_code) {
                $assistantDetails = $this->findAssistant($request->gpt_code);
                $responseArray = json_decode($assistantDetails, true);
                if (isset($responseArray['error'])) {
                    return response()->json(
                        [
                            'status'=>'error',
                            'message' => 'Error',
                            'title' => $responseArray['error']['message']
                        ]
                    );
                }
            }
            $agent = Agent::create($request->all());
            if ($request->hasFile('avatar')) {
                if ($agent->avatar != null) {
                    unlinkFile(storage_path().'/app/public/backend/agents/', $agent->avatar);
                }
                $image = $request->file('avatar');
                $extension = $image->getClientOriginalExtension();
                $imageName = 'profile_image_' . $agent->id . '_' . uniqid() . '.' . $extension;
                $destinationPath = storage_path('app/public/backend/agents/') . $imageName;
                $image->storeAs('public/backend/agents', $imageName);
                $resizedImagePath = $destinationPath; // Use the destination path as the resized image path
                $this->resizeImage($resizedImagePath, 300, 200);
                $agent->avatar = $imageName;
                $agent->save();
            }

            if (!$request->gpt_code) {
                $model = Category::find($request->model_id);
                $addResponse = $this->addAssistant($agent->name, generateAgentPrompt($agent), $model->name);
                $addresponseArray = json_decode($addResponse, true);
                if (isset($addresponseArray['error'])) {
                    $agent->delete();
                    return response()->json(
                        [
                            'status'=>'error',
                            'message' => 'Error',
                            'title' => $addresponseArray['error']['message']
                        ]
                    );
                }
                $agent->gpt_code = $addresponseArray['id'];
                $agent->save();
            }

            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Agent created successfully!'
                    ]
                );
            }
            return redirect()->route('panel.admin.agent.index')->with('success', 'Agent created successfully.');
        // } catch (Exception $e) {
        //     return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        // }
    }
    /**end-hqai-m-store**/

   
    public function show($id)
    {
        try {
            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }
            $data['item'] = Agent::find($id);
            $data['user'] = auth()->user();
            return view('panel.admin.agents.show', $data);
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**start-hqai-m-edit**/
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $agent =Agent::whereId($id)->firstOrFail();
            $scope_categories = getCategoriesByCode('ScopeCategories');
            $handle_types = Agent::HANDLE_TYPES;
            $scope_access_categories = Category::where('parent_id', $agent->scope_id)->select('id','name')->get();
            
            return view('panel.admin.agents.edit', compact('agent','scope_categories','handle_types','scope_access_categories'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    /**end-hqai-m-edit**/


    /**start-hqai-m-update**/
    public function update(Request $request, $id)
    {
        $agent = Agent::find($id);
        $this->validate(
            $request,
            [
                'name'     => 'required',
                'meta'     => 'nullable',
            ]
        );
        try {
            if ($agent) {
                //check if gpt_code is entered and match in AI
                if ($request->gpt_code != null) {
                    $assistantDetails = $this->findAssistant($request->gpt_code);
                    $responseArray = json_decode($assistantDetails, true);
                    if (isset($responseArray['error'])) {
                        return response()->json(
                            [
                                'status'=>'error',
                                'message' => 'Error',
                                'title' => $responseArray['error']['message']
                            ]
                        );
                    }
                    $chk = $agent->update($request->all());
                    if ($request->hasFile('avatar')) {
                        if ($agent->avatar != null) {
                            unlinkFile(storage_path().'/app/public/backend/agents/', $agent->avatar);
                        }
                        $image = $request->file('avatar');
                        $extension = $image->getClientOriginalExtension();
                        $imageName = 'profile_image_' . $agent->id . '_' . uniqid() . '.' . $extension;
                        $destinationPath = storage_path('app/public/backend/agents/') . $imageName;
                        $image->storeAs('public/backend/agents', $imageName);
                        $resizedImagePath = $destinationPath; // Use the destination path as the resized image path
                        $this->resizeImage($resizedImagePath, 300, 200);
                        $agent->avatar = $imageName;
                        $agent->save();
                    }
                  
                    if (request()->ajax()) {
                        return response()->json(
                            [
                                'status'=>'success',
                                'message' => 'Success',
                                'title' => 'Agent updated successfully!'
                            ]
                        );
                    }
                } else {
                    if ($request->hasFile('avatar')) {
                        if ($agent->avatar != null) {
                            unlinkFile(storage_path().'/app/public/backend/agents/', $agent->avatar);
                        }
                        $image = $request->file('avatar');
                        $extension = $image->getClientOriginalExtension();
                        $imageName = 'profile_image_' . $agent->id . '_' . uniqid() . '.' . $extension;
                        $destinationPath = storage_path('app/public/backend/agents/') . $imageName;
                        $image->storeAs('public/backend/agents', $imageName);
                        $resizedImagePath = $destinationPath; // Use the destination path as the resized image path
                        $this->resizeImage($resizedImagePath, 300, 200);
                        $agent->avatar = $imageName;
                        $agent->save();
                    }
                    if (!$request->gpt_code) {
                        $model = Category::find($request->model_id);
                        $addResponse = $this->addAssistant($agent->name, generateAgentPrompt($agent), $model->name);
                        $addresponseArray = json_decode($addResponse, true);
                        $agent->gpt_code = $addresponseArray['id'];
                        $agent->save();
                    }

                    //Update Agent in OPEN AI
                    $model = Category::find($agent->model_id);
                    $updateResponse = $this->updateAssistant($agent->gpt_code, $agent->name, generateAgentPrompt($agent), $model->name);

                    if (request()->ajax()) {
                        return response()->json(
                            [
                                'status'=>'success',
                                'message' => 'Success',
                                'title' => 'Agent created successfully!'
                            ]
                        );
                    }
                }
            }
            return redirect()->route('panel.admin.agents.index')->with('success', 'Agent updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }
    /**end-hqai-m-update**/
   
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $agent =Agent::whereId($id)->firstOrFail();
            if ($agent) {
                $agent->delete();
                return back()->with('success', 'Agent deleted successfully');
            } else {
                return back()->with('error', 'agent not found');
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
                    Agent::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                case ('columnUpdate'):
                    Agent::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
    
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('status'):
                            $html['badge_color'] = $request->value != 0 ? "success" : "danger";
                            $html['badge_label'] = $request->value != 0 ? "Active" : "Inactive";
    
                            $title = "Updated ".count($request->ids)." records successfully!";
                            break;
                        default:
                            $type = "error";
                            $title = 'No action selected!';
                    }
                    
                    break;
                case ('platformUpdate'):
                    $agents = Agent::whereIn('id', $request->ids)->get();
        
                    foreach ($agents as $agent) {
                        $model = Category::find($agent->model_id);
                        $updateResponse = $this->updateAssistant($agent->gpt_code, $agent->name, generateAgentPrompt($agent), $model->name);
                    }
                    $title = "Updated ".count($request->ids)." records successfully!";
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
        
            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }

    public function clone(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $find_agent =Agent::whereId($id)->first();
            $new_agent = $find_agent->replicate();
            $new_agent->name = 'Copy of - '.$new_agent->name;
            $new_agent->gpt_code = null;
            $new_agent->save();
            $agent = $new_agent;

            $model = Category::find($agent->model_id);
            $addResponse = $this->addAssistant($agent->name, generateAgentPrompt($agent), $model->name);
            $addresponseArray = json_decode($addResponse, true);
            $agent->gpt_code = $addresponseArray['id'];
            $agent->save();

            return redirect()->route('panel.admin.agents.edit', $agent->id)->with('success', 'Agent cloned successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

}
