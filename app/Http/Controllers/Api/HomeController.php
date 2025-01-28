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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\WebsitePage;
use App\Models\Slider;
use Auth;

class HomeController extends Controller
{
    public function homeSlider()
    {
        try {
            $sliders = Slider::where('slider_type_id', 6)->get()->makeHidden(['media']);
            return $this->success($sliders);
        } catch (\Throwable $th) {
            return $this->error("Something went wrong! ".$th->getMessage());
        }
    }
    public function bottomSlider()
    {
        try {
            $sliders = Slider::where('slider_type_id', 7)->get()->makeHidden(['media']);
            return $this->success($sliders);
        } catch (\Throwable $th) {
            return $this->error("Something went wrong! ".$th->getMessage());
        }
    }
    public function pageContent(Request $request, $slug)
    {
        try {
            $page = WebsitePage::where('slug', $slug)->first();
            if ($page) {
                return $this->success($page);
            } else {
                return $this->errorOk('Contant not found!');
            }
        } catch (\Throwable $th) {
            return $this->error("Something went wrong! ".$th->getMessage());
        }
    }
}
