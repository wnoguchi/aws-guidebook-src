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

$bucket = BOOK_BUCKET;
$filename = "tumblr_mq11gvsdDq1qz53a8o1_500.jpg";
$key = "/vagrant/$filename";
$data = file_get_contents($key);
$contentType = guessType($key);

uploadObject($s3, $bucket, $key, $data, CannedAcl::PUBLIC_READ, $contentType);
