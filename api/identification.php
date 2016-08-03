<?php
require_once "db_connection.php";
if ($_POST['submit_sign_up']){
    $sign_up_fields = json_decode($_POST['submit_sign_up']);

    if(strcasecmp($sign_up_fields->password,$sign_up_fields->password_check) == 0) {
            $login_check = $db -> query("SELECT * FROM  `users` WHERE `login` LIKE '" . $sign_up_fields->login . "' LIMIT 0, 1");//Проверка логина на занятость
            $info = $login_check -> fetch_array();
            if ($info[0] == 0) {
                $email_check = $db -> query("SELECT * FROM `users` WHERE `email` LIKE '" . $sign_up_fields->email . "' LIMIT 0, 1");//Проверка e-mail на занятость
                $info = $email_check -> fetch_array();
                if ($info[0] == 0) {
                    $activation = md5($_POST['email'] . time());
                    $current_date = time();
                    $query = $db -> query("INSERT INTO `users` SET `login`='" . $sign_up_fields->login . "' ,`password`='" . $sign_up_fields->password . "',`name`='" . $sign_up_fields->username . "',`email`='" . $sign_up_fields->email . "',`activation`='$activation', `registration_date`='$current_date' ");
                    if ($query) {
                        //Отправляем письмо с подтверждением регистрации

                        $to = $sign_up_fields->email;
                        $subject = "Подтверждение электронной почты";
                        $body = '';

                        mail($to, $subject, "Здравствуйте.\nДля окончания регистрации на сайте bartbord.ru перейдите по ссылке\nhttp:/barter/activation/" . $activation . "\nЕсли вы не регистрировались на этом сайте, проигнорируйте это письмо.");
                        echo json_encode(array("res" => "mail_suc"));
                        exit();
                    } else echo(json_encode(array("res" => "unknown")));

                } else echo(json_encode(array("res" => "email_error")));
            } else echo(json_encode(array("res" => "login_error")));
    }
    else
        echo json_encode(array("res" => "password_error"));
}

if($_POST['submit_sign_in']){
    $sign_in_data = json_decode($_POST['submit_sign_in']);
        $query = $db -> query("SELECT `id`, `password` FROM `users` WHERE `login` LIKE '".$sign_in_data->login."' LIMIT 0,1");
        $array = $query -> fetch_assoc();

        if(strcasecmp($array['password'],$sign_in_data->password) == 0){
            setcookie("id", $array['id'], time()+60*60*12, "/");
            echo json_encode(array("res" => 1));
            exit();
        }
        else
            echo json_encode(array("res" => "Неверная пара логин/пароль"));
}
if($_POST['sign_out']) {
    setcookie("id", "", time() - 60 * 60 * 12, "/");
    echo json_encode(array("res" => 1));
}
if($_POST['check_status']) {
    if(!isset($_COOKIE['id']))
        echo json_encode(array("res" => 0));
    else {
        $status_req = $db->query("SELECT `status` FROM `users` WHERE `id` LIKE '".$_COOKIE['id']."' LIMIT 0,1");
        $status = $status_req -> fetch_all(MYSQLI_ASSOC);
        if($status[0]['status'] == 1)
            echo json_encode(array("res" => 2));
        else
            echo json_encode(array("res" => 1));
    }
}
