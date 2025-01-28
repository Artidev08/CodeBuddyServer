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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParagraphContent;
use Illuminate\Http\Request;

class ParagraphContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {
            $paragraphContents = ParagraphContent::whereIn('code', $request->codes)->get();
            //response
            if ($paragraphContents) {
                return $this->success($paragraphContents);
            } else {
                return $this->errorOk('Paragraph Data Does not exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
}
