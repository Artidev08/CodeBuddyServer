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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PayoutDetail;
use Illuminate\Support\Facades\Hash;
use DateTimeZone;

class PayoutDetailController extends Controller
{
    public function store(Request $request)
    {
        try {
            $payload = [
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'account_no' => $request->account_no,
                'ifsc_code' => $request->ifsc_code,
                'branch' => $request->branch,
                'hereBy' => $request->hereBy,
            ];
            $payoutDetail = new PayoutDetail();
            $payoutDetail->user_id = $request->user_id;
            $payoutDetail->type = $request->type;
            $payoutDetail->payload = $payload;
            $payoutDetail->save();
            return back()->with('success', 'Your Payout Details Created Successfully!');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
            'id'     => 'required',
            ]
        );
        try {
            $payoutDetail = PayoutDetail::where('id', $request->id)->first();
            if ($payoutDetail) {
                $payload = [
                    'account_holder_name' => $request->account_holder_name,
                    'account_no' => $request->account_no,
                    'ifsc_code' => $request->ifsc_code,
                    'bank_name' => $request->bank_name,
                    'branch' => $request->branch,
                    'hereBy' => $request->hereBy,
                ];
                $request['user_id'] = auth()->id();
                $request['payload'] = $payload;
                $payoutDetail->payload = $payload;
                $payoutDetail->save();
    
                return back()->with('success', ' Information Updated!');
            }
            return back()->with('error', ' Payout not found!');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function destroy(PayoutDetail $payoutDetail)
    {
        try {
            if ($payoutDetail) {
                $payoutDetail->delete();
                return back()->with('success', 'Payout Detail deleted successfully');
            } else {
                return back()->with('error', 'Payout Detail not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
