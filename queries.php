<?php
    include "main.php";
    if($_POST['suggest'])
    {
        //query string creation except city_id
        $param = json_decode($_POST['suggest']);
        $fields = "";
        $values = "'";
        foreach ($suggest as &$val) {
            $fields = $fields.$val.",";
            $values = $values.$param->$val."','";
        }
        unset($val);
        //connection to db
        $suggest_db = new mysqli("barter", "root", "", "main");
        if ($suggest_db->connect_errno) {
            $result = array( "res" => "Не удалось подключиться:".$suggest_db->connect_error);
            exit();
        }
        //calculation city_id
        $city = $param->city;
        $region_id = $param->region;
        $city_id = 0;

        $cities_query = $suggest_db->query("SELECT name,id FROM cities WHERE region_id='$region_id'");
        $cities_array = $cities_query->fetch_all(MYSQL_ASSOC);

        foreach($cities_array as $val)
        {
            if(strnatcasecmp($val[name],$city) == 0)
                $city_id = $val[id];
        }
        unset($val);
        if($city_id == 0)
        {
            $suggest_db->real_query("INSERT INTO cities (name,region_id) VALUES ('$city','$region_id')");
            $city_query = $suggest_db->query("SELECT id FROM cities WHERE name='$city' AND region_id='$region_id'");
            $city_array = $city_query->fetch_all(MYSQL_ASSOC);
            $city_id = $city_array[0][id];
        }
        $fields = $fields."city";
        $values = $values.$city_id."'";
        //query to db

        if($suggest_db->real_query("INSERT INTO advertisements ($fields) VALUES ($values)"))
            $result = array( "res" => "ok");
        else
            $result = array( "res" => "not ok");
        $suggest_db->close();
        echo json_encode($result);
        exit();
    }
    if($_POST['region'])
    {
        $region_id = json_decode($_POST['region'])->region;
        $region_db = new mysqli("barter", "root", "", "main");
        if ($suggest_db->connect_errno) {
            exit();
        }
        $query_cities = $region_db->query("SELECT name,id FROM cities WHERE region_id='$region_id'");
        $cities_result = $query_cities->fetch_all(MYSQL_ASSOC);
        $cities_array = array();
        foreach ($cities_result as $val) {
            array_push($cities_array, array($val[name],$val[id]));
        }
        unset($val);
        echo json_encode($cities_array);
        $region_db->close();
        exit();
    }