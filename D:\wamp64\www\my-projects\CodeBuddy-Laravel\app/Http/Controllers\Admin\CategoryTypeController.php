<?php
/**
 *
 * @category Hq.ai
 *
 * @ref Defenzelite Product
 * @author <Defenzelite  hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <Hq.ai: 1.2.0>
 * @link <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryTypeRequest;
use App\Models\CategoryType;
use App\Models\Category;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryTypeController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Category Groups';
    }

      /**start-hqai-m-index**/
    public function index(Request $request)
    {
        $length = $request->get('length', 10);
        $categoryTypes = CategoryType::query();

        if ($search = $request->get('search')) {
            $categoryTypes->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
        }

        $orderType = $request->get('asc') ?? $request->get('desc');
        $orderDirection = $request->get('asc') ? 'asc' : 'desc';

        if ($orderType) {
            $categoryTypes->orderBy($orderType, $orderDirection);
        }

        if ($request->get('trash') == 1) {
            $categoryTypes->onlyTrashed();
        }

        $categoryTypes = $categoryTypes->latest()->paginate($length);

        if ($request->ajax()) {
            return view('panel.admin.category-types.load', ['categoryTypes' => $categoryTypes])->render();
        }

        return view('panel.admin.category-types.index', ['categoryTypes' => $categoryTypes, 'label' => $this->label ]);
    }
/**end-hqai-m-index**/

    public function print(Request $request)
    {
        $categoryTypes_arr = collect($request->records['data'])->pluck('id');
        $categoryTypes = CategoryType::whereIn('id', $categoryTypes_arr)->latest()->get();
        return view('panel.admin.category-types.print', ['categoryTypes' => $categoryTypes])->render();
    }

  
    public function create()
    {
       
        $permissions = Permission::get();
        $label = Str::singular($this->label);
        return view('panel.admin.category-types.create', compact('permissions', 'label'));
    }

    
      /**start-hqai-m-store**/
    public function store(CategoryTypeRequest $request)
    {
        $categoryType = new CategoryType($request->validated());

        try {
            $categoryType->save();

            $message = [
                'status' => 'success',
                'message' => 'Success',
                'title' => 'Category Group created successfully'
            ];

            return $request->ajax() ? response()->json($message) :
            redirect(route('panel.admin.category-types.index'))->with('success', $message['title']);
        } catch (\Exception $e) {
            return back()->with('error', "Error: {$e->getMessage()}");
        }
    }
/**end-hqai-m-store**/

    
    public function show(CategoryType $categoryType, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
    }

   
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $categoryType = CategoryType::whereId($id)->first();
        $permissions = Permission::get();
        $label = Str::singular($this->label);
        return view('panel.admin.category-types.edit', compact('categoryType', 'permissions', 'label'));
    }

  
  /**start-hqai-m-update**/
    public function update(Request $request, CategoryType $categoryType)
    {
        try {
            $categoryType->update($request->all());

            $message = [
                'status' => 'success',
                'message' => 'Success',
                'title' => 'Record Updated Successfully'
            ];

            return $request->ajax() ? response()->json($message) :
            redirect()->route('panel.admin.category-types.index')->with('success', $message['title']);
        } catch (\Exception $e) {
            return back()->with('error', "Error: {$e->getMessage()}");
        }
    }
/**end-hqai-m-update**/
   

 /**start-hqai-m-destroy**/
    public function destroy(CategoryType $categoryType, $id)
    {
        try {
            $categoryType = CategoryType::find($id);

            if (!$categoryType) {
                return back()->with('error', 'Category Type not found');
            }

            $categoryType->delete();

            return back()->with('success', 'Category Type deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', "There was an error: {$e->getMessage()}");
        }
    }
/**end-hqai-m-destroy**/


 /**start-hqai-m-bulkAction**/
    public function bulkAction(Request $request)
    {
        $ids = array_filter(explode(',', $request->ids));

        if (empty($ids)) {
            return back()->with('error', 'There were no rows selected by you!');
        }

        try {
            CategoryType::destroy($ids);

            $message = [
                'status' => 'success',
                'message' => 'Success',
                'title' => 'Category Group Deleted Successfully!'
            ];

            return $request->ajax() ? response()->json($message) :
            back()->with('success', $message['title']);
        } catch (Exception $e) {
            return back()->with('error', "There was an error: {$e->getMessage()}");
        }
    }
/**end-hqai-m-bulkAction**/

/**start-hqai-m-moreAction**/
    public function moreAction(Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['error' => "Please select at least one record."], 401);
        }

        try {
            $action = explode('-', $request->action)[0];
        
            switch ($action) {
                case 'Move To Trash':
                    CategoryType::destroy($request->ids);
                    return response()->json(['message' => 'Records moved to trash successfully.']);
                break;

                case 'Delete Permanently':
                    CategoryType::whereIn('id', $request->ids)->forceDelete();
                    return response()->json(['message' => 'Records deleted permanently successfully.']);
                break;

                case 'Restore':
                    CategoryType::whereIn('id', $request->ids)->restore();
                    return response()->json(['message' => 'Records restored successfully.']);
                break;

                default:
                    return response()->json(['error' => "Sorry! Action not found."], 401);
                break;
            }
        } catch (Exception $e) {
            return response()->json(['error' => "Error: {$e->getMessage()}"], 500);
        }
    }
/**end-hqai-m-moreAction**/
}
