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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WhitelistIp;

class WhitelistIpController extends Controller
{

    public function index(Request $request){
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        // if()
        $items = WhitelistIp::query();
        
        if ($request->get('search')) {
            $search = $request->get('search');
            $items->where(
                function ($q) use ($search) {
                    $q->orWhere('ip_address', 'like', '%'.$search.'%');
                    $q->orWhere('name', 'like', '%'.$search.'%');
                }
            );
        }
        $items= $items->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.whitelist-ips.load', ['items' => $items])->render();
        }
        return view('admin.whitelist-ips.index', compact('items'));
    }

    
    public function store(Request $request)
    {
        $this->validate($request,[
            'ip_address'     => 'required',
        ]);
          
        try {
            if(empty($request->id)){
                $exist = WhitelistIp::where('ip_address', $request->ip_address)->exists();
                if($exist){
                    if ($request->ajax()) {
                        return response([
                            'status'=>'error',
                            'message' => 'error',
                            'title' => 'Record already exists!'
                        ]);
                    }
                }
                $items = WhitelistIp::create($request->all());
            }else{
                $items = WhitelistIp::find($request->id);
                $items->update($request->all());
            }
            
            if ($request->ajax()) {
                return response([
                    'data'=>$items,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record saved successfully!'
                ],200);
            }
            return back()->with('success', 'Record saved successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Something went wrong'.$th->getMessage());
        }
    }
  
  
    public function destroy($id)
    {
        try {
            $item = WhitelistIp::find($id);
            if ($item) {
                $item->delete();
                return back()->with('success', 'Record deleted successfully');
            } else {
                return back()->with('error', 'Record not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
