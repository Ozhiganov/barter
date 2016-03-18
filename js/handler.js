jQuery(function($){

    function sign_in() {
        $("#tools").html("<a id='cabinet'>Личный кабинет</a> | <a id='sign_out'>Выйти</a>");
    }

    function sign_out() {
        $("#tools").html("<a id='show_sign_up'>Зарегистрироваться</a> | <a id='show_sign_in'>Войти</a>");
    }

    var media = "";
    var input_file = $('#file_input');
    input_file.damnUploader({
        url: 'queries.php',
        fieldName:  'my-file',
        limit: 5,
        dataType: 'json',
        acceptType: 'image/*'
    });



    var isImgFile = function(file) {
        return file.type.match(/image.*/);
    };

    var createRowFromUploadItem = function(ui) {
        var $preview;
        if (isImgFile(ui.file)) {
            $preview = $('<img/>').attr('height', 120);
            ui.readAs('DataURL', function(e) {
                $preview.attr('src', e.target.result);
            });
        }
        $($preview).prependTo($('#upload_pic')); // Preview
    };

    // File adding handler
    var fileAddHandler = function(e) {
        var ui  = e.uploadItem;
        var filename = ui.file.name || "";
        if(ui.file.size >= 4194304) {
            alert("File is too much");
            e.preventDefault();
            return ;
        }

        if (!isImgFile(ui.file)) {
            alert("This file is not a picture");
            e.preventDefault();
            return ;
        }

        if (!filename.length) {
            ui.replaceName = "custom-data";
        } else if (filename.length > 14) {
            ui.replaceName = filename.substr(0, 10) + "_" + filename.substr(filename.lastIndexOf('.'));
        }

        createRowFromUploadItem(ui);
        ui.completeCallback = function(success, data, errorCode) {
            media += "," + data['status'];
        };
    };

    // Uploader events
    input_file.on({
        'du.add' : fileAddHandler,

        'du.limit' : function() {
            log("File upload limit exceeded!");
        },

        'du.completed' : function() {
            alert("lol");
            var data = {
                'suggest_from': $("#from_topics_of_barter_suggest option:selected").val(),
                'suggest_to': $("#to_topics_of_barter_suggest option:selected").val(),
                'title': $("#title_suggest").val(),
                'description': $("#description_suggest").val(),
                'contacts': $("#contacts_suggest").val(),
                'price': $("#price_suggest").val(),
                'region': $("#region_suggest option:selected").val(),
                'city': $("#city_selected_suggest").val(),
                'media': media
            };
            $.ajax({
                type: 'POST',
                url: 'queries.php',
                dataType: 'json',
                data: "suggest="+JSON.stringify(data),
                success: function(html) {
                    $("#suggest_form").trigger('reset');
                    $('#clear_btn').trigger('click');
                    $("#suggest_div").css('display', 'none')
                    $("#message_container")
                        .css('display', 'block')
                        .animate({opacity: 1, top: '0%'}, 200);
                    $("#message").html("Ваше объявление успешно размещено");
                    //TODO: callback
                }
            });

        }
    });

    // Clear button
    $('#clear_btn').on('click', function() {
        input_file.duCancelAll();
        $('#upload_pic').empty();
    });
    $(document).ready(function(){
        $('#region_suggest').trigger('change');
        $.ajax({
            type: 'POST',
            url: 'identification.php',
            dataType: 'json',
            data: "check_status= ",
            success: function(html) {
                if(html['res'] == 1 || html['res'] == 2) {
                    sign_in();
                }
                else
                    sign_out();
            }
        });
    });
    $('body').on('click','.modal_close, #overlay', function() {
        $('.modal_div').animate({opacity: 0}, 200, function(){
            $(this).css('display', 'none');
            $('#overlay').fadeOut(400);
        })
    });
    $('body').on('click','#cabinet', function(){
        window.location = 'cabinet.php';
    });
    $('body').on('click', '#find_btn', function (e) {
        e.preventDefault();
        $("#find_form_div").css("display", "block").hide().fadeIn(500);
    });
    $('body').on('change','#region_suggest',function(e){
        e.preventDefault();
        var data = {
            'region': $("#region_suggest option:selected").val()
        };
        $.ajax({
            type: 'POST',
            url: 'queries.php',
            dataType: 'json',
            data: "region="+JSON.stringify(data),
            success: function(html) {
                var html_string = "";
                for (var i in html) {
                    html_string+="<option  value=" + html[i] + "></option>";
                }
                $("#city_suggest").html(html_string);
            }
        });

    });
    $('body').on('change','#region_find',function(e){
        e.preventDefault();
        var region_id = $("#region_find option:selected").val();
        if(region_id == 0)
            $("#city_selected_find").css("display","none");
        else {
            $("#city_selected_find").css("display","block");
            var data = {
                'region': region_id
            };
            $.ajax({
                type: 'POST',
                url: 'queries.php',
                dataType: 'json',
                data: "region=" + JSON.stringify(data),
                success: function (html) {
                    var html_string = "";
                    for (var i in html) {
                        html_string += "<option value=" + html[i] + "></option>";
                    }
                    $("#city_find").html(html_string);
                }
            });
        }

    });
    $('body').on('click', '#suggest_btn', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'identification.php',
            dataType: 'json',
            data: "check_status= ",
            success: function(html) {
                if(html['res'] == 2) {
                    $("#find_form_div").css("display", "none").hide().fadeOut(500);
                    $("#overlay").fadeIn(400, function(){
                        $("#suggest_div")
                            .css('display', 'block')
                            .animate({opacity: 1, top: '0%'}, 200);
                    });
                }
                else if(html['res'] == 1) {
                    $("#overlay").fadeIn(400, function(){
                        $("#message_container")
                            .css('display', 'block')
                            .animate({opacity: 1, top: '30%'}, 200);
                    });
                    $("#message").html("Вы должны активировать свой аккаунт, чтобы размещать объявления");
                }
                else {
                    $("#overlay").fadeIn(400, function(){
                        $("#message_container")
                            .css('display', 'block')
                            .animate({opacity: 1, top: '30%'}, 200);
                    });
                    $("#message").html("Вы должны зарегистрироваться, чтобы размещать объявления");
                }

            }
        });

    })
    $('body').on('submit', '#suggest_form', function (e) {
        e.preventDefault();
        if ($.support.fileSending) {
            input_file.duStart();
        }
    });
    $('body').on('click','#logotype',function() {
        window.location = 'index.php';
    });
    $('body').on('submit','#sign_up_form', function (e){
        e.preventDefault();
        var sign_up_data = {
            'username':$("#username ").val(),
            'login':$("#reg_login ").val(),
            'email':$("#email ").val(),
            'password':$("#reg_password ").val(),
            'password_check':$("#password_check ").val()
        };
        $.ajax({
            type: 'POST',
            url: 'identification.php',
            dataType: 'json',
            data: "submit_sign_up="+JSON.stringify(sign_up_data),
            success: function(sup) {
                $('input').css('border','');
                $('.reg_block').css('display','none');
                switch(sup['res']){
                    case 'mail_suc':
                        $("#sign_up_form").trigger('reset');
                        $("#hidden_sign_up_form").css('display', 'none')
                        $("#message_container")
                            .css('display', 'block')
                            .animate({opacity: 1, top: '30%'}, 200);
                        $("#message").html("Для завершения регистрации проверьте почту, которую вы указали при регистрации и проследуйте инструкциям");
                        break;
                    case 'unknown':
                        //Неизвестная ошибка
                        break;
                    case 'email_error':
                        $('#email').css("border", "1px solid red");
                        $('#email_block').css("display","inline");
                        break;
                     case 'login_error':
                        $('#reg_login').css("border", "1px solid red");
                        $('#login_block').css("display","inline");
                        break;
                      case 'password_error':
                        $('#reg_password, #password_check').css("border", "1px solid red");
                        $('#password_check_block').css("display","inline");
                        break;
                 }
            }
        });
    });
    $('body').on('submit','#sign_in_form', function(e){
        e.preventDefault();
        var sign_in_data ={
            'login': $("#login").val(),
            'password': $("#password").val()
        }

        $.ajax({
            type: 'POST',
            url: 'identification.php',
            dataType: 'json',
            data: "submit_sign_in="+JSON.stringify(sign_in_data),
            success: function(sup) {
                if(sup['res'] == 1) {
                    $('input').css('border','');
                    $('.reg_block').css('display','none');
                    $(".modal_close").trigger('click');
                    sign_in();
                }
                else {
                    $('#login, #password').css("border", "1px solid red");
                    $('#auth_block').css("display","inline");
                }
                //TODO: callback
            }
        });
    });
    $('body').on('click',"#sign_out", function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'identification.php',
            dataType: 'json',
            data: "sign_out= ",
            success: function(sup) {
                if(sup['res'] == 1){
                    sign_out();
                }
                //TODO: callback
            }
        });

    });
    $('body').on('click', '#show_sign_in', function() {
        $("#overlay").fadeIn(320, function(){
            $("#hidden_sign_in_form")
                .css('display', 'block')
                .animate({opacity: 1}, 160);
        });
    });
    $('body').on('click', '#show_sign_up', function(){
        $("#overlay").fadeIn(320, function(){
            $("#hidden_sign_up_form")
                .css('display', 'block')
                .animate({opacity: 1}, 160);
        });
    });
});
