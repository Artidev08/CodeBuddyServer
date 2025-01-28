<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
// use Spatie\PdfToText\Pdf;
use Asika\Pdf2text;
use Aws\S3\S3Client;
use Aws\Textract\TextractClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Spatie\PdfToImage\Pdf;

trait GoogleVisionExtractor
{
    //extract content from Google Vision
    public function extractContentFromGoogleVision($filePath,$type = 'image'){
        $pathToJsonKeyFile = public_path('member/google-credentials/credentials.json');
        $imageAnnotator = new ImageAnnotatorClient([
            'credentials' => $pathToJsonKeyFile,
        ]);
        $image = file_get_contents($filePath);
        $response = $imageAnnotator->textDetection($image);
        $annotations = $response->getTextAnnotations();
        if($type == 'image'){
            $output = $annotations[0]->getDescription();
        }else{
            $output = '';
            foreach ($annotations as $text) {
                $output .= $text->getDescription() . "\n";
            }
        }
        return $output;
    }
}
