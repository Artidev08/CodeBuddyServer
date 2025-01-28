<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserKyc;

class UserEkycController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kyc = UserKyc::where('user_id', auth()->id())->get();
        return $this->success($kyc);
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
    public function store(Request $request)
    {
        $request->validate(
            [
            'type' => 'required',
            'number' => 'required',
            'front_image' => 'required|file',
            'back_image' => 'required|file',
            'face_with_doc' => 'required|file',
            ]
        );

        try {
            if ($request->hasFile("front_image")) {
                $document_front = $this->uploadFile($request->file("front_image"), "kyc")->getFilePath();
            } else {
                $document_front = null;
            }
            
            if ($request->hasFile("back_image")) {
                $document_back = $this->uploadFile($request->file("back_image"), "kyc")->getFilePath();
            } else {
                $document_back = null;
            }

            if ($request->hasFile("face_with_doc")) {
                $face_with_document = $this->uploadFile($request->file("face_with_doc"), "kyc")->getFilePath();
            } else {
                $face_with_document = null;
            }

            $userEkyc = UserKyc::create(
                [
                'user_id' => auth()->id(),
                'status' => 0,
                'type' => $request->type,
                'number' => $request->number,
                'front_image' => $document_front,
                'back_image' => $document_back,
                'face_with_doc' => $face_with_document,
                'remark' => $request->remark,
                'legal_name' => $request->legal_name,
                ]
            );
            
            return $this->successMessage('User Ekyc Submitted Successfully!');
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
