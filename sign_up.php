<?php
/*
if (isset($_POST['submit_sign_up']) && !empty($_POST['submit_sign_up'])){*/
echo json_encode(array("res" => "Ты лох :)"));
if ($_POST['submit_sign_up']){
    echo json_encode(array("res" => "Ты лах :)"));
    $sign_up_fields = json_decode($_POST['submit_sign_up']);


    if (isset($_POST["login"])!= 0 && isset($_POST["password"])!= 0 && isset($_POST["password_check"])!=0 && isset($_POST["username"])){

        $connect_main_db = mysqli_connect('barter', 'root', '', 'barter_main');
        if (!$connect_main_db){
            die('Ошибка подключения (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }
        else{

            $login_check = mysqli_query($connect_main_db, "SELECT * FROM  `users` WHERE `login` LIKE '" . $_POST['login'] . "' LIMIT 0, 1");//Проверка логина на занятость
            $info = mysqli_fetch_array($login_check);
            if ($info[0] == 0) {
                $email_check = mysqli_query($connect_main_db,"SELECT * FROM `users` WHERE `email` LIKE '".$_POST['email']."' LIMIT 0, 1");//Проверка e-mail на занятость
                $info = mysqli_fetch_array($email_check);
                if($info[0]==0){
                    if ($_POST["password"] == $_POST["password_check"]){
                        $activation = md5($_POST['email'].time());
                        $query = mysqli_query($connect_main_db, "INSERT INTO `users` SET `login`='".$_POST['login']."' ,`password`='".$_POST['password']."',`name`='".$_POST['username']."',`email`='".$_POST['email']."',`activation`='$activation' ");
                        if ($query){
                            //Отправляем письмо с подтверждением регистрации

                            $to=$_POST['email'];
                            $subject="Подтверждение электронной почты";
                            $body='';

                            mail($to, $subject,"Здравствуйте.\nДля окончания регистрации на сайте bartbord.ru перейдите по ссылке\nhttp:/barter/activation/".$activation."\nЕсли вы не регистрировались на этом сайте, проигнорируйте это письмо.");
                            echo json_encode(array("res" => "Проверьте почту :)"));
                            exit();
                        } else echo("something wrong");

                    } else echo("Регистрация неуспешна:пароли не совпадают");
                } else echo("Регистрация неуспешна: такой e-mail «" .$_POST['email']. "» занят :( ");
            } else echo("Регистрация неуспешна: логин «" .$_POST['login']. "» занят :( ");

        }
    }
    else echo("Регистрация неуспешна: Все поля формы обязательны для заполнения!");
}