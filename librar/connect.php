<?php

// 建立M$SQL的資料庫連接
try {
    $connection = new PDO('mysql:host=localhost; dbname=dice;', 'root', 'rC2W9?vq');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(print_r($e->getMessage()));
}
$talent = '';
//骰子相關
require "role_dice.php";
//天賦相關
require "talent.php";
//角色相關
require "role.php";
//裝備相關
require "equipment.php";
function get_Database_field($name, $field, $where)
{
    global $connection;
    $select = "SELECT " . $field . " FROM `" . $name . "` WHERE " . $where;
    $db_select = $connection->query($select);
    foreach ($db_select as $row) {
        return $row[$field];
    }

    //return $data;
}
function get_Database_field_array($name, $field, $where)
{
    global $connection;
    $select = "SELECT " . $field . " FROM `" . $name . "` WHERE " . $where;
    $db_select = $connection->query($select);
    foreach ($db_select as $row) {
        return $row;
    }

    //return $data;
}
