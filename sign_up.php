<?php

if (isset($_POST['submit_sign_up']) && !empty($_POST['submit_sign_up'])){
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
                $email_check = mysqli_query($connect_main_db,"SELECT * FROM `users` WHERE `email` LIKE '".$_POST['email']."' LIMIT 0, 1");
                $info = mysqli_fetch_array($email_check);
                if($info[0]==0){
                    if ($_POST["password"] == $_POST["password_check"]){
                        $query = mysqli_query($connect_main_db, "INSERT INTO `users` SET `login`='".$_POST['login']."' ,`password`='".$_POST['password']."',`name`='".$_POST['username']."',`email`='".$_POST['email']."' ");
                        if ($query){
                            echo("Регистрация прошла успешно :)");
                            echo("<html><script>window.location = 'index.php'</script></html>");
                        } else echo("something wrong");

                    } else echo("Регистрация неуспешна:пароли не совпадают");
                } else echo("Регистрация неуспешна: такой e-mail «" .$_POST['email']. "» занят :( ");
            } else echo("Регистрация неуспешна: логин «" .$_POST['login']. "» занят :( ");

        }
    }
    else echo("Регистрация неуспешна: Все поля формы обязательны для заполнения!");
}