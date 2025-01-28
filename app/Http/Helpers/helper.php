<?php 
use App\Models\UserAddress;
use Illuminate\Support\Facades\Http;

//// General System Helpers
if (!function_exists('getSetting')) {
    function getSetting($key,$setting = null)
    {
        if($setting){
            $value = $setting->where('key', $key)->first()->value ?? '';
            return $value;
        }
        if (is_array($key)) {
            $records = App\Models\Setting::select('group', 'key', 'value')->whereIn('group', $key)->get();
            $settings = [];
            foreach ($records as $key => $record) {
                $settings[$record->key] = $record->value;
            }
        } else {
            $settings = App\Models\Setting::where('key', $key)->first()->value ?? '';
        }
        return $settings;
    }
}

if (!function_exists('UserRole')) {
    function UserRole($id)
    {
        return App\Models\User::find($id)->roles[0];
    }
}
if (!function_exists('getAdminId')) {
    function getAdminId()
    {
        return App\Models\User::whereRoleIs(['Admin'])->value('id');
    }
}
function commentOutStart() 
{
  return "{{--";
}
function commentOutEnd() 
{
  return "--}}";
}
if (!function_exists('getSeoData')) {
    function getSeoData($code)
    {
        return  App\Models\SeoTag::where('code',$code)->first() ?? '';
    }
}

if (!function_exists('getBackendLogo')) {
    function getBackendLogo($img_name)
    {
        return asset($img_name);
    }
}

if (!function_exists('getSocialLinks')) {
    function getSocialLinks()
    {
        $social_links = [];

        if (getSetting('facebook_login_active')) {
            $social_links[] = "<a href='".route('social.login', 'facebook')."' class='btn social-btn btn-facebook'><i class='fab fa-facebook-f'></i></a>";
        }

        if (getSetting('google_login_active')) {
            $social_links[] = "<a href='".route('social.login', 'google')."' class='btn social-btn btn-google'><i class='fab fa-google'></i></a>";
        }

        if (getSetting('linkedin_login_active')) {
            $social_links[] = "<a href='".route('social.login', 'linkedin')."' class='btn social-btn btn-linkedin'><i class='fab fa-linkedin'></i></a>";
        }

        if (getSetting('twitter_login_active')) {
            $social_links[] = "<a href='".route('social.login', 'twitter')."' class='btn social-btn btn-twitter'><i class='fab fa-twitter'></i></a>";
        }

        return $social_links;
    }
}

if (!function_exists('getBlogImage')) {
    function getBlogImage($path){
        $profile_img = asset($path);
        if($profile_img){
            return $profile_img;
        }else{
            asset('admin/default/default-avatar.png');
        }
    }
}
// if (!function_exists('getBlogImage')) {
//     function getBlogImage($path){
//         $profile_img = asset('storage/site/blog/'.$path);
//         if($profile_img){
//             return $profile_img;
//         }else{
//             asset('admin/default/default-avatar.png');
//         }
//     }
// }



if (!function_exists('AuthRole')) {
    function AuthRole()
    {
        return ucWords(auth()->user()->roles[0]->name ?? '');
    }
}

if (!function_exists('getAuthProfileImage')) {
    function getAuthProfileImage($path){
        if(\Str::contains($path, 'https:')){
            return $path;
        }
        $profile_img = $path;
        if($profile_img != null){
            return $profile_img;
        }
    }
}

if (!function_exists('unlinkFile')) {
    function unlinkFile($filepath, $filename)
    {
        if ($filename != null) {
            $file = $filepath.'/'.$filename;
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}

if (!function_exists('unlinkPdf')) {
    function unlinkPdf($filepath)
    {
        if ($filepath != null) {
            $file = $filepath;
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}

if (!function_exists('activeClassIfRoutes')){
    function activeClassIfRoutes($routes, $output = 'active', $fallback = '')
    {
        if (in_array(Route::currentRouteName(), $routes)){
            return $output;
        } else {
            return $fallback;
        }
    }
}
if (!function_exists('activeClassIfRoute')){
    function activeClassIfRoute($route, $output = 'active', $fallback = '')
    {
        if (Route::currentRouteName() == $route) {
            return $output;
        } else {
            return $fallback;
        }
    }
}
//formats currency
if (! function_exists('format_price')) {
    function format_price($price)
    {   
        if (App\Models\Setting::where('key', 'decimal_separator')->first()->value == 1) {
            $formatted_price = number_format($price, App\Models\Setting::where('key', 'no_of_decimal')->first()->value);
        } else {
            $formatted_price = number_format($price, App\Models\Setting::where('key', 'no_of_decimal')->first()->value, ',', '.');
        }

        if (App\Models\Setting::where('key', 'currency_position')->first()->value == 1) {
            return getSetting('app_currency').$formatted_price;
        }
        return $formatted_price.getSetting('app_currency');
    }
}

if (! function_exists('getAuthenticationMode')) {
    function getAuthenticationMode($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>1,'name'=>"Login with Password"],
                ['id'=>2,'name'=>"Login with OTP"],
            ];
            }else{
                foreach(getAuthenticationMode() as $row){
                    if($id == $row['id']){
                    return $row;
                }
            }
            return ['id'=>0,'name'=>''];
        }
    }
}

function getKeysByValue($val, $array)
{
    $arr = [];
    foreach ($array as $k => $ar) {
        if (is_array($ar)) {
            getKeysByValue($val, $ar);
        } else {
            if($val == $ar)
            $arr[] = $k;
        }
    }
    return $arr;
}

if (! function_exists('getPublishStatus')) {
    function getPublishStatus($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>0,'name'=>"Unpublished",'color'=>"danger"],
                ['id'=>1,'name'=>"Published",'color'=>"success"],
            ];
            }else{
                foreach(getPublishStatus() as $row){
                    if($id == $row['id']){
                    return $row;
                }
            }
            return ['id'=>0,'name'=>''];
        }
    }
}

if (! function_exists('getCategoryCount')) {
    function getCategoryCount($id)
    {
        return App\Models\Category::whereCategoryTypeId($id)->count();
    }
}

if (! function_exists('getSliderCount')) {
    function getSliderCount($id)
    {
        return App\Models\Slider::whereSliderTypeId($id)->count();
    }
}


function getSelectValues($arr,$noKey=true,$char=":"){
    if($noKey){
        $temp = [];
        foreach ($arr as $key => $val){
        $temp[] = str_after($val,":");
        }
        return $temp;
    }else{
        $temp = [];
        foreach ($arr as $key => $val){
            if(str_contains($val,$char)) {
                $temp[explode($char,$val)[0]] = explode($char,$val)[1];
            }else{
                $temp[$val] = $temp[$val];
            }
        }
        return $temp;
    }
}




if (! function_exists('getHelp')) {
    function getHelp($message)
    {
        return '<i class="ik ik-help-circle text-muted" title="'.$message.'"></i>';
    }
}
//
//// Categories List By Using Code
//
if (!function_exists('getCategoriesByCode')) {
    function getCategoriesByCode($code,$parent = null)
    {
        $chk = App\Models\CategoryType::whereCode($code)->first();
        if($chk){
            if($parent != null){
                return App\Models\Category::select('id','name','category_type_id','parent_id','icon')->whereCategoryTypeId($chk->id)->where('parent_id',$parent)->latest()->get();
            }
            return App\Models\Category::select('id','name','category_type_id','parent_id','icon')->whereCategoryTypeId($chk->id)->where('parent_id',null)->latest()->get();
        }
        return [];
    }
}

// Paragraph Content by Code
if (!function_exists('getParagraphContent')) {
    function getParagraphContent($code)
    {
        if(is_array($code)){
            $records = App\Models\ParagraphContent::select('code','value')->whereIn('code', $code)->get();
            $content = [];
            foreach ($records as $key => $record) {
                $content[$record->code] = $record->value;
            }
        }else{
            $content = App\Models\ParagraphContent::select('code','value')->where('code',$code)->first();
        }
        return $content;
    }
}


// 
//// Communication Helpers
// 

if (!function_exists('pushOnSiteNotification')) {
    function pushOnSiteNotification($data)
    {
        // Check if notification enable
        if(getSetting('notification') == 1){
            $notification = App\Models\Notification::create([
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'link' => $data['link'],
                'notification' => $data['notification'],
                'is_read' => 0, // unseen
            ]);
            return $notification;
        }
    }
}
// Check File Exists 
if (!function_exists('fileExists')) {
    function fileExists($path)
    {
        return File::exists($path);
    }
}


// 
//// Data Processing Helpers
// 

function taxFormatter($tax, $name, $slap, $amount)
{
    $tax_format = App\Models\Order::TAX_STRUCTURE;
    $tax_format['name'] = $name;
    $tax_format['slap'] = $slap;
    $tax_format['amount'] = $amount;
    return $tax_format;
}

function getTxnCode()
{
    $code = now()->format('Ymd').'-UID'.auth()->id().'-'.rand(0000,9999);
    if(App\Models\Order::where('txn_no', '=', $code)->count() > 0) {
        getTxnCode();
    }
    return $code;
}

function str_after($str, $search)
{
    return $search === '' ? $str : array_reverse(explode($search, $str, 2))[0];
}

// 
//// Typing Validations
// 

function getTypingValidation($code = -1)
{
    if($code == -1){
        return [
            ['code'=>"code",'pattern'=>"[^\s]+"],
            ['code'=>"email",'pattern'=>"[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"],
            ['code'=>"search",'pattern'=>"[^'\x22]"],
        ];
        }else{
            foreach(getTypingValidation() as $row){
                if($code == $row['code']){
                return $row['code'];
            }
        }
        return null;
    }
    
}

if (!function_exists('manualEmail')) {
    function manualEmail($emails,$mailSubject,$mailBody,$replaceable,$data = []){

        // Replace User Defined Variables
        foreach (array_keys($replaceable) as $key) {
            $mailBody = str_replace($key, $replaceable[$key], $mailBody);
            $mailSubject = str_replace($key, $replaceable[$key], $mailSubject);
        }

        // Replace default Variables
        $mailBody = str_replace('{nl}', '<br>', $mailBody);
        $mailBody = str_replace('{br}', '<br>', $mailBody);
        $mailBody = str_replace('{app.name}', getSetting('app_name'), $mailBody);
        $mailBody = str_replace('{app.url}', url('/'), $mailBody);
        // Replace variables from Subject
        $mailSubject = str_replace('{app.name}', getSetting('app_name'), $mailSubject);
        $mailSubject = str_replace('{app.url}', url('/'), $mailSubject);
        $attachment = [];
        if(@$data['attachments'] && !empty($data['attachments']))
            $attachment = $data['attachments'];
        
        // With provided mail template
        $mail = \Mail::to($emails);
        if(@$data['cc'] && !empty($data['cc']))
            $mail->cc($data['cc']);
        if(@$data['bcc'] && !empty($data['bcc']))
            $mail->bcc($data['bcc']);
      

        if ((int)getSetting('mail_queue_enabled')) {
            $mail->send(new App\Mail\DynamicMailQueued($mailSubject, $mailBody),function($message) use($attachment){
                if (is_array($attachment)) {
                    foreach ($attachment as $file) {
                        $message->attach($file->getRealPath(), [
                            'as' => $file->getClientOriginalName(),
                            'mime' => $file->getClientMimeType()
                        ]);
                    }
                } else {
                    $message->attach($attachment->getRealPath(), [
                        'as' => $attachment->getClientOriginalName(),
                        'mime' => $attachment->getClientMimeType()
                    ]);
                }
            });
        } else {
            $mail->send(new App\Mail\DynamicMail($mailSubject, $mailBody),function($message) use($attachment){
                if (is_array($attachment)) {
                    foreach ($attachment as $file) {
                        $message->attach($file->getRealPath(), [
                            'as' => $file->getClientOriginalName(),
                            'mime' => $file->getClientMimeType()
                        ]);
                    }
                } else {
                    $message->attach($attachment->getRealPath(), [
                        'as' => $attachment->getClientOriginalName(),
                        'mime' => $attachment->getClientMimeType()
                    ]);
                }
            });
        }
    }
}
if (!function_exists('getBankName')) {
    function getBankName($id = -1)
    {
        if($id == -1){
            return [
                ["name"=>'AXIS Bank'],
                ["name"=>'ICICI Bank'],
                ["name"=>'SBI Bank'],
                ["name"=>'UNION Bank'],
                ["name"=>'HDFC Bank'],

                ];
            }else{
                foreach(getBankName() as $row){
                if($id == $row['id']){
                return $row;
                }
            }
            return ["name"=>' '];
        }
    }
}

function storeDefaultAddress($userId,$request=null)
{
    $data = new UserAddress();
    $data->user_id = $userId;
    $data->is_primary = $request->is_primary ?? 0;
    $arr = [
        'name'   => 'default address',
        'address_1' => null,
        'address_2' => null,
        'phone' => null,
        'type' => null,
        'pincode_id' => null,
        'country' => null,
        'state' => null,
        'city' => null
    ];
    $data->details = $arr;
    $data->save();
    return $data;

}
function getSellerAddresses($id = -1)
{
    if($id == -1){
        return [
            (object)['id'=>1,'name'=> 'Seller 1','address_1' => "near tilwada road gandhi nagar",'address_2' => "beside shri ram mandir",'phone' => "0000-000-000",'type' => "Home",'pincode_id' => "480661",'country' => "India",'state' =>"Madhya Pradesh",'city' => "seoni"],
            (object)['id'=>2,'name'=> 'Seller 1','address_1' => "near nehru road shukrawari road",'address_2' => "beside shri ram mandir",'phone' => "0000-000-000",'type' => "Office",'pincode_id' => "480661",'country' => "India",'state' =>"Madhya Pradesh",'city' => "seoni"],
            (object)['id'=>3,'name'=> 'Seller 2','address_1' => "near tilwada road gandhi nagar",'address_2' => "beside shri ram mandir",'phone' => "0000-000-000",'type' => "Home",'pincode_id' => "480661",'country' => "India",'state' =>"Madhya Pradesh",'city' => "seoni"],
            (object)['id'=>4,'name'=> 'Seller 2','address_1' => "near tilwada road gandhi nagar",'address_2' => "beside shri ram mandir",'phone' => "0000-000-000",'type' => "Office",'pincode_id' => "480661",'country' => "India",'state' =>"Madhya Pradesh",'city' => "seoni"],
        ];       
    }else{
        foreach(getSellerAddresses() as $address){
            if($address->id == $id)
                return $address;
        }
        return false;
    }
}
if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        if ($number >= 10000000) {
            // Convert to crore (1Cr = 10 million)
            $formattedNumber = round($number / 10000000, 2) . 'Cr';
        } elseif ($number >= 100000) {
            // Convert to lakh (1L = 100 thousand)
            $formattedNumber = round($number / 100000, 2) . 'L';
        } elseif ($number >= 1000) {
            // Convert to thousand (1k = 1000)
            $formattedNumber = round($number / 1000, 2) . 'k';
        } else {
            // No conversion needed
            $formattedNumber = $number;
        }
    
        return $formattedNumber;
    }
    
 }
if (!function_exists('getGreetingBasedOnTime')) {
    function getGreetingBasedOnTime()
    {
        $utc_time  = auth()->user()->timezone;
        $timezone = $utc_time != null ? $utc_time : 'UTC';
        $dat = new DateTime('now', new DateTimeZone($timezone));
        $hour = $dat->format('H');
        if ($hour >= 20) {
            $greetings = "Good Night";
        } elseif ($hour > 17) {
            $greetings = "Good Evening";
        } elseif ($hour > 11) {
            $greetings = "Good Afternoon";
        } elseif ($hour < 12) {
            $greetings = "Good Morning";
        }
        return $greetings;
        
    }
 }

if (!function_exists('pushItemVisit')) {
    function pushItemVisit($item_id)
    {
        $item = App\Models\Item::where('id', $item_id)->first();
        $item->update([
            'views' => $item->views+1,
        ]);
        return true;
    }
}

if (!function_exists('getSliderData')) {
    function getSliderData($code)
    {
       return $sliderType = App\Models\SliderType::where('code', $code)->with('sliders')->first();
    }
}
function getExtensionFromMimeType($mimeType) {
    $mimeExtensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
        'video/mp4' => 'mp4',
        'video/mpeg' => 'mpeg',
        'video/quicktime' => 'mov',
        'video/x-flv' => 'flv',
        'video/x-msvideo' => 'avi',
        'video/x-ms-wmv' => 'wmv',
        'audio/mpeg' => 'mp3',
        'audio/wav' => 'wav',
        'audio/ogg' => 'ogg',
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/zip' => 'zip',
        'application/x-gzip' => 'gzip',
        'application/x-tar' => 'tar',
        'text/plain' => 'txt',
        'text/html' => 'html',
        // Add more MIME types and their corresponding extensions as needed
    ];

    $mimeType = strtok($mimeType, ';');
    return $mimeExtensions[$mimeType] ?? null;
}
function mime2ext($mime)
{
  $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp","image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp","image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp","application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg","image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],"wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],"ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg","video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],"kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],"rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application","application\/x-jar"],"zip":["application\/x-zip","application\/zip","application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],"7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],"svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],"mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],"webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],"pdf":["application\/pdf","application\/octet-stream"],"pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],"ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office","application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],"xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],"xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel","application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],"xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo","video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],"log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],"wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],"tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop","image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],"mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar","application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40","application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],"cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary","application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],"ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],"wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],"dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php","application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],"swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],"mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],"rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],"jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],"eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],"p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],"p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
  $all_mimes = json_decode($all_mimes,true);
  foreach($all_mimes as $key => $value ) 
    if( array_search($mime,$value) !== false ) return $key;
  return false;
}
if (!function_exists('getTextWrapped')) {
    function getTextWrapped($content, $class)
    {
        $wrapped_content = preg_replace("/\*\*(.*?)\*\*/", "<span class='$class'>$1</span>", $content);
        return $wrapped_content;
    }
}

if (!function_exists('getPermissionName')) {
    function getPermissionName($id)
    {
       return $permission_name = App\Models\Permission::where('id', $id)->first()->name;
    }
}

if (!function_exists('makeUrlFriendly')) {
    function makeUrlFriendly($param) {
        // Replace spaces with hyphens
        $param = str_replace(' ', '-', $param);
      
        // Remove any non-alphanumeric characters except hyphens
        $param = preg_replace('/[^a-zA-Z0-9-]/', '', $param);
      
        // Convert to lowercase
        $param = strtolower($param);
      
        return $param;
      }
}
//Support ticket subject 
if (!function_exists('getSupportPrioity')) {
    function getSupportPrioity() {
        return [
            ["id"=>1,"name"=>"Low"],
            ["id"=>2,"name"=>"Medium"],
            ["id"=>3,"name"=>"High"],
        ];
    }
}

if (!function_exists('secureToken')) {
    function secureToken($id , $mode='encrypt') 
    {
        if(env('SECURE_ENDPOINT') == 0){
            return $id;
        }
        if($mode == 'encrypt'){
            return encrypt($id);
        }
        else{
            return decrypt($id);
        }
        
    }
}

if (!function_exists('NameByEmail')) {
    function NameByEmail($email){
        $pattern = '/^([^@]*)@.*$/';
        preg_match($pattern, $email, $matches);
        $name = $matches[1];
        $name = ucwords(trim(preg_replace('/[^A-Za-z\s]/', ' ',preg_replace('/\d+/', '', $name))));
        return $name;
    }
}
if (!function_exists('getTemplateVariables')) {
    function getTemplateVariables($str)
    {
        $start='{';
        $end='}';
        $with_from_to=true;
        $arr = []; 
        $last_pos = 0;
        $last_pos = strpos($str, $start, $last_pos);
        while ($last_pos !== false) {
            $t = strpos($str, $end, $last_pos);
            $arr[] = ($with_from_to ? $start : '').substr($str, $last_pos + 1, $t - $last_pos - 1).($with_from_to ? $end : '');
            $last_pos = strpos($str, $start, $last_pos+1);
        }
        $arr[] = '{app.name}'; 
        $arr[] = '{app.url}'; 
        $arr[] = '{nl}'; 
        $arr[] = '{br}';
        $array = array_unique($arr);
        return $array;
    }
}
if (!function_exists('getNewAcquisitionForUsers')) {
    function getNewAcquisitionForUsers() {
        $previousMonth = \Carbon\Carbon::now()->subMonth();
        $latestMonth = \Carbon\Carbon::now();
        $previousMonthUsers = App\Models\User::whereRoleIs(['User'])->whereYear('created_at', $previousMonth->year)
        ->whereMonth('created_at', $previousMonth->month)
        ->count();
        $latestMonthUsers = App\Models\User::whereRoleIs(['User'])->whereYear('created_at', $latestMonth->year)
        ->whereMonth('created_at', $latestMonth->month)
        ->count();
         $count = $latestMonthUsers - $previousMonthUsers;
        if($count == 0){
            $count = $count;
        }else{
            if($count > 0){
                $count = '+'. $count;
            }else{
                $count = $count;
            }
        }
        return $count;
    }
}
if (!function_exists('getNewAcquisitionForOrders')) {
    function getNewAcquisitionForOrders() {
        $previousMonth = \Carbon\Carbon::now()->subMonth();
        $latestMonth = \Carbon\Carbon::now();
        $previousMonthUsers = App\Models\Order::whereYear('created_at', $previousMonth->year)
        ->whereMonth('created_at', $previousMonth->month)
        ->count();
        $latestMonthUsers = App\Models\Order::whereYear('created_at', $latestMonth->year)
        ->whereMonth('created_at', $latestMonth->month)
        ->count();
         $count = $latestMonthUsers - $previousMonthUsers;
        if($count == 0){
            $count = $count;
        }else{
            if($count > 0){
                $count = '+'. $count;
            }else{
                $count = $count;
            }
        }
        return $count;
    }
}

if (! function_exists('getPdfExtractionLibraries')) {
    function getPdfExtractionLibraries($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>1,'label'=>"Parser",'name'=>'Advanced Extractor (Free)'],
                ['id'=>2,'label'=>"ConvertApi", "name"=>'Smart Extractor (Paid)'],
                ['id'=>3,'label'=>"Pdf2Text",'name'=>'Old Extractor (Free) [work in older version to PDF]'],
                ['id'=>4,'label'=>"AWS", "name"=>'Standard Extractor (Paid)'],
            ];
            }else{
                foreach(getPdfExtractionLibraries() as $row){
                    if($id == $row['id']){
                    return $row;
                }
            }
            return ['id'=>0,'label'=>''];
        }
    }
}


if (! function_exists('getChartPdfExtractionLibraries')) {
    function getChartPdfExtractionLibraries($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>1,'label'=>"ConvertApi", "name"=>'Smart Extractor (Paid)'],
            ];
            }else{
                foreach(getChartPdfExtractionLibraries() as $row){
                    if($id == $row['id']){
                    return $row;
                }
            }
            return ['id'=>0,'label'=>''];
        }
    }
}


if (! function_exists('getPermisssions')) {
    function getPermisssions($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>1,'label'=>"snipping",'name'=>'Snipping'],
                ['id'=>2,'label'=>"image", "name"=>'Image Extraction'],
            ];
            }else{
                foreach(getPermisssions() as $row){
                    if($id == $row['id']){
                    return $row;
                }
            }
            return ['id'=>0,'label'=>''];
        }
    }
}
if (! function_exists('getImageExtractionLibraries')) {
    function getImageExtractionLibraries($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>1,'label'=>"Lens",'name'=>'Advanced Mode (Paid)'],
                ['id'=>2,'label'=>"AWS", "name"=>'Standard Mode (Paid)'],
            ];
            }else{
                foreach(getImageExtractionLibraries() as $row){
                    if($id == $row['id']){
                    return $row;
                }
            }
            return ['id'=>0,'label'=>''];
        }
    }
}

if (! function_exists('getPromtVariables')) {
    function getPromtVariables($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>1,'label'=>"first_name",'name'=>'First Name'],
                ['id'=>2,'label'=>"last_name",'name'=>'Last Name'],
                ['id'=>3,'label'=>"dob", "name"=>'DOB'],
                ['id'=>4,'label'=>"age", "name"=>'Age'],
                ['id'=>5,'label'=>"gender", "name"=>'Gender'],
                ['id'=>6,'label'=>"dos", "name"=>'DOS'],
                ['id'=>7,'label'=>"page_no", "name"=>'Page No'],
                ['id'=>8,'label'=>"hcc", "name"=>'HCC'],
                ['id'=>9,'label'=>"medication", "name"=>'Medication'],
                ['id'=>10,'label'=>"record_type", "name"=>'Record Type'],
                ['id'=>11,'label'=>"doctor_name", "name"=>'Doctor Name'],
            ];
            }else{
                foreach(getPromtVariables() as $row){
                    if($id == $row['label']){
                    return $row;
                }
            }
            return ['id'=>0,'label'=>''];
        }
    }
}

function moneyFormat($value)
{
    $number = (int) $value;
    $decimal = round($value - (int) $value,2);
    $formattedDecimalNumber = formatDecimalPart(100 * $decimal);
    $digits = ['0' => 'Zero','1' => 'One','2' => 'Two','3' => 'Three','4' => 'Four','5' => 'Five','6' => 'Six','7' => 'Seven','8' => 'Eight','9' => 'Nine','10' => 'Ten','11' => 'Eleven','12' => 'Twelve','13' => 'Thirteen','14' => 'Fourteen','15' => 'Fifteen','16' => 'Sixteen','17' => 'Seventeen','18' => 'Eighteen','19' => 'Nineteen','20' => 'Twenty','30' => 'Thirty','40' => 'Forty','50' => 'Fifty','60' => 'Sixty','70' => 'Seventy','80' => 'Eighty','90' => 'Ninety',
    ];
    if ($number < 21) {
        return preg_replace('/\s+/', ' ',($digits[$number]. ' ' . $formattedDecimalNumber));
    }
    if ($number < 100) {
        $tens = floor($number / 10) * 10;
        $remainder = $number % 10;
        return $digits[$tens] . ($remainder > 0 ? ' ' . moneyFormat($remainder) : '') . ' ' . $formattedDecimalNumber;
    }
    if ($number < 1000) {
        $hundreds = floor($number / 100);
        $remainder = $number % 100;
        return preg_replace('/\s+/', ' ',($digits[$hundreds] . ' Hundred' . ($remainder > 0 ? ' ' . moneyFormat($remainder) : '') . ' ' . $formattedDecimalNumber));
    }
    if ($number < 100000) {
        $thousands = floor($number / 1000);
        $remainder = $number % 1000;
        return preg_replace('/\s+/', ' ',(moneyFormat($thousands) . ' Thousand' . ($remainder > 0 ? ' ' . moneyFormat($remainder) : ' '). '' . $formattedDecimalNumber));
    }
    if ($number < 10000000) {
        $lakhs = floor($number / 100000);
        $remainder = $number % 100000;
        return preg_replace('/\s+/', ' ',(moneyFormat($lakhs) . 'Lakh' . ($remainder > 0 ? ' ' . moneyFormat($remainder) : ''). ' ' . $formattedDecimalNumber));
    }
    $crores = floor($number / 10000000);
    $remainder = $number % 10000000;
    $numberwithoutDecimal = moneyFormat($crores) . 'Crore' . ($remainder > 0 ? ' ' . moneyFormat($remainder) : '');
    $numberwithDecimal = $numberwithoutDecimal . ' ' . $formattedDecimalNumber;
    return preg_replace('/\s+/', ' ',($numberwithDecimal));
}


function formatDecimalPart($decimalPart)
{
    $decimalDigits = [ '0' => 'Zero', '1' => 'One', '2' => 'Two', '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six', '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    ];
    $decimalPart = (int) $decimalPart;
    if ($decimalPart === 0) {
        return '';
    }
    $decimalDigitsArray = str_split($decimalPart);
    $decimalDigitsString = '';
    foreach ($decimalDigitsArray as $digit) {
        $decimalDigitsString .= $decimalDigits[$digit] . ' ';
    }
    return 'Rupee & ' . rtrim($decimalDigitsString) . ' Paise';
}

function getFileType($fileExtension) {
    $imageExtensions = ["jpg", "jpeg", "png", "gif", "bmp",'svg'];
    $pptExtensions = ["ppt", "pptx"];
    $pdfExtensions = ["pdf"];
    $docxExtensions = ["doc", "docx",];
    $excelExtensions = ["xls", "xlsx",'csv'];
    $videoExtensions = ["mp4", "avi", "mkv", "mov"];
    $audioExtensions = ["mp3", "wav", "ogg"];
    
    if (in_array($fileExtension, $imageExtensions)) {
        return "image";
    } elseif (in_array($fileExtension, $pptExtensions)) {
        return "ppt";
    } elseif (in_array($fileExtension, $pdfExtensions)) {
        return "pdf";
    } elseif (in_array($fileExtension, $docxExtensions)) {
        return "docx";
    } elseif (in_array($fileExtension, $excelExtensions)) {
        return "excel";
    } elseif (in_array($fileExtension, $videoExtensions)) {
        return "video";
    } elseif (in_array($fileExtension, $audioExtensions)) {
        return "audio";
    } else {
        return "image"; // If the extension is not recognized
    }
}
function getFileNameByUrl($path){
    return basename($path);
}
function getFilePathByUrl($path){
    if(in_array(getFileExtByUrl($path),['txt']))
    return $path;
   $fileType = getFileType(getFileExtByUrl($path));
   return route('preview.'.$fileType,['path' => urlencode($path)]);
}

function getFileExtByUrl($path){
    return pathinfo($path, PATHINFO_EXTENSION);
}

function removeDuplicatePatterns($text) {
    $pattern = '/(\d+ of \d+)/';
    preg_match_all($pattern, $text, $matches);

    $seen = [];
    foreach ($matches[0] as $match) {
        if (isset($seen[$match])) {
            // Remove the duplicate occurrence
            $text = str_replace_first($match, '', $text);
        } else {
            $seen[$match] = true;
        }
    }

    return $text;
}

function str_replace_first($from, $to, $subject) {
    $from = '/'.preg_quote($from, '/').'/';
    return preg_replace($from, $to, $subject, 1);
}

function extractContentBetweenPatterns($text) {
    $text = removeDuplicatePatterns($text);
    
    $pattern = '/(\d+) of (\d+)/';
    preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

    $results = [];
    $lastPos = 0;
    $numMatches = count($matches[0]);

    // Add content before the first match
    if ($numMatches > 0) {
        $firstMatchStart = $matches[0][0][1];
        $firstContent = substr($text, 0, $firstMatchStart);
        if (trim($firstContent) !== '') {
            $results[] = trim($firstContent);
        }
    }

    // Extract content between matches
    for ($i = 0; $i < $numMatches; $i++) {
        $start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
        $end = $i < $numMatches - 1 ? $matches[0][$i + 1][1] : strlen($text);

        $content = substr($text, $start, $end - $start);
        if (trim($content) !== '') {
            $results[] = trim($content);
        }
    }

    return $results;
}

function searchStringInsensitive($long_string, $search_string) {
    // Check for no data
    if (empty($long_string) || empty($search_string)) {
        return false;
    }

    // Search for the string (case-insensitive)
    if (stripos($long_string, $search_string) !== false) {
        return true;
    } else {
        return false;
    }
}
function searchDateFromText($paragraph){

    $datePatterns = [
        "/\b(?:\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}|\d{4}-\d{2}-\d{2})\b/",
        "/\b\d{1,2}\s+(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{2,4}\b/",
        "/\b(?:\d{2,4}-(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December)-(?:0[1-9]|[12][0-9]|3[01])|\d{1,2}\s+(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December))\s+\d{2,4}\b/",
        "/\b\d{1,2}\s+(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{1,2},?\s*\d{2,4}\b/",
        "/\b(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{1,2},?\s*\d{2,4}\b/",
    ];
    // Initialize an array to store matches with their positions
    $matchesWithPosition = [];

    // Iterate over each pattern and find matches
    foreach ($datePatterns as $pattern) {
        preg_match_all($pattern, $paragraph, $matches, PREG_OFFSET_CAPTURE);

        // Add matches to the array with their positions
        foreach ($matches[0] as $match) {
            $matchesWithPosition[] = ['match' => $match[0], 'position' => $match[1]];
        }
    }

    // Sort the array based on the position in the paragraph
    usort($matchesWithPosition, function ($a, $b) {
        return $a['position'] - $b['position'];
    });

    // Extract only the 'match' values
    $extractedDates = array_column($matchesWithPosition, 'match');


    return standardizeAndUniqueDates($extractedDates);
}

function removeBrackets($content){
    // Define an array of brackets to remove
    $bracketsToRemove = ['{', '}', '[', ']', '(', ')'];

    $cleanedText = str_replace($bracketsToRemove, '', $content);
    return $cleanedText;
}

function removeDuplicateWords($string) {
    $ignoreItems = [
        "is", "am", "are", "an", "the", "and", "or", "but", "nor", "so", "for", 
        "yet", "after", "although", "as", "because", "before", "once", "since", 
        "though", "till", "until", "when", "whenever", "where", "whereas", 
        "wherever", "while", "of", "in", "to", "with", "on", "at", "from", "by", 
        "about", "as", "into", "like", "through", "after", "over", "between", 
        "out", "against", "during", "without", "within", "along", "following", 
        "across", "behind", "beyond", "plus", "except", "but", "up", "down", 
        "off", "near", "be", "have", "do", "if", "no", "not", "this", "that", 
        "these", "those",'Version'
    ];
    // Split the string into words
    $words = preg_split('/\s+/', $string);

    // Array to keep track of words seen
    $seenWords = [];

    // Iterate over words
    foreach ($words as $index => $word) {
        $lowerWord = strtolower($word); // Convert to lowercase for case-insensitive comparison
        if (in_array($lowerWord, $ignoreItems) || strlen($word) <= 3) {
            continue; // Skip ignore items and words shorter than or equal to 3 characters
        }

        if (isset($seenWords[$lowerWord])) {
            // If the word has been seen before, remove this occurrence
            unset($words[$index]);
        } else {
            // Mark this word as seen
            $seenWords[$lowerWord] = true;
        }
    }

    // Reconstruct and return the string
    return implode(' ', $words);
}

function determineLocation($description = -1)
{
$patterns = [
    'HPI' => '/(history of present illness|HPI|current complaint details|symptoms began when|description of current illness|onset of the patient\'s symptoms|evolution of symptoms|detailed patient history)/i',
    'CC' => '/(chief complaint|CC|main concern|primary problem|reason for the visit|presenting complaint|patient states that|patient presents with|patient complaining of|reason for encounter)/i',
    'Past Medical History' => '/(past medical history|PMH|previous diagnoses|medical background|historical health information|patient\'s past health|prior medical conditions|health history|medical history review)/i',
    'Physical Examination' => '/(physical examination|physical exam findings|physical assessment|clinical examination|exam results|physical inspection|body examination|examined the patient|patient examination|inspection of)/i',
    'Problem List' => '/(problem list|list of current medical issues|ongoing health problems|active health issues|current diagnoses|ongoing medical concerns|patient\'s medical problems|current medical conditions|health issues list)/i',
    'Chronic Problem' => '/(chronic conditions|chronic issues|long-term health problems|persistent medical issues|ongoing chronic problems|long-standing health concerns|chronic health issues|long-term medical conditions)/i',
    'PMH/Problem List' => '/(complete medical and problem history|full health and problem history|comprehensive patient history and problem list|total medical history and current problems|all past health issues and present problems)/i',
    'PFSH' => '/(past, family, and social history|social and family medical background|patient\'s personal and family history|personal health history and family health|social, family, and personal medical history)/i',
    'Surgical History' => '/(surgical history|history of surgeries|past surgical procedures|list of previous surgeries|operative history|surgical background|previous surgical interventions|patient\'s surgery history|past operations)/i',
    'Assessment' => '/(medical assessment|clinical judgment|diagnostic conclusion|evaluation of patient|patient assessment|clinical evaluation|assessment of health|diagnosis and assessment|health assessment)/i',
    'Plan' => '/(treatment plan|management plan|care plan for patient|planned treatment|strategy for management|plan for care|treatment strategy|healthcare plan|patient care plan|planned healthcare interventions)/i',
    'Visit Diagnosis' => '/(diagnosis from visit|diagnosis during visit|visit-based diagnosis|diagnosis made during visit|conclusive diagnosis from visit|visit-related diagnosis|diagnosis at the time of visit|diagnosis following visit)/i',
    'Reason for Visit' => '/(reason for today\'s visit|purpose of current visit|patient here for|main reason for patient visit|why the patient came in today|patient\'s reason for today\'s appointment)/i',
    'Indication' => '/(indication for the procedure|reason for the test|rationale behind procedure|why the test is needed|basis for the chosen intervention|purpose of this medical intervention)/i',
    'Postoperative Diagnosis' => '/(postoperative findings|diagnosis after surgery|post-surgery condition|diagnosis following surgery|post-surgical diagnosis|findings after the operation)/i',
    'Preoperative Diagnosis' => '/(preoperative assessment|diagnosis before surgery|pre-surgery condition|initial diagnosis before surgery|before surgery assessment|pre-surgical diagnosis)/i',
    'Impression' => '/(clinical impression|initial impression|doctor\'s impression|diagnostic impression|first impression of condition|physician\'s initial thoughts|early diagnosis thoughts)/i',
    'Review of System' => '/(review of systems|systems review|complete system check|full body system review|organ system review|patient system analysis|comprehensive system examination)/i',
    'ROS' => '/(review of symptoms|symptom review|list of current symptoms|patient symptom report|documenting symptoms|symptoms reported by patient|systematic symptom check)/i',
    'Objective' => '/(objective findings|objective data|measurable patient data|observable signs from patient|objective clinical findings|quantifiable health indicators|measurable indicators|objective medical evidence)/i',
    'Subjective' => '/(subjective information|patient\'s perspective|patient-reported feelings|patient\'s subjective account|patient\'s own words|patient\'s self-report|patient\'s personal view|patient\'s description of)/i',
];
    if($description == -1){
        return $patterns;
    }

    foreach ($patterns as $section => $pattern) {
        if (preg_match($pattern, $description)) {
            return $section;
        }
    }

    return '--'; // Default if no pattern matches
}
function identifyRecordTypeFromExtracted($patternsToFind = -1)
{
    $patterns = [
        'Clinical Documentation' => '/(clinical notes|physician\'s notes|nursing notes|therapy notes|clinical observations|patient encounters|clinical data)/i',
        'History and Physical (H&P)' => '/(medical history|physical examination|H&P|history and physical|patient history|initial consultation|complete medical history|full physical examination)/i',
        'Operative Reports' => '/(operative report|surgical procedure detail|post-operative findings|surgery notes|surgical record|operative findings|surgery details|postoperative course)/i',
        'Progress Notes' => '/(progress notes|patient progress|hospital stay progress|outpatient visit summary|daily updates|treatment progress|follow-up notes|therapy progress)/i',
        'Discharge Summaries' => '/(discharge summary|hospital stay summary|patient discharge notes|post-hospitalization summary|discharge instructions|hospital discharge|summary of stay|discharge plan)/i',
        'Emergency Department Records' => '/(ER records|emergency department visit|ED summary|emergency visit details|ER visit notes|emergency room documentation|acute care records)/i',
        'Laboratory and Test Reports' => '/(lab results|test report|diagnostic test findings|laboratory data|test outcomes|laboratory findings|diagnostic results|lab data analysis)/i',
        'Pathology Reports' => '/(pathology report|tissue analysis|biopsy findings|pathological examination|pathologist\'s report|histology report|pathology findings|tissue examination results)/i',
        'Radiology Reports' => '/(radiology report|X-ray findings|CT scan report|MRI results|ultrasound findings|imaging results|radiological assessment|diagnostic imaging report)/i',
        'Pharmacy Records' => '/(medication records|prescription details|pharmacy notes|drug administration|medication regimen|pharmaceutical records|medication history|prescribed drugs)/i',
        'Billing Records' => '/(billing information|charge capture|itemized bill|service billing details|financial records|patient billing|healthcare charges|billing summary)/i',
        'Outpatient Records' => '/(outpatient visit|clinic visit record|outpatient surgery details|outpatient care summary|clinic records|outpatient treatment|outpatient services|non-admission visit)/i',
        'Consultation Notes' => '/(consultation notes|specialist consultation|referral notes|consult notes|consultation summary|referral response|specialist advice|consultation report)/i',
        'Anesthesia Records' => '/(anesthesia records|anesthetic report|anesthesia chart|anesthetist\'s notes|sedation record|anesthesia documentation|anesthesia log|pre-anesthesia evaluation)/i',
        'Rehabilitation Notes' => '/(rehabilitation notes|rehab notes|therapy session notes|rehabilitation plan|physical therapy records|occupational therapy notes|rehabilitation progress|therapy documentation)/i',
        'Mental Health Records' => '/(mental health records|psychiatric evaluation|mental health notes|psychological assessment|therapy notes|counseling session records|psychiatric notes|mental health treatment)/i',
        'Nursing Home Records' => '/(nursing home records|long-term care notes|resident care notes|nursing facility documentation|elder care records|caregiver notes|nursing home chart|long-term care documentation)/i',
        'Immunization Records' => '/(immunization records|vaccination history|vaccine administration|immunization chart|vaccination records|inoculation history|immunization log|vaccine log)/i',
    ];

    $matchingKey = null;

    foreach ($patterns as $key => $pattern) {
        if (preg_match($pattern, $patternsToFind)) {
            $matchingKey = $pattern;
            break;
        }
    }
    return $matchingKey; // Default if no pattern matches
}

function identifyRecordType($text = -1)
{
    $patterns = [
    'Clinical Documentation' => '/(clinical notes|physician\'s notes|nursing notes|therapy notes|clinical observations|patient encounters|clinical data)/i',
    'History and Physical (H&P)' => '/(medical history|physical examination|H&P|history and physical|patient history|initial consultation|complete medical history|full physical examination)/i',
    'Operative Reports' => '/(operative report|surgical procedure detail|post-operative findings|surgery notes|surgical record|operative findings|surgery details|postoperative course)/i',
    'Progress Notes' => '/(progress notes|patient progress|hospital stay progress|outpatient visit summary|daily updates|treatment progress|follow-up notes|therapy progress)/i',
    'Discharge Summaries' => '/(discharge summary|hospital stay summary|patient discharge notes|post-hospitalization summary|discharge instructions|hospital discharge|summary of stay|discharge plan)/i',
    'Emergency Department Records' => '/(ER records|emergency department visit|ED summary|emergency visit details|ER visit notes|emergency room documentation|acute care records)/i',
    'Laboratory and Test Reports' => '/(lab results|test report|diagnostic test findings|laboratory data|test outcomes|laboratory findings|diagnostic results|lab data analysis)/i',
    'Pathology Reports' => '/(pathology report|tissue analysis|biopsy findings|pathological examination|pathologist\'s report|histology report|pathology findings|tissue examination results)/i',
    'Radiology Reports' => '/(radiology report|X-ray findings|CT scan report|MRI results|ultrasound findings|imaging results|radiological assessment|diagnostic imaging report)/i',
    'Pharmacy Records' => '/(medication records|prescription details|pharmacy notes|drug administration|medication regimen|pharmaceutical records|medication history|prescribed drugs)/i',
    'Billing Records' => '/(billing information|charge capture|itemized bill|service billing details|financial records|patient billing|healthcare charges|billing summary)/i',
    'Outpatient Records' => '/(outpatient visit|clinic visit record|outpatient surgery details|outpatient care summary|clinic records|outpatient treatment|outpatient services|non-admission visit)/i',
    'Consultation Notes' => '/(consultation notes|specialist consultation|referral notes|consult notes|consultation summary|referral response|specialist advice|consultation report)/i',
    'Anesthesia Records' => '/(anesthesia records|anesthetic report|anesthesia chart|anesthetist\'s notes|sedation record|anesthesia documentation|anesthesia log|pre-anesthesia evaluation)/i',
    'Rehabilitation Notes' => '/(rehabilitation notes|rehab notes|therapy session notes|rehabilitation plan|physical therapy records|occupational therapy notes|rehabilitation progress|therapy documentation)/i',
    'Mental Health Records' => '/(mental health records|psychiatric evaluation|mental health notes|psychological assessment|therapy notes|counseling session records|psychiatric notes|mental health treatment)/i',
    'Nursing Home Records' => '/(nursing home records|long-term care notes|resident care notes|nursing facility documentation|elder care records|caregiver notes|nursing home chart|long-term care documentation)/i',
    'Immunization Records' => '/(immunization records|vaccination history|vaccine administration|immunization chart|vaccination records|inoculation history|immunization log|vaccine log)/i',
];
    if($text == -1){
        return $patterns;
    }

    foreach ($patterns as $recordType => $pattern) {
        if (preg_match($pattern, $text)) {
            return $recordType;
        }
    }

    return '--'; // Default if no pattern matches
}

function pageStringFormat($input) {
    $maxLength = 100;
    $ellipsis = ' ... ';

    if (strlen($input) <= $maxLength) {
        return $input;
    }

    // Length of the starting and ending parts
    $partLength = ($maxLength - strlen($ellipsis)) / 2;

    $start = substr($input, 0, floor($partLength));
    $end = substr($input, -ceil($partLength));

    return $start . $ellipsis . $end;
}

function getExtractedContent($proceededContentArray){
    $dxs =  json_decode(file_get_contents(public_path('json/dx-directories.json')));
    $orgs =  json_decode(file_get_contents(public_path('json/org-directories.json')));
    $result = [];
    foreach($proceededContentArray as $key => $proceededContent){

        $proceededContent = removeBrackets($proceededContent);
        $pageNo = $key + 1;
        $filteredContent = removeDuplicateWords($proceededContent);
        // Searching Dx
        foreach($dxs as $dxCode => $dx){
            if(searchStringInsensitive($filteredContent,$dx)){
                $result[$pageNo]['dx'][] = $dxCode; 
                $result[$pageNo]['dx_desc'][] = $dx; 
            }
        }
        // Searching Doctor Name
        foreach($orgs as $orgCode => $org){
            if(searchStringInsensitive($proceededContent,$org)){
                $result[$pageNo]['org'][] = $org; 
            }
        }
        // Searching Dos
            $result[$pageNo]['dos'] = searchDateFromText($proceededContent,$org);
           
           
        // Searching Location
        $result[$pageNo]['loc'] = determineLocation($proceededContent);
        
         // Searching Record Type
        $result[$pageNo]['rt'] = identifyRecordType($proceededContent);
       
    }
    return $result;
}

function standardizeAndUniqueDates(array $dates) {
    $standardDates = [];
    // Extended list of date formats
    $formats = [
        'd-m-Y', 'Y-m-d', 'd/m/Y', 'F d Y', // Basic formats
        'm-d-Y', 'm/d/Y', 'd-M-Y', 'd.M.Y', // Variations with different separators
        'Y/m/d', 'Y M d', 'd M, Y', 'M d, Y', // Year first formats
        'Ymd', 'dmY', 'mdY', 'dmy', // Formats without separators
        'jS F Y', // Formats with ordinal numbers
        'd-F-Y', // Formats with full month name
        'd-F-y', // Formats with two-digit year
        // Add more formats as required
    ];

    foreach ($dates as $dateString) {
        $dateFound = false;
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $dateString);
            if ($date && $date->format($format) == $dateString) {
                // Successfully parsed the date
                $standardizedDate = $date->format('m/d/Y');
                if (!in_array($standardizedDate, $standardDates)) {
                    $standardDates[] = $standardizedDate;
                }
                $dateFound = true;
                break; // Break the inner loop once date is parsed
            }
        }

        if (!$dateFound) {
            // Handle the case where the date format was not recognized
            // You can either add the original string or ignore it
            // For example, to ignore unrecognized formats, do nothing here
            // To add the original string, uncomment the next line:
            // $standardDates[] = $dateString;
        }
    }
        // Sort dates chronologically
        usort($standardDates, function($a, $b) {
            $dateA = DateTime::createFromFormat('m/d/Y', $a);
            $dateB = DateTime::createFromFormat('m/d/Y', $b);
        
            return $dateA <=> $dateB;
        });
        
    return  $standardDates;
}
function cleanString($input) {
    // Replace more than two spaces with a single space
    $input = preg_replace('/ {2,}/', ' ', $input);

    // Replace more than one newline with a single newline
    $input = preg_replace('/(\r?\n){2,}/', "\n", $input);

    return $input;
}



if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        // Parse the original date using Carbon with the specified format
        $carbonDate = Carbon\Carbon::createFromFormat('d/m/Y', $date);

        $formattedDate = $carbonDate->format('m/d/Y');
        return $date;
    }
}

if (!function_exists('pushActivityLog')) {
    function pushActivityLog($chart,$remark)
    {
        $authId = auth()->id();
        App\Models\ActivityLog::create([
            'model_id' => $authId,
            'model_type' => App\Models\User::class,
            'title' => auth()->user()->name." has ".$remark." chart ".$chart->getPrefix(),
            'description' => null,
            'related_id' => $chart->id,
            'related_type' => App\Models\Chart::class,
            'record_type' =>2
        ]);
        return true;
    }
}

if (! function_exists('getProcessingModes')) {
    function getProcessingModes($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>0,'label'=>"GPT",'name'=>'Smart Mode'],
                // ['id'=>1,'label'=>"detectEntities", "name"=>'Advance Mode'],
                // ['id'=>2,'label'=>"detectEntitiesV2", "name"=>'Advance Mode (V2)'],
            ];
            }else{
                foreach(getProcessingModes() as $row){
                    if($id == $row['id']){
                    return $row;
                }
            }
            return ['id'=>0,'label'=>''];
        }
    }
}
if (! function_exists('getSearchType')) {
    function getSearchType($id = -1)
    {
        if($id == -1){
            return [
                ['id'=>0,'label'=>"normal",'name'=>'Normal Search'],
                ['id'=>1,'label'=>"exact",'name'=>'Exact Search'],
                ['id'=>2,'label'=>"begin", "name"=>'Begin Search'],
                ['id'=>3,'label'=>"end", "name"=>'End Search'],
            ];
            }else{
                foreach(getSearchType() as $row){
                    if($id == $row['id']){
                    return $row;
                }
            }
            return ['id'=>0,'label'=>''];
        }
    }
}
function getYears()
{
    $years = array(
        0 => "2020",
        1 => "2021",
        2 => "2022",
        3 => "2023",
        4 => "2024",
        5 => "2025",
        6 => "2026",
        7 => "2027",
        8 => "2028",
        9 => "2029",
        10 => "2030",
    );
    return $years;
}

function getYearBadgeColor($year) {
    $colors = [
        '2020' => 'red', '2021' => 'blue', '2022' => 'green', '2023' => 'red', '2024' => 'orange', '2025' => 'yellow', '2026' => 'purple', '2027' => 'cyan', '2028' => 'magenta', '2029' => 'green', '2030' => 'brown'
    ];

    // If the year is not found in the $colors array, default to a certain color
    $defaultColor = 'green';

    return $colors[$year] ?? $defaultColor;
}

if (!function_exists('generateUniqueGroupId')) {
    function generateUniqueGroupId($input)
    {
        $input = str_replace(',', '-', $input);
        $filteredInput = \Str::limit(strtoupper($input),10);
        $group = generateRandomString(8); // Adjust the length as needed
        $checkExist = App\Models\ProjectEntry::where("group", "=", $group)->first();
        if($checkExist){
            return generateUniqueGroupId($input);
        }else{
            return $filteredInput.'-'.$group;
        }
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

function getExcludeDescription($medicalConditions) {
    $descriptions = [];

    foreach ($medicalConditions as $medicalCondition) {
        $matcher = $medicalCondition->code;
        $matchend = $medicalConditions->whereNotIn('code', $matcher);

        foreach ($matchend as $match) {
            $matchedFromCode = App\Models\Exclude::where(function ($query) use ($matcher, $match) {
                $query->where('from_code', $matcher)->where('to_code', $match->code);
            })->orWhere(function ($query) use ($matcher, $match) {
                $query->where('from_code', $match->code)->where('to_code', $matcher);
            })->first();

            if ($matchedFromCode) {
                $descriptions[$matchedFromCode->id] = $matchedFromCode->description;
            }
        }
        
    }
    return $descriptions;
}

function getCombinationDescription($medicalConditions) {
    $descriptions = [];
    $combinations = App\Models\CombinationCode::get();
    foreach ($combinations as $combination) {
        $codesVariables = explode(',',$combination->codes[0]);
        $inputCode = $medicalConditions->pluck('code')->toArray();
        // Use array_intersect to find common elements
        $commonCodes = array_intersect($codesVariables, $inputCode);
        if(!empty($commonCodes)){
            $descriptions[$combination->id] = $combination->description;
        }
    }
    return $descriptions;
}


// Update the timestamp for the last run of a specific cron job.
if (!function_exists('updateCronJobTime')) {
    function updateCronJobTime($key)
    {
        $lastCronRunAt = App\Models\Setting::where('key',$key)->first();
        // If no record is found, create a new entry with the current timestamp.
        if(!$lastCronRunAt){
            App\Models\Setting::create([
                'key' => $key,
                'value' => now(),
                'group_name' => 'cron_logics',
            ]);
        }else{
            // If a record is found, update the timestamp to the current time.
            $lastCronRunAt->value = now();
            $lastCronRunAt->group = 'cron_logics';
            $lastCronRunAt->save();
        }
    }
}

function splitContent($content, $keyword) {
    // Split the content based on the keyword
    $chunks = explode($keyword, $content);

    // Initialize an array to hold the filtered chunks
    $filteredChunks = [];
    
    // Iterate over chunks starting from the second element to skip the initial content before the first keyword
    foreach ($chunks as $index => $chunk) {
        // Skip the first chunk as it is before the first keyword
        if ($index == 0) continue;
        
        $trimmedChunk = trim($chunk);
        // Append keyword and trimmed chunk to the filtered chunks
        if ($trimmedChunk !== '') {
            $filteredChunks[] = $keyword . "\n" . $trimmedChunk;
        }
    }

    return $filteredChunks;
}
function getCoderPrompt(){
    $coder = App\Models\MailSmsTemplate::where('title', 'Medical Coder')->first();
    if($coder && $coder->body != null){
        $prompt = $coder->body;
    }else{
        $prompt = 'Role: Senior Doctor & Medical Coding Specialist
    
        Task: Check given content and provide a ICD10 codes of medical conditions.
    
        Criteria:
        Given content check disease and provide ICD10 codes.
        There are multiple ICD10 codes on this given content.
        Avoid duplicate ICD10 codes.
    
        Output Format:
        [
                {
                        "doctor_name": "",
                        "from_dos": "",
                        "to_dos": "",
                        "location": "",
                        "findings": [
                                {
                                        "disease_name": "",
                                        "icd10_code": "",
                                        "comment": "", // Remember to note the reference keyword so the coder can search it in text if needed.
                                },
                                ...
                        ]
                },
                ...
        ]
    
        Condition:
        If no relevant records found return "404" only.
    
        Note:
        Don’t write anything else.';
    }

    return $prompt;
}

function getReviewerPrompt(){
    $reviewer = App\Models\MailSmsTemplate::where('title', 'Medical Reviewer')->first();
    if($reviewer && $reviewer->body != null){
        $prompt = $reviewer->body;
    }else{
        $prompt = 'Role: Senior Doctor & Medical Coding Specialist

        Task: Verify and correct JSON content against the provided text content.

        Criteria:
        Thoroughly check each key in the JSON against the provided text content.
        Ensure all key information is accurate, verified, and properly aligned with the text content.

        Note: 
        The information is highly confidential and sensitive. Handle it with utmost care and attention.
        Pls follow Output conditions. Do not write anything else.

        Structure Definition:
        [
                {
                        "doctor_name": // This is the name of the Doctor
                        "from_dos": // This is the date of Encounter (range between 2023 to 2024)
                        "to_dos": // This is the date of Encounter (range between 2023 to 2024)
                        "location": // This is the name of the medical facility
                        "findings": [
                                {
                                        "disease_name":
                                        "icd10_code":
                                        "comment": // Remember to note the reference headline name so the coder can recheck it if needed.
                                },
                                ...
                        ]
                },
                ...
        ]

        Output Conditions:
        If the provided JSON is correct and no changes needed return "200".
        If the provided JSON is invalid return "404".
        If corrections are possible, provide the corrected and accurate version of JSON only.';
    }

    return $prompt;
}

function getHCCFinderPrompt(){
    $hcc_finder = App\Models\MailSmsTemplate::where('title', 'Medical HCC Finder')->first();
    if($hcc_finder && $hcc_finder->body != null){
        $prompt = $hcc_finder->body;
    }else{
        $prompt = 'Role: Senior Doctor & Medical Coding Specialist

        Task: Check given diagnosis based on new line and provide a HCC values of medical conditions like rx, cms, esrd.

        Criteria:
        Given content check diagnosis and provide HCC values.

        Output Format:
        [
            {
                "diagnosis": "",
                "rx": "",
                "cms": "",
                "esrd": ""
            },
            ...
        ]

        Condition:
        If no relevant records found return "404" only.

        Note:
        Don’t write anything else.';
    }

    return $prompt;
}

// process Coder
function processCoder($content){
    $prompt = getCoderPrompt();
    $response = Http::withToken(env('CHATGPT_API_KEY'))
         ->timeout(600) 
        ->post('https://api.openai.com/v1/chat/completions', [
          'model' => 'gpt-4o-mini',
          'messages' => [[
              'role' => 'system',
              'content' => $prompt
          ], [
              'role' => 'user',
              'content' => $content,
          ]],
      ])
      ->throw()
      ->json();
    $output = $response['choices'][0]['message']['content'];
    return $output;
}

// process HCC Reviewer
function processReviewer($content){
    $prompt = getReviewerPrompt();
    $response = Http::withToken(env('CHATGPT_API_KEY'))
         ->timeout(600) 
        ->post('https://api.openai.com/v1/chat/completions', [
          'model' => 'gpt-4o-mini',
          'messages' => [[
              'role' => 'system',
              'content' => $prompt
          ], [
              'role' => 'user',
              'content' => $content,
          ]],
      ])
      ->throw()
      ->json();
    $output = $response['choices'][0]['message']['content'];
    return $output;
}

// process HCC finder
function processHCCFinder($content){
    $prompt = getHCCFinderPrompt();
    $response = Http::withToken(env('CHATGPT_API_KEY'))
         ->timeout(600) 
        ->post('https://api.openai.com/v1/chat/completions', [
          'model' => 'gpt-4o-mini',
          'messages' => [[
              'role' => 'system',
              'content' => $prompt
          ], [
              'role' => 'user',
              'content' => $content,
          ]],
      ])
      ->throw()
      ->json();
    $output = $response['choices'][0]['message']['content'];
    return $output;
}

function extractionTestFromConvertApi($pdfPath){
    \ConvertApi\ConvertApi::setApiSecret(env('CONVERT_API_KEY'));
    $result = \ConvertApi\ConvertApi::convert('txt', ['File' => $pdfPath]);
    return $result->getFile()->getContents();
}

function isExactMatchCaseInsensitive($rawString, $searchString) {
    // Convert both strings to a common case (lowercase) for comparison
    $normalizedSearchString = preg_quote(mb_strtolower($searchString, 'UTF-8'), '/');
    $pattern = '/\b' . $normalizedSearchString . '\b/i';

    // Perform a case-insensitive regex match with word boundaries
    return preg_match($pattern, $rawString) === 1;
}

// calculate chart progress
function calculateChartProgress($chartId) {
    $chunks = App\Models\ChartChunk::where('chart_id', $chartId)->get();

    $totalStatuses = 7; // Total number of statuses per chunk
    $totalChunks = $chunks->count();
    $totalProgress = 0;

    foreach ($chunks as $chunk) {
        $completedStatuses = 0;

        // Check each status and count if it's completed
        if ($chunk->status == App\Models\ChartChunk::STATUS_COMPLETED) $completedStatuses++;
        if ($chunk->entry_sync_status == App\Models\ChartChunk::ENTRY_SYNC_STATUS_COMPLETED) $completedStatuses++;
        if ($chunk->mc_sync_status == App\Models\ChartChunk::MC_SYNC_STATUS_COMPLETED) $completedStatuses++;
        if ($chunk->hcc_sync_status == App\Models\ChartChunk::HCC_SYNC_STATUS_COMPLETED) $completedStatuses++;
        if ($chunk->native_sync_status == App\Models\ChartChunk::NATIVE_SYNC_STATUS_COMPLETED) $completedStatuses++;
        if ($chunk->location_sync_status == App\Models\ChartChunk::LOCATION_SYNC_STATUS_COMPLETED) $completedStatuses++;
        if ($chunk->rt_sync_status == App\Models\ChartChunk::RT_SYNC_STATUS_COMPLETED) $completedStatuses++;

        // Calculate progress for this chunk
        $chunkProgress = ($completedStatuses / $totalStatuses) * 100;
        $totalProgress += $chunkProgress; // Aggregate progress
    }

    // Calculate overall progress by averaging the progress across all chunks
    $overallProgress = $totalChunks > 0 ? $totalProgress / $totalChunks : 0;

    return $overallProgress; // Return the overall progress as a percentage
}



function syncCrons($key = null){
        
    $crons = [
        'cron/extract-pdf-text/HTFW24535',
        'cron/chunk-convertor/GTFD3421',
        'cron/chunk-medical-coder/NHUY6547',
        'cron/chunk-entry-sync/BHYT6543',
        'cron/native-icd-sync/UHGT5437',
        'cron/medical-hcc-finder/NHUM7683',
        'cron/medical-condition-sync/BGFC4378',
        'cron/location-sync/NHG5478',
        'cron/record-type-sync/GHBV4378',
    ];

    if($key){
        $url = $crons[$key];
        try {
            $response = Http::get(url($url));

            // Store each response (or data) in the responses array
            $responses[] = [
                'url' => url($url),
                'status' => $response->status(),
                'data' => $response->json(), // You can use $response->body() if you need the raw response
            ];
        } catch (\Exception $e) {
            // Handle the exception and store error details
            $responses[] = [
                'url' => $url,
                'error' => $e->getMessage(),
            ];
        }
    }else{
        foreach ($crons as $url) {
            try {
                $response = Http::get(url($url));
    
                // Store each response (or data) in the responses array
                $responses[] = [
                    'url' => url($url),
                    'status' => $response->status(),
                    'data' => $response->json(), // You can use $response->body() if you need the raw response
                ];
            } catch (\Exception $e) {
                // Handle the exception and store error details
                $responses[] = [
                    'url' => $url,
                    'error' => $e->getMessage(),
                ];
            }
        }
    }
    \Log::info($responses);

    // Return all the responses as a JSON response
    return response()->json($responses);

}