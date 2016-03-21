<?php
if(!isset($_COOKIE['id']))
    echo("<html><script>window.location = 'index.php'</script></html>");

if($_GET['act']) {
    $action = $_GET['act'];

    switch($action){
        case 'logout':{
            setcookie("id", "", time() -60*60*12, "/");
            echo("<html><script>window.location = 'index.php'</script></html>"); exit();
            break;}

        default: echo("<html><script>window.location = 'cabinet.php'</script></html>");
    }
    // 1.
// Оплата заданной суммы с выбором валюты на сайте мерчанта
// Payment of the set sum with a choice of currency on merchant site

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)
    $mrh_login = "barter_demo";
    $mrh_pass1 = "barter_password_1";

// номер заказа
// number of order
    $inv_id = 0;

// описание заказа
// order description
    $inv_desc = "BARTER add money to account";

// сумма заказа
// sum of order
    $out_summ = "8.96";

// тип товара
// code of goods
    $shp_item = 1;

// предлагаемая валюта платежа
// default payment e-currency
    $in_curr = "";

// язык
// language
    $culture = "ru";

// кодировка
// encoding
    $encoding = "utf-8";

// формирование подписи
// generate signature
    $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ваш кабинет</title>
    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="js/handler.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mainstyle.css" media="all">
</head>
<body>
<div id="header">
    <div class="header_case">
        <div id="center">

            <div id="logotype">

            </div>

            <div id="tools">
                <a id="show_sign_up">Зарегистрироваться</a> |
                <a id="show_sign_in">Войти</a>
                <a id="sign_out">Выйти</a>
            </div>

        </div>
    </div>
</div>
<hr>

    <h2>Ваш кабинет</h2>
    <br>
    <a>Ваш баланс<br></a>
    <a>Положить на счет</a>
    <script language=JavaScript
            src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?MrchLogin=demo&Out
            Sum=8.96&InvId=0&IncCurrLabel=&Desc=ROBOKASSA
            Advanced User Guide&SignatureValue=f7738250525ae5245f71565f040ed4d9
            &Shp_item=1&Culture=ru&Encoding=utf-8'>
    </script>
    <a>Здесь что-то типа списка моих объявлений, внутри которых будет эта кнопка</a>
    <form action='https://merchant.roboxchange.com/Index.aspx' method=POST>
        <input type=hidden name=MrchLogin value=barter_demo>
        <input type=hidden name=OutSum value=8.96>
        <input type=hidden name=InvId value=0>
        <input type=hidden name=Desc value='ROBOKASSA Advanced User Guide'>
        <input type=hidden name=SignatureValue value=a5f8e2bd761a85bea03fbb44ee1280d9>
        <input type=hidden name=Shp_item value='2'>
        <input type=hidden name=IncCurrLabel value=>
        <input type=hidden name=Culture value=ru>
        <input type=submit value='Оплатить первое место в городе'>
    </form>
    <a href="?act=logout">
        <button>Выйти</button>
    </a>


</body>
</html>
