<?PHP
function travel_save_con($frm)
{
    global $connection;

    $insert = "INSERT INTO `role_round`( `role_id`, `travel_first`, `travel_second`, `travel_third`, `travel_fourth`, `travel_now`, `add_time`) VALUES " .
    "('" . $frm['role_id'] . "','" . $frm['travel_0'] . "','" . $frm['travel_1'] . "','" . $frm['travel_2'] . "','" . $frm['travel_3'] . "','1','" . date("Y-m-d h:i:s", strtotime("now")) . "')";
    $db = $connection->query($insert);
    $role_round_id = $connection->lastInsertId();

    return $role_round_id;
}
