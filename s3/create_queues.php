<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\Sqs\SqsClient;

$client = SqsClient::factory($config);

if (count($argv) < 2)
{
    exit("Usage: " . $argv[0] . " QUEUE...\n");
}

for ($i = 1; $i < count($argv); $i++)
{
    $queue = $argv[$i];
    
    try {
        $result = $client->createQueue(array(
            'QueueName' => $queue,
        ));
    } catch (Exception $ex) {
        exit("error while creating queue: " . $queue . ": " . $ex->getMessage() . "\n");
    }
    
    $queueUrl = $result->get('QueueUrl');
    echo "Queue: $queue has been created: ${queueUrl}\n";
}

echo "finished!\n";
