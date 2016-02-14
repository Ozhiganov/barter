<?php
//You need to perform this only one time
$create_db = new mysqli("barter", "root", "");
    if ($create_db->connect_errno) {
         echo "Не удалось подключиться:".$create_db->connect_error;
    exit();
    }
$create_db->query("CREATE DATABASE `main` CHARACTER SET utf8 COLLATE utf8_general_ci");
$create_db->close();
$create_table = new mysqli("barter","root","","main");
if ($create_table->connect_errno) {
    echo "Не удалось подключиться:".$create_table->connect_error;
    exit();
}
$create_table->query("CREATE TABLE topics (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name TEXT NOT NULL
)");
$create_table->query("CREATE TABLE regions (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name TEXT NOT NULL
)");
$create_table->query("CREATE TABLE cities (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
region_id INT(11),
name TEXT NOT NULL
)");
$create_table->query("CREATE TABLE advertisements (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
suggest_from INT(11),
suggest_to INT(11),
title TEXT NOT NULL,
description TEXT NOT NULL,
contacts TEXT NOT NULL,
name TEXT NOT NULL,
media TEXT NOT NULL,
region INT(11),
city INT(11),
price INT(11)
)");

$handle = @fopen("regions.txt", "r");
$buffer = fgets($handle, 4096);
$create_table->set_charset("utf8");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        $region = $create_table->real_escape_string($buffer);
        if($create_table->real_query("INSERT INTO regions (name) VALUES ('$region')"))
            printf("%s\n","Success");
        else
            printf("%s\n","error");
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}