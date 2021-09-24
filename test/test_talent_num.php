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
    $num = mt_rand(-3, 5);
    $msg[$num] += 1;
}
ksort($msg);
foreach ($msg as $key => $val) {
    echo "<PRE>";
    echo "$key = $val\n";
    echo "</PRE>";
}
