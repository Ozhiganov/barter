<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<!--    <div align='center'>-->

        <h2>Регистрация</h2>
        <form action="" method="post">
            <label for="username">Имя</label>
            <input type="text" name="username" id="username" placeholder="Как к вам обращаться?" required>
            <br><br>
            <label for="login">Логин</label>
            <input type="text" name="login"  id='login' autocomplete="off" required>
            <br><br>
            <label for="password">Пароль</label>
            <input type="password" name="password" id='password' autocomplete="off" required>
            <br><br>
            <label for="password_check">Повторите пароль</label>
            <input type="password" name="password_check" id="password_check" autocomplete="off" required>
            <br><br>

            <input type="submit" name="submit" value="Зарегистрироваться">
        </form>

<!--    </div>-->
</body>
</html>
<?php

/*if(isset($_COOKIE['id']))
    echo("<html><script>window.location = '/USER/cabinet.php'</script></html>");*/

if (isset($_POST['submit']) && !empty($_POST['submit'])){
    if (isset($_POST["login"])!= 0 && isset($_POST["password"])!= 0 && isset($_POST["password_check"])!=0 && isset($_POST["username"])){


        $connect_main_db = mysqli_connect('barter', 'root', '', 'main');
        if (!$connect_main_db){
            die('Ошибка подключения (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }
        else{
            $login_check = mysqli_query($connect_main_db, "SELECT * FROM  `users` WHERE `login` LIKE '" . $_POST['login'] . "' LIMIT 0, 1");//Проверка логина на занятость
            $info = mysqli_fetch_array($login_check);
            if ($info[0] == 0) {
                if ($_POST["password"] == $_POST["password_check"]){
                    mysqli_query($connect_main_db, "INSERT INTO `users` SET `login`='". $_POST['login'] ."' ,`password`='" . $_POST['password'] . "',`name`='".$_POST['username']."'");

                    echo("Регистрация прошла успешно :)");
                } else echo("Регистрация неуспешна:пароли не совпадают");
            } else echo("Регистрация неуспешна:логин " .$_POST['login']. " занят :( ");
        }
    }
    else echo("Регистрация неуспешна: Все поля формы обязательны для заполнения!");
}

?>
