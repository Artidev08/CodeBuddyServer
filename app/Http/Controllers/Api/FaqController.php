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
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use App\Models\Category;
use Illuminate\Http\Request;

class FaqController extends Controller
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

            $faqs = Faq::query();
            
            if ($request->has('category_id') && $request->get('category_id')) {
                $faqs->where('category_id', $request->get('category_id'));
            }

            $faqs = $faqs->with('category')->limit($limit)
                ->offset(($page - 1) * $limit)->latest()->get();
                
            //response
            if ($faqs) {
                return $this->success($faqs);
            } else {
                return $this->errorOk('Faq Data Does not exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }

    public function show(Faq $faq, $category_id)
    {
        try {
            $faqs = Faq::where('category_id', $category_id)->get();
            if (!$faqs) {
                return $this->errorOk('Faqs not found!');
            }
            return $this->success($faqs);
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
}
