<?php

//
// キューからアイテムを取り出すスクリプト
//

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\Sqs\SqsClient;

$client = SqsClient::factory($config);

$queue = 'aaa';
$message = pullMessage($client, $queue);

if (!empty($message)) {

  // Do something with the message
  $receiptHandle = $message['ReceiptHandle'];
  $messageBody = $message['MessageBody'];
  
  echo "Message: " . $messageBody . "\n";
  
//  $client->deleteMessage(array(
//    'QueueUrl' => $queueUrl,
//    'ReceiptHandle' => $receiptHandle,
//  ));

}
else
{
  echo "Empty.\n";
}

