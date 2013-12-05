<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\S3\S3Client;

$s3 = S3Client::factory($config);

if ($argc != 2)
{
  exit("Argument Error.\n");
}

if ($argv[1] == '-')
{
  $bucket_name = BOOK_BUCKET;
}
else
{
  $bucket_name = $argv[1];
}

$res = $s3->createBucket(array(
  'Bucket' => $bucket_name,
  // Tokyo Region
  'LocationConstraint' => \Aws\Common\Enum\Region::AP_NORTHEAST_1,
));

if (!empty($res))
{
  echo $res["Location"] . "\n";
  echo $res["RequestId"] . "\n";
  echo "'" . $bucket_name . "' bucket created.\n";
}

