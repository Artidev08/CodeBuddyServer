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
use App\Http\Requests\LocationRequest;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Locations';
    }
    public function country(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $countries = Country::query();
        if ($request->get('from') && $request->get('to')) {
            $countries->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->get('search')) {
            $countries->where('name', 'like', '%'.$request->get('search').'%');
        }
            $countries= $countries->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.locations.loads.country', ['countries' => $countries])->render();
        }
            $label = $this->label;
        return view('admin.locations.country', compact('countries', 'label'));
    }
    public function state(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $states = State::query();
        if ($request->get('search')) {
            $states->where('name', 'like', '%'.$request->get('search').'%');
        }
        if ($request->get('country')) {
             $states->whereCountryId($request->get('country'));
        }
            $states= $states->paginate($length);
        if ($request->ajax()) {
            return view('admin.locations.loads.state', ['states' => $states])->render();
        }

            $country = Country::where('id', request()->get('country'))->first();
        return view('admin.locations.state', compact('states', 'country'));
    }
    public function city(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $cities = City::query();
        if ($request->get('state')) {
            $cities->whereStateId($request->get('state'));
        }
            $cities= $cities->paginate($length);
        if ($request->ajax()) {
            return view('admin.locations.loads.city', ['cities' => $cities])->render();
        }
            $state = State::where('id', request()->get('state'))->first();
        return view('admin.locations.city', compact('cities', 'state'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = Str::singular($this->label);
        return view('admin.locations.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        try {
            $country = new Country();
            $country->name=$request->name;
            $country->phonecode=$request->phonecode;
            $country->region=$request->region;
            $country->currency=$request->currency;
            $country->iso3=$request->iso3;
           
            $country->capital=$request->capital;
            $country->emoji=$request->emoji;
            $country->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Country created successfully'
                    ]
                );
            }
            return redirect(route('admin.locations.country'))->with('success', 'Country created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function stateStore(LocationRequest $request)
    {
        try {
            $country = Country::whereId($request->country_id)->first();
            if ($country) {
                $state = new State();
                $state->name=$request->name;
                $state->country_id=$request->country_id;
                $state->country_code=$country->iso2;
                $state->iso2=$request->iso2;
                $state->fips_code=$request->fips_code;
                $state->save();
                return redirect(route('admin.locations.state')."?country=".$request->country_id)->with('success', 'State created successfully.');
            } else {
                return back()->with('error', 'Oops! Something went wrong');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function stateUpdate(LocationRequest $request)
    {
        try {
            $state = State::whereId($request->id)->first();
            if ($state) {
                $state->name=$request->name;
                $state->iso2=$request->iso2;
                $state->fips_code=$request->fips_code;
                $state->save();
                return redirect(route('admin.locations.state')."?country=".$state->country_id)->with('success', 'State updated successfully.');
            } else {
                return back()->with('error', 'Oops! State not found!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
public function cityStore(LocationRequest $request)
    {
        try {
            $country = Country::whereId($request->country_id)->first();
            if ($country) {
                $city = new City();
                $city->name=$request->name;
                $city->country_id=$request->country_id;
                $city->country_code=$country->iso2;
                $city->state_id=$request->state_id;
                $city->state_code=$request->state_code;
                $city->latitude=$request->latitude;
                $city->longitude=$request->longitude;
                $city->pincode=$request->pincode;
                $city->save();
                return redirect(route('admin.locations.city')."?state=".$request->state_id)->with('success', 'City created successfully.');
            } else {
                return back()->with('error', 'Oops! Something went wrong');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function cityUpdate(LocationRequest $request)
    {
        try {
            $city = City::whereId($request->id)->first();
            if ($city) {
                $city->name=$request->name;
                $city->latitude=$request->latitude;
                $city->longitude=$request->longitude;
                $city->pincode=$request->pincode;   
                $city->save();
                return redirect(route('admin.locations.city')."?state=".$city->state_id)->with('success', 'City updated successfully.');
            } else {
                return back()->with('error', 'Oops! City not found!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        if (!is_numeric($id)) {
            $id = decrypt($id);
        }
        $country  =Country::whereId($id)->firstOrFail();

        // $country = Country::whereId($id)->first();
        $label = Str::singular($this->label);
        return view('admin.locations.edit', compact('country', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Article      $article
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, $id)
    {
        try {
            $country = Country::whereId($id)->first();
            $country->name=$request->name;
            $country->phonecode=$request->phonecode;
            $country->region=$request->region;
            $country->currency=$request->currency;
            $country->iso3 = $request->iso3;
            $country->capital = $request->capital;
            $country->emoji = $request->emoji;
            $country->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Country updated successfully'
                    ]
                );
            }
            return redirect(route('admin.locations.country'))->with('success', 'Country Updated Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
}
