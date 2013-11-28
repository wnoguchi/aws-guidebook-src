<?php
require_once("vendor/autoload.php");
require_once("awssecure.inc.php");

use Aws\S3\S3Client;

$s3 = S3Client::factory($config);

?><!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title></title>
<meta name="keywords" content="">
<meta name="description" content="">
<script
src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>
<?php
$buckets= $s3->listBuckets();

?>
<pre>
<?php
foreach ($buckets as $bucket)
{
  var_dump($bucket);
}
?>
</pre>
</body>
</html>
