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
use Illuminate\Http\Request;
use App\Traits\CanSendFCMNotification;
use App\Models\User;

class BroadcastController extends Controller
{
    use CanSendFCMNotification;
    public function index(Request $request)
    {
        // return $request->all();
        $fcmTokens = User::when(
            $request->has('role_name') && !is_null($request->get('role_name')),
            function ($q) use ($request) {
                if ($request->get('admin_selected') !== null) {
                    $q->whereIn('id', $request->admin_selected);
                } else {
                    $q->role(ucfirst($request->get('role_name')));
                }
            }
        )->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
        if ($request->get('role_name') == 'Admin') {
            $this->fcm()
                ->setTitle(config('app.name'))
                ->setBody($request->get('brodcast'))
                ->setTokens($fcmTokens)
                ->send();
        } elseif ($request->get('role_name') == 'User') {
            $this->fcm()
                ->setFcmServer(env('FCM_SERVER_KEY2'), env('FCM_SENDER_ID2'))
                ->setTokens($fcmTokens)
                ->setTitle(config('app.name'))
                ->setBody($request->get('brodcast'))
                ->send();
        } else {
            $this->fcm()
                ->setTitle(config('app.name'))
                ->setBody($request->get('brodcast'))
                ->setTokens($fcmTokens)
                ->send();

            $this->fcm()
                ->setFcmServer(env('FCM_SERVER_KEY2'), env('FCM_SENDER_ID2'))
                ->setTokens($fcmTokens)
                ->setTitle(config('app.name'))
                ->setBody($request->get('brodcast'))
                ->send();
        }
            return redirect()->back()->with('success', 'Successfully notify to the users!');
    }


    public function roleWiseRecord(Request $request)
    {
        $users = User::whereRoleIs([$request->role_name])->get();
        $html = "<option value='' readonly> Select Users</option>";
        if ($users) {
            foreach ($users as $user) {
                $html .= '<option value="'.$user->id.'">'.$user->full_name.'</option>';
            }
            return response()->json(
                [
                    'data'=>$html,
                    'success'=>true,
                ]
            );
        }
    }
}
