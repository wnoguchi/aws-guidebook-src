<?php
define('BOOK_BUCKET', 'sitepoint-aws-cloud-book-wnoguchi');

date_default_timezone_set('Asia/Tokyo');

use Aws\S3\Enum\CannedAcl;

/**
 * List All Bucket Objects.
 * Not Limited 1000 objects this function call.
 */
function getAllBucketObjects($client, $bucket, $prefix = '')
{
  global $log;

  $objects = array();
  $next = '';

  do
  {
//    $log->debug("API Request.");

    $result = $client->listObjects(array (
      'Bucket' => $bucket,
      'Marker' => urlencode($next),
      'Prefix' => $prefix,
    ));

    if (!$result['Contents']) {
      break;
    }

    foreach ($result['Contents'] as $obj)
    {
      $objects[] = $obj;
    }

    $isTruncated = $result['IsTruncated'];

    if ($isTruncated) {
      $log->debug("is truncated");
      $next = $objects[count($objects) - 1]['Key'];
    }

  } while ($isTruncated);

//  $log->debug("Reach End of function.");

  return $objects;

}

/**
 * Upload Object utility function.
 */
function uploadObject($client, $bucket, $key, $data, $acl = CannedAcl::PRIVATE_ACCESS,
                      $contentType = "text/plain")
{
  $try = 1;
  $sleep = 1;
  $result = null;
  
  do {
    try {
      $result = $client->putObject(array(
        'Bucket' => $bucket,
        'Key' => $key,
        'Body' => $data,
        'ACL' => $acl,
        'ContentType' => $contentType,
      ));
      
    } catch (Exception $ex) {
      echo $ex->toString();
    }
    
    if ($result != null) {
      break;
    }
    
    sleep($sleep);
    $sleep = $sleep * 2;
    
    $try++;
    
  } while ($try);
  
  return $result;
}

/**
 * Guess MIME Type from file path.
 */
function guessType($file)
{
  $info = pathinfo($file, PATHINFO_EXTENSION);
  $mimeType = '';
  
  switch (strtolower($info))
  {
    case "jpg":
    case "jpeg":
      $mimeType = "image/jpg";
      break;
      
    case "png":
      $mimeType = "image/png";
      break;
      
    case "gif":
      $mimeType = "image/gif";
      break;
      
    case "htm":
    case "html":
      $mimeType = "text/html";
      break;
      
    case "txt":
      $mimeType = "text/plain";
      break;
      
    default:
      $mimeType = "text/plain";
      break;
  }
  
  return $mimeType;
}

//
//
//

define('URL_QUEUE', 'c_url');
define('PARSE_QUEUE', 'c_parse');
define('IMAGE_QUEUE', 'c_image');
define('RENDER_QUEUE', 'c_render');

function pullMessage($client, $queue)
{
  // Get Queue URL
  $result = $client->createQueue(array(
    'QueueName' => $queue,
  ));
  $queueUrl = $result->get('QueueUrl');

  $result = $client->receiveMessage(array(
    'QueueUrl' => $queueUrl,
    'WaitTimeSeconds' => 1,
  ));
  
  $messages = $result->get('Messages');
  $message = $messages[0];
  $messageBody = $message['Body'];

  if (empty($messageBody)) {
    return null;
  }

  $messageDetail = json_decode($messageBody, true);
  $receiptHandle = $message['ReceiptHandle'];

  // 返却するオブジェクトを構築
  $return_object = array(
    'QueueURL' => $queueUrl,
    'Timestamp' => date('c'),
    'Message' => $message,
    'MessageBody' => $messageBody,
    'MessageDetail' => $messageDetail,
    'ReceiptHandle' => $receiptHandle,
  );

  return $return_object;
}

