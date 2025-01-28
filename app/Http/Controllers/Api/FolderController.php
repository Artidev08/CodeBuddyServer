<?php
/**
 * Class FolderController
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
use App\Models\Folder;

class FolderController extends Controller
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

            $folders = Folder::query();

            if($request->get('search')){
                $folders->where('id','like','%'.$request->search.'%')                  
                ->orWhere('titile','like','%'.$request->search.'%')                   
                ->orWhere('created_by','like','%'.$request->search.'%')                 
                ;
            }
            
            $folders = $folders->limit($limit)->offset(($page - 1) * $limit)->get();

            return $this->success($folders);
        } catch(\Exception $e){
            return $this->error("Error: " . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try{
                
            $this->validate($request, [                'titile' => 'nullable',                'created_by' => 'nullable',
            ]);
             
            $folder = Folder::create($request->all());

            if($folder){
                return $this->success($folder, 201);
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
    * @param Folder $folder
    * @return \Illuminate\Http\JsonResponse
    */
    public function show(Folder $folder)
    {
        try{
            return $this->success($folder);
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
     public function update(Request $request, Folder $folder)
    {
        try{        
            $this->validate($request, [
                'titile' => 'nullable',
                'created_by' => 'nullable',
            ]);
           
            $folder = $folder->update($request->all());

            return $this->success($folder, 201);
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
            $folder = Folder::findOrFail($id);
                             
             $folder->delete();
 
             return $this->successMessage("Folder deleted successfully!");
         } catch(\Exception $e){
             return $this->error("Error: " . $e->getMessage());
         }
     }
    
}
