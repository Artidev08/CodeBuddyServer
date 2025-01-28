<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Media;
use PhpOffice\PhpWord\IOFactory;

class UtilityController extends Controller
{
   public function image(Request $request) {
      $path = str_replace(env('APP_URL'), env('CUSTOM_URL'), urldecode($request->path));
        return view('utilities.image',compact('path'))->render();
   }
   public function ppt(Request $request) {
      $path = str_replace(env('APP_URL'), env('CUSTOM_URL'), urldecode($request->path));
        return view('utilities.ppt',compact('path'))->render();
   }
   public function pdf(Request $request) {
      $path = str_replace(env('APP_URL'), env('CUSTOM_URL'), urldecode($request->path));
        return view('utilities.pdf',compact('path'))->render();
   }
   public function docx(Request $request) {
      $path = str_replace(env('APP_URL'), env('CUSTOM_URL'), urldecode($request->path));
        return view('utilities.docx',compact('path'))->render();
   }
   public function excel(Request $request) {
      $path = str_replace(env('APP_URL'), env('CUSTOM_URL'), urldecode($request->path));
        return view('utilities.excel',compact('path'))->render();
   }
   public function video(Request $request) {
      $path = str_replace(env('APP_URL'), env('CUSTOM_URL'), urldecode($request->path));
        return view('utilities.video',compact('path'))->render();
   }
   public function audio(Request $request) {
      $path = str_replace(env('APP_URL'), env('CUSTOM_URL'), urldecode($request->path));
        return view('utilities.audio',compact('path'))->render();
   }
}
