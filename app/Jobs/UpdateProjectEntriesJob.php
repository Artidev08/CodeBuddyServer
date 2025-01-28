<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ProjectEntry;
use App\Models\CriticalDiagnosis;
use App\Models\Gender;
use App\Models\Laterality;
use App\Models\MoreSpecific;
use App\Models\CombinationCode;
use App\Models\Exclude;

class UpdateProjectEntriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $group;
    protected $medicalConditions;
    protected $inputContent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group, $medicalConditions, $inputContent)
    {
        $this->group = $group;
        $this->medicalConditions = $medicalConditions;
        $this->inputContent = $inputContent;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $medicalConditions = $this->medicalConditions;
        $group = $this->group;
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
    }
}
