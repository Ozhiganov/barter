<?php
if(isset($_GET['id'])) {
$advertisement_db = new mysqli("barter", "root", "", "barter_main");
if ($advertisement_db->connect_errno) {
    exit();
}
$advertisement_req = $advertisement_db->query("SELECT * FROM `advertisements` WHERE `id`='$_GET[id]' LIMIT  0,1");
$advertisement_info = $advertisement_req->fetch_all(MYSQLI_ASSOC);

$media_str = $advertisement_info[0][media];
$media = preg_split("/[,]+/",$media_str);

?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="js/jquery.damnUploader.js"></script>
    <script type="text/javascript" src="js/handler.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mainstyle.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/modal.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/gallery.css" media="all">
</head>
<body>
<div id="header">
    <div class="header_case">
        <div id="center">

            <div id="logotype">

            </div>

            <div id="tools">
            </div>

        </div>
    </div>
</div>
<hr>
<div id="overlay"></div>
<div class="slider" style="display: none">
    <ul>
        <?php
        $i = 0;
        foreach($media as $value) {
            echo "<li id='$i'><img src=' ../" . $value . "''/></li>";
            $i++;
        }
        unset($value);
        ?>
    </ul>
</div>
<div class="body_container">
    <div class="main_case">


        <?php
    $suggest_id = $advertisement_info[0][suggest_from];
    $suggest_from_req = $advertisement_db->query("SELECT `name` FROM `topics` WHERE `id`='$suggest_id' LIMIT  0,1");
    $suggest_from = $suggest_from_req->fetch_all(MYSQLI_ASSOC);

    $suggest_id = $advertisement_info[0][suggest_to];
    $region_id = $advertisement_info[0][region];
    $city_id = $advertisement_info[0][city];
    $bd_req = $advertisement_db->query("SELECT topics.name AS topic, cities.name AS city, regions.name AS region
      FROM topics, cities, regions WHERE topics.id ='$suggest_id' AND cities.id ='$city_id' AND regions.id = '$region_id' LIMIT 0,1");
    $bd = $bd_req->fetch_all(MYSQLI_ASSOC);
    //Текстовая информация
    ?>

        <h2><? echo $advertisement_info[0][title]?></h2>
        <p>
    <?

    echo "<br>Меняю: ".$suggest_from[0][name];



    echo "<br>На: ".$bd[0][topic];

    setlocale(LC_ALL, "Russian");
    echo "<br>Дата публикации: ".strftime("%d.%m.%Y %H:%M",$advertisement_info[0][publish_date]);

    echo "<br>Регион: ".$bd[0][region];

    echo "<br>Город: ".$bd[0][city];

    echo "<br>Имя: ".$advertisement_info[0][name];

    echo "<br>Контактные данные: ".$advertisement_info[0][contacts];

    echo "<br>Описание: ".$advertisement_info[0][description];
    ?>
        </p>

        <!--Изображения-->
        <div class="pictures_box">
    <?



    $i=0;
    foreach($media as $value) {
        echo "<img id='$i' class='preview'  src=' ../" . $value . "''/><br>";
        $i++;
    }
    unset($value);
}
?>      </div>

        </div>

<!--ФОРМА АВТОРИЗАЦИИ В ОКНЕ-->
<div id="hidden_sign_in_form" class="modal_div" style="">
    <span class="modal_close">x</span>
    <h2>Вход</h2>
    <form id="sign_in_form" method="post">
        <p>
            введите данные, указанные при регистрации в поля, расположенные ниже
        </p>
        <label for="login">Логин</label><br>
        <input type="text" name="login"  id='login' autocomplete="off" required>
        <br><br>
        <label for="password">Пароль</label><br>
        <input type="password" name="password" id='password' autocomplete="off" required>
        <br><br>
        <br>
        <input type="submit" class="agree_button_mini" style=""  name="hidden_sign_in_form" value="Авторизоваться">
    </form>
</div>


    <script>
        (function() {

            $('.preview').on('click', function(){
                $('.slider').fadeIn(400);
                $('#'+this.id).addClass("active");
                var pos = "-"+(this.id * 515)+"px";
                $("ul").css("left", pos);
            });
            $('body').keyup(function(eventObject){
                if(eventObject.which == 27 && $('.slider').css('display') === 'block')
                    $('.slider').fadeOut(400);
            });
            $("li").on("click", function(){
                var item = $(this),
                    pos = "-"+(item.index() * 515)+"px";
                item.addClass("active");
                item.siblings().removeClass("active");

                $("ul").css("left", pos);

            });

        })();
    </script>

<!--ФОРМА РЕГИСТРАЦИИ В ОКНЕ-->
<div id="hidden_sign_up_form"  class="modal_div" style="top: 15%; width:270px; ">
    <span class="modal_close" style="right: 20px;">x</span>
    <h2>Регистрация</h2>
    <form id="sign_up_form">
        <label for="username">Имя</label><br>
        <input type="text" name="username" id="username" placeholder="как к вам обращаться? :)" autocomplete="off" required>
        <br><br>
        <label for="login">Логин</label><br>
        <input type="text" name="login"  id='reg_login' autocomplete="off" required>
        <br><br>
        <label for="email">Ваш email | электронная почта</label><br>
        <input type="email" name="email"  id='email' autocomplete="off" placeholder="" required>
        <br><br>
        <label for="password">Пароль</label><br>
        <input type="password" name="password" id='reg_password' autocomplete="off" required>
        <br><br>
        <label for="password_check">И ещё раз пароль</label><br>
        <input type="password" name="password_check" id="password_check" autocomplete="off" required>
        <br><br>
        <br>
        <input type="submit" class="agree_button_mini" name="submit_sign_up" value="Зарегистрироваться">
        <span class='msg'><?php echo $message; ?></span>
    </form>
</div>
    </div>
</div>
</body>