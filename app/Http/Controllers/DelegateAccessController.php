<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DelegateAccess;
use App\Models\User;
use Illuminate\Http\Request;

class DelegateAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $delegateAccessCode = auth()->user()->delegate_access;

         return view('panel\user\delegate-access\index', compact('delegateAccessCode'));
    }


    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function changeCode()
    {
        $delegate_access = rand(100000, 999999);
        auth()->user()->update([
        'delegate_access' => $delegate_access,
        ]);
        return back()->with('success', 'Your Delegate Access Code has been Changed!');
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function validateCode(Request $request)
    {
        if ($request->user_id) {
            $user = User::where('id', $request->user_id)->first();
            if ($user->delegate_access == $request->delegate_access) {
                return redirect()->route('admin.users.login-as', $user->id);
            }
            return back()->with('error', 'Wrong Delegate Code!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DelegateAccess  $delegateAccess
     * @return \Illuminate\Http\Response
     */
    public function show(DelegateAccess $delegateAccess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DelegateAccess  $delegateAccess
     * @return \Illuminate\Http\Response
     */
    public function edit(DelegateAccess $delegateAccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DelegateAccess  $delegateAccess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DelegateAccess $delegateAccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DelegateAccess  $delegateAccess
     * @return \Illuminate\Http\Response
     */
    public function destroy(DelegateAccess $delegateAccess)
    {
        //
    }
}
