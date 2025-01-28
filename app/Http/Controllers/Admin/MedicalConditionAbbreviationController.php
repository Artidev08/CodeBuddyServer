<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MedicalConditionAbbreviation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\CodeBookmark;

class MedicalConditionAbbreviationController extends Controller
{
    private $medicalCondition;

    public function index(Request $request)
    {

        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $med_conditions = MedicalConditionAbbreviation::query();
        $search_keywords = explode(' ', request('search'));
        

        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $med_conditions->where(function ($query) {
                    $query->where('title', request('search'));
                    
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $med_conditions->where(function ($query) {
                    $query->where('title', 'like', "%" . request('search'));
                     
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $med_conditions->where(function ($query) {
                    $query->where('title', 'like', request('search') . '%');
                       
                });
            } else {
                if (count($search_keywords) == 1) {
                    $keyword = $search_keywords[0];
                    $med_conditions->where(function ($query) use ($keyword) {
                        $query->where('title', 'like', '%' . $keyword . '%');
                         
                    });
                } else {

                    foreach ($search_keywords as $index => $term) {
                        if ($index == 0) {
                            $med_conditions->where('title', 'LIKE', "%" . $term . "%")
                                ->orWhere('code', 'LIKE', "%" . $term . "%");
                        } else {
                            $med_conditions->orWhere('title', 'LIKE', "%" . $term . "%");
                        
                        }
                    }
                }
            }
        }
        $med_conditions = $med_conditions->paginate($length);
        if ($request->ajax()) {
            return view('admin.medical-conditions.load', ['med_conditions' => $med_conditions])->render();
        }
        return view('admin.medical-conditions.index', compact('med_conditions'));
    }

    public function show(MedicalConditionAbbreviation $medicalCondition)
    {
        return view('admin.medical-conditions.show', compact('medicalCondition'));
    }

    public function create()
    {
        return view('admin.medical-conditions.create');
    }

    public function store(Request $request)
    {
        // return $request;
        
 
        $existingRecord = MedicalConditionAbbreviation::where('title', $request->title)
        
         ->exists();
        // if ($existingRecord) {
            
        //     return back()->with('error', 'There is already a record with this title, and it should be unique!');
        // } else {
            // $text = trim($_POST['title']);
            // $textAr = explode("\r\n", $text);
            // $titles = array_filter($textAr, 'trim');
            // foreach($titles as $title){
            //     $existingRecord = MedicalConditionAbbreviation::where('title', $title)
            //     ->exists();
            //     if(!$existingRecord){
            //         $this->medicalCondition = MedicalConditionAbbreviation::create([
            //             'title' => $title,
            //             'medical_condition_id' => $request['medical_condition_id'],
            //         ]);
            //     }
               
            // }
            
        // }
        $text = trim($_POST['title']);
        $textAr = explode("\r\n", $text);
        $titles = array_filter($textAr, 'trim');
        foreach($titles as $title){
            $existingRecord = MedicalConditionAbbreviation::where('medical_condition_id',$request['medical_condition_id'])->where('title', $title)
            ->exists();
            if(!$existingRecord){
                $this->medicalCondition = MedicalConditionAbbreviation::create([
                    'title' => $title,
                    'medical_condition_id' => $request['medical_condition_id'],
                ]);
            }
           
        }

        return back()->with('success','Abbrebiation created successfully') ;
    }

    public function edit(MedicalConditionAbbreviation $medicalCondition)
    {
        $this->medicalCondition = $medicalCondition;
        return view('admin.medical-conditions.edit', compact('medicalCondition'));
    }


    public function update(MedicalConditionAbbreviation $medicalCondition, Request $request)
    {
        $existingRecord = MedicalConditionAbbreviation::where('id', '!=', $medicalCondition->id)->where('title', $request->title);

        $this->medicalCondition = $medicalCondition;
        $medicalCondition->update([
            'title' => $request->get('title'),

            'search_title' => Str::lower($request->get('title')),
            'code' => $request->get('code'),
        ]);


        $this->uploadMedia($request);

        return $this->redirectSuccess(route("admin.medconditions.index"), 'MedicalConditionAbbreviation Updated!');
    }


    private function uploadMedia($request)
    {
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $name = Str::random(64);
                $ext = strtolower($file->getClientOriginalExtension());
                $target = storage_path('app/public/uploads/medical-conditions');
                $fileName = $name . "." . $ext;
                $filePath = "storage/uploads/medical-conditions/" . $fileName;

                $file->move($target, $fileName);

                
            }
        }
    }

    public function destroy(MedicalConditionAbbreviation $med_condition_abbreviation)
    {
        try {
            $med_condition_abbreviation = MedicalConditionAbbreviation::find($med_condition_abbreviation->id);
            if ($med_condition_abbreviation) {
                $med_condition_abbreviation->delete();
                return back()->with('success', 'Abbreviations deleted successfully');
            } else {
                return back()->with('error', 'Abbreviations not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function mediaBulkAction(Request $request, MedicalConditionAbbreviation $medicalCondition)
    {
        // return $request->all();
        $ids = explode(',', $request->ids);
        foreach ($ids as $id) {
            if ($id != null) {
                if ($request->get('action') == 'publish') {
                    MedicalConditionAbbreviation::where('id', $id)->update(['is_published' => 1]);
                } else if ($request->get('action') == 'unpublish') {
                    MedicalConditionAbbreviation::where('id', $id)->update(['is_published' => 0]);
                } else {
                    MedicalConditionAbbreviation::where('id', $id)->delete();
                }
            }
        }
        if ($ids == [""]) {
            return back()->with('error', 'There were no rows selected by you!');
        } else {
            if ($request->get('action') == 'publish') {
                return back()->with('success', 'Abbreviations Published Successfully!');
            } else if ($request->get('action') == 'unpublish') {
                return back()->with('success', 'Abbreviations Unpublished Successfully!');
            } else {
                return back()->with('success', 'Abbreviations Deleted Successfully!');
            }
        }
    }
    public function mediaDestroy(Media $media)
    {
        $filePath = $media->path;
        if ($media->forceDelete()) {
            $fileNameOnly = collect(explode('/', $filePath))->last();
            if (File::exists(storage_path('app/public/uploads/medical-conditions/' . $fileNameOnly))) {
                unlink(storage_path('app/public/uploads/medical-conditions/' . $fileNameOnly));
            }
        }
        return $this->backSuccess('Media deleted!');
    }

    public function clearBulkAction(Request $request)
    {
        try {
            if ($request->final_quote == 'delete permanently') {
                CodeBookmark::whereType('MedicalConditionAbbreviation')->delete();
                MedicalConditionAbbreviation::whereNotNull('id')->delete();
                return back()->with('success', 'All Record Deleted Successfully!');
            } else {
                return back()->with('error', 'Incorrect input. Please type "delete permanently" to confirm!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
