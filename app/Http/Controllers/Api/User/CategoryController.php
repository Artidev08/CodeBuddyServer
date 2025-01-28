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

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $resultLimit = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {
            $categories = Category::where('id', 5)->with('childrenCategories')->get();
                
            //response
            if ($categories) {
                return $this->success($categories);
            } else {
                return $this->errorOk('Category Data Does not exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
    public function faqCategory(Request $request)
    {
        try {
            $categories = Category::where('category_type_id', 1)->get();
                
            //response
            if ($categories) {
                return $this->success($categories);
            } else {
                return $this->errorOk('Category Data Does not exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
    public function supportTicketCategory(Request $request)
    {
        try {
            $categories = Category::where('id', 3)->get();
                
            //response
            if ($categories) {
                return $this->success($categories);
            } else {
                return $this->errorOk('Category Data Does not exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
    public function getCategoryData(Request $request)
    {
        try {
            $categories = Category::query();
            if (request()->has('category_id') && request()->get('category_id') && request()->has('level') && request()->get('level')) {
                $categories->where('category_id', request()->get('category_id'))->where('level', request()->get('level'));
            }
            if (request()->has('category_id') && request()->get('category_id')) {
                $categories->where('category_id', request()->get('category_id'));
            }
            $categories = $categories->get();
            //response
            if ($categories) {
                return $this->success($categories);
            } else {
                return $this->errorOk('Category Data Does not exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
}
