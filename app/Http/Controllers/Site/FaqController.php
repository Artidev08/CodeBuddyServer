<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'FAQS';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $faqs = Faq::query();
        if (request()->has('search') && request()->get('search')) {
            $faqs->where('title', 'like', '%'.request()->get('search').'%')
                ->orWhereHas(
                    'category', function ($q) {
                        $q->where('name', 'like', '%'.request()->get('search').'%');
                    }
                );
        }
        if($request->get('asc')) {
            $faqs->orderBy($request->get('asc'), 'asc');
        }
        if($request->get('desc')) {
            $faqs->orderBy($request->get('desc'), 'desc');
        }
        $faqs= $faqs->latest()->paginate($length);
        // if ($request->ajax()) {
        //     return view('admin.faqs.load', ['faqs' => $faqs])->render();
        // }
        $categories = getCategoriesByCode('FaqCategories');
        $label = $this->label;
        return view('site.faq.index', compact('faqs', 'label', 'categories'));
    }
}
