<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MedicalCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\CodeBookmark;
use App\Models\MedicalConditionAbbreviation;

class MedConditionController extends Controller
{
    private $medicalCondition;

    public function index(Request $request)
    {

        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $med_conditions = MedicalCondition::query();
        $search_keywords = explode(' ', request('search'));
      
        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $med_conditions->where(function ($query) {
                    $query->where('title', request('search'))
                        ->orWhere('code', request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $med_conditions->where(function ($query) {
                    $query->where('title', 'like', "%" . request('search'))
                        ->orWhere('code', 'like', "%" . request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $med_conditions->where(function ($query) {
                    $query->where('title', 'like', request('search') . '%')
                        ->orWhere('code', 'like', request('search') . '%');
                });
            } else {
                if (count($search_keywords) == 1) {
                    $keyword = $search_keywords[0];
                    $med_conditions->where(function ($query) use ($keyword) {
                        $query->where('title', 'like', '%' . $keyword . '%')
                            ->orWhere('code', 'like', '%' . $keyword . '%');
                    });
                } else {

                    foreach ($search_keywords as $index => $term) {
                        if ($index == 0) {
                            $med_conditions->where('title', 'LIKE', "%" . $term . "%")
                                ->orWhere('code', 'LIKE', "%" . $term . "%");
                        } else {
                            $med_conditions->orWhere('title', 'LIKE', "%" . $term . "%");
                            $med_conditions->orWhere('code', 'LIKE', "%" . $term . "%");
                            $med_conditions->orWhere('code', 'LIKE', "%" . $term);
                            $med_conditions->orWhere('code', 'LIKE', $term . "%");
                        }
                    }
                }
            }
        }
        $med_conditions = $med_conditions->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.medical-conditions.load', ['med_conditions' => $med_conditions])->render();
        }
        return view('admin.medical-conditions.index', compact('med_conditions'));
    }

    public function print(Request $request){
        // return $request->all();
        $med_conditions_arr = collect($request->records['data'])->pluck('id');
        $med_conditions = MedicalCondition::whereIn('id', $med_conditions_arr)->get();
            return view('admin.medical-conditions.print', ['med_conditions' => $med_conditions])->render();  
       
    }

    public function show(MedicalCondition $medicalCondition)
    {
        return view('admin.medical-conditions.show', compact('medicalCondition'));
    }

    public function create()
    {
        return view('admin.medical-conditions.create');
    }

    public function store(Request $request)
    {
        $details = [
            'classification' => $request->classification,
        ];

        $existingRecord = MedicalCondition::where('title', $request->title)
                ->exists();
        if ($existingRecord) {
            return back()->with('error', 'There is already a record with this title, and it should be unique!');
        } else {
            $this->medicalCondition = MedicalCondition::create([
                'title' => $request->get('title'),
                'details' => json_encode($details),
                'search_title' => Str::lower($request->get('title')),
                'code' => $request->get('code'),
                'hcc' => [
                    'rx' => $request->get('hcc-rx'),
                    'cms' => $request->get('hcc-cms'),
                    'esrd' => $request->get('hcc-esrd'),
                ],

            ]);
        }

 

        return $this->redirectSuccess(route("admin.medical-conditions.index"), 'Medical condition record added!');
    }

    public function edit(MedicalCondition $medicalCondition)
    {
        $this->medicalCondition = $medicalCondition;
        $abbreviations = MedicalConditionAbbreviation::where('medical_condition_id',$medicalCondition->id)->get();
        return view('admin.medical-conditions.edit', compact('medicalCondition','abbreviations'));
    }


    public function update(MedicalCondition $medicalCondition, Request $request)
    {
        $existingRecord = MedicalCondition::where('id','!=',$medicalCondition->id)->where('title', $request->title)
                ->exists();
        if($existingRecord){
            return back()->with('error', 'There is already a record with this title, and it should be unique!');
        }
        $details = [
            'classification' => $request->classification,
        ];
        $this->medicalCondition = $medicalCondition;
        $medicalCondition->update([
            'title' => $request->get('title'),
            'details' => $details,
            'search_title' => Str::lower($request->get('title')),
            'code' => $request->get('code'),
            'hcc' => [
                'rx' => $request->get('hcc-rx'),
                'cms' => $request->get('hcc-cms'),
                'esrd' => $request->get('hcc-esrd'),
            ],
        ]);

        return $this->redirectSuccess(route("admin.medical-conditions.index"), 'Medical condition Updated!');
    }


    public function destroy(MedicalCondition $medicalCondition)
    {
        try {
            $medicalCondition = MedicalCondition::find($medicalCondition->id);
            if ($medicalCondition) {
                if ($medicalCondition->versions->count() > 0){
                    return back()->with('error', 'Medical Condition can not be deleted because its associated with other resource');
                } 

                $medicalCondition->delete();
                return back()->with('success', 'Medical Condition deleted successfully');
            } else {
                return back()->with('error', 'Medical Condition not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function mediaBulkAction(Request $request, MedicalCondition $medicalCondition)
    {
        // return $request->all();
        $ids = explode(',', $request->ids);
        foreach ($ids as $id) {
            if ($id != null) {
                if ($request->get('action') == 'publish') {
                    MedicalCondition::where('id', $id)->update(['is_published' => 1]);
                } else if ($request->get('action') == 'unpublish') {
                    MedicalCondition::where('id', $id)->update(['is_published' => 0]);
                } else {
                    MedicalCondition::where('id', $id)->delete();
                }
            }
        }
        if ($ids == [""]) {
            return back()->with('error', 'There were no rows selected by you!');
        } else {
            if ($request->get('action') == 'publish') {
                return back()->with('success', 'Medical condition Published Successfully!');
            } else if ($request->get('action') == 'unpublish') {
                return back()->with('success', 'Medical condition Unpublished Successfully!');
            } else {
                return back()->with('success', 'Medical condition Deleted Successfully!');
            }
        }
    }
   

    public function clearBulkAction(Request $request)
    {
        try {
            if ($request->final_quote == 'delete permanently') {
                CodeBookmark::whereType('Medical Condition')->delete();
                MedicalCondition::whereNotNull('id')->delete();
                return back()->with('success', 'All Record Deleted Successfully!');
            } else {
                return back()->with('error', 'Incorrect input. Please type "delete permanently" to confirm!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
