<?php
if(isset($_COOKIE['id']))
    echo("<html><script>window.location = 'cabinet.php'</script></html>");

if(isset($_POST['submit'])&& !empty($_POST['submit'])){
    if (isset($_POST["login"])!= 0 && isset($_POST["password"])!= 0){
        $connect_main_db = mysqli_connect('barter', 'root', '', 'main');//Подключение к основной базе данных
        if (!$connect_main_db){
            die('Ошибка подключения (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }
        else{
            $query = mysqli_query($connect_main_db,"SELECT `id`, `password` FROM `users` WHERE `login` LIKE '".$_POST['login']."' LIMIT 0,1");
            $array = mysqli_fetch_assoc($query);
            if(($array['password'])===($_POST["password"])){
                setcookie("id", $array['id'], time()+60*60*12);
                echo("<html><script>window.location = 'cabinet.php'</script></html>"); exit();
            } else echo("Неверная пара логин-пароль");
        }
    }else echo("Все поля обязательны для заполнения");
}
include("test.html");