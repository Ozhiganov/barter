
jQuery(function($){

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
            $preview = $('<img/>').attr('width', 120);
            ui.readAs('DataURL', function(e) {
                $preview.attr('src', e.target.result);
            });
        }
        $($preview).prependTo($uploadRows); // Preview
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
                'name': $("#name_suggest").val(),
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

    $('body').on('click', '#find_btn', function (e) {
        e.preventDefault();
        $("#find_form_div").css("display", "block").hide().fadeIn(500);
        $("#suggest_div").css("display", "none");
        $('#region_suggest').trigger('change');
        $('#region_find').trigger('change');
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
                    html_string+="<option value="+html[i]+"></option>";
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
        $("#find_form_div").css("display", "none");
        $("#suggest_div").css("display", "block").hide().fadeIn(500);
        $('#region_suggest').trigger('change');
        $('#region_find').trigger('change');
    })
    $('body').on('submit', '#suggest_form', function (e) {
        e.preventDefault();
        if ($.support.fileSending) {
            $fileInput.duStart();
        }
    });
    $('body').on('submit','#find_form', function (e) {
        e.preventDefault();
        var data_request = {
            'find_from': $("#from_topics_of_barter_find option:selected").val(),
            'find_to': $("#to_topics_of_barter_find option:selected").val(),
            'keywords': $("#description_find").val(),
            'region': $("#region_find option:selected").val(),
            'city': $("#city_selected_find").val()
        };
        var search_data = {
            'find_from': $("#from_topics_of_barter_find option:selected").text(),
            'find_to': $("#to_topics_of_barter_find option:selected").text(),
            'region': $("#region_find option:selected").text(),
            'city': $("#city_selected_find").val()
        };
        $.ajax({
            type: 'POST',
            url: 'queries.php',
            dataType: 'json',
            data: "find="+JSON.stringify(data_request),
            success: function(html) {
                alert("lsalfa");
                $("#suggest_area").css("display","none");
                var search_result = "";
                for (var i in html) {
                    var current = html[i];
                    search_result += "<div>" +
                    "<div align='center'>" + current['title'] + "</div>" +
                    "From:"+search_data['find_from']+"<br>" +
                    "To:"+search_data['find_to']+"<br>" +
                    "Region:"+search_data['region']+"<br>" +
                    "Description:"+current['description']+"<br>" +
                    "Contacts:"+current['contacts']+"<br>" +
                    "Name:"+current['name']+"<br></div>";
                }
                $("#search_area").html(search_result);
                $("#close_find").css("display","block");
            }
        });
    });
    $('body').on('click', '#close_find', function(e) {
        e.preventDefault();
        $("#suggest_area").css("display","block");
        $("#search_area").empty();
        $("#close_find").css("display","none");
    });
    $('body').on('click', '#advertisment-open', function(e){
        e.preventDefault();
        $('#advertisment').modal().open();
    });

    // attach modal close handler
    $('.modal .close').on('click', function(e){
        e.preventDefault();
        $.modal().close();
    });
});
