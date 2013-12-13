<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\Sqs\SqsClient;

$client = SqsClient::factory($config);

try {
    $result = $client->listQueues();
} catch (Exception $ex) {
    exit("error list queue: " . $ex->getMessage() . "\n");
}

$queueUrlList = $result->get('QueueUrls');

foreach ($queueUrlList as $queue)
{
    echo "Queue: ${queue}\n";
}

echo "finished!\n";
