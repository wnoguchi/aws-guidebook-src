<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\Sqs\SqsClient;

if ($argc < 2)
{
  exit("Usate: ${argv[0]} URL...\n");
}

$client = SqsClient::factory($config);

$queue = URL_QUEUE;
$result = $client->createQueue(array(
  'QueueName' => $queue,
));
$queueUrl = $result->get('QueueUrl');

$origin = $argv[0];
for ($i = 1; $i < $argc; $i++)
{
  // オブジェクトデータ構築
  $histItem = array("Posted by $origin . at " . date('c'));
  $url = $argv[$i];
  $message = array (
    'Action' => 'FetchPage',
    'Origin' => $origin,
    'Data' => $url,
    'History' => $histItem,
  );
  $message = json_encode($message);
  
  try {
    $result = $client->sendMessage(array(
      'QueueUrl'    => $queueUrl,
      'MessageBody' => $message,
    ));
    
    //$messageId = $result->get('MessageId');
    echo "Posted $message to queue " . URL_QUEUE . "\n";

  } catch (Exception $ex) {
    exit("Could not post message to " . $queue . ": " . $message . $ex->getMessage() . "\n");
  }
  
  echo "Queue: $queue has been created: ${queueUrl}\n";
}

echo "finished!\n";
