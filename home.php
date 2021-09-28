<?PHP
/* Session
//控置中心
if ($_SERVER['REMOTE_ADDR'] !== $_SESSION['LAST_REMOTE_ADDR'] || $_SERVER['HTTP_USER_AGENT'] !== $_SESSION['LAST_USER_AGENT']) {
session_destroy();
}
session_regenerate_id();
$_SESSION['LAST_REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['LAST_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
 */
date_default_timezone_set("Asia/Taipei");
error_reporting(0);
require_once "model/rand.php";
require_once "model/connect.php";
if ($_POST['type'] != '') {
    $type = $_POST['type'];
} else if ($_GET['type'] == 'creatRole_show') {
    $type = 'creatRole_show';
} else {
    $type = '';
}

switch ($type) {
    case "creatRole_show":
        creatRole_show();
        break;
    case "creatRole":
        creatRole($_POST);
        break;
    case "role_ethnicity":
        role_ethnicity();
        break;
    case "role_ability":
        role_ability();
        break;
    case "start_role_talent":
        start_role_talent($_POST);
        break;
    case "get_talent_detail":
        get_talent_detail($_POST);
        break;
    case "get_status":
        get_status($_POST);
        break;
    case "travel_save":
        travel_save($_POST);
        break;
    case "maze_monster_creat":
        maze_monster_creat($_POST);
        break;
    case "travel_instruction":
        travel_instruction($_POST);
        break;
    case "start_game":
        start_game($_POST['role_id'], $_POST['difficulty']);
        break;
    default:
        index_figlet();
        break;
}
//進入頁
function index_figlet()
{
/*
require '../../php/vendor/autoload.php';
$figlet = new Laminas\Text\Figlet\Figlet();
$title = "<BR>";
$title .= $figlet->setFont('fonts/starwars.flf')->render('DICE');
 */
    require_once "./view/index_figlet.html";
}
//創建角色畫面
function creatRole_show()
{
    $get_role = role();
    $btn = '';
    for ($j = 0; $j < count($get_role); $j++) {
        $btn .= '<button type="button" class="btn btn-info" onclick="javascript: explanation(\'' . $get_role[$j]['Name'] . '\',' . $get_role[$j]['Str'] . ',' . $get_role[$j]['Dex'] . ',' . $get_role[$j]['intellect'] . ',' . $get_role[$j]['Vit'] . ',' . $get_role[$j]['Viter'] . ',\'' . $get_role[$j]['explanation'] . '\')">' . $get_role[$j]['Name'] . '</button>';
    }

    require_once "./view/range.html";
}
//骰種族
function role_ethnicity()
{
    $god = new rand_for_dice();
    $role_id = $_POST['role_id'];
    echo json_encode(rand_role_ethnicity($god->god(), $role_id));
}
//骰先天能力值
function role_ability()
{
    $ethnicity = $_POST['ethnicity'];
    $role_id = $_POST['role_id'];

    echo json_encode(rand_role_ability($ethnicity, $role_id));
}
//寫入資料庫-創建角色
function creatRole($frm)
{
    $talent = 0;
    $role_data = get_sql_role_data($frm['role_id']);
    if ($role_data['ErrorCode']) {
        if ($frm['str'] != $role_data['Str']) {
            $talent++;
        }
        if ($frm['agi'] != $role_data['Dex']) {
            $talent++;
        }
        if ($frm['int'] != $role_data['intellect']) {
            $talent++;
        }
        if ($frm['con'] != $role_data['Vit']) {
            $talent++;
        }
        if ($frm['vit'] != $role_data['Viter']) {
            $talent++;
        }
        $update = update_role_Name($frm['role_id'], json_encode($frm), $talent);
    } else {
        print_r($role_data['ErrorMsg']);
        die;
    }
    start_game($frm['role_id'], $frm['Difficulty']);
}
//開始遊戲畫面
function start_game($role_id, $difficulty)
{
    if ($difficulty == '') {$$difficulty = get_Database_field("role", "difficulty", "ID='{$role_id}' ");}
    //骰天賦
    $js = "js/game_start_role_talent.js";
    require_once "./view/index.php";
    require_once './view/footer_index.html';

}
function start_role_talent($frm)
{
    echo json_encode(role_talent($frm));
}
//取得天賦內容
function get_talent_detail($frm)
{
    if ($frm['detail']) {
        echo get_talent_detail_con($frm);
    } else {
        echo json_encode(talent_detail_con($frm));
    }
}
//取得數據狀態
function get_status($frm)
{
    echo json_encode(get_status_con($frm['role_id']));
}
function travel_save($frm)
{

    //儲存最新行程

    if (travel_save_con($frm)) {
        travel_start($frm['role_id']);
    } else {
        MsgError($frm['role_id']);
    }

}
function travel_start($role_id)
{
    $get_now_round = get_Database_field("role_round", "travel_now", "role_id='{$role_id}' ORDER BY add_time DESC limit 1");
    switch ($get_now_round) {
        case 1:
            $get_travel = get_Database_field("role_round", "travel_first", "role_id='{$role_id}' ORDER BY add_time DESC limit 1");
            break;
        case 2:
            $get_travel = get_Database_field("role_round", "travel_second", "role_id='{$role_id}' ORDER BY add_time DESC limit 1");
            break;
        case 3:
            $get_travel = get_Database_field("role_round", "travel_third", "role_id='{$role_id}' ORDER BY add_time DESC limit 1");
            break;
        case 4:
            $get_travel = get_Database_field("role_round", "travel_fourth", "role_id='{$role_id}' ORDER BY add_time DESC limit 1");
            break;
        default:
            MsgError($role_id);
            break;
    }
    switch ($get_travel) {
        case 0:
            //製作迷宮
            $maze = maze_creat($role_id);
            $maze_id = $maze['maze_id'];
            if (!$maze['Error']) {
                $js = "js/travel_start.js";
                require_once "./view/adventure.php";
                require_once "./view/footer_adventure.html";
            } else {
                MsgError($role_id);
            }
            break;
        case 1:
            //載入事件
            require_once "./view/index.php";
            require_once './view/footer_index.html';
            break;
        case 2:
            //載入事件
            require_once "./view/index.php";
            require_once './view/footer_index.html';
            break;
        case 3:
            //載入事件
            require_once "./view/index.php";
            require_once './view/footer_index.html';
            break;
        default:
            MsgError($role_id);
            break;

    }

}
function maze_monster_creat($frm)
{
    $maze_id = $frm['maze_id'];
    $role_id = $frm['role_id'];
    echo json_encode(maze_monster_creat_con($maze_id));
}
function travel_instruction($frm)
{

}
//錯誤畫面
function MsgError($role_id)
{
    $js = "js/MsgError.js";
    require_once "./view/index_error.php";
    require_once './view/footer_error.html';
}
