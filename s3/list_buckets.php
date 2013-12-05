<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\S3\S3Client;

$s3 = S3Client::factory($config);

$blist = $s3->listBuckets();

foreach ($blist['Buckets'] as $b)
{
  echo "${b['Name']}: created at ${b['CreationDate']}\n";
}

