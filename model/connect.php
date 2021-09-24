<?php

// 建立M$SQL的資料庫連接
try {
    $connection = new PDO('mysql:host=localhost; dbname=dice;', 'root', 'rC2W9?vq');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(print_r($e->getMessage()));
}
date_default_timezone_set("Asia/Taipei");
$talent = '';
//骰子相關
require_once "role_dice.php";
//天賦相關
require_once "talent.php";
//角色相關
require_once "role.php";
//冒險相關
require_once "travel.php";
//裝備相關
require_once "equipment.php";
//迷宮相關
require_once "maze.php";
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
