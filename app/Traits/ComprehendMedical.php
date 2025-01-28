<?php
namespace App\Traits;
use Aws\S3\S3Client;
use Aws\Textract\TextractClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Aws\ComprehendMedical\ComprehendMedicalClient;

trait ComprehendMedical
{
    /**
     * Extracts medical information using AWS Comprehend Medical API version 2.
     *
     * @param Request $request The request object containing the necessary data.
     * @return mixed The extracted medical information from version 2.
     */
    public function extractMedicalInfoV2($text)
    {
        try {
            // Implementation for AWS Comprehend Medical extraction V2. Placeholder for actual method.
            $comprehendMedical = $this->comprehendAWSConfiguration();
            $params = [
                'Text' => $text,
            ];
            $result = $comprehendMedical->detectEntitiesV2($params);
            $entities = isset($result['Entities']) ? $result['Entities'] : array();
            return $entities;
        } catch (Exception $e) {
            error_log('Error inferring detectEntitiesV2: ' . $e->getMessage());
            throw $e; // Re-throw to allow for handling in calling code
        }
    }

    // Implementation for AWS Comprehend Medical extraction. Placeholder for actual method.
    public function extractMedicalInfo($text) {
        try {
            $comprehendMedical = $this->comprehendAWSConfiguration();
            $params = [
                'Text' => $text,
            ];
            $result = $comprehendMedical->detectEntities($params);
            $entities = isset($result['Entities']) ? $result['Entities'] : array();
            return $entities;
        } catch (Exception $e) {
            error_log('Error inferring detectEntities: ' . $e->getMessage());
            throw $e; // Re-throw to allow for handling in calling code
        }
    }


    private function comprehendAWSConfiguration(){
        return $this->comprehendMedical = new ComprehendMedicalClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'), // Replace with your AWS region
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

}
