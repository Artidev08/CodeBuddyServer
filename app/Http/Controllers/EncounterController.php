<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class EncounterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function secureEncounter($encounter_id)
    {
        if(!is_numeric($encounter_id)){
            $encounter_id = secureToken($encounter_id,'decrypt');
        }
        return view();
    }
}
