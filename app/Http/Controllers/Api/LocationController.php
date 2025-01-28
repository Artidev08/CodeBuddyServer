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
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private $resultLimit = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function countryList(Request $request)
    {
        try {
            $page = $request->has('page') ? $request->get('page') : 1;
            $limit = $request->has('limit') ? $request->get('limit') : $this->resultLimit;

            $countries = Country::query();
            
            $countries =  $countries->latest()->limit($limit)
                ->offset(($page - 1) * $limit)->get();
                
            //response
            if ($countries) {
                return $this->success($countries);
            } else {
                return $this->errorOk('Country Data Does not exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }

    public function stateList(Request $request, $countryId)
    {
        if ($countryId) {
            $states = State::where('country_id', $countryId)->get();
            return response(['states' => $states,'success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }
    public function cityList(Request $request, $stateId)
    {
        if ($stateId) {
            $cities = City::where('state_id', $stateId)->get();
            return response(['cities' => $cities,'success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }
}
