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
use App\Http\Requests\SliderTypeRequest;
use App\Models\SliderType;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderTypeController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Slider Groups';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $sliderTypes = SliderType::query();
        if ($request->get('asc')) {
            $sliderTypes->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $sliderTypes->orderBy($request->get('desc'), 'desc');
        }
        $sliderTypes= $sliderTypes->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.slider-types.load', ['sliderTypes' => $sliderTypes])->render();
        }
            $label = $this->label;
        return view('admin.slider-types.index', compact('sliderTypes', 'label'));
    }

    public function print(Request $request)
    {
        $sliderTypes_arr = collect($request->records['data'])->pluck('id');
        $sliderTypes = SliderType::whereIn('id', $sliderTypes_arr)->get();
        return view('admin.slider-types.print', ['sliderTypes' => $sliderTypes])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $label = Str::singular($this->label);
            return view('admin.slider-types.create', compact('label'));
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
    public function store(SliderTypeRequest $request)
    {
        try {
            if (!$request['is_published']) {
                $request['is_published'] = 0;
            }
            $sliderType = SliderType::create($request->all());
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Slider Type Created Successfully'
                    ]
                );
            }
            return redirect()->route('admin.slider-types.index')->with('success', 'Slider Type Created Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SliderType $sliderType
     * @return \Illuminate\Http\Response
     */
    public function show(SliderType $sliderType)
    {
        try {
            return view('admin.slider-types.show', compact('sliderType'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SliderType $sliderType
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $sliderType =SliderType::whereId($id)->firstOrFail();
            $label = Str::singular($this->label);
            return view('admin.slider-types.edit', compact('sliderType', 'label'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\SliderType   $sliderType
     * @return \Illuminate\Http\Response
     */
    public function update(SliderTypeRequest $request, SliderType $sliderType)
    {
        try {
            if ($sliderType) {
                if (!$request['is_published']) {
                    $request['is_published'] = 0;
                }
                $chk = $sliderType->update($request->all());
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Record Updated'
                        ]
                    );
                }
                return redirect()->route('admin.slider-types.index')->with('success', 'Record Updated!');
            }
            return back()->with('error', 'Slider Type not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SliderType $sliderType
     * @return \Illuminate\Http\Response
     */
    public function destroy(SliderType $sliderType)
    {
        try {
            if ($sliderType) {
                    Slider::where('slider_type_id', $sliderType->id)->delete();
                    $sliderType->delete();
                    return back()->with('success', 'Record Deleted!');
            }
            return back()->with('error', 'Slider Type not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(SliderType $sliderType, Request $request)
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
                    SliderType::whereIn('id', $request->ids)->delete();
                    Slider::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
                return back()->with('success', 'Slider Type Deleted Successfully!');
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
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
    // public function bulkAction(SliderType $sliderType,Request $request)
    // {
    //     try{
    //         $ids = explode(',',$request->ids);
    //         foreach($ids as $id) {
    //             if($id != null){
    //                 Slider::where('slider_type_id',$id)->delete();
    //                 SliderType::where('id', $id)->delete();
    //             }
    //         }
    //         if($ids == [""]){
    //             return back()->with('error', 'There were no rows selected by you!');
    //         }else{
    //             if(request()->ajax()){
    //                 return response()->json(
    //                     [
    //                         'status'=>'success',
    //                         'message' => 'Success',
    //                         'title' => 'Slider Type Deleted Successfully'
    //                     ]
    //                 );
    //             }
    //             return back()->with('success', 'Slider Type Deleted Successfully!');
    //         }
    //     }catch(Exception $e){
    //         return back()->with('error', 'There was an error: ' . $e->getMessage());
    //     }
    // }
}
