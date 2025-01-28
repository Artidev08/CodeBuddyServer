<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\CategoryType;

class ItemController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $items = Item::query();
         
        if ($request->get('search')) {
            $items->where('id', 'like', '%'.$request->search.'%')
                                  
                ->orWhere('name', 'like', '%'.$request->search.'%')
                                 
                ->orWhere('status', 'like', '%'.$request->search.'%')
                                 
                ->orWhere('slug', 'like', '%'.$request->search.'%')
                                 
                ->orWhere('sku', 'like', '%'.$request->search.'%')
                
                ->orWhere('sell_price', 'like', '%'.$request->search.'%')

                ->orWhereHas(
                    'category',
                    function ($q) use ($request) {
                        $q->where('name', 'like', '%'.$request->search.'%');
                    }
                );
        }
        if ($request->get('from') && $request->get('to')) {
            $items->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->has('category_id') && $request->get('category_id') != null) {
            $items->where('category_id', $request->get('category_id'));
        }

        if ($request->get('asc')) {
            $items->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $items->orderBy($request->get('desc'), 'desc');
        }
           $items = $items->latest()->paginate($length);
           $item_categories = getCategoriesByCode('ItemCategories');

        if ($request->ajax()) {
            return view('admin.items.load', ['items' => $items])->render();
        }
 
           return view('admin.items.index', compact('items', 'item_categories'));
    }
        // public function print(Request $request){
        //     $items = collect($request->records['data']);
        //         return view('admin.items.print', ['items' => $items])->render();
            
        // }
    public function print(Request $request)
    {
        $items_arr = collect($request->records['data'])->pluck('id');
        $items = Item::whereIn('id', $items_arr)->get();
        return view('admin.items.print', ['items' => $items])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $item_categories = getCategoriesByCode('ItemCategories');
            $category_types = CategoryType::all();
            return view('admin.items.create', compact('item_categories', 'category_types'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {

        // return $request->all();
            $this->validate(
                $request,
                [
                'name'     => 'required',
                'category_id'     => 'required',
                'sell_price'     => 'required|max:9',
                'mrp_price'     => 'required|max:9',
                'is_published'     => 'nullable',
                'short_description'     => 'nullable',
                'description'     => 'nullable',
                'identifier'     => 'nullable',
                'meta'     => 'nullable',
                'is_featured'     => 'nullable',
                'sku'     => 'nullable',
                'tax_percent'     => 'nullable|numeric|max:100|min:0',
                'item_image'     => 'image',
                ]
            );
            
            try {
                if (!$request->has('is_featured')) {

                    $request['is_featured'] = 0;
                }
                if (!$request->has('status')) {
                    $request['status'] = 'Notavailable';
                }
                if (!$request->has('is_published')) {
                    $request['is_published'] = 0;
                }

                $request['user_id'] = auth()->id();
                $item = Item::create($request->all());
                // if ($request->hasFile('item_images') && $request->file('item_images')->isValid()) {
                //     $item->addMediaFromRequest('item_images')->toMediaCollection('item_images');
                // }
                // Item Images
                // return $item;
                if ($request->hasFile('item_image')) {
                    $fileAdders = $item->addMultipleMediaFromRequest(['item_image'])->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('item_image');
                    });
                }
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Item created successfully!'
                        ]
                    );
                }
                return redirect(route('admin.item.index'))->with('success', 'Item created successfully.');
            }catch(Exception $e){            
                return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
            }
        }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        try {
            return view('admin.items.show', compact('item'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    
     public function edit(Request $request, $id)
    {   
        try{
            if(!is_numeric($id)){
                $id = secureToken($id ,'decrypt');
            }
            $item =item::whereId($id)->firstOrFail();
            $item_categories = getCategoriesByCode('ItemCategories');
            // return $item_categories;
        return view('admin.items.edit',compact('item','item_categories'));
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        // return $request->all();

        $this->validate(
            $request,
            [
            'name'     => 'required',
            'category_id'     => 'required',
            'sell_price'     => 'required',
            'mrp_price'     => 'required',
            'status'     => 'nullable',
            'is_published'     => 'nullable',
            'short_description'     => 'nullable',
            'description'     => 'nullable',
            'identifier'     => 'nullable',
            'slug'     => 'unique:items,slug,'.$item->id,
            'meta'     => 'nullable',
            'is_featured'     => 'nullable',
            'sku'     => 'nullable',
            'tax_percent'     => 'nullable',
            'item_image'     => 'image',
            ]
        );
        try {
            if (!$request->has('is_featured')) {
                $request['is_featured'] = 0;
            }
            if (!$request->has('status')) {
                $request['status'] = 'Notavailable';
            }
            if (!$request->has('is_published')) {
                $request['is_published'] = 0;
            }
            // $request->meta = json_encode($request->meta,true);
            // $item->slug = \Str::slug($request->name);
           
            if($item){     
                $request['user_id'] = auth()->id();
                $chk = $item->update($request->all());
                if ($request->hasFile('item_image')) {
                    $fileAdders = $item->addMultipleMediaFromRequest(['item_image'])->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('item_image');
                    });
                }
                // if($request->hasFile('item_image') && $request->file('item_image')->isValid()){
                //     if ($item->getMedia('item_image')->count()) {
                //         $item->clearMediaCollection('item_image');
                //     }
                //     $item->addMediaFromRequest('item_image')->toMediaCollection('item_image');
                // }
                                  
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Item created successfully!'
                        ]
                    );
                }
            }
            return back()->with('error', 'Item not found')->withInput($request->all());
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        try {
            if ($item) {
                // Check if Item sold prevent deletion
                if ($item->orderItems->count() > 0) {
                    return back()->with('warning', 'You can\'t delete this item, It has associated order records. Delete Order First.');
                }
                $item->delete();
                return back()->with('success', 'Item deleted successfully');
            } else {
                return back()->with('error', 'Item not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
        
    public function destroyMedia(Item $item, Request $request)
    {
        try {
            if ($item) {
                if ($item->getMedia($request->media)->count()) {
                    $item->clearMediaCollection($request->media);
                }
                return back()->with('success', 'Media deleted successfully');
            } else {
                return back()->with('error', 'Media not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request)
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
                    Item::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                case ('columnUpdate'):
                    Item::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('is_published'):
                            $html['badge_color'] = $request->value != 0 ? "success" : "danger";
                            $html['badge_label'] = $request->value != 0 ? "Published" : "UnPublished";
    
                            $title = "Updated ".count($request->ids)." records successfully!";
                            break;
                        default:
                            $type = "error";
                            $title = 'No action selected!';
                    }
                    
                    break;
                default:
                    $type = "error";
                    $title = 'No action selected!';
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
        
            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
    public function ckeditorUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
      
            $request->file('upload')->move(public_path('media'), $fileName);
      
            $url = asset('media/' . $fileName);
  
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }
    public function getItems(Request $request)
    {
        $input = $request->all();
        $items = Item::query();
        $items->select(['id','name','sell_price','status']);
        if ($request->has('query') && !empty($input['query'])) {
            $items->whereIsPublished(1)
                ->where("name", "like", '%'.$input['query'].'%')
                ->orWhere("sell_price", "like", '%'.$input['query'].'%')
                ->orWhere("status", "like", '%'.$input['query'].'%');
        } else {
            $items->whereIsPublished(1);
        }
        $items = $items->latest()->limit(15)->get();
        return response()->json($items);
    }
}
