<?php

$values = array(
  1, "one",
  array(1, 2, 3),
  array(1, 2, array('a' => 'Uno', 'b' => 'Dos')),
);

$encoded_value = json_encode($values);

echo $encoded_value . "\n";

// Result:
// [1,"one",[1,2,3],[1,2,{"a":"Uno","b":"Dos"}]]

$decoded_value = json_decode($encoded_value, true);
var_dump($decoded_value);
