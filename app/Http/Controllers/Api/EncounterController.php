<?php
/**
 * Class EncounterController
 *
 * @category ZStarter
 *
 * @ref zCURD
 * @author  Defenzelite <hq@defenzelite.com>
 * @license https://www.defenzelite.com Defenzelite Private Limited
 * @version <zStarter: 1.1.0>
 * @link    https://www.defenzelite.com
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Encounter;

class EncounterController extends Controller
{
   
    private $resultLimit;

    public function __construct(){
        $this->resultLimit = 10;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $page = $request->has('page') ? $request->get('page') : 1;
            $limit = $request->has('limit') ? $request->get('limit') : $this->resultLimit;

            $encounters = Encounter::query();

            if($request->get('search')){
                $encounters->where('id','like','%'.$request->search.'%')                  
                ->orWhere('payload','like','%'.$request->search.'%')                   
                ->orWhere('created_by','like','%'.$request->search.'%')                 
                ;
            }
            
            $encounters = $encounters->limit($limit)->offset(($page - 1) * $limit)->get();

            return $this->success($encounters);
        } catch(\Exception $e){
            return $this->error("Error: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try{
                
            $this->validate($request, [                'folder_id' => 'required',                'payload' => 'nullable',                'created_by' => 'nullable',
            ]);
               
            $encounter = Encounter::create($request->all());

            if($encounter){
                return $this->success($encounter, 201);
            }else{
                return $this->error("Error: Record not Created!");
            }

        } catch(\Exception $e){
            return $this->error("Error: " . $e->getMessage());
        }
    }


    /**
    * Return single instance of the requested resource
    *
    * @param Encounter $encounter
    * @return \Illuminate\Http\JsonResponse
    */
    public function show(Encounter $encounter)
    {
        try{
            return $this->success($encounter);
        } catch(\Exception $e){
            return $this->error("Error: " . $e->getMessage());
        }
    }
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, Encounter $encounter)
    {
        try{        
            $this->validate($request, [
                'folder_id' => 'required',
                'payload' => 'nullable',
                'created_by' => 'nullable',
            ]);
            
            $encounter = $encounter->update($request->all());

            return $this->success($encounter, 201);
        } catch(\Exception $e){
            return $this->error("Error: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
     public function destroy($id)
     {
         try{
            $encounter = Encounter::findOrFail($id);
                               
             $encounter->delete();
 
             return $this->successMessage("Encounter deleted successfully!");
         } catch(\Exception $e){
             return $this->error("Error: " . $e->getMessage());
         }
     }
    
}
