<?PHP
require "connect.php";
//創建迷宮
function maze_creat($role_id, $travel_size)
{
    global $connection;
    //難度區分
    switch ($travel_size) {
        case 0:
            $size = 20;
            $monster_num = mt_rand(1, 20);
            $boss_check = mt_rand(500, 1000);
            $boss_num = mt_rand(1, 10);
            break;
        case 1:
            $size = 10;
            $monster_num = mt_rand(0, 10);
            $boss_check = mt_rand(300, 1000);
            $boss_num = mt_rand(1, 5);
            break;
        case 2:
            $size = 5;
            $monster_num = mt_rand(0, 5);
            $boss_check = mt_rand(1, 1000);
            $boss_num = 1;
            break;
    }
    $area_size = mt_rand(1, $size);
    $terrain = mt_rand(1, 9);

}
//創建迷宮怪物
function maze_monster_creat()
{

}
//迷宮怪物骰子生成
function monster_dice()
{

}
