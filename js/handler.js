
jQuery(function($){
    $('body').on('click', '#find_btn', function () {
        $("#find_form_div").css("display", "block").hide().fadeIn(500);
        $("#suggest_div").css("display", "none")
    });
    $('body').on('click', '#suggest_btn', function () {
        $("#find_form_div").css("display", "none");
        $("#suggest_div").css("display", "block").hide().fadeIn(500);
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
            'region': $("#region_suggest option:selected").val()
        }
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
