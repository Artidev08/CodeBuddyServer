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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private $resultLimit = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $page = $request->has('page') ? $request->get('page') : 1;
            $limit = $request->has('limit') ? $request->get('limit') : $this->resultLimit;
            $blogs = Blog::query();

            if ($request->get('from') && $request->get('to')) {
                $blogs->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            }
            if ($request->has('category_id') && $request->get('category_id')) {
                $blogs->where('category_id', $request->get('category_id'));
            }
            $blogs = $blogs->with(
                ['user'=>function ($q) {
                    $q->select('id', 'first_name', 'last_name', 'avatar', 'email', 'phone', 'gender', 'country_id', 'state_id', 'city_id');
                },'category'=>function ($qa) {
                    $qa->select('id', 'name');
                }]
            )->latest()->limit($limit)
             ->offset(($page - 1) * $limit)->get();
            if ($blogs) {
                return $this->success($blogs);
            } else {
                return $this->success([]);
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $page = $request->has('page') ? $request->get('page') : 1;
            $limit = $request->has('limit') ? $request->get('limit') : $this->resultLimit;

            $blogs = Blog::query();
            
            $blogs = $blogs->latest()->limit($limit)
                ->offset(($page - 1) * $limit)->get();
                
            //response
            if ($blogs) {
                return $this->success($blogs);
            } else {
                return $this->errorOk('Blog Data Does not exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }

    public function show(Request $request, $slug)
    {
        try {
            $blog = Blog::where('slug', $slug);
            if ($blog->exists()) {
                $blog = $blog->with(
                    'category',
                    function ($q) {
                        $q->select('id', 'name');
                    }
                )->first();
                return $this->success($blog);
            } else {
                return $this->errorOk('This blog is not exist!');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
    public function categoryList()
    {
        $categories = Category::where('category_type_id', 2);
        if ($categories->exists()) {
            $categories = $categories->get();
            return $this->success($categories);
        } else {
            return $this->errorOk('No Categories yet!');
        }
    }
}
