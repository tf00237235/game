<?PHP
//骰天賦
function role_talent($frm)
{
    global $connection, $talent;
    $talent = '';
    $talent_ID = "'0'";

//檢查天賦
    $check_talent = get_Database_field('role', 'talent', '`ID` = "' . $frm['role_id'] . '"');
    if ($check_talent != '') {
        $msg = already_talent();
        return $msg;
    }

    //檢查作弊
    $cheat = get_Database_field('role', 'cheat', '`ID` = "' . $frm['role_id'] . '"');
    //檢查種族
    $role_ethnicity = get_Database_field('role', 'ethnicity', '`ID` = "' . $frm['role_id'] . '"');
    //獲得先天能力值
    $role_ethnicity_congenital = get_Database_field_array('role', 'Str,Dex,intellect,viter', '`ID` = "' . $frm['role_id'] . '"');
    //隨機天賦數量
    $talent_num = mt_rand(5, 15);
    //人族天賦額外+5
    if ($role_ethnicity == 1) {
        $talent_num += 5;
    }
    //難度
    switch ($frm['difficulty']) {
        case "0":
            $talent_num += 3;
            break;
        case "1":
            $talent_num += 1;
            break;
        case "2":
            break;
        case "3":
            $talent_num -= 1;
            break;
        case "4":
            $talent_num -= 3;
            break;
        case "5":
            $talent_num_plus = mt_rand(-3, 5);
            $talent_num += $talent_num_plus;
            break;
    }
    for ($i = 1; $i <= $talent_num; $i++) {
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
            $msg[$i]['content'] = "【系統】取得天賦「" . $row['Name'] . "」！";
        }
    }

    //種族天賦
    $msg = get_role_ethnicity($role_ethnicity, $talent, $msg);
    //先天天賦
    $msg = congenital_talent($role_ethnicity_congenital, $role_ethnicity, $talent, $msg);
    //專屬天賦
    $msg = exclusive_talent(get_Database_field('role', 'Name', '`ID` = "' . $frm['role_id'] . '"'), $talent, $msg);

    //作弊相關
    if ($cheat == 5) {
        $cheater_talent['name'] = '';
        $cheater_talent['content'] = '【系統】取得天賦「作弊者」';
        $talent .= '46|';
        array_unshift($msg, $cheater_talent);
    }

    if ($cheat) {
        $cheater['name'] = '';
        $cheater['content'] = '...';
        $cheater_system['name'] = 'system';
        $cheater_system['content'] = '【系統】發現不正確數據！';
        $cheater_talent['name'] = '';
        $cheater_talent['content'] = '【系統】取得天賦「舞弊者」';
        $talent .= '48|';
        array_unshift($msg, $cheater, $cheater, $cheater, $cheater, $cheater, $cheater_system, $cheater_talent);
    }
    //系統說明
    $system['name'] = "system";
    $system['content'] = "【系統】 初始化中，請稍後！";
    array_unshift($msg, $system);
    if ($frm['difficulty'] == 5) {
        $system['name'] = "system";
        $system['content'] = "【系統】「?????」給你增加了：" . $talent_num_plus . "個天賦！";
        array_unshift($msg, $system);
    }
    //寫入資料庫
    $update = "UPDATE `role` SET `talent` = '" . $talent . "' WHERE `ID` = '" . $frm['role_id'] . "'";
    $db = $connection->query($update);
    //骰子、間距計算
    $msg = dice_range($frm['role_id'], $msg, $talent, $role_ethnicity);

    return $msg;
}
//種族獨有天賦
function get_role_ethnicity($role_ethnicity, $talent, $msg)
{
    global $connection, $talent;
    switch ($role_ethnicity) {
        case 1:
            //人族例外
            $msg = get_role_ethnicity_humen($role_ethnicity, $talent, $msg);
            return $msg;
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
        $ethnicity_talent['content'] = "【系統】取得種族天賦「" . $row['Name'] . "」！";
        $talent .= $row['ID'] . '|';
        array_unshift($msg, $ethnicity_talent);
    }
    return $msg;
}
//人類獨有天賦
function get_role_ethnicity_humen($role_ethnicity, $talent, $msg)
{
    global $connection, $talent;
    $ethnicity_congenital = "SELECT Str,Dex,intellect,viter FROM `ethnicity` WHERE ID = '" . $role_ethnicity . "'";
    $db = $connection->query($ethnicity_congenital);
    //骰10次
    $talent_ID = "'0'";
    for ($i = 0; $i < 10; $i++) {
        $range_role_talent = mt_rand(100, 1000);
        $select = "SELECT ID,Name FROM `talent` WHERE `Type` ='2' AND range_role <= '" . $range_role_talent . "' AND `ID` NOT IN (" . $talent_ID . ") ORDER BY RAND() LIMIT 1";
        $db = $connection->query($select);
        $num = $db->rowCount();
        if ($num) {
            foreach ($db as $row) {
                $talent .= $row['ID'] . '|';
                $talent_ID .= ",'" . $row['ID'] . "'";
                $humen_talent['name'] = '';
                $humen_talent['content'] = "【系統】取得種族天賦「" . $row['Name'] . "」！";
                array_unshift($msg, $humen_talent);
            }
            break;
        }
    }
    return $msg;

}
//先天天賦
function congenital_talent($role_ethnicity_congenital, $role_ethnicity, $talent, $msg)
{
    global $connection, $talent;
    $ethnicity_congenital = "SELECT Str,Dex,intellect,viter FROM `ethnicity` WHERE ID = '" . $role_ethnicity . "'";
    $db = $connection->query($ethnicity_congenital);
    foreach ($db as $row) {
        if ($role_ethnicity_congenital['Str'] == $row['Str']) {
            $ethnicity_talent['name'] = "";
            $ethnicity_talent['content'] = "【系統】取得先天天賦「天生神力」！";
            $talent .= '1|';
            array_unshift($msg, $ethnicity_talent);
        }
        if ($role_ethnicity_congenital['Dex'] == $row['Dex']) {
            $ethnicity_talent['name'] = "";
            $ethnicity_talent['content'] = "【系統】取得先天天賦「天生迅捷」！";
            $talent .= '2|';
            array_unshift($msg, $ethnicity_talent);
        }
        if ($role_ethnicity_congenital['intellect'] == $row['intellect']) {
            $ethnicity_talent['name'] = "";
            $ethnicity_talent['content'] = "【系統】取得先天天賦「天生聰穎」！";
            $talent .= '3|';
            array_unshift($msg, $ethnicity_talent);
        }
        if ($role_ethnicity_congenital['viter'] == $row['viter']) {
            $ethnicity_talent['name'] = "";
            $ethnicity_talent['content'] = "【系統】取得先天天賦「天生強健」！";
            $talent .= '4|';
            array_unshift($msg, $ethnicity_talent);
        }
    }
    return $msg;
}
//人物專屬天賦
function exclusive_talent($role_name, $talent, $msg)
{
    global $talent;
    if ($role_name == '茄汁蝦') {
        $exclusive_talent['name'] = "";
        $exclusive_talent['content'] = "【系統】取得專屬天賦「不眠蝦」！";
        array_unshift($msg, $exclusive_talent);
        $exclusive_talent['name'] = "";
        $exclusive_talent['content'] = "【系統】取得專屬天賦「鮮蝦」！";
        array_unshift($msg, $exclusive_talent);
        $exclusive_talent['name'] = "";
        $exclusive_talent['content'] = "【系統】取得專屬天賦「炸蝦」！";
        array_unshift($msg, $exclusive_talent);
        $exclusive_talent['name'] = "";
        $exclusive_talent['content'] = "【系統】取得專屬天賦「茄汁蝦」！";
        array_unshift($msg, $exclusive_talent);
        $talent .= '53|54|55|56|';
    }
    return $msg;
}
//天賦詳細資料按鈕
function get_talent_detail_con($frm)
{
    global $connection;
    $select = "SELECT talent FROM `role` WHERE `ID` = '" . $frm['role_id'] . "'";
    $db = $connection->query($select);
    foreach ($db as $row) {
        $talent = $row['talent'];
    }
    $talent = substr($talent, 0, -1);
    $talent = explode("|", $talent);
    $sql = '';
    for ($i = 0; $i < count($talent); $i++) {
        $sql .= "'" . $talent[$i] . "',";
    }
    $sql = substr($sql, 0, -1);
    $select = "SELECT ID,Name FROM `talent` WHERE `ID` in (" . $sql . ")  ORDER BY `range_role` DESC,`Type` DESC,`ID` ASC";
    $db = $connection->query($select);
    $html = '';
    foreach ($db as $row) {
        $html .= '<button onclick="javascript:btn_show_talent(' . $row['ID'] . ')" style="color: #000;">' . $row['Name'] . '</button>';
        $row_div[] = $row;
    }
    $html .= '<div id="show_talent" style="color:#000;"></div>';

    return $html;
}
//天賦詳細資料
function talent_detail_con($frm)
{
    global $connection;
    $select = "SELECT Name,synopsis,ability,side_effect,obtain FROM `talent` WHERE `ID` = '" . $frm['talent_id'] . "'";
    $db = $connection->query($select);

    foreach ($db as $row) {
        return $row;
    }

}
//檢查天賦
function already_talent()
{
    $msg[0]['name'] = "";
    $msg[0]['content'] = "【系統】 回到城鎮！";
    return $msg;
}
