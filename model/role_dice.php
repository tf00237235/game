<?PHP

function dice_range($role_id, $msg, $talent, $role_ethnicity)
{
    global $connection;
    $role = get_Database_field_array('role', 'Str,Dex,intellect,viter,vit,lck', '`ID` = "' . $role_id . '"');
    //攻、防、閃、物點數
    $atk_dice_range = round(($role['Str'] * 1.6) + ($role['intellect'] * 1.4)) . "," . round(($role['Str'] * 2) + ($role['intellect'] * 1.4));
    $def_dice_range = round($role['vit'] * 1.1) . "," . round($role['vit'] * 1.8);
    $dodge_dice_range = round($role['Dex'] * 1.1) . "," . round($role['Dex'] * 1.8);
    $goods_dice_range = round($role['intellect'] * 1.6) . "," . round($role['intellect'] * 1.8);
    //六圍點數
    $str_dice_range = round($role['Str'] * 1.7) . "," . round($role['Str'] * 1.9);
    $dex_dice_range = round($role['Dex'] * 1.7) . "," . round($role['Dex'] * 1.9);
    $int_dice_range = round($role['intellect'] * 1.7) . "," . round($role['intellect'] * 1.9);
    $con_dice_range = round($role['vit'] * 1.7) . "," . round($role['vit'] * 1.9);
    $vit_dice_range = round($role['viter'] * 1.7) . "," . round($role['viter'] * 1.9);
    $luck_dice_range = round($role['lck'] * 1.7) . "," . round(abs($role['lck'] * 1.9));

    if (get_Database_field('role_dice', 'ID', '`role_id` = "' . $role_id . '"') == '') {
        //寫入
        $insert = "INSERT INTO `role_dice`( `strengthen_num`, `role_id`, `str_dice_num`, `dex_dice_num`, `int_dice_num`, `con_dice_num`, `vit_dice_num`, `luk_dice_num`, `str_dice_range`, `dex_dice_range`, `int_dice_range`, `con_dice_range`, `vit_dice_range`, `luck_dice_range`, `atk_dice_num`, `def_dice_num`, `dodge_dice_num`, `atk_dice_range`, `def_dice_range`, `dodge_dice_range`, `goods_dice_num`, `goods_dice_range`) VALUES";
        $insert .= "('3','" . $role_id . "','1','1','1','1','1','1','" . $str_dice_range . "','" . $dex_dice_range . "','" . $int_dice_range . "','" . $con_dice_range . "','" . $vit_dice_range . "','" . $luck_dice_range . "','1','1','1','" . $atk_dice_range . "','" . $def_dice_range . "','" . $dodge_dice_range . "','1','" . $goods_dice_range . "')";
        $db = $connection->query($insert);
        $dice_range['name'] = '';
        $dice_range['content'] = '【系統】基礎骰子數設定中！';
        array_push($msg, $dice_range);
        $dice_range['name'] = '';
        $dice_range['content'] = '【系統】基礎骰子數值設定中！';
        array_push($msg, $dice_range);
        $dice_range['name'] = '';
        $dice_range['content'] = '【系統】基礎骰子套用天賦！';
        array_push($msg, $dice_range);
        $dice_range['name'] = '';
        $dice_range['content'] = '【系統】基礎骰子點數套用天賦！';
        array_push($msg, $dice_range);
        $msg = dice_range_talent($role_id, $msg, $talent, $role_ethnicity);
        return $msg;
    } else {
        $dice_range['name'] = '';
        $dice_range['content'] = '【系統】狀態更新終了！(天賦)';
        array_push($msg, $dice_range);
        return $msg;
    }
}
//天賦調整
function dice_range_talent($role_id, $msg, $talent, $role_ethnicity)
{
    global $connection;
    $select = "SELECT * FROM `role_dice` WHERE `role_id` = '" . $role_id . "' ";
    $db = $connection->query($select);
    foreach ($db as $row) {}

    $talent = substr($talent, 0, -1);
    $talent = explode("|", $talent);
    for ($i = 0; $i < count($talent); $i++) {
        $row = switch_talent($talent[$i], $row, $role_ethnicity);
    }
    $row = ethnicity_dice($row, $role_ethnicity);
    //寫入sql
    $update = "UPDATE `role_dice` SET `str_dice_num`='$row[str_dice_num]',`dex_dice_num`='$row[dex_dice_num]',`int_dice_num`='$row[int_dice_num]',
    `con_dice_num`='$row[con_dice_num]',`vit_dice_num`='$row[vit_dice_num]',`luk_dice_num`='$row[luk_dice_num]',`str_dice_range`='$row[str_dice_range]',
    `dex_dice_range`='$row[dex_dice_range]',`int_dice_range`='$row[int_dice_range]',`con_dice_range`='$row[con_dice_range]',
    `vit_dice_range`='$row[vit_dice_range]',`luck_dice_range`='$row[luck_dice_range]',`atk_dice_num`='$row[atk_dice_num]',
    `def_dice_num`='$row[def_dice_num]',`dodge_dice_num`='$row[dodge_dice_num]',`goods_dice_num`='$row[goods_dice_num]',
    `atk_dice_range`='$row[atk_dice_range]',`def_dice_range`='$row[def_dice_range]',`dodge_dice_range`='$row[dodge_dice_range]',
    `goods_dice_range`='$row[goods_dice_range]',`strengthen_num`='$row[strengthen_num]' WHERE `role_id` = '" . $role_id . "'";
    $db = $connection->query($update);

    $dice_range['name'] = '';
    $dice_range['content'] = '【系統】天賦套用完畢！';
    array_push($msg, $dice_range);

    return $msg;
}
function switch_talent($talent, $row, $role_ethnicity)
{

    switch ($talent) {
        case "1":
            $row['str_dice_num'] += 1;
            break;
        case "2":
            $row['dex_dice_num'] += 1;
            break;
        case "3":
            $row['int_dice_num'] += 1;
            break;
        case "4":
            $row['vit_dice_num'] += 1;
            break;
        case "5":
            $row['strengthen_num'] += 1;
            $row['int_dice_num'] -= 1;
            break;
        case "6":
            $row['strengthen_num'] += 2;
            break;
        case "20":
            $row['strengthen_num'] += 1;
            break;
        case "23":
            $row['int_dice_num'] -= 3;
            break;
        case "24":
            $row['luk_dice_num'] -= 1;
            break;
        case "25":
            $row['luk_dice_num'] += 1;
            break;
        case "26":
            $row['strengthen_num'] += 1;
            break;
        case "27":
            $row['int_dice_num'] += 2;
            break;
        case "28":
            $row = range_determination_low_plus($row, "dex_dice_range", 3);
            break;
        case "29":
            $row = range_determination_low_plus($row, "str_dice_range", 3);
            break;
        case "30":
            $row = range_determination_low_plus($row, "int_dice_range", 3);
            break;
        case "31":
            $row = range_determination_low_plus($row, "vit_dice_range", 3);
            break;
        case "32":
            $row = range_determination_low_plus($row, "dex_dice_range", 2);
            break;
        case "33":
            $row = range_determination_low_plus($row, "str_dice_range", 2);
            break;
        case "34":
            $row = range_determination_low_plus($row, "int_dice_range", 2);
            break;
        case "35":
            $row = range_determination_low_plus($row, "vit_dice_range", 2);
            break;
        case "36":
            $row = range_determination_low_plus($row, "atk_dice_range", 10);
            $row = range_determination_low_subtraction($row, "def_dice_range", 10);
            $row = range_determination_low_subtraction($row, "dodge_dice_range", 10);
            break;
        case "37":
            $row = range_determination_low_plus($row, "def_dice_range", 5);
            break;
        case "38":
            $row = range_determination_low_plus($row, "atk_dice_range", 5);
            break;
        case "39":
            $row = range_determination_low_plus($row, "dodge_dice_range", 5);
            break;
        case "40":
            $row = range_determination_high_subtraction($row, "def_dice_range", 10);
            break;
        case "41":
            $row = range_determination_high_subtraction($row, "atk_dice_range", 10);
            break;
        case "42":
            $row = range_determination_high_subtraction($row, "dodge_dice_range", 10);
            break;
        case "43":
            $row['def_dice_num'] += 1;
            break;
        case "44":
            $row['atk_dice_num'] += 1;
            break;
        case "45":
            $row['dodge_dice_num'] += 1;
            break;
        case "47":
            $row['vit_dice_num'] -= 2;
            break;
        case "50":
            $row = range_determination_high_plus($row, "goods_dice_range", 10);
            break;
        case "51":
            $row['atk_dice_num'] += 1;
            $row = range_determination_low_plus($row, "atk_dice_num", 5);
            break;
        case "53":
            $row = range_determination_high_plus($row, "goods_dice_range", 10);
            $row = range_determination_high_plus($row, "atk_dice_num", 10);
            $row = range_determination_low_plus($row, "atk_dice_num", 5);
            break;
    }

    return $row;
}
//骰子點數下限+
function range_determination_low_plus($row, $determination, $num)
{
    $dice_range = explode(",", $row[$determination]);
    $dice_range[0] += $num;
    //最小點數不可大於最大點數
    if ($dice_range[0] > $dice_range[1]) {
        $dice_range[1] = $dice_range[0];
    }
    $row[$determination] = $dice_range[0] . "," . $dice_range[1];
    return $row;
}
//骰子點數下限-
function range_determination_low_subtraction($row, $determination, $num)
{
    $dice_range = explode(",", $row[$determination]);
    $dice_range[0] -= $num;
    $row[$determination] = $dice_range[0] . "," . $dice_range[1];
    return $row;
}
//骰子點數上限+
function range_determination_high_plus($row, $determination, $num)
{
    $dice_range = explode(",", $row[$determination]);
    $dice_range[1] += $num;
    $row[$determination] = $dice_range[0] . "," . $dice_range[1];
    return $row;
}
//骰子點數上限-
function range_determination_high_subtraction($row, $determination, $num)
{
    $dice_range = explode(",", $row[$determination]);
    $dice_range[1] -= $num;
    if ($dice_range[1] < $dice_range[0]) {
        $dice_range[0] = $dice_range[1];
    }
    $row[$determination] = $dice_range[0] . "," . $dice_range[1];
    return $row;
}
//修改攻擊點數計算方式
function change_dice_num($row, $type)
{
    $type = explode(",", $type);
    $role = get_Database_field_array('role', 'Str,Dex,intellect,viter,vit,lck', '`ID` = "' . $row['role_id'] . '"');
    $row['atk_dice_num'] = round(($role[$type[0]] * 1.6) + ($role[$type[1]] * 1.4)) . "," . round(($role[$type[0]] * 2) + ($role[$type[1]] * 1.4));
    return $row;
}
//種族專屬天賦
function ethnicity_dice($row, $role_ethnicity)
{
    //獸人族專屬天賦
    if ($role_ethnicity == '2') {
        $row['strengthen_num'] -= 2;
        $row['str_dice_num'] += 5;
        $row = range_determination_low_plus($row, "str_dice_range", 5);
    }
    //龍族、龍人族專屬天賦
    if ($role_ethnicity == '4' || $role_ethnicity == '5') {
        $row['atk_dice_num'] += 3;

        $row = range_determination_low_plus($row, "atk_dice_range", 8);
        $row = range_determination_low_plus($row, "def_dice_range", 8);

        $atk_dice_range = explode(",", $row['atk_dice_range']);
        $def_dice_range = explode(",", $row['def_dice_range']);

        $row['atk_dice_range'] = round($atk_dice_range[0] * 1.5) . "," . round($atk_dice_range[1] * 1.5);
        $row['def_dice_range'] = round($def_dice_range[0] * 1.5) . "," . round($def_dice_range[1] * 1.5);
    }
    return $row;
}
