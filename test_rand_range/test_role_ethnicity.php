<?PHP
set_time_limit(0);
// 建立M$SQL的資料庫連接
try {
    $connection = new PDO('mysql:host=localhost; dbname=dice;', 'root', 'rC2W9?vq');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(print_r($e->getMessage()));
}
$god_pass = 0;
$t1 = 0;
$t2 = 0;
$t3 = 0;
$t4 = 0;
$t5 = 0;
$t6 = 0;
for ($j = 0; $j < 100000; $j++) {
    $test = rand_role_ethnicity();
    switch ($test['ethnicity']) {
        case "龍族":
            $t1++;
            break;
        case "龍人族":
            $t2++;
            break;
        case "海族":
            $t3++;
            break;
        case "森人族":
            $t4++;
            break;
        case "獸人族":
            $t5++;
            break;
        case "人族":
            $t6++;
            break;
    }
}
echo "測試中共出現：<br>" . "龍族：" . $t1 . "次<br>" . "龍人族：" . $t2 . "次<br>" . "海族：" . $t3 . "次<br>" . "森人族：" . $t4 . "次<br>" . "獸人族：" . $t5 . "次<br>" . "人族：" . $t6 . "次<br>";
echo "共計" . ($t1 + $t2 + $t3 + $t4 + $t5 + $t6) . "次<br>";
echo "上帝顯靈共：" . $god_pass . "次";
function rand_role_ethnicity()
{
    global $connection, $god_pass;
    $god_role = 0;
    $god = mt_rand(0, 1000000);
    $range_role = mt_rand(100, 1000);
    if ($god >= 960000) {
        $god_pass += 1;
        $god_role = mt_rand(-300, 300);
        $range_role += $god_role;
    }
    if ($range_role < 0) {
        $range_role = 900;
    }
    $select = "SELECT * FROM `ethnicity` WHERE `range_role`<='" . $range_role . "' AND Type = '0' ORDER BY RAND() LIMIT 1";
    $db_select = $connection->query($select);
    foreach ($db_select as $row) {

    }
    return array('ethnicity' => $row['Name'], 'god_role' => $god_role);
}
