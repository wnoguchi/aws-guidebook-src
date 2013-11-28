<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\S3\S3Client;

$s3 = S3Client::factory($config);

$res = $s3->createBucket(array(
  'Bucket' => BOOK_BUCKET,
  // Tokyo Region
  'LocationConstraint' => \Aws\Common\Enum\Region::AP_NORTHEAST_1,
));

if (!empty($res))
{
  echo $res["Location"] . "\n";
  echo $res["RequestId"] . "\n";
  echo "'" . BOOK_BUCKET . "' bucket created.\n";
}

