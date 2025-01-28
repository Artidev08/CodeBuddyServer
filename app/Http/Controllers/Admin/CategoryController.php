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
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Categories';
    }
    public function index(CategoryRequest $request, $categoryTypeId)
    {
        $length = 5;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $categories = Category::query();
        if (request()->has('search') && request()->get('search')) {
            $categories->where('name', 'like', '%'.request()->get('search').'%');
        }

        if ($request->has('level')) {
            $level = $request->get('level');
        } else {
            $level = 1;
        }
        $nextLevel = $level + 1;
        if ($request->has('parent_id')) {
            $categories->where('category_type_id', $categoryTypeId)->where('level', $level)->where('parent_id', $request->get('parent_id'));
        } else {
            $categories->where('category_type_id', $categoryTypeId)->where('level', $level);
        }
        if($request->get('trash') == 1){
            $categories->onlyTrashed();
        }
        $categories = $categories->latest()->paginate($length);
        $categoryType = CategoryType::where('id', $categoryTypeId)->first();
        $childCategory = Category::where('category_type_id', $categoryTypeId)->where('parent_id', null)->first();
        $label = $this->label;
        if ($request->ajax()) {
            return view('admin.categories.load', ['categories' => $categories,'level' => $level,'categoryTypeId' => $categoryTypeId ,'nextLevel' => $nextLevel,'childCategory' => $childCategory,'label' => $label,'categoryType' => $categoryType])->render();
        }
        return view('admin.categories.index', compact('categories', 'level', 'categoryTypeId', 'categoryType', 'nextLevel', 'label', 'childCategory'));
    }
    
    public function print(Request $request)
    {
        $categories_arr = collect($request->records['data'])->pluck('id');
        $categories = Category::whereIn('id', $categories_arr)->get();
        return view('admin.categories.print', ['categories' => $categories])->render();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($categoryTypeId, $level = 1, $parent_id = null)
    {
        $categoryType = CategoryType::where('id', $categoryTypeId)->first();
        $label = Str::singular($this->label);
        return view('admin.categories.create', compact('categoryTypeId', 'categoryType', 'level', 'parent_id', 'label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $text = trim($_POST['name']);
        $textAr = explode("\r\n", $text);
        $categoryNames = array_filter($textAr, 'trim');

        if ($request->has('category_type_code') && $request->get('category_type_code')) {
            $categoryType = CategoryType::where('code', $request->category_type_code)->first();
            if (!$categoryType) {
                if (request()->ajax()) {
                    return response(['error'=>'Invalid category type']);
                } else {
                    return back()->with('error', 'Invalid category type');
                }
            }
            $request['category_type_id'] = $categoryType->id;
        }
        try {
            foreach ($categoryNames as $categoryName) {
                $category = new Category();
                $category->name = $categoryName;
                $category->level = $request->level;
                $category->category_type_id = $request->category_type_id;
                $category->parent_id = $request->parent_id;
                if ($request->hasFile('icon')) {
                    $image = $request->file('icon');
                    $path = storage_path('app/public/backend/category-icon');
                    $imageName = 'category-icon' . $category->id.rand(000, 999).'.' . $image->getClientOriginalExtension();
                    $image->move($path, $imageName);
                    $category->icon=$imageName;
                }
                $category->save();
            }
            if (request()->ajax()) {
                return response()->json(
                    [
                        'data' => $category,
                        'category_type_id' => $request->category_type_id,
                        'level' => $request->level,
                        'parent_id' => $request->parent_id,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Category created successfully'
                    ]
                );
            }
            return redirect()->route('admin.categories.index', [$request->category_type_id,'level' =>$request->level,'parent_id' => $request->parent_id])->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryRequest $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $category = Category ::whereId($id)->firstOrFail();
        $parent = Category::where('id', $category->parent_id)->first();
        $categoryType = CategoryType::where('id', $category->category_type_id)->first();
        $categoryTypes = CategoryType::get();
        $types = Category::TYPES;
        $parents = Category::where('level', '!=', 3)->get();
        $label = Str::singular($this->label);
        return view('admin.categories.edit', compact('category', 'parent', 'categoryType', 'categoryTypes', 'types', 'parents', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request $request
     * @param  \App\Models\Category       $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        // return $request->all();
        if ($request->hasFile('icon')) {
            if ($category->icon != null) {
                unlinkFile(storage_path() . '/app/public/backend/category-icon', $category->icon);
            }
            $image = $request->file('icon');
            $path = storage_path('app/public/backend/category-icon');
            $imageName = 'category-icon' . $category->id.rand(000, 999).'.' . $image->getClientOriginalExtension();
            $image->move($path, $imageName);
            $category->icon=$imageName;
        }
            $category->name=$request->name;
            $category->level=$request->level;
            $category->category_type_id=$request->category_type_id;
            $category->parent_id=$request->parent_id;
            $category->save();
            return redirect()->route('admin.categories.index', [$request->category_type_id,'level'=> $request->level,'parent_id'=>$request->parent_id])->with('success', 'Category update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            if ($category) {
                $category->delete();
                return back()->with('success', 'Category Deleted Successfully!');
            } else {
                return back()->with('error', 'Category not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Category $category, CategoryRequest $request)
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
                    Category::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
                return back()->with('success', 'Category Type Deleted Successfully!');
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
    public function moreAction(CategoryRequest $request)
    {
        if(!$request->has('ids') || count($request->ids) <= 0){
            return response()->json(['error' => "Please select atleast one record."], 401);
        }
        try{
            switch (explode('-',$request->action)[0]) {     
                // case 'status':
                //     $action = explode('-',$request->action)[1];                    
                //      Category::withTrashed()->whereIn('id', $request->ids)->each(function($q) use($action){
                //         $q->update(['status'=>trim($action)]);
                //     });
                //     return response()->json([
                //         'message' => 'Status changed successfully.',
                //     ]);
                //     break;  ;
    
                case 'Move To Trash':
                    Category::whereIn('id', $request->ids)->delete();
                    return response()->json([
                        'message' => 'Records moved to trashed successfully.',
                    ]);
                    break;
    
                case 'Delete Permanently':
                    
                    for ($i=0; $i < count($request->ids); $i++) {                
                        $category = Category::withTrashed()->find($request->ids[$i]);                 
                        $category->forceDelete();
                    }
                    return response()->json([
                        'message' => 'Records deleted permanently successfully.',
                    ]);
                    break;
    
                case 'Restore':
                    
                    for ($i=0; $i < count($request->ids); $i++) {               
                        $category = Category::withTrashed()->find($request->ids[$i]);               
                        $category->restore();
                    }
                    return response()->json([
                        'message' => 'Records restored successfully.',
                    ]);
                    break;
    
                // case 'Export':

                //     return Excel::download(new CategoryExport($request->ids), 'category-'.time().'.csv');
                //     return response()->json(['error' => "Sorry! Action not found."], 401);
                //     break;
                
                default:
                
                    return response()->json(['error' => "Sorry! Action not found."], 401);
                    break;
            }
        }catch(Exception $e){
            return response()->json(['error' => "Sorry! Action not found."], 401);
        }
    }
}
