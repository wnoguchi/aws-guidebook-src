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

for ($i = 1; $i < count($argv); $i++)
{
  $message = $argv[$i];
  
  try {
    $result = $client->sendMessage(array(
      'QueueUrl'    => $queueUrl,
      'MessageBody' => $message,
    ));
    
    $messageId = $result->get('MessageId');
    echo "Posted to $queue ${messageId}: $message\n";

  } catch (Exception $ex) {
    exit("Could not post message to " . $queue . ": " . $message . $ex->getMessage() . "\n");
  }
  
  echo "Queue: $queue has been created: ${queueUrl}\n";
}

echo "finished!\n";
