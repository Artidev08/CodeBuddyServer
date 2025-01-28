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
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use App\Models\SliderType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    protected $path;
    public $label;
    public function __construct()
    {
        $this->path =   storage_path() . "/app/public/backend/slider/";
        $this->label = 'Sliders';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $sliders = Slider::query();
        if ($request->get('search')) {
            $sliders->where('title', 'like', '%'.$request->get('search').'%');
        }
        $sliders= $sliders->where('slider_type_id', request()->get('sliderTypeId'))->paginate($length);
        if ($request->ajax()) {
            return view('admin.sliders.load', ['sliders' => $sliders])->render();
        }
        $sliderType = SliderType::where('id', request()->get('sliderTypeId'))->first();
        $label = $this->label;
        return view('admin.sliders.index', compact('sliders', 'sliderType', 'label'));
    }

    public function print(Request $request)
    {
        $sliders_arr = collect($request->records['data'])->pluck('id');
        $sliders = Slider::whereIn('id', $sliders_arr)->get();
        return view('admin.sliders.print', ['sliders' => $sliders])->render();
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
            $sliderType = SliderType::where('id', request()->get('sliderTypeId'))->first();
            return view('admin.sliders.create', compact('label', 'sliderType'));
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
    public function store(Slider $slider, Request $request)
    {
        try {
            if (!$request->has('status')) {
                $request['status'] = 0;
            }
            $slider = Slider::create($request->all());

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $slider->addMediaFromRequest('image')->toMediaCollection('image');
            }
                return response()->json(
                    [
                        'sliderId' => $slider->id,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Slider Created Successfully'
                    ]
                );
            return redirect()->route('admin.sliders.edit', ['sliderTypeId' => $request->slider_type_id])->with('success', 'Slider Created Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        try {
            return view('admin.sliders.show', compact('slider'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }

            $slider = Slider::whereId($id)->firstOrFail();
            $sliderType = SliderType::where('id', $slider->slider_type_id)->first();
            return view('admin.sliders.edit', compact('slider', 'sliderType'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Slider       $slider
     * @return \Illuminate\Http\Response
     */
    public function update(SliderRequest $request, Slider $slider)
    {
        // return request()->hasFile('image');
        try {
            if ($slider) {
                if (!$request->status) {
                    $slider['status']  = 0;
                } else {
                    $slider['status']  = 1;
                }
                
                if (request()->hasFile('image') && request()->file('image')->isValid()) {
                    if ($slider->getMedia('image')->count()) {
                        $slider->clearMediaCollection('image');
                    }
                    $slider->addMediaFromRequest('image')->toMediaCollection('image');
                }
                $slider->getFirstMediaUrl('image');
                $chk = $slider->update($request->all());
                return response()->json(
                    [
                        'sliderTypeId' => $request->slider_type_id,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Record Updated!'
                    ]
                );
                return redirect()->route('admin.sliders.index', ['sliderTypeId' => $request->slider_type_id])->with('success', 'Record Updated!');
            }
            return back()->with('error', 'Slider Type not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        try {
            if ($slider) {
                $slider->delete();
                return back()->with('success', 'Record Deleted!');
            }
            return back()->with('error', 'Slider not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Slider $slider, Request $request)
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
                    Slider::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
                //Column Update
                case ('columnUpdate'):
                    Slider::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
    
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('status'):
                            $html['badge_color'] = $request->value != 0 ? "success" : "danger";
                            $html['badge_label'] = $request->value != 0 ? "Published" : "Unpublished";
    
                            $title = "Updated ".count($request->ids)." records successfully!";
                            break;
                        default:
                            $type = "error";
                            $title = 'No action selected!';
                    }
                    
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
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
    public function destroyMedia(Slider $slider, Request $request)
    {
        try {
            if ($slider) {
                if ($slider->getMedia($request->media)->count()) {
                    $slider->clearMediaCollection($request->media);
                }
                return back()->with('success', 'Media deleted successfully');
            } else {
                return back()->with('error', 'Media not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
