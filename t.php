<?php

include_once 'DynoDB.php';

$db = new DynoDB();

$db->connect();

$r = $db->get_data_with_relations(2);
var_dump($r);

/* 
$r = $db->add_relation('versions', 1, 2);
var_dump($r); */

/* $r = $db->runQuery('SELECT COLUMN_JSON(value) as value FROM data');
var_dump($r);
echo "\n------------\n";
var_dump($r[0]);
echo "\n------------\n";
var_dump(json_decode($r[0]['value']));

$b = true;

echo "\n==" . strval($b) . "\n"; */

$db->close();


?>