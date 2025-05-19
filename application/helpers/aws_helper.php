<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'aws/aws-autoloader.php');

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Store client in global variable
$GLOBALS['aws_s3_client'] = null;

// Define default bucket here (change this to your actual bucket)
define('AWS_DEFAULT_BUCKET', getenv('S3_BUCKET_NAME'));

// Singleton S3 client
if (!function_exists('get_s3_client')) {
    function get_s3_client()
    {
        if ($GLOBALS['aws_s3_client'] === null) {
            $GLOBALS['aws_s3_client'] = new S3Client([
                'region' => 'us-east-1', // change to your region
                'version' => 'latest',
                'credentials' => [
                    'key'    => getenv('AWS_ACCESS_KEY'),
                    'secret' => getenv('AWS_SECRET_KEY'),
                ]
            ]);
        }

        return $GLOBALS['aws_s3_client'];
    }
}

// Upload file to S3
if (!function_exists('upload_file_to_s3')) {
    /**
     * @param string|null $bucket Optional. If null, uses default.
     * @param string $key Required. Path+filename in S3 (like "folder/image.jpg").
     * @param string $sourceFilePath Local file path to upload.
     * @param string $contentType Optional. Defaults to 'application/octet-stream'.
     * @return string|false URL on success, false on failure.
     */
    function upload_file_to_s3($bucket, $key, $sourceFilePath, $contentType = 'application/octet-stream')
    {
        if (!$bucket) {
            $bucket = AWS_DEFAULT_BUCKET;
        }

        try {
            $result = get_s3_client()->putObject([
                'Bucket'      => $bucket,
                'Key'         => $key,
                'SourceFile'  => $sourceFilePath,
                'ContentType' => $contentType,
                'ACL'         => 'public-read' // Optional: public file access
            ]);

            return $result['ObjectURL'];
        } catch (AwsException $e) {
            log_message('error', 'AWS Upload Error: ' . $e->getMessage());
            return false;
        }
    }
}

// Generate signed URL
if (!function_exists('generate_presigned_url')) {
    /**
     * @param string|null $bucket Optional. If null, uses default.
     * @param string $key Required. Path+filename in S3.
     * @param string $expiresIn Optional. Example: "+60 minutes".
     * @return string|false
     */
    function generate_presigned_url($bucket, $key, $expiresIn = '+60 minutes')
    {
        if (!$bucket) {
            $bucket = AWS_DEFAULT_BUCKET;
        }

        try {
            $cmd = get_s3_client()->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key'    => $key,
            ]);

            $request = get_s3_client()->createPresignedRequest($cmd, $expiresIn);

            return (string) $request->getUri();
        } catch (AwsException $e) {
            log_message('error', 'AWS Presigned URL Error: ' . $e->getMessage());
            return false;
        }
    }
}
