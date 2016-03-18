<?php
    include "main.php";
    if($_POST['suggest'])
    {
        //query string creation except city_id and publish_date
        $param = json_decode($_POST['suggest']);//Преобразования json в массив
        $fields = "";
        $values = "'";
        foreach ($suggest as &$val) {
            $fields = $fields.$val.",";
            if(strnatcasecmp($val, "media") == 0) //CRUTCH
                $values = $values.substr($param->$val,1)."','";
            else
                $values = $values.$param->$val."','";
        }
        unset($val);
        //connection to db
        $suggest_db = new mysqli("barter", "root", "", "barter_main");
        if ($suggest_db->connect_errno) {
            $result = array( "res" => "Не удалось подключиться:".$suggest_db->connect_error);
            exit();
        }
        //calculation city_id
        $city = $param->city;
        $region_id = $param->region;
        $city_id = 0;

        $cities_query = $suggest_db->query("SELECT name,id FROM cities WHERE region_id='$region_id'");
        $cities_array = $cities_query->fetch_all(MYSQLI_ASSOC);

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
            $city_array = $city_query->fetch_all(MYSQLI_ASSOC);
            $city_id = $city_array[0][id];
        }
        $name_req = $suggest_db->query("SELECT `name` FROM `users` WHERE `id` LIKE '".$_COOKIE['id']."' LIMIT 0,1");
        $name = $name_req->fetch_all(MYSQLI_ASSOC);
        $fields = $fields."city,publish_date,user_id,name";
        $values = $values.$city_id."','".time()."','".$_COOKIE['id']."','".$name[0][name]."'";
        //query to db

        if($suggest_db->real_query("INSERT INTO advertisements ($fields) VALUES ($values)"))
            echo json_encode(array("res" => "ok"));
        else
            echo json_encode(array("res" => substr("insert error",1)));
        $suggest_db->close();
        exit();
    }
    if($_POST['region'])
    {
        $region_id = json_decode($_POST['region'])->region;
        $region_db = new mysqli("barter", "root", "", "barter_main");
        if ($region_db->connect_errno) {
            exit();
        }
        $cities_query = "SELECT name FROM cities WHERE region_id='$region_id'";
        $query_result = $region_db->query($cities_query);
        $cities_result = $query_result->fetch_all(MYSQLI_ASSOC);
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
        $find_db = new mysqli("barter", "root", "", "barter_main");
        if ($find_db->connect_errno) {
            exit();
        }
        $find_query = "SELECT id,media,title,publish_date,city,region  FROM advertisements WHERE suggest_from='$find_fields->find_from' AND suggest_to='$find_fields->find_to'";
        if(strnatcasecmp("", $find_fields->keywords) != 0)
            $find_query .= " AND LOWER(title) LIKE '%".strtolower($find_fields->keywords)."%'";
        if($find_fields->region_id != 0) {
            $find_query .= " AND region='$find_fields->region_id'";
            $region_name = $find_fields->region_name;
            if(strnatcasecmp("", $find_fields->city) != 0){
                $city_query = $find_db->query("SELECT id FROM cities WHERE name='$find_fields->city'");
                $city_array = $city_query->fetch_all(MYSQLI_ASSOC);
                $city_id = $city_array[0][id];
                $find_query .= " AND city='$city_id'";
                $city_name = $find_fields->city;
            }
            else
                $city_name = null;
        }
        else {
            $region_name = null;
            $city_name = null;
        }

        $find_result = $find_db->query($find_query);
        $find_result_array = $find_result->fetch_all(MYSQLI_ASSOC);
        setlocale(LC_ALL, "Russian");
        $json_result = array();
        foreach ($find_result_array as $val) {
            if($region_name == null) {
                $current_region_req = $find_db->query("SELECT `name` FROM `regions` WHERE `id`='$val[region]' LIMIT 0,1");
                $current_region_arr = $current_region_req->fetch_all(MYSQLI_ASSOC);
                $current_region = $current_region_arr[0][name];
            }
            else
                $current_region = $region_name;
            if($city_name == null) {
                $current_city_req = $find_db->query("SELECT `name` FROM `cities` WHERE `id`='$val[city]' LIMIT 0,1");
                $current_city_arr = $current_city_req->fetch_all(MYSQLI_ASSOC);
                $current_city = $current_city_arr[0][name];
            }
            else
                $current_city = $city_name;
            array_push($json_result, array("id" => $val[id],"city" => $current_city,"title" => $val[title],"region" => $current_region, "media" => $val[media], "date" => strftime("%d.%m.%Y %H:%M", $val[publish_date])));
        }
        echo json_encode($json_result);
        $find_db->close();
        exit();
    }

    if(!empty($_FILES) && !empty($_FILES['my-file'])) {
        //path to load
        $path = 'img/'.$_COOKIE['id'].'/';
        $tmp_path = 'tmp/';
        $_FILES['my-file']['name'] = md5(time());
        function resize($file)
        {
           global $tmp_path;

           $max_size = 600;
           if ($file['type'] == 'image/jpeg') {
               $source = imagecreatefromjpeg($file['tmp_name']);
               $extension = ".jpeg";
           }
           elseif ($file['type'] == 'image/png') {
               $source = imagecreatefrompng($file['tmp_name']);
               $extension = ".png";
           }
           elseif ($file['type'] == 'image/gif') {
               $source = imagecreatefromgif($file['tmp_name']);
               $extension = ".gif";
           }
           else
               return false;
           $w_src = imagesx($source);
           $h_src = imagesy($source);

           if ($w_src > $max_size)
           {
               // proportions
               $ratio = $w_src/$max_size;
               $w_dest = round($w_src/$ratio);
               $h_dest = round($h_src/$ratio);
               // create empty image
               $dest = imagecreatetruecolor($w_dest, $h_dest);

               // copy to empty image
               imagecopyresampled($dest, $source, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

               imagejpeg($dest, $tmp_path.$file['name'], 75);
               imagedestroy($dest);
               imagedestroy($source);

               return $extension;
           }
           else
           {
               imagejpeg($source, $tmp_path . $file['name'], 75);
               imagedestroy($source);
               return $extension;
           }
        }

        $ext = resize( $_FILES['my-file']);
        $new_path = $path.$_FILES['my-file']['name'].$ext;
        if (!@copy($tmp_path.$_FILES['my-file']['name'], $new_path)) {
            echo json_encode(array('status' => "error",));
            exit();
        }
        echo json_encode(array('status' => $new_path));
        unlink($tmp_path.$_FILES['my-file']['name']);
    }