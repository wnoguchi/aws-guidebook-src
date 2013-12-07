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

// Set Bucket Name
if (isset($_GET['bucket'])) {
  $bucket_name = $_GET['bucket'];
}
else
{
  $bucket_name = BOOK_BUCKET;
}

$result = getAllBucketObjects($s3, $bucket_name);
?><!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>List Bucket Objects - <?php echo htmlspecialchars($bucket_name, ENT_QUOTES, 'UTF-8'); ?></title>
<meta name="keywords" content="">
<meta name="description" content="">
<script
src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>
<h1>List Bucket Objects - <?php echo htmlspecialchars($bucket_name, ENT_QUOTES, 'UTF-8'); ?></h1>
<?php

// 欲しい情報に整形
if (!empty($result)) {
  ?>
  <p>Total items count: <?php echo count($result); ?></p>
  <table border="1">
    <thead>
      <tr>
        <th>File</th>
        <th>Size</th>
      </tr>
    </thead>
    <tbody>
  <?php
  foreach ($result as $obj)
  {
    $key = $obj['Key'];
    $size = number_format($obj['Size']);
    $url = $s3->getObjectUrl($bucket_name, $key);

    ?>
    <tr>
      <td>
        <a href="<?php echo $url ?>"><?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?></a>
      </td>
      <td>
        <?php echo $size; ?>
      </td>
    </tr>
    <?php

  }
  ?>
    </tbody>
  </table>
  <?php
}
else
{
  echo "<strong>No objects found in " . $bucket_name . "</strong>";
}
?>
</body>
</html>