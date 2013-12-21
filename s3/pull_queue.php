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
  
  $result = $client->receiveMessage(array(
    'QueueUrl' => $queueUrl,
    'WaitTimeSeconds' => 1,
  ));
  
  $messages = $result->get('Messages');
  if (!empty($messages)) {

    foreach ($messages as $message) {
      // Do something with the message
      $receiptHandle = $message['ReceiptHandle'];
      $messageBody = $message['Body'];
      
      echo "Message: " . $messageBody . "\n";
      
      $client->deleteMessage(array(
        'QueueUrl' => $queueUrl,
        'ReceiptHandle' => $receiptHandle,
      ));
    }
    echo "=========================================\n";

  }
  else
  {
    echo "Empty.\n";
  }
  
  
}
