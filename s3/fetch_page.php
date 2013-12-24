<?php

//
// HTMLを取得するパイプライン
//

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\Sqs\SqsClient;
use Aws\S3\S3Client;

$sqs = SqsClient::factory($config);
$s3 = S3Client::factory($config);

$result = $sqs->createQueue(array(
    'QueueName' => URL_QUEUE,
));
$queueURL_URL = $result->get('QueueUrl');

$result = $sqs->createQueue(array(
    'QueueName' => PARSE_QUEUE,
));
$queueURL_Parse = $result->get('QueueUrl');

echo $queueURL_URL . "\n";
echo $queueURL_Parse . "\n";

