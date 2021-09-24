<?PHP
//創建迷宮
function maze_creat($role_id, $difficulty)
{
    global $connection;
    $boss_num = 0;
    //難度區分
    switch ($difficulty) {
        case 0:
            $parameter = array(
                "role_id" => $role_id,
                "difficulty" => $difficulty,
                "monster_num" => "1,20",
                "boss_check" => "1000,1000",
                "boss_num" => "5,10",
                "event_num" => "1,20",
                "size" => "20");
            break;
        case 1:
            $parameter = array(
                "role_id" => $role_id,
                "difficulty" => $difficulty,
                "monster_num" => "0,10",
                "boss_check" => "300,1000",
                "boss_num" => "1,5",
                "event_num" => "1,10",
                "size" => "10");
            break;
        case 2:
            $parameter = array(
                "role_id" => $role_id,
                "difficulty" => $difficulty,
                "monster_num" => "0,5",
                "boss_check" => "1,1000",
                "boss_num" => "1,5",
                "event_num" => "1,5",
                "size" => "5");
            break;
    }
    //創建迷宮
    $maze_creat = travel_size($parameter);
    //回傳
    if (!$maze_creat['Error']) {
        $msg = array("Error" => "0");
        return $msg;
    } else {
        $msg = array("Error" => "1");
        return $msg;
    }
}
//創建迷宮怪物
function maze_monster_creat()
{

}
//迷宮怪物骰子生成
function monster_dice()
{

}
//骰迷宮本體
function travel_size($parameter)
{
    global $connection;
    //參數拆解
    $parameter['monster_num'] = explode(",", $parameter['monster_num']);
    $parameter['boss_check'] = explode(",", $parameter['boss_check']);
    $parameter['boss_num'] = explode(",", $parameter['boss_num']);
    $parameter['event_num'] = explode(",", $parameter['event_num']);
    //骰迷宮參數
    $monster_num = mt_rand($parameter['monster_num'][0], $parameter['monster_num'][1]);
    $boss_check = mt_rand($parameter['boss_check'][0], $parameter['boss_check'][1]);
    $boss_num = 0;
    if ($boss_check >= 500) {
        $boss_num = mt_rand($parameter['boss_num'][0], $parameter['boss_num'][1]);
    }
    $event_num = mt_rand($parameter['event_num'][0], $parameter['event_num'][1]);
    //大小
    $area_size = mt_rand(1, $parameter['size']);
    //地區
    $terrain = mt_rand(1, 9);
    $get_round_id = get_Database_field("role_round", "ID", "role_id='" . $parameter['role_id'] . "' ORDER BY add_time DESC limit 1");
    //sql
    $insert = "INSERT INTO `maze`(`role_id`, `difficulty`, `terrain`, `size`, `monster_num`, `boss_num`, `event_num`, `role_round_id`) VALUES
    ('" . $parameter['role_id'] . "','" . $parameter['difficulty'] . "','" . $terrain . "','" . $area_size . "','" . $monster_num . "','" . $boss_num . "','" . $event_num . "','" . $get_round_id . "')";
    $db = $connection->query($insert);

    $msg = array("Error" => '0');
    return $msg;
}
