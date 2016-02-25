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

        $suggest_db->real_query("INSERT INTO advertisements ($fields) VALUES ($values)");
        $suggest_db->close();
        echo json_encode($result);
        exit();
    }
    if($_POST['region'])
    {
        $region_id = json_decode($_POST['region'])->region;
        $region_db = new mysqli("barter", "root", "", "main");
        if ($region_db->connect_errno) {
            exit();
        }
        $cities_query = "SELECT name FROM cities WHERE region_id='$region_id'";
        $query_result = $region_db->query($cities_query);
        $cities_result = $query_result->fetch_all(MYSQL_ASSOC);
        $cities_array = array();
        foreach ($cities_result as $val) {
            array_push($cities_array, $val[name]);
            }
        unset($val);
        echo json_encode($cities_array);
        $region_db->close();
        exit();
    }
    if($_POST['find']){
        $find_fields = json_decode($_POST['find']);
        $find_db = new mysqli("barter", "root", "", "main");
        if ($find_db->connect_errno) {
            exit();
        }
        $find_query = "SELECT title, description, contacts, name  FROM advertisements WHERE suggest_from='$find_fields->find_from' AND suggest_to='$find_fields->find_to'";
        if(strnatcasecmp("", $find_fields->keywords) != 0)
            $find_query .= " AND LOWER(title) LIKE '%".strtolower($find_fields->keywords)."%'";
        if($find_fields->region != 0)
            $find_query .= " AND region='$find_fields->region'";
        if(strnatcasecmp("", $find_fields->city) != 0){
            $city_query = $find_db->query("SELECT id FROM cities WHERE name='$find_fields->city'");
            $city_array = $city_query->fetch_all(MYSQL_ASSOC);
            $city_id = $city_array[0][id];
            $find_query .= " AND city='$city_id'";
        }
        $find_result = $find_db->query($find_query);
        $find_result_array = $find_result->fetch_all(MYSQL_ASSOC);
        $json_result = array();
        foreach ($find_result_array as $val)
            array_push($json_result,array("title" => $val[title],"description" => $val[description],"contacts" => $val[contacts],"name" => $val[name]));
        echo json_encode($json_result);

        /*if(strnatcasecmp("", $find_fields->keywords) == 0)
            $result = array("res" => "empty");
        else
            $result = array("res" => "not empty");
        echo json_encode($result);*/
        exit();
    }