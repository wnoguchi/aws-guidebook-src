<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

require_once('include/book.inc.php');

use Aws\CloudFront\CloudFrontClient;

$cf = CloudFrontClient::factory($config);

$distributionList = $cf->listDistributions();

printf ("%-16s %-32s %-40s\n", "ID", "Domain Name", "Origin");
printf ("%'=-16s %'=-32s %'=-40s\n", "", "", "");

foreach ($distributionList['Items'] as $k => $v)
{
  $id = $v['Id'];
  $domainName = $v['DomainName'];
  $origin = $v['Origins']['Items'][0]['DomainName'];

  printf ("%-16s %-32s %-40s\n", $id, $domainName, $origin);
}
