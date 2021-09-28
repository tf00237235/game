<?PHP
set_time_limit(0);
error_reporting(0);
// 建立M$SQL的資料庫連接
try {
    $connection = new PDO('mysql:host=localhost; dbname=dice;', 'root', 'rC2W9?vq');
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(print_r($e->getMessage()));
}

for ($i = 1; $i <= 1000; $i++) {
    $range_role_talent = mt_rand(100, 1000);
    $select = "SELECT ID,Name FROM `talent` WHERE `Type` IN ('0','1') AND range_role <= '{$range_role_talent}' ORDER BY RAND() LIMIT 1";
    $db = $connection->query($select);
    foreach ($db as $row) {
        $msg['rand_time'] += 1;
        $msg[$row['Name']] += 1;
    }
}
echo "<PRE>";
print_r($msg);
echo "</PRE>";
