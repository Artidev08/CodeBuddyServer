<?php


namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MedicalCondition;
use App\Models\MedicalConditionAbbreviation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\MedCodeVersion;
use App\Models\ProjectEntry;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Gender;
use App\Models\Laterality;
use App\Models\MoreSpecific;
use App\Models\CombinationCode;
use App\Models\Exclude;
use App\Jobs\UpdateProjectEntriesJob;

use App\Models\CriticalDiagnosis;
use App\Models\SupportDiagnosis;
use Aws\Support\SupportClient;

class MedicalConditionAbbreviationController extends Controller
{
    private $medicalCondition;

    public function index(Request $request)
    {

        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
       

        $search_keywords = explode(' ', request('search'));
        $group = null;
        $result_count = null;
        $medicalConditionsQuery = null;
        if (request()->has('search') && request()->get('search') != null) {
           // Convert the search input to uppercase to standardize the search
            $project = request('project');
            $search = strtoupper(request('search'));
            if(request('filter_by') == 'comma'){
                $unique_codes = array_unique(explode(',',$search));
            }else{
                $unique_codes = array_unique(explode("\n", $search));
            }
            // Retrieve medical conditions based on the search term
            $medicalConditions = $this->medicalCondition($search,$unique_codes);
            // Extract the IDs of the found medical conditions
            $medicalConditionsIds = $medicalConditions->pluck('id')->toArray();

            // Retrieve medical condition abbreviations excluding the previously found medical conditions
            $medCodeAbbreviations = $this->medCodeAbbreviations($search, $medicalConditionsIds,$unique_codes);
            // Extract the IDs of the found abbreviations' related medical conditions
            $medCodeAbbreviationsIds = $medCodeAbbreviations->pluck('id')->toArray();

            // Merge the IDs of both medical conditions and abbreviations' related medical conditions
            $mergedMedIds = array_merge($medicalConditionsIds, $medCodeAbbreviationsIds);

            $medicalConditionsQuery = MedicalCondition::query();
            $medicalConditionsQuery->whereIn('id', $mergedMedIds);
            if($medicalConditionsQuery->count() > 0){
                $group = generateUniqueGroupId(request('search'));
                //create project entity record
                $this->storeProjectEntry($medicalConditionsQuery->get(), $project,request('search'),$group);
                UpdateProjectEntriesJob::dispatch($group, $medicalConditionsQuery->get(), request('search'));
            }else{
                $result_count = $medicalConditionsQuery->count();
            }
            // Apply the 'latest' sorting and limit the results to the specified length
            $med_conditions = $medicalConditionsQuery->latest()->limit($length)->get();
            $supportDiagnosis = SupportDiagnosis::whereIn('dx',$unique_codes)->get();
            $medCodeVersions = MedCodeVersion::whereIn('medical_id', $mergedMedIds)->pluck('code_version')->toArray();
        }else{
            $med_conditions = new Collection([]);
            $medCodeVersions = array();
            $supportDiagnosis = array();
        }
        
        if ($request->ajax()) {
            return response()->json([
                'result_count' => $result_count,
                'view'=> view('admin.include.modal.dx-sync-data-ajax', compact('med_conditions','medCodeVersions','group','supportDiagnosis'))->render()
            ]);    
        }
        return view('member.med_condition_abbreviations.index', ['med_conditions' => $med_conditions,'$medCodeVersions' => $medCodeVersions,'group' => $group,'supportDiagnosis' => $supportDiagnosis])->render();
    }

    public function medicalCondition($search,$unique_codes = null){
        $medicalConditions = MedicalCondition::query();
       
        if($unique_codes){
            $medicalConditions->whereIn('code',$unique_codes);
        }else{
            $search_keywords = explode(' ', $search);
            if(request()->has('search_type') && request('search_type') == 'exact'){
                $medicalConditions->where(function ($query) use($search) {
                    $query->where('title', $search)
                    ->orWhere('code', $search);
                });
            }else{
                if(count($search_keywords) == 1){
                    $keyword = $search_keywords[0];
                    $medicalConditions->where(function ($query) use($keyword) {
                        $query->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('code', 'like', '%' . $keyword . '%');
                    });
                        
                }else{
                    foreach ($search_keywords as $index => $term){
                        if(strlen($term) > 3){
                            if($index == 0){
                                $medicalConditions->where('title', 'LIKE', "%" . $term . "%")
                                                ->orWhere('code', 'LIKE', "%" . $term . "%");
                            } else {
                                $medicalConditions->orWhere('title', 'LIKE', "%" . $term . "%");
                                $medicalConditions->orWhere('code', 'LIKE', $term . "%");
                            }
                        }
                    }
                }
            }
        }
       
        return  $medicalConditions->orderBy('code', 'ASC');

    }

    public function medCodeAbbreviations($search , $medicalConditionsIds,$unique_codes = null){
        $abbreviations = MedicalConditionAbbreviation::query();
        $abbreviations->whereNotIn('medical_condition_id', $medicalConditionsIds);
        $search_keywords = explode(' ', $search);
        if($unique_codes){
            $abbreviations->whereIn('title',$unique_codes);
        }else{
            if(request()->has('search_type') && request('search_type') == 'exact'){
                $abbreviations->where(function ($query) use($search) {
                    $query->where('title', $search);
                });
            }else{
                if(count($search_keywords) == 1){
                    $keyword = $search_keywords[0];
                    $abbreviations->where(function ($query) use($keyword) {
                        $query->where('title', 'like', '%' . $keyword . '%');
                    });
                        
                }else{
                    foreach ($search_keywords as $index => $term){
                        if(strlen($term) > 3){
                            if($index == 0){
                                $abbreviations->where('title', 'LIKE', "%" . $term . "%");
                            } else {
                                $abbreviations->orWhere('title', 'LIKE', "%" . $term . "%");
                            }
                        }
                    }
                }
            }
        }
        $medicalConditionIds = $abbreviations->pluck('medical_condition_id')->toArray();
        $medicalConditions = MedicalCondition::query();
        $medicalConditions->whereIn('id',$medicalConditionIds);
       
        return  $medicalConditions->orderBy('title', 'ASC');
    }

    
    public function storeProjectEntry($medicalConditions, $projectId,$inputContent,$group)
    {
        foreach ($medicalConditions as $medicalCondition){
            $projectEntry = new ProjectEntry;
            $projectEntry->user_id = auth()->id();
            $projectEntry->medical_condition_id = $medicalCondition->id;
            $projectEntry->project_id = $projectId;
            $projectEntry->input_content = $inputContent;
            $projectEntry->status = ProjectEntry::STATUS_IN_QUEUE;
            $projectEntry->group = $group;
            $projectEntry->save();
        }
        return true;
    }

    public function updateProjectEntry($group,$medicalConditions,$inputContent)
    {
        $codes = $medicalConditions->pluck('code');
        $projectEntries = ProjectEntry::where('group', $group)->get();
        $combinationDescription = getCombinationDescription($medicalConditions);
        foreach ($projectEntries as $projectEntry) {
            // Initialize the criteriaPayload array
            $criteriaPayload = [];
         
            // Check critical diagnosis
            $isCritical = CriticalDiagnosis::where('code', $projectEntry->medicalCondition->code)->first();
            if ($isCritical) {
                $criticalDescription = 
                [
                    $isCritical->id => $isCritical->description,
                ];
                $criteriaPayload['critical'] = [
                    'is_critical' => 1,
                    'critical_description' => $criticalDescription,
                ];
            } else {
                $criteriaPayload['critical'] = [
                    'is_critical' => 0,
                    'critical_description' => null,
                ];
            }
        
            // Check gender
            $isGender = Gender::where('code', $projectEntry->medicalCondition->code)->first(); // Assuming a similar table for gender
            if ($isGender) {
                $genderDescription = 
                [
                    $isGender->id => $isGender->name,
                ];
                $criteriaPayload['gender'] = [
                    'is_gender' => 1,
                    'gender_description' => $genderDescription,
                ];
            } else {
                $criteriaPayload['gender'] = [
                    'is_gender' => 0,
                    'gender_description' => null,
                ];
            }
        
            // Check literality
            $isLiterality = Laterality::where('code', $projectEntry->medicalCondition->code)->first(); // Assuming a similar table for literality
            if ($isLiterality) {
                $literalityDescription = 
                [
                    $isLiterality->id => $isLiterality->description,
                ];
                $criteriaPayload['literality'] = [
                    'is_literality' => 1,
                    'literality_description' => $literalityDescription,
                ];
            } else {
                $criteriaPayload['literality'] = [
                    'is_literality' => 0,
                    'literality_description' => null,
                ];
            }
            

            // Check more specific
            $isMoreSpecific = MoreSpecific::where('code', $projectEntry->medicalCondition->code)->first(); // Assuming a similar table for literality
            if ($isMoreSpecific) {
                $moreSpecificDescription = 
                [
                    $isMoreSpecific->id => $isMoreSpecific->description,
                ];
                $criteriaPayload['more_specific'] = [
                    'is_more_specific' => 1,
                    'more_specific_description' => $moreSpecificDescription,
                ];
            } else {
                $criteriaPayload['more_specific'] = [
                    'is_more_specific' => 0,
                    'more_specific_description' => null,
                ];
            }

            // Check Combination
            if (!empty($combinationDescription)) {
                $criteriaPayload['combination'] = [
                    'is_combination' => 1,
                    'combination_description' => $combinationDescription,
                ];
            } else {
                $criteriaPayload['combination'] = [
                    'is_combination' => 0,
                    'combination_description' => null,
                ];
            }

            // Check Excluded
            $excludeDescription = getExcludeDescription($medicalConditions);
            if (!empty($excludeDescription)) {
                $criteriaPayload['exclude'] = [
                    'is_exclude' => 1,
                    'exclude_description' => $excludeDescription,
                ];
            } else {
                $criteriaPayload['exclude'] = [
                    'is_exclude' => 0,
                    'exclude_description' => null,
                ];
            }
        
            // Assign the criteria payload and update the project entry status
            $projectEntry->criteria_payload = $criteriaPayload;
            $projectEntry->status = ProjectEntry::STATUS_READY;
            $projectEntry->save();
        }
        return true;
    }

}
