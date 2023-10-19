<?php

include_once 'DynoDB.php';

$db = new DynoDB();

$db->connect();

echo "all data:\n\n";

$r = $db->get_all_data();
var_dump($r);

echo "\n\n\n\nadding new versioned data:\n";

$r = $db->add_new_versioned_data([
    'string_data' => "hello world",
    'integer_data' => 200,
    'bool_data' => false,
]);
var_dump($r);

echo "\n\n\n\nadding another one but with the same version:\n";

$r = $db->add_data($r[0]['version'],[
    'string_data' => "another hello world",
    'integer_data' => 50,
    'bool_data' => true,
]);
var_dump($r);

echo "\n\n\n\nnow checking all data:\n";

$r = $db->get_all_data();
var_dump($r);

echo "\n\n\n\nupdateing the last data:\n";

$updated_id=$r[1]['id'];
$r = $db->update_data($r[1]['id'],
    [
        'string_data' => "updated hello world",
        'integer_data' => 1000,
        'bool_data' => false,
    ]
);
var_dump($r);

echo "\n\n\n\nlets see the change in the last data:\n";

$r = $db->get_data($updated_id);
var_dump($r);

echo "\n\n\n\nok lets delete it:\n";

$r = $db->delete_data($updated_id);
var_dump($r);

echo "\n\n\n\nnow lets look at the overall data:\n";

$r = $db->get_all_data();
var_dump($r);

$db->close();


?>