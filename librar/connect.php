<?php

// 建立M$SQL的資料庫連接
try {
    $connection = new PDO('mysql:host=localhost; dbname=dice;', 'root', 'rC2W9?vq');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(print_r($e->getMessage()));
}
$talent = '';
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
function role()
{
    global $connection;
    $select = "SELECT * FROM `ethnicity` WHERE `Type`='0'";
    $db_select = $connection->query($select);
    foreach ($db_select as $row) {
        $data[] = $row;
    }

    return $data;

}

function max_role($type)
{
    global $connection;
    $select = "SELECT ROUND(sum(Str+Dex+intellect+Vit+Viter)*0.8) as max_role,Str,Dex,intellect,Vit,Viter  FROM `ethnicity` WHERE `Name`='" . $type . "'";
    $db_select = $connection->query($select);
    foreach ($db_select as $row) {
        return $row;
    }
}

function rand_role_ethnicity($god, $role_id)
{
    global $connection;
    $range_role = mt_rand(100, 1000);
    if ($god >= 960000) {
        $range_role += mt_rand(-300, 300);
    }
    if ($range_role < 0) {
        $range_role = 900;
    }
    $select = "SELECT * FROM `ethnicity` WHERE `range_role`<='" . $range_role . "' AND Type = '0' ORDER BY RAND() LIMIT 1";
    $db_select = $connection->query($select);
    foreach ($db_select as $row) {

    }
    //防作弊 & 資料庫定位
    $role_id = check_role_ethnicity($row['ID'], $role_id);

    return array('ethnicity' => $row['Name'], 'god' => $god, 'rand' => $range_role, 'role_id' => $role_id);
}
function rand_role_ability($type = '人族', $role_id)
{
    $get_max_role = max_role($type);
    do {
        $range_role_str = mt_rand(1, $get_max_role['Str']);
        $range_role_agi = mt_rand(1, $get_max_role['Dex']);
        $range_role_int = mt_rand(1, $get_max_role['intellect']);
        $range_role_con = mt_rand(1, $get_max_role['Vit']);
        $range_role_vit = mt_rand(1, $get_max_role['Viter']);
        $rand_role_total = $range_role_str + $range_role_agi + $range_role_int + $range_role_con + $range_role_vit;
    } while ($get_max_role['max_role'] <= $rand_role_total);
    $ability = array('str' => $range_role_str, 'agi' => $range_role_agi, 'int' => $range_role_agi, 'con' => $range_role_con, 'vit' => $range_role_vit);
    //防作弊 & 資料庫定位
    $role_id = check_role_ability(json_encode($ability), $role_id);

    return $ability;
}
function check_role_ethnicity($row, $role_id)
{
    global $connection;
    //檢查存在與否
    $select = "SELECT ID FROM `role` WHERE ID = '" . $role_id . "'";
    $db_select = $connection->query($select);
    $num = $db_select->rowCount();

    if ($num) {
        //更新骰出結果
        $update = "UPDATE `role` SET `ethnicity`='" . $row . "' WHERE `ID` = '" . $role_id . "'";
        $db_update = $connection->query($update);
    } else {
        //新增骰出結果
        $insert = "INSERT INTO `role` (`ethnicity`) VALUE ('" . $row . "')";
        $db_insert = $connection->query($insert);
        $role_id = $connection->lastInsertId();
    }

    return $role_id;
}
function check_role_ability($row, $role_id)
{
    global $connection;
    $row = json_decode($row, true);

    //檢查存在與否
    $select = "SELECT ID FROM `role` WHERE ID = '" . $role_id . "'";
    $db_select = $connection->query($select);
    $num = $db_select->rowCount();

    if ($num) {
        $ability = "`Str`='" . $row['str'] . "',`Dex`='" . $row['agi'] . "',`intellect`='" . $row['int'] . "',`Vit`='" . $row['con'] . "',`Viter`='" . $row['vit'] . "'";
        //更新骰出結果
        $update = "UPDATE `role` SET " . $ability . " WHERE `ID` = '" . $role_id . "'";
        $db_update = $connection->query($update);
    } else {
        $ability = "'" . $row['str'] . "','" . $row['agi'] . "','" . $row['int'] . "','" . $row['con'] . "','" . $row['vit'] . "'";
        //新增骰出結果
        $insert = "INSERT INTO `role` (`Str`, `Dex`, `intellect`, `Vit`, `Viter`) VALUE ('" . $row . "')";
        $db_insert = $connection->query($insert);
        $role_id = $connection->lastInsertId();
    }

    return $role_id;
}
//創建角色
function get_sql_role_data($role_id)
{
    global $connection;
    $select = "SELECT `Str`, `Dex`, `intellect`, `Vit`,`Viter` FROM `role` WHERE ID = '" . $role_id . "'";
    $db_select = $connection->query($select);
    $num = $db_select->rowCount();
    if ($num) {
        foreach ($db_select as $row) {

        }
        $row['ErrorCode'] = '1';
        $row['ErrorMsg'] = '';
    } else {
        $row['ErrorCode'] = '0';
        $row['ErrorMsg'] = 'Error：Creat role Fail ,plz retry again！';
    }
    return $row;
}
function update_role_Name($role_id, $frm, $talent)
{
    global $connection;
    $frm = json_decode($frm, true);
    $luk = mt_rand(-5, 5);
    $update = "UPDATE `role` SET `cheat`='" . $talent . "', `Difficulty` = '" . $frm['Difficulty'] . "', `Deat` = '0', `add_time` = '" . date("Y-m-d H:i:s", strtotime("now")) . "', `name`='" . $frm['name'] . "', `Lck`='" . $luk . "' WHERE `ID` = '" . $role_id . "'";
    $db_update = $connection->exec($update);
}

function role_talent($frm)
{
    global $connection, $talent;
    $talent = '';
    $talent_ID = "'0'";
    $cheat = get_Database_field('role', 'cheat', '`ID` = "' . $frm['role_id'] . '"');
    //隨機天賦
    for ($i = 0; $i < mt_rand(5, 15); $i++) {
        $range_role_talent = mt_rand(100, 1000);
        $select = "SELECT ID,Name FROM `talent` WHERE `Type` IN ('0','1') AND range_role <= '" . $range_role_talent . "' AND `ID` NOT IN (" . $talent_ID . ") ORDER BY RAND() LIMIT 1";
        $db = $connection->query($select);
        foreach ($db as $row) {
            $talent .= $row['ID'] . '|';
            $talent_ID .= ",'" . $row['ID'] . "'";
            //避免裝備狂卡位
            if ($row['ID'] == '8' || $row['ID'] == '9' || $row['ID'] == '10' || $row['ID'] == '11') {
                $talent_ID .= ",'8','9','10','11'";
            }
            $msg[$i]['name'] = '';
            $msg[$i]['content'] = "取得天賦「" . $row['Name'] . "」！";
        }
    }
    $role_ethnicity = get_Database_field('role', 'ethnicity', '`ID` = "' . $frm['role_id'] . '"');
    $role_ethnicity_congenital = get_Database_field_array('role', 'Str,Dex,intellect,viter', '`ID` = "' . $frm['role_id'] . '"');
    //種族天賦
    $msg = get_role_ethnicity($role_ethnicity, $talent, $msg);
    //先天天賦
    $msg = congenital_talent($role_ethnicity_congenital, $role_ethnicity, $talent, $msg);

    //作弊相關
    if ($cheat == 5) {
        $cheater_talent['name'] = '';
        $cheater_talent['content'] = '取得天賦「作弊者」';
        $talent .= '46|';
        array_unshift($msg, $cheater_talent);
    }

    if ($cheat) {
        $cheater['name'] = '';
        $cheater['content'] = '...';
        $cheater_system['name'] = 'system';
        $cheater_system['content'] = '【系統】發現不正確數據！';
        $cheater_talent['name'] = '';
        $cheater_talent['content'] = '取得天賦「舞弊者」';
        $talent .= '48|';
        array_unshift($msg, $cheater, $cheater, $cheater, $cheater, $cheater, $cheater_system, $cheater_talent);
    }

    return $msg;
}
function get_role_ethnicity($role_ethnicity, $talent, $msg)
{
    global $connection, $talent;
    switch ($role_ethnicity) {
        case 1:
            $role_ethnicity = 2;
            break;
        case 2:
            $role_ethnicity = 3;
            break;
        case 3:
            $role_ethnicity = 4;
            break;
        case 9:
            $role_ethnicity = 5;
            break;
        case 4 || 5:
            $role_ethnicity = 6;
            break;
    }

    $select = "SELECT ID,Name FROM `talent` WHERE `Type` = '" . $role_ethnicity . "'";
    $db = $connection->query($select);
    foreach ($db as $row) {
        $ethnicity_talent['name'] = "";
        $ethnicity_talent['content'] = "取得種族天賦「" . $row['Name'] . "」！";
        $talent .= $row['ID'] . '|';
        array_unshift($msg, $ethnicity_talent);
    }
    return $msg;
}
function congenital_talent($role_ethnicity_congenital, $role_ethnicity, $talent, $msg)
{
    global $connection, $talent;
    $ethnicity_congenital = "SELECT Str,Dex,intellect,viter FROM `ethnicity` WHERE ID = '" . $role_ethnicity . "'";
    $db = $connection->query($ethnicity_congenital);
    foreach ($db as $row) {
        if ($role_ethnicity_congenital['Str'] == $row['Str']) {
            $ethnicity_talent['name'] = "";
            $ethnicity_talent['content'] = "取得先天天賦「天生神力」！";
            $talent .= '1|';
            array_unshift($msg, $ethnicity_talent);
        }
        if ($role_ethnicity_congenital['Dex'] == $row['Dex']) {
            $ethnicity_talent['name'] = "";
            $ethnicity_talent['content'] = "取得先天天賦「天生迅捷」！";
            $talent .= '2|';
            array_unshift($msg, $ethnicity_talent);
        }
        if ($role_ethnicity_congenital['intellect'] == $row['intellect']) {
            $ethnicity_talent['name'] = "";
            $ethnicity_talent['content'] = "取得先天天賦「天生聰穎」！";
            $talent .= '3|';
            array_unshift($msg, $ethnicity_talent);
        }
        if ($role_ethnicity_congenital['viter'] == $row['viter']) {
            $ethnicity_talent['name'] = "";
            $ethnicity_talent['content'] = "取得先天天賦「天生強健」！";
            $talent .= '4|';
            array_unshift($msg, $ethnicity_talent);
        }
    }
    return $msg;
}
