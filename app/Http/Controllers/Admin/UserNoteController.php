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

use App\Models\UserNote;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserNoteRequest;
use Illuminate\Http\Request;

class UserNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserNoteRequest $request)
    {
        try {
            $data = new UserNote();
            $data->title=$request->title;
            $data->category_id=$request->category_id;
            $data->description=$request->description;
            $data->type=$request->type;
            $data->type_id=$request->type_id;
            $data->save();
            return redirect()->back()->with('success', 'User Note created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error:' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserNote $userNote
     * @return \Illuminate\Http\Response
     */
    public function show(UserNote $userNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserNote $userNote
     * @return \Illuminate\Http\Response
     */
    public function edit(UserNote $userNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\UserNote     $userNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserNote $userNote)
    {
        try {
            $userNote->title=$request->title;
            $userNote->description=$request->description;
            $userNote->type=$request->type;
            $userNote->type_id=$request->type_id;
            $userNote->save();
            return redirect()->back()->with('success', 'User Note update successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserNote $userNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserNote $userNote)
    {
        try {
            if ($userNote) {
                $userNote->delete();
                return back()->with('success', 'User Note Deleted Successfully!');
            } else {
                return back()->with('error', 'User Note not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
