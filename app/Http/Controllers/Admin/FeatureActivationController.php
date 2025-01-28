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
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FeatureActivationController extends Controller
{
    public function featuresActivationIndex()
    {
        $groups = [
            'Sales & Payments' => [
                'options' => [
                        ['name'=>"Order Management",'key'=>'order_activation','tooltip'=>"Activate Order Management"],
                        ['name'=>"Subscribers",'key'=>'subscribers_activation','tooltip'=>"Activate Subscribers Management"],
                        ['name'=>"Control Payout",'key'=>'payout_activation','tooltip'=>"Activate Payout Management"]
                    ],
            ],
            'Control Products' => [
                'options' => [
                    ['name'=>"Manage Items",'key'=>'item_activation','tooltip'=>"Activate Item Management"],
                ],
        ],
        'Item Module' => [
            'options' => [
                    ['name'=>"Item Management",'key'=>'item_activation','tooltip'=>"Activate Item Management"],
                ]
            ],

            'Administrator Module' => [
                'options' => [
                    ['name'=>"User Management",'key'=>'user_management_activation','tooltip'=>"Activate User Management"],
                    ['name'=>"Add User",'key'=>'add_user_activation','tooltip'=>"Activate Add User Management"],
                    ['name'=>"Role & Permission",'key'=>'roles_and_permission_activation','tooltip'=>"Activate Role Management"],
                    // ['name'=>"Permission",'key'=>'roles_and_permission_activation','tooltip'=>"Activate Permission Management"],
                    // ['name'=>"E KYC Verification ",'key'=>'e_kyc_verification_activation','tooltip'=>"Activate E KYC Verification Management"],
                    
                ],
            ],

            'Contact Enquiry Module' => [
                'options' => [
                    ['name'=>"Website Enquiry",'key'=>'website_enquiry_activation','tooltip'=>"Activate Website Enquiry Management"],
                    ['name'=>"Support Tickets",'key'=>'ticket_activation','tooltip'=>"Activate Support Tickets Management"],
                    ['name'=>"Newsletters",'key'=>'newsletter_activation','tooltip'=>"Activate Newsletters Management"],
                    ['name'=>"Leads",'key'=>'lead_activation','tooltip'=>"Activate Leads Management"]
                ],
            ],

            'Content Management Module' => [
                'options' => [
                    ['name'=>"Blogs",'key'=>'article_activation','tooltip'=>"Activate Blogs Management"],
                    ['name'=>"Mail/Text Templates",'key'=>'mail_sms_activation','tooltip'=>"Activate Mail/Text Templates Management"],
                    ['name'=>"Category Group",'key'=>'category_management_activation','tooltip'=>"Activate Category Group Management"],
                    ['name'=>"Slider Group ",'key'=>'slider_activation','tooltip'=>"Activate Slider Group Management"],
                    ['name'=>"Paragraph Content",'key'=>'paragraph_content_activation','tooltip'=>"Activate Paragraph Content Management"],
                    ['name'=>"Manage FAQs",'key'=>'faq_activation','tooltip'=>"Activate Manage FAQs Management"],
                    ['name'=>"Location",'key'=>'location_activation','tooltip'=>"Activate Location Management"],
                    ['name'=>"Subscription Plans",'key'=>'subscription_plans_activation','tooltip'=>"Activate Subscription Plans Management"],
                    ['name'=>"Control Seo",'key'=>'seo_tags_activation','tooltip'=>"Activate Seo Tags Management"],
                    ['name'=>"Pages",'key'=>'pages_activation','tooltip'=>"Activate Pages Management"],
                    ['name'=>"Wallet",'key'=>'wallet_activation','tooltip'=>"Activate Wallet Management"],
                    ['name'=>"Article",'key'=>'article_activation','tooltip'=>"Activate Article Management"],
                ],
            ],

            'Setup & Configurations Module' => [
                'options' => [
                    ['name'=>"Basic Details",'key'=>'basic_details_activation','tooltip'=>"Activate Basic Details Management"],
                    // ['name'=>"Appearance",'key'=>'appearance_activation','tooltip'=>"Activate Appearance Management"],
                    ['name'=>"General Configuration",'key'=>'manage_general_configuration_activation','tooltip'=>"Activate General Configuration Management"],
                    ['name'=>"Mail/SMS Configuration",'key'=>'mail_sms_configuration_activation','tooltip'=>"Activate Mail/SMS Configuration Management"],
                    // ['name'=>"Payment Gateway",'key'=>'payment_gateway_activation','tooltip'=>"Activate Payment Gateway Management"],
                    
            ],
        ],
            
            
        ];
        if (env('DEV_MODE') == 1) {
            return view('admin.features-activation.index', compact('groups'));
        }
        return abort(404);
    }
    public function featuresActivationStore(Request $request)
    {
        try {
            $setting = Setting::where('key', '=', $request->key)->first();
            if ($setting) {
                $setting->value = $request->val;
                $setting->group = "activation";
                $setting->save();
            } else {
                $data = new setting();
                $data->key = $request->key;
                $data->value = $request->val;
                $data->group = "activation";
                $data->save();
            }
      
            return response("Updated!", 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 200);
        }
    }
}
