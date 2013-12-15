<?php

//
// キューからアイテムを取り出すスクリプト
//

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

use Aws\Sqs\SqsClient;

$client = SqsClient::factory($config);

if ($argc != 2)
{
  exit("Usage: " . $argv[0] . " QUEUE_NAME\n");
}

$queue = $argv[1];
$result = $client->createQueue(array(
  'QueueName' => $queue,
));
$queueUrl = $result->get('QueueUrl');

while (true) {
  
  // TODO: write codes here.
  $result = $client->receiveMessage(array(
    'QueueUrl' => $queueUrl,
  ));
  
  foreach ($result->getPath('Messages/*/Body') as $messageBody) {
    // Do something with the message
    echo $messageBody . "\n";
  }
  break;
  
}

echo "finished!\n";
