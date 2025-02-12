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

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Project;
use App\Models\CodeRegister;
use App\Models\CodeRegisterAgent;
use App\Models\CodeRegisterFile;
use App\Models\Agent;

class CronController extends Controller
{
    public function assignModuleTasks()
    {
        // Get All Auto Project 
        $projects = Project::where('worker_type', Project::WORKER_TYPE_AUTO)
        ->get();
        $agentIds = Agent::pluck('id');
        foreach($projects as $project){
            $codeRegisters = CodeRegister::where('project_id', $project->id)->get();
            foreach($codeRegisters as $codeRegister){
                $codeRegisterAgent = CodeRegisterAgent::where('project_id', $project->id)
                ->where('code_register_id', $codeRegister->id)->first();
                if(!$codeRegisterAgent || $codeRegisterAgent->status == CodeRegisterAgent::STATUS_NO_CHANGE_NEEDED){
                    $randomAgentId = $agentIds->random(); 
                    if($codeRegisterAgent){
                        $agentExists = Agent::where('id', $codeRegisterAgent->agent_id)->first();
                        if($agentExists){
                            $randomAgentId = $agentIds->random(); 
                        }
                    }
                    $agent = Agent::where('id', $randomAgentId)->first();
                    $cr_agent = new CodeRegisterAgent();
                    $cr_agent->code_register_id = $codeRegister->id;
                    $cr_agent->project_id = $codeRegister->project->id;
                    $cr_agent->agent_id = $agent->id;
                    $cr_agent->name = $agent->name;
                    $cr_agent->gpt_code = $agent->gpt_code;
                    $cr_agent->model_id = $agent->model_id;
                    $cr_agent->scope_ids = $agent->scope_ids;
                    $cr_agent->prompt = generateAgentPrompt($agent) ?? '';
                    $cr_agent->save();

                    // sync logs
                    $scopedRegisterFiles = CodeRegisterFile::whereCodeRegisterId($codeRegister->id)->get();
                    foreach ($scopedRegisterFiles as $file){
                        $agentLogs = $this->createAgentLogs($file,$cr_agent);
                    }
                }
            }
        }

        return response()->jason([
            'status' => 'success',
            'message' => 'Code Register Created!',
        ]);
    }
}
