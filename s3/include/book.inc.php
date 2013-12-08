<?php
define('BOOK_BUCKET', 'sitepoint-aws-cloud-book-wnoguchi');

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
 *
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
 *
 */
function guessType($file)
{
  $info = pathinfo($file, PATHINFO_EXTENSION);
  $mimeType = '';
  
  switch (strlower($info))
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
