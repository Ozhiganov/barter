<?php
/**
 * Скрипт активации профиля пользователя после перехода по ссылке из письма
 */
$connect_main_db = mysqli_connect('barter', 'root', '', 'barter_main');//Подключение к основной базе данных
if (!$connect_main_db){
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}
else{
$msg='';
if(!empty($_GET['code']) && isset($_GET['code']))
{
    $code = mysqli_real_escape_string($connect_main_db,$_GET['code']);
    $code = $_GET['code'];
    $c = mysqli_query($connect_main_db,"SELECT `id` FROM `users` WHERE `activation`='$code'");
    if(mysqli_num_rows($c) > 0)
    {
        $count=mysqli_query($connect_main_db,"SELECT `id` FROM `users` WHERE `activation`='$code' and status='0'");

        if(mysqli_num_rows($count) == 1)
        {
            mysqli_query($connect_main_db,"UPDATE `users` SET status='1' WHERE `activation`='$code'");
            $res = $count->fetch_all(MYSQL_ASSOC);
            mkdir("img/".$res[0][id]);
            $msg="Ваш аккаунт активирован";
        }
        else
        {
            $msg ="Ваш аккаунт уже активирован, нет необходимости активировать его снова.";
        }
    }
    else
    {
        $msg ="Неверный код активации.";
    }
}
}
?>
    //HTML часть
<?php echo $msg; ?>