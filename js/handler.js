
jQuery(function($){
    $('body').on('click', '#find_btn', function () {
        $("#find_form_div").css("display", "block").hide().fadeIn(500);
        $("#suggest_div").css("display", "none");
        $('#region_suggest').trigger('change');
        $('#region_find').trigger('change');
    });
    $('body').on('change','#region_suggest',function(){
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
    $('body').on('change','#region_find',function(){
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
    $('body').on('click', '#suggest_btn', function () {
        $("#find_form_div").css("display", "none");
        $("#suggest_div").css("display", "block").hide().fadeIn(500);
        $('#region_suggest').trigger('change');
        $('#region_find').trigger('change');
    })
    $('body').on('submit', '#suggest_form', function () {
        var data = {
            'suggest_from': $("#from_topics_of_barter_suggest option:selected").val(),
            'suggest_to': $("#to_topics_of_barter_suggest option:selected").val(),
            'title': $("#title_suggest").val(),
            'description': $("#description_suggest").val(),
            'contacts': $("#contacts_suggest").val(),
            'name': $("#name_suggest").val(),
            'price': $("#price_suggest").val(),
            'region': $("#region_suggest option:selected").val(),
            'city': $("#city_selected_suggest").val()
        };
        $.ajax({
            type: 'POST',
            url: 'queries.php',
            dataType: 'json',
            data: "suggest="+JSON.stringify(data),
            success: function(html) {
                alert(html['res']);
                //TODO: do smth in background
            }
        });

    });
    $('body').on('submit','#find_form', function () {
        var data = {
            'find_from': $("#from_topics_of_barter_find option:selected").val(),
            'find_to': $("#to_topics_of_barter_find option:selected").val(),
            'keywords': $("#description_find").val(),
            'region': $("#region_find option:selected").val(),
            'city': $("#city_selected_find").val()
        };
        $.ajax({
            type: 'POST',
            url: 'queries.php',
            dataType: 'json',
            data: "find="+JSON.stringify(data),
            success: function(html) {
                for (var i in html)
                 alert(html[i]['name']);
            }
        });
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
