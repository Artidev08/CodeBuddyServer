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

use App\Http\Requests\MediaRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Hash;
use DateTimeZone;

class MediaController extends Controller
{
  
    public function destroy(MediaRequest $request)
    {
        try {
            $model = "\App\Models\\".$request->model_type;
            $record = $model::whereId($request->model_id)->first();
            if ($record) {
                if ($record->getMedia($request->media)->count()) {
                    $record->clearMediaCollection($request->media);
                }
                if ($request->ajax()) {
                    return response()->json(['message'=>'Media deleted successfully'], 200);
                } else {
                    return back()->with('success', 'Media deleted successfully');
                }
            } else {
                if ($request->ajax()) {
                    return response()->json(['error'=>'Media not found'], 401);
                } else {
                    return back()->with('error', 'Media not found');
                }
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error'=>$e->getMessage()], 500);
            } else {
                return back()->with('error', 'There was an error: ' . $e->getMessage());
            }
        }
    }
    public function destroyById($id, MediaRequest $request)
    {
        try {
            $media = Media::find($id);
            if ($media) {
                $model_type = $media->model_type;

                $model = $model_type::find($media->model_id);
                $model->deleteMedia($media->id);
                if ($request->ajax()) {
                    return response()->json(['message'=>'Media deleted successfully'], 200);
                } else {
                    return back()->with('success', 'Media deleted successfully');
                }
            } else {
                if ($request->ajax()) {
                    return response()->json(['error'=>'Media not found'], 401);
                } else {
                    return back()->with('error', 'Media not found');
                }
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error'=>$e->getMessage()], 500);
            } else {
                return back()->with('error', 'There was an error: ' . $e->getMessage());
            }
        }
    }
    public function ckeditorUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
         
                $request->file('upload')->move(str_replace('/core/public',"",public_path('media')), $fileName);
            $url = asset('media/' . $fileName);
            // $url = env('APP_URL').'/media/'. $fileName;
  
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }
}
