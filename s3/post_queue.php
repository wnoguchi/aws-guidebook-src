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
  exit("Usage: " . $argv[0] . " QUEUE_NAME ITEM...\n");
}

$queue = $argv[1];
$result = $client->createQueue(array(
  'QueueName' => $queue,
));
$queueUrl = $result->get('QueueUrl');

for ($i = 2; $i < count($argv); $i++)
{
  $message = $argv[$i];
  
  try {
    $result = $client->sendMessage(array(
      'QueueUrl'    => $queueUrl,
      'MessageBody' => 'An awesome message!',
    ));
    
    $messageId = $result->get('MessageId');
    echo "Posted to $queue ${messageId}: $message\n";

  } catch (Exception $ex) {
    exit("Could not post message to " . $queue . ": " . $message . $ex->getMessage() . "\n");
  }
  
  echo "Queue: $queue has been created: ${queueUrl}\n";
}

echo "finished!\n";
