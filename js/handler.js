jQuery(function($){

    function sign_in() {
        $("#sign_out").css("display","inline-block");
        $("#show_sign_up").css("display","none");
        $("#show_sign_in").css("display","none");
    }

    function sign_out() {
        $("#sign_out").css("display","none");
        $("#show_sign_up").css("display","inline-block");
        $("#show_sign_in").css("display","inline-block");
    }

    //FOR damnUploader
    var $fileInput = $('#file_input');
    var $uploadForm = $('#suggest_form');
    var $uploadRows = $('#upload_pic');
    var $clearBtn = $('#clear_btn');
    var media = "";

    $fileInput.damnUploader({
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
        // e.uploadItem represents uploader task as special object,
        // that allows us to define complete & progress callbacks as well as some another parameters
        // for every single upload
        var ui  = e.uploadItem;
        var filename = ui.file.name || ""; // Filename property may be absent when adding custom data

        if (!isImgFile(ui.file)) {
            alert("This file is not a picture");
            e.preventDefault();
            return ;
        }

        // We can replace original filename if needed
        if (!filename.length) {
            ui.replaceName = "custom-data";
        } else if (filename.length > 14) {
            ui.replaceName = filename.substr(0, 10) + "_" + filename.substr(filename.lastIndexOf('.'));
        }

        // We can add some data to POST in upload request
        //ui.addPostData($uploadForm.serializeArray()); // from array
        //ui.addPostData('original-filename', filename); // .. or as field/value pair
        // Show info and response when upload completed
        createRowFromUploadItem(ui);
        ui.completeCallback = function(success, data, errorCode) {
            media += "," + data['status'];
        };

        // Updating progress bar value in progress callback
//                ui.progressCallback = function(percent) {
//                    $progressBar.css('width', Math.round(percent) + '%');
//                };

    };


    ///// Setting up events handlers

    // Uploader events
    $fileInput.on({
        'du.add' : fileAddHandler,

        'du.limit' : function() {
            log("File upload limit exceeded!");
        },

        'du.completed' : function() {
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
                    $clearBtn.trigger('click');
                    $(".modal_close").trigger('click');

                    alert(html['res']);
                    //TODO: callback
                }
            });

        }
    });

    // Clear button
    $clearBtn.on('click', function() {
        $fileInput.duCancelAll();
        $uploadRows.empty();
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
                    $("#close_find").trigger("click");
                    $("#overlay").fadeIn(400, function(){
                        $("#suggest_div")
                            .css('display', 'block')
                            .animate({opacity: 1, top: '6%'}, 200);
                    });
                }
                else if(html['res'] == 1)
                    alert("Вы должны активировать свой аккаунт");
                else
                    alert("Вы должны зарегистрироваться, чтобы размещать объявления");

            }
        });

    })
    $('body').on('submit', '#suggest_form', function (e) {
        e.preventDefault();
        if ($.support.fileSending) {
            $fileInput.duStart();
        }
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
                alert(sup['res']);
                $("#sign_up_form").trigger('reset');
                //$(".modal_close").trigger('click');
                //TODO: callback
                switch(sup['res']){
                    case 'mail_suc':
                        //Успершное мыло, проверьте почту
                        // $('#mail_suc').style.display='none';
                        break;
                    case 'unknown':
                        //Неизвестная ошибка
                        //$('#unknown').style.display='none';
                        break;
                    case 'email_error':
                        //Емейл занят
                        $('#email_block').css("display","inline");
                        break;
                     case 'login_error':
                        //ошибка логина
                        $('#login_block').css("display","inline");
                        break;
                      case 'password_error':
                        //пароли не совпадают
                        $('#password_check_block').css("display","inline");
                        break;
                 }
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
                $(".modal_close").trigger('click');
                if(sup['res'] == 1) {
                    sign_in();
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
        $("#email_block").hide();
        $("#login_block").hide();
        $("#password_check_block").hide();
        $("#unknown").hide();
        $("#mail_suc").hide();
    });
});
