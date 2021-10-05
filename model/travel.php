<?PHP
//儲存玩家每季選擇
function travel_save_con($frm)
{
    global $connection;
    $role_round_id = get_field_num("role_round", "ID", "`travel_now` <5 AND `role_id` = '{$frm['role_id']}'");
    if (!$role_round_id) {
        $insert = "INSERT INTO `role_round`( `role_id`, `travel_first`, `travel_second`, `travel_third`, `travel_fourth`, `travel_now`, `add_time`) VALUES " .
        "('" . $frm['role_id'] . "','" . $frm['travel_0'] . "','" . $frm['travel_1'] . "','" . $frm['travel_2'] . "','" . $frm['travel_3'] . "','1','" . date("Y-m-d h:i:s", strtotime("now")) . "')";
        $db = $connection->query($insert);
        $role_round_id = $connection->lastInsertId();
    }

    return $role_round_id;
}
//冒險各指令
class travel
{
    private $data = array(
        0 => array(
            0 => "",
            1 => "",
        ),
    );
    private $frm;
    public function travel_instruction($frm)
    {
        $data = $this->data;
        $now = get_Database_field("maze", "now_area", "`ID`={$frm['maze_id']}");
        if ($now == 0) {
            return $this->never_move($frm);
        } else {
            return $this->move($frm);
        }
        //$data = msg_push($data, "【 測試 】");
        return msg_creat($data, 0);
    }
    private function never_move($frm)
    {
        $data = $this->data;
        if ($frm['behavior_type'] == 1 | $frm['behavior_type'] == 2 | $frm['behavior_type'] == 3 | $frm['behavior_type'] == 4) {
            if ($frm['instruction_num'] >= 10) {
                $data = msg_push($data, "【 選探索啦！這裡就只是個入口處而已啦！ 】");
                return msg_creat($data, 0);
            }
            switch ($frm['behavior_type']) {
                case "1":
                    $data = msg_push($data, "【 攻擊空氣！ 】");
                    break;
                case "2":
                    $data = msg_push($data, "( 正在躡手躡腳的移動 )");
                    $data = msg_push($data, "【 嘗試在迷宮入口處偷襲...空氣? 】");
                    break;
                case "3":
                    $data = msg_push($data, "【 在入口處偷空氣 】");
                    break;
                case "4":
                    $data = msg_push($data, "【 ... 】");
                    $data = msg_push($data, "【 在入口處嘗試繞過...迷宮? 】");
                    break;
            }
            return msg_creat($data, 0);
        }
        return $this->move($frm);
    }
    private function move($frm)
    {
        $data = $this->data;
        $now = get_Database_field("maze", "now_area", "`ID`={$frm['maze_id']}");
        $frm['maze_location'] = $now;
        //剛進迷宮第0區絕對安全
        if ($now == 0) {
            update_field("maze", "now_area", ($now + 1), "`ID`={$frm['maze_id']}");
            $data = msg_push($data, "【 你謹慎的向迷宮內移動 】");
            return msg_creat($data, 0);
        } else {
            //判定有沒有撞到怪
            if ($this->check_monster_now($frm)) {
                $data = msg_push($data, "【 似乎有什麼奇怪的聲響 】");
            }
            if ($this->check_monster_warn_num($frm)) {

            }
        }

    }
    private function check_monster_now($frm)
    {
        return get_field_num("monster", "maze_location", "`maze_id`={$frm['maze_id']}");
    }
    private function check_monster_warn_num($frm)
    {
        return get_field_array("monster", "warn_num", "`maze_id`='{$frm['maze_id']}' AND `maze_location`='{$frm['maze_location']}'");
    }
}
