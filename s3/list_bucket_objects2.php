<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

$log = Logger::getLogger("list_bucket_objects_page");

use Aws\S3\S3Client;

$s3 = S3Client::factory($config);

$bucket_name = BOOK_BUCKET;
//$result = getAllBucketObjects($s3, $bucket_name, '7200');
$result = getAllBucketObjects($s3, $bucket_name);

if (!empty($result)) {
  echo "-----------------------------------------------------\n";
  foreach ($result as $obj)
  {
    echo $obj['Key'] . "\n";
  }
  echo "-----------------------------------------------------\n";
  echo count($result) . " items fetched.\n";
}
else
{
  echo "No objects found in " . $bucket_name . "\n";
}

