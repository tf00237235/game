<?PHP

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
    $hp = $row['con'] * (10 + $row['vit']);
    if ($num) {
        $ability = "`HP`='{$hp}',`Str`='" . $row['str'] . "',`Dex`='" . $row['agi'] . "',`intellect`='" . $row['int'] . "',`Vit`='" . $row['con'] . "',`Viter`='" . $row['vit'] . "'";
        //更新骰出結果
        $update = "UPDATE `role` SET " . $ability . " WHERE `ID` = '" . $role_id . "'";
        $db_update = $connection->query($update);
    } else {
        $ability = "'{$hp}','" . $row['str'] . "','" . $row['agi'] . "','" . $row['int'] . "','" . $row['con'] . "','" . $row['vit'] . "'";
        //新增骰出結果
        $insert = "INSERT INTO `role` (`HP`,`Str`, `Dex`, `intellect`, `Vit`, `Viter`) VALUE ('" . $row . "')";
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
    $age = mt_rand(18, 99);
    $frm = special_name($frm, $role_id);
    $update = "UPDATE `role` SET `ethnicity`='" . $frm['ethnicity'] . "', `max_age` = '" . $age . "', `age`='17', `cheat`='" . $talent . "', `Difficulty` = '" . $frm['Difficulty'] . "', `Deat` = '0', `add_time` = '" . date("Y-m-d H:i:s", strtotime("now")) . "', `name`='" . $frm['name'] . "', `Lck`='" . $luk . "' WHERE `ID` = '" . $role_id . "'";
    $db_update = $connection->exec($update);
}
function get_status_con($role_id)
{
    global $connection;
    $select = "SELECT rd.*,role.Name,ethnicity.Name as eName FROM `role_dice` as rd
    LEFT JOIN `role` ON `role`.ID = rd.role_id
    LEFT JOIN `ethnicity` ON `role`.ethnicity = `ethnicity`.ID
    WHERE rd.`role_id` = '" . $role_id . "'";
    $db = $connection->query($select);
    foreach ($db as $row) {}
    return $row;
}
function special_name($frm, $role_id)
{
    $frm['ethnicity'] = get_Database_field("role", "ethnicity", "`ID`='" . $role_id . "'");
    if ($frm['name'] == '茄汁蝦') {
        $frm['ethnicity'] = '9';
    }
    return $frm;
}
