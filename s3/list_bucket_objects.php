<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\S3\S3Client;

$s3 = S3Client::factory($config);

$result = $s3->listObjects(array (
  'Bucket' => BOOK_BUCKET,
));

if ($result['Contents']) {
  foreach ($result['Contents'] as $obj)
  {
  //  var_dump($obj);
    echo $obj['Key'] . "\n";
  }
}
else
{
  echo "No objects found in " . BOOK_BUCKET . "\n";
}

