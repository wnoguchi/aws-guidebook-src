<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

use Aws\Ec2\Ec2Client;

$ec2 = Ec2Client::factory($config);

