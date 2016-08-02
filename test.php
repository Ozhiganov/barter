<?php
$path = "img/3/";
$user_files = scandir($path);
$names = array();
foreach($user_files as $val)
{
    $tmp = preg_split("/[.]+/",$val);
    $names[] = $tmp[0];
}
sort($names);