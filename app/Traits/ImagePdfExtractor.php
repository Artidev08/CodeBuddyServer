<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\PdfToText\Pdf;
use Asika\Pdf2text;
use Aws\S3\S3Client;
use Aws\Textract\TextractClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
// use Spatie\PdfToImage\Pdf;
use \ConvertApi\ConvertApi;

trait ImagePdfExtractor
{
    public function StoreImage($request){
        $image = $request->encounter_type_file;
        $imageName = 'image_scan'.rand(00000, 99999).'.' . $image->getClientOriginalExtension();
        $imagePath = public_path('member/img/temp/'.$imageName);
        $path = public_path() . '/member/img/temp/';
        $image->move($path, $imageName);
        $output = $this->extractContentFromAws($imageName,$imagePath);
        unlinkfile(public_path() . '/member/img/temp', $imageName);
        return $output;
    }

    public function storeSnippetImage($request){
        $snip_extraction_type = $request->snip_extraction_type;
        $folderPath = public_path('member/img/temp/');
        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $imageName = uniqid() . '.png';
        $imagePath = $folderPath.$imageName;
        file_put_contents($imagePath, $image_base64);
        if($snip_extraction_type == 'Lens'){
            $output = $this->extractContentFromGoogleVision($imagePath);
        }elseif($snip_extraction_type == 'AWS'){
            $output = $this->extractContentFromAws($imageName,$imagePath);
        }
        unlinkfile(public_path() . '/member/img/temp', $imageName);
        return $output;
    }
   
    public function StorePdf($request){
        $pdf = $request->encounter_type_pdf;
        $path = public_path() . '/member/pdf/temp/';
        $pdfName = 'pdf_scan'.rand(00000, 99999).'.' . $pdf->getClientOriginalExtension();
        $pdf->move($path, $pdfName);
        $pdfPath = public_path('member/pdf/temp/'.$pdfName); 
        $output = '';
        //if extraction type is Pdf2Text
        if($request->extraction_type == 'Pdf2Text'){
            $pdf2text = new Pdf2text();
            $output = $pdf2text->decode($pdfPath);
        }
        //if extraction type is Parser
        elseif($request->extraction_type == 'Parser'){
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($pdfPath);
            $output = $pdf->getText();
        }
        //if extraction type is AWS
        elseif($request->extraction_type == 'AWS'){
            $output = $this->extractContentFromAws($pdf,$pdfPath);
        }
        //if extraction type is Vision
        elseif($request->extraction_type == 'ConvertApi'){
            // $pdfPath = $pdfPath;
            // $outputPath = public_path('member/img/'); 
            // $pdf = new Pdf($pdfPath);
            // $pdf->setResolution(300);
            // $pdf->setOutputFormat('jpg');
            // $pdf->setPage(1)->saveImage($outputPath);
            // $pdf->saveImage($outputPath);
            // dd('sd');
            // $output = $this->extractContentFromGoogleVision($pdfPath,'pdf');
            // dd($pdfPath);
            ConvertApi::setApiSecret('mz2DiqHQQfbEUdjF');
            $result = ConvertApi::convert('txt', ['File' => $pdfPath]);
            $output = $result->getFile()->getContents();
        }
        unlinkfile(public_path() . '/member/pdf/temp', $pdfName);
        return $output;
    }

    public function UploadPDFTextExtractionTest($pdfPath){
       
        ConvertApi::setApiSecret('mz2DiqHQQfbEUdjF');
            $result = ConvertApi::convert('txt', ['File' => $pdfPath]);
          return $result->getFile()->getContents();
    }
}
