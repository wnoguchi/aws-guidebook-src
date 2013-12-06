<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\S3\S3Client;

$s3 = S3Client::factory($config);

$bucket_name = BOOK_BUCKET;
//$result = getAllBucketObjects($s3, $bucket_name, '7200');
$result = getAllBucketObjects($s3, $bucket_name);

if (!empty($result)) {
  foreach ($result as $obj)
  {
    echo $obj['Key'] . "\n";
  }
}
else
{
  echo "No objects found in " . $bucket_name . "\n";
}

