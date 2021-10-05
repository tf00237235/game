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
    $db_select = $connection->query($select)->fetchAll(PDO::FETCH_ASSOC);
    foreach ($db_select as $row) {
        return $row[$field];
    }
}
function get_Database_field_array($name, $field, $where)
{
    global $connection;
    $select = "SELECT " . $field . " FROM `" . $name . "` WHERE " . $where;
    $db_select = $connection->query($select)->fetchAll(PDO::FETCH_ASSOC);
    foreach ($db_select as $row) {
        return $row;
    }
}
function get_field_array($name, $field, $where)
{
    global $connection;
    $select = "SELECT " . $field . " FROM `" . $name . "` WHERE " . $where;
    $db_select = $connection->query($select)->fetchAll(PDO::FETCH_ASSOC);
    foreach ($db_select as $row) {
        $data[] = $row;
    }
    return $data;
}
function get_field_num($name, $field, $where)
{
    global $connection;
    $select = "SELECT " . $field . " FROM `" . $name . "` WHERE " . $where;
    $db_select = $connection->query($select);
    $num = $db_select->rowCount();
    return $num;
}
function update_field($name, $field, $value, $where)
{
    global $connection;
    $select = "UPDATE `{$name}` SET `{$field}`='{$value}' WHERE {$where}";
    $db_select = $connection->query($select);

}
function msg_creat($data, $sleep)
{
    foreach ($data as $key => $value) {
        $msg[$key]['name'] = $value[0];
        $msg[$key]['content'] = $value[1];
    }
    sleep($sleep);
    return $msg;
}
function msg_push($data, $value)
{
    $data_value[0] = '';
    $data_value[1] = $value;
    array_push($data, $data_value);
    return $data;
}
function msg_unshift($data, $value)
{
    $data_value[0] = '';
    $data_value[1] = $value;
    array_unshift($data, $data_value);
    return $data;
}
