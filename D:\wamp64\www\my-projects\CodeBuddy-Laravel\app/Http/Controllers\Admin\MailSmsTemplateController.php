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
use App\Http\Requests\MailSmsTemplateRequest;
use App\Models\MailSmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MailSmsTemplateController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'MailSmsTemplates';
    }
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $mailSmsTemplates = MailSmsTemplate::query();
        if ($request->get('from') && $request->get('to')) {
            $mailSmsTemplates->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if (request()->has('type') && request()->get('type') != null) {
            $mailSmsTemplates->where('type', request()->get('type'));
        }
        if (request()->has('search') && request()->get('search')) {
            $mailSmsTemplates->where('subject', 'like', '%'.request()->get('search').'%')
                ->orWhere('code', 'like', '%'.request()->get('search').'%');
        }
        $mailSmsTemplates = $mailSmsTemplates->latest()->paginate($length);
        if ($request->ajax()) {
            return view('panel.admin.mail-sms-templates.load', ['mailSmsTemplates' => $mailSmsTemplates])->render();
        }
        $label = $this->label;
        return view('panel.admin.mail-sms-templates.index', compact('mailSmsTemplates', 'label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = Str::singular($this->label);
         return view('panel.admin.mail-sms-templates.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $mailSmsTemplate = new MailSmsTemplate();
            $mailSmsTemplate->code=$request->code;
            $mailSmsTemplate->subject=$request->subject;
            $mailSmsTemplate->type=$request->type;
            $mailSmsTemplate->purpose=$request->purpose;
            $mailSmsTemplate->is_default=$request->is_default ?? 0;
            $mailSmsTemplate->content = '';
            $mailSmsTemplate->save();

            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Mail Sms Template created successfully'
                    ]
                );
            }
            return redirect()->route('admin.mail-sms-templates.index')->with('success', 'Mail Sms Template created successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MailSmsTemplate $mailSmsTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $mailSmsTemplate = MailSmsTemplate::whereId($id)->firstOrFail();
        $label = Str::singular($this->label);
        return view('panel.admin.mail-sms-templates.show', compact('mailSmsTemplate' , 'label'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MailSmsTemplate $mailSmsTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $mailSmsTemplate = MailSmsTemplate::whereId($id)->firstOrFail();
        $label = Str::singular($this->label);
        return view('panel.admin.mail-sms-templates.edit', compact('mailSmsTemplate', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  \App\Models\MailSmsTemplate $mailSmsTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MailSmsTemplate $mailSmsTemplate)
    {
        try {
            $mailSmsTemplate->subject=$request->subject;
            $mailSmsTemplate->purpose=$request->purpose;
            $mailSmsTemplate->content=$request->content;
           
            $mailSmsTemplate->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Mail Sms Template updated successfully'
                    ]
                );
            }
            return redirect(route('admin.mail-sms-templates.index'))->with('success', 'Mail Sms Template update successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailSmsTemplate $mailSmsTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(MailSmsTemplate $mailSmsTemplate)
    {
        try {
            if ($mailSmsTemplate) {
                $mailSmsTemplate->delete();
                return back()->with('success', 'Mail Sms Template Deleted Successfully!');
            } else {
                return back()->with('error', 'Mail Sms Template not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request)
    {
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json(
                    [
                        'status'=>'error',
                    ]
                );
                return back()->with('error', 'Hands Up!","Atleast one row should be selected');
            }
            switch ($request->action) {
                  // Delete
                case ('delete'):
                    MailSmsTemplate::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
                return back()->with('success', 'Mail Sms Template Deleted Successfully!');
            }

            if (request()->ajax()) {
                return response()->json(
                    [
                     'status'=>'success',
                     'column'=>$request->column,
                     'action'=>$request->action,
                     'data' => $request->ids,
                     'title' => $title,
                     'html' => $html,

                     ]
                );
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
