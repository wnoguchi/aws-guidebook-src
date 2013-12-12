<?php

error_reporting(E_ALL);

// Composer
require_once('vendor/autoload.php');
// AWS
require_once('include/awssecure.inc.php');

use Aws\Ec2\Ec2Client;

$config['region'] = \Aws\Common\Enum\Region::AP_NORTHEAST_1;
$ec2 = Ec2Client::factory($config);

try {
  $options = array(
    'ImageId' => 'ami-b1fe9bb0',
    'MinCount' => 1,
    'MaxCount' => 1,
    'KeyName' => 'awsguidebook',
    'SecurityGroups' => array(
      'webserver' // sg-24040f46
    ),
    'InstanceType' => 't1.micro',
  );
  $result = $ec2->RunInstances($options);
} catch (Exception $ex) {
  exit("Instance Launch failed.: " . $ex->getMessage() . "\n");
}

// インスタンス情報を取得
$instance = $result['Instances'][0];
$instanceId = $instance['InstanceId'];
$availabilityZone = $instance['Placement']['AvailabilityZone'];

echo "Instance ID: $instanceId\n";
echo "Availability Zone: $availabilityZone\n";

// wait for instance ready.
do {
  $result = $ec2->describeInstances(array(
    'InstanceIds' => array(
      $instanceId
    )
  ));
  
  $status = $result['Reservations'][0]['Instances'][0]['State']['Name'];
  $runnning = ($status == 'running');
  
  if (!$runnning) {
    echo "instance is currently in $status, waiting 10 seconds.\n";
    sleep(10);
  }
  
} while(!$runnning);

try {
  // ElasticIPを割り当て
  $result = $ec2->allocateAddress();
  $publicIp = $result['PublicIp'];
  echo "allocate IP Address: $publicIp\n";
} catch (Exception $ex) {
  exit("Allocate IP Address failed. " . $ex->getMessage() . "\n");
}

try {
  // ElasticIPをインスタンスにアタッチ
  $ec2->associateAddress(array(
    'InstanceId' => $instanceId,
    'PublicIp' => $publicIp,
  ));
  echo "Associated IP address : ${publicIp} to ${instanceId}\n";
} catch (Exception $ex) {
  exit("IP Address Association failed. " . $ex->getMessage() . "\n");
}

try {
  // 先ほど得られたアベイラビリティゾーンに1GiBのボリュームを2つ作成
  $res1 = $ec2->createVolume(array(
    'Size' => 1,
    'AvailabilityZone' => $availabilityZone,
  ));
  $res2 = $ec2->createVolume(array(
    'Size' => 1,
    'AvailabilityZone' => $availabilityZone,
  ));
  $volumeId1 = $res1['VolumeId'];
  $volumeId2 = $res2['VolumeId'];
  echo "EBS Volume: $volumeId1, $volumeId2 created.\n";
} catch (Exception $ex) {
  exit("EBS Volume create failed. " . $ex->getMessage() . "\n");
}


// wait for volumes ready.
do {
  $result = $ec2->describeVolumes(array(
    'VolumeIds' => array(
      $volumeId1, $volumeId2
    )
  ));
  
  $status1 = $result['Volumes'][0]['State'];
  $status2 = $result['Volumes'][1]['State'];
  $volumeReady = ($status1 == 'available' && $status2 == 'available');
  
  if (!$volumeReady) {
    echo "volumes are currently in ($status1, $status2), waiting 10 seconds.\n";
    sleep(10);
  }
  
} while(!$volumeReady);


try {
  // ボリュームをインスタンスにアタッチ
  $res1 = $ec2->attachVolume(array(
    'VolumeId' => $volumeId1,
    'InstanceId' => $instanceId,
    'Device' => '/dev/sdf',
  ));
  $res2 = $ec2->attachVolume(array(
    'VolumeId' => $volumeId2,
    'InstanceId' => $instanceId,
    'Device' => '/dev/sdg',
  ));

  echo "Volume $volumeId1, $volumeId2 has been attached successfully.\n";
} catch (Exception $ex) {
  exit("EBS Attache failed. " . $ex->getMessage() . "\n");
}

echo "Finished!!\n";
