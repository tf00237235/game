<?PHP
//創建迷宮
function maze_creat($role_id)
{
    $boss_num = 0;
    $difficulty = mt_rand(0, 1000);
    //難度區分
    switch ($difficulty) {
        case $difficulty >= 700:
            $parameter = array(
                "role_id" => $role_id,
                "difficulty" => "0",
                "monster_num" => "1,20",
                "boss_check" => "1000,1000",
                "boss_num" => "5,10",
                "size" => "20");
            break;
        case $difficulty >= 450:
            $parameter = array(
                "role_id" => $role_id,
                "difficulty" => "1",
                "monster_num" => "0,10",
                "boss_check" => "300,1000",
                "boss_num" => "1,5",
                "size" => "10");
            break;
        default:
            $parameter = array(
                "role_id" => $role_id,
                "difficulty" => "2",
                "monster_num" => "0,5",
                "boss_check" => "1,1000",
                "boss_num" => "1,5",
                "size" => "5");
            break;
    }
    //創建迷宮
    $maze_creat = travel_size($parameter);
    //maze_monster_creat($maze_creat['maze_id']);

    //回傳
    if (!$maze_creat['Error']) {
        $msg = array("Error" => "0", "maze_id" => $maze_creat['maze_id']);
        return $msg;
    } else {
        $msg = array("Error" => "1");
        return $msg;
    }
}
//創建迷宮怪物
function maze_monster_creat_con($maze_id)
{
    global $connection;
    if (get_Database_field("maze", "monster_creat_finish", "`ID`='{$maze_id}'")) {
        $data = array(
            0 => array(
                0 => "",
                1 => "",
            ),
            1 => array(
                0 => "",
                1 => "【 已創建過怪物 】",
            ),
        );
        return msg_creat($data, 1);
    }
    //取得迷宮大小上限
    $get_maze_size = get_Database_field("maze", "size", "`ID`='{$maze_id}'");
    //怪物
    $get_monster_num = get_Database_field("maze", "monster_num", "`ID`='{$maze_id}'");
    for ($j = 0; $j < $get_monster_num; $j++) {
        $monster_name = '';
        $maze_location = mt_rand(1, $get_maze_size);
        $type = 0;
        for ($k = 0; $k < 5; $k++) {
            $monster_dice[$k] = mt_rand(1, 30);
        }
        $monster_dice[5] = mt_rand(-5, 5);
        if ($monster_dice[0] >= 20) {
            $type = 1;
            $monster_name .= "強力的";
        }
        if ($monster_dice[1] >= 20) {
            $type = 1;
            $monster_name .= "靈敏的";
        }
        if ($monster_dice[2] >= 20) {
            $type = 1;
            $monster_name .= "智慧的";
        }
        if ($monster_dice[3] >= 20) {
            $type = 1;
            $monster_name .= "活力的";
        }
        if ($monster_dice[4] >= 20) {
            $type = 1;
            $monster_name .= "強壯的";
        }
        if ($monster_dice[5] >= 3) {
            $type = 1;
            $monster_name .= "幸運的";
        }
        $monster_name .= ($type) ? "菁英怪物" : "普通的怪物";

        $warn_num = mt_rand(0, 100);
        $hp = $monster_dice['3'] * (10 + $monster_dice['4']);
        $insert = "INSERT INTO `monster`(`hp`, `maze_id`, `Name`, `warn_num`, `Str`, `Dex`, `intellect`, `Vit`, `Viter`, `Lck`, `type`,`maze_location`, `Deat`) VALUES " .
            "('{$hp}','{$maze_id}','{$monster_name}','{$warn_num}','{$monster_dice[0]}','{$monster_dice[1]}','{$monster_dice[2]}','{$monster_dice[3]}','{$monster_dice[4]}','{$monster_dice[5]}','{$type}','{$maze_location}','0')";
        $db = $connection->query($insert);
    }
    //boss
    $get_boss_num = get_Database_field("maze", "boss_num", "`ID`='{$maze_id}'");
    for ($j = 0; $j < $get_boss_num; $j++) {
        $monster_name = '';
        $maze_location = mt_rand(1, $get_maze_size);
        $type = 2;
        for ($k = 0; $k < 5; $k++) {
            $monster_dice[$k] = mt_rand(20, 40);
        }
        $monster_dice[5] = mt_rand(0, 5);

        $monster_name .= "BOSS";
        $warn_num = mt_rand(0, 300);
        $hp = ($monster_dice['3'] * (15 + $monster_dice['4'])) + 500;
        $insert = "INSERT INTO `monster`(`hp`, `maze_id`, `Name`, `warn_num`, `Str`, `Dex`, `intellect`, `Vit`, `Viter`, `Lck`, `type`,`maze_location`, `Deat`) VALUES " .
            "('{$hp}','{$maze_id}','{$monster_name}','{$warn_num}','{$monster_dice[0]}','{$monster_dice[1]}','{$monster_dice[2]}','{$monster_dice[3]}','{$monster_dice[4]}','{$monster_dice[5]}','{$type}','{$maze_location}','0')";
        $db = $connection->query($insert);
    }
    update_field("maze", "monster_creat_finish", "1", "`ID`='{$maze_id}'");
    return monster_dice($maze_id);
}
//怪物骰子生成
function monster_dice($maze_id)
{
    global $connection;
    $select = get_field_array("monster", "ID,type,Str,Dex,intellect,viter,vit,lck", "`maze_id`='{$maze_id}'");
    foreach ($select as $key => $role) {
        $monster_type = $role['type'];
        $monster_id = $role['ID'];
        unset($role['ID']);
        unset($role['type']);
        //攻、防、閃、物點數
        $def_dice_range = round($role['vit'] * 1.1) . "," . round($role['vit'] * 1.8);
        $dodge_dice_range = round($role['Dex'] * 1.1) . "," . round($role['Dex'] * 1.8);
        //boss 特化骰子
        if ($monster_type >= 2) {
            rsort($role);
            $atk_dice_range = round(($role['0'] * 1.6) + ($role['1'] * 1.4)) . "," . round(($role['0'] * 2) + ($role['1'] * 1.4));
        } else {
            $atk_dice_range = round(($role['Str'] * 1.6) + ($role['intellect'] * 1.4)) . "," . round(($role['Str'] * 2) + ($role['intellect'] * 1.4));
        }

        $insert = "INSERT INTO `monster_dice`( `monster_id`, `atk_dice_num`, `def_dice_num`, `dodge_dice_num`, `atk_dice_range`, `def_dice_range`, `dodge_dice_range`) VALUES " .
            "('{$monster_id}','1','1','1','{$atk_dice_range}','{$def_dice_range}','{$dodge_dice_range}')";
        $db = $connection->query($insert);
    }
    $get_monster_num = get_field_num("monster", "ID", "`maze_id`='{$maze_id}' AND `type`='0'");
    $data = array(
        0 => array(
            0 => "",
            1 => "",
        ),
        1 => array(
            0 => "",
            1 => "【 怪物創建成功 】",
        ),
        2 => array(
            0 => "",
            1 => "【 本次創建 {$get_monster_num} 隻普通怪物 】",
        ),
        3 => array(
            0 => "",
            1 => "【 ??? 隻菁英怪物 】",
        ),
        4 => array(
            0 => "",
            1 => "【 ??? 隻BOSS級怪物 】",
        ),
    );

    return msg_creat($data, 1);
}
//骰迷宮本體
function travel_size($parameter)
{
    global $connection;
    //參數拆解
    $parameter['monster_num'] = explode(",", $parameter['monster_num']);
    $parameter['boss_check'] = explode(",", $parameter['boss_check']);
    $parameter['boss_num'] = explode(",", $parameter['boss_num']);
    //骰迷宮參數
    $monster_num = mt_rand($parameter['monster_num'][0], $parameter['monster_num'][1]);
    $boss_check = mt_rand($parameter['boss_check'][0], $parameter['boss_check'][1]);
    $boss_num = 0;
    if ($boss_check >= 500) {
        $boss_num = mt_rand($parameter['boss_num'][0], $parameter['boss_num'][1]);
    }
    //大小
    $area_size = mt_rand(1, $parameter['size']);
    //地區
    $terrain = mt_rand(1, 9);
    $get_round_id = get_Database_field("role_round", "ID", "role_id='" . $parameter['role_id'] . "' ORDER BY add_time DESC limit 1");
    //sql
    $insert = "INSERT INTO `maze`(`role_id`, `difficulty`, `terrain`, `size`, `monster_num`, `boss_num`, `now_area`, `role_round_id`,`monster_creat_finish`) VALUES
    ('" . $parameter['role_id'] . "','" . $parameter['difficulty'] . "','" . $terrain . "','" . $area_size . "','" . $monster_num . "','" . $boss_num . "','0','" . $get_round_id . "','0')";
    $db = $connection->query($insert);
    $maze_id = $connection->lastInsertId();
    $msg = array("Error" => '0', "maze_id" => $maze_id);
    return $msg;
}
