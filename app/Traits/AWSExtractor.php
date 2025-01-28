<?php
namespace App\Traits;
use Aws\S3\S3Client;
use Aws\Textract\TextractClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Aws\ComprehendMedical\ComprehendMedicalClient;

trait AWSExtractor
{
    //extract content from AWS
    public function extractContentFromAws($fileName,$filePath){
        $bucketName = 'snippetimage';
        $keyName = 'images/' . $fileName;
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $textract = new TextractClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $s3->putObject([
            'Bucket'     => $bucketName,
            'Key'        => $keyName,
            'SourceFile' => $filePath,
        ]);
        $result = $textract->startDocumentTextDetection([
            'DocumentLocation' => [
                'S3Object' => [
                    'Bucket' => $bucketName,
                    'Name'   => $keyName,
                ],
            ],
        ]);
        $jobId = $result['JobId'];
        do {
            $jobStatus = $textract->getDocumentTextDetection(['JobId' => $jobId])['JobStatus'];
            if ($jobStatus === 'SUCCEEDED') {
                break;
            } 
            // elseif ($jobStatus === 'FAILED' || $jobStatus === 'PARTIAL_SUCCESS') {
            //     // Handle job failure or partial success
            //     return 'Error: Textract job failed or partially succeeded.';
            // }
            // sleep(5);
        } while (true);
        $result = $textract->getDocumentTextDetection(['JobId' => $jobId]);
        $detectedText = '';
        foreach ($result['Blocks'] as $block) {
            if ($block['BlockType'] == 'LINE') {
                $detectedText .= $block['Text'] . "\n";
            }
        }
        $s3->deleteObject([
            'Bucket' => $bucketName,
            'Key'    => $keyName,
        ]);
        return $detectedText;
    }

    //extract content from AWS
    public function extractContentFromAwsOld($fileName,$filePath){
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $detectedText = '';
        $sample_document = [];
        $bucketName = 'snippetimage';
        $keyName = 'images/'.$fileName;
        $result = $s3->putObject([
            'Bucket' => $bucketName,
            'Key'    => $keyName,
            'SourceFile' => $filePath,
        ]);
        $textract = new TextractClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $result = $textract->detectDocumentText([
            'Document' => [
                'S3Object' => [
                    'Bucket' => $bucketName,
                    'Name' => $keyName,
                ],
            ],
        ]);
        foreach ($result->get('Blocks') as $block) {
            if ($block['BlockType'] == 'LINE') {
                $detectedText .= $block['Text'] . "\n";
            }
        }
        $result = $s3->deleteObject([
            'Bucket' => $bucketName,
            'Key' => $keyName,
        ]);
        return $detectedText;
    }


    //using ComprehendMedicalClient for getting medical information
    function inferICD10CM($text) {
        $comprehendMedical = new ComprehendMedicalClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'), // Replace with your AWS region
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        try {
            $params = [
                'Text' => $text,
            ];
            $result = $comprehendMedical->inferICD10CM($params);
            $ICD10CMConcepts = null;
            if($result['Entities']){
                $ICD10CMConcepts = $result['Entities'][0]['ICD10CMConcepts'];
            }

            return $ICD10CMConcepts;
        } catch (Exception $e) {
            error_log('Error inferring ICD10CM codes: ' . $e->getMessage());
            throw $e; // Re-throw to allow for handling in calling code
        }
    }

}
