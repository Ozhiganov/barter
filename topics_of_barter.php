<?php


$suggest_db = new mysqli("barter", "root", "", "main");
if ($suggest_db->connect_errno) {
    $result = array( "res" => "Не удалось подключиться:".$suggest_db->connect_error);
    exit();
}
if($suggest_db->real_query("INSERT INTO advertisements (title, name) VALUES ('1','дебил')"))
    echo "luck";
else
    echo "fuck";
/*$addRegions = new mysqli("barter", "root", "", "main");
if($addRegions->connect_errno)
{
    echo "fuck";
    exit();
}
$handle = @fopen("tst.txt", "r");
$buffer = fgets($handle, 4096);
$addRegions->set_charset("utf8");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        $region = $addRegions->real_escape_string($buffer);
        if($addRegions->real_query("INSERT INTO regions (name) VALUES ('$region')"))
            printf("%s\n","Success");
        else
            printf("%s\n","error");
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}*/
?>