<?php
    include "main.php";
    if($_POST['suggest'])
    {
        $param = json_decode($_POST['suggest']);
        $fields = "";
        $values = "'";
        foreach ($suggest as &$val) {
            $fields = $fields.$val.",";
            $values = $values.$param->$val."','";
        }
        unset($val);


        $suggest_db = new mysqli("barter", "root", "", "main");
        if ($suggest_db->connect_errno) {
            $result = array( "res" => "Не удалось подключиться:".$suggest_db->connect_error);
            exit();
        }
        $fields = (substr($fields, 0, -1));
        $values = (substr($values, 0, -2));

        if($suggest_db->real_query("INSERT INTO advertisements ($fields) VALUES ($values)"))
            $result = array( "res" => "ok");
        else
            $result = array( "res" => "not ok");
        $suggest_db->close();
        echo json_encode($result);
        exit();
    }