<?php
define('BOOK_BUCKET', 'sitepoint-aws-cloud-book-wnoguchi');

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
    $log->debug("API Request.");

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

  $log->debug("Reach End of function.");

  return $objects;

}
