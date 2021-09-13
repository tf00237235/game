<?PHP

function dice_range($role_id, $msg, $talent)
{
    global $connection;
    $role = get_Database_field_array('role', 'Str,Dex,intellect,viter,vit,lck', '`ID` = "' . $role_id . '"');
    if (get_Database_field('role_dice', 'ID', '`role_id` = "' . $role_id . '"') == '') {
        //攻、防、閃、物點數
        $atk_dice_range = ($role['Str'] * 1.6) + ($role['intellect'] * 1.4) . "," . ($role['Str'] * 2) + ($role['intellect'] * 1.4);
        $def_dice_range = $role['vit'] * 1.1 . "," . $role['vit'] * 1.8;
        $dodge_dice_range = $role['Dex'] * 1.1 . "," . $role['Dex'] * 1.8;
        $goods_dice_range = $role['intellect'] * 1.6 . "," . $role['intellect'] * 1.8;
        //六圍點數
        $str_dice_range = $role['Str'] * 1.7 . "," . $role['Str'] * 1.9;
        $dex_dice_range = $role['Dex'] * 1.7 . "," . $role['Dex'] * 1.9;
        $int_dice_range = $role['intellect'] * 1.7 . "," . $role['intellect'] * 1.9;
        $con_dice_range = $role['vit'] * 1.7 . "," . $role['vit'] * 1.9;
        $vit_dice_range = $role['viter'] * 1.7 . "," . $role['viter'] * 1.9;
        $luck_dice_range = $role['lck'] * 1.7 . "," . abs($role['lck'] * 1.9);
        //寫入
        $insert = "INSERT INTO `role_dice`( `role_id`, `str_dice_num`, `dex_dice_num`, `int_dice_num`, `con_dice_num`, `vit_dice_num`, `luk_dice_num`, `str_dice_range`, `dex_dice_range`, `int_dice_range`, `con_dice_range`, `vit_dice_range`, `luck_dice_range`, `atk_dice_num`, `def_dice_num`, `dodge_dice_num`, `atk_dice_range`, `def_dice_range`, `dodge_dice_range`, `goods_dice_num`, `goods_dice_range`) VALUES";
        $insert .= "('" . $role_id . "','1','1','1','1','1','1','" . $str_dice_range . "','" . $dex_dice_range . "','" . $int_dice_range . "','" . $con_dice_range . "','" . $vit_dice_range . "','" . $luck_dice_range . "','1','1','1','" . $atk_dice_range . "','" . $def_dice_range . "','" . $dodge_dice_range . "','1','" . $goods_dice_range . "')";
        $db = $connection->query($insert);
        $dice_range['name'] = '';
        $dice_range['content'] = '【系統】基礎骰子數設定中！';
        array_push($msg, $dice_range);
        $dice_range['name'] = '';
        $dice_range['content'] = '【系統】基礎骰子數值設定中！';
        array_push($msg, $dice_range);
        $dice_range['name'] = '';
        $dice_range['content'] = '【系統】套用天賦！';
        array_push($msg, $dice_range);
        $msg = dice_range_talent($role_id, $msg, $talent);

        return $msg;
    } else {
        return $msg;
    }
}
//天賦調整
function dice_range_talent($role_id, $msg, $talent)
{
    global $connection;
    $select = "SELECT * FROM `role_dice` WHERE `role_id` = '" . $role_id . "' ";
    $db = $connection->query($select);
    foreach ($db as $row) {}
    $talent = substr($talent, 0, -1);
    $talent = explode("|", $talent);

}
