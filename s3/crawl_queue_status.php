<?php

//
// キューの状態を確認するスクリプト
//

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\Sqs\SqsClient;

$client = SqsClient::factory($config);

// 検査するキューのリスト
$queueList = array(
  URL_QUEUE,
  PARSE_QUEUE,
  IMAGE_QUEUE,
  RENDER_QUEUE,
);

$underlines = '';
foreach ($queueList as $queue)
{
  $result = $client->createQueue(array(
      'QueueName' => $queue,
  ));
  $queueUrl = $result->get('QueueUrl');
  
  $result = $client->getQueueAttributes(array(
    'QueueUrl' => $queueUrl,
    'AttributeNames' => array(
      'ApproximateNumberOfMessages',
    )
  ));
  $attributes = $result->get('Attributes');
  $queueSize = $attributes['ApproximateNumberOfMessages'];
  echo "$queue: $queueSize\n";

}

