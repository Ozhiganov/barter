<?php
$find_db = new mysqli("barter", "root", "", "barter_main");
if ($find_db->connect_errno) {
    exit();
}
$find_result = $find_db->query("SELECT * FROM `advertisements` LIMIT 1");
$find_result_array = $find_result->fetch_all(MYSQLI_ASSOC);
setlocale(LC_ALL, "Russian");

echo strftime("%d %B %Y %H:%M",$find_result_array[0][publish_date]);