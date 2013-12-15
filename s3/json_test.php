<?php

$values = array(
  1, "one",
  array(1, 2, 3),
  array(1, 2, array('a' => 'Uno', 'b' => 'Dos')),
);

echo json_encode($values) . "\n";

// Result:
// [1,"one",[1,2,3],[1,2,{"a":"Uno","b":"Dos"}]]
