<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\S3\S3Client;
use Aws\S3\Enum\CannedAcl;

$s3 = S3Client::factory($config);

if ($argc < 3) {
  exit("Usage: " . $argv[0] . " bucket files...\n");
}

$bucket = ($argv[1] == '-') ? BOOK_BUCKET : $argv[1];
echo "Bucket: $bucket\n";

for ($i = 2; $i < $argc; $i++)
{
  $key = $argv[$i];
  $data = file_get_contents($key);
  $contentType = guessType($key);
  
  echo "uploading...\n";
  
  if (($model = uploadObject($s3, $bucket, $key, $data, CannedAcl::PUBLIC_READ, $contentType)) == null)
  {
    echo "failed to upload $key to $bucket\n";
  }
  else
  {
    $url = $s3->getObjectUrl($bucket, $key);
    echo "upload successfully $url.\n";
  }
}

echo "finished.\n";
