function checkAnswer(id, answer_id) {
    if (id === answer_id) {
        $('.hide-study').fadeOut(0);
        $('.hide-translate-true').fadeIn().delay(500).fadeOut();
        var url = "href='/learning/answer?infinitive_id=" + id;
        $.getJSON(url, function (data) {
            if(!data)
                location.reload();
            $('#learn-word').html(data.infinitive);
            $.each(data.mistakes, function (key, value) {
                var div = document.createElement('div');
                div.innerHTML = "<div class='col-md-2'>" +
                    "<a id='translate' class='btn btn-info' " +
                    "href='#' onclick='checkAnswer(" + data.id + "," + value.id + ")'> " +
                    value.translate + "</a>" +
                    "</div>";
                $('#translates').append(div);
            });
        });
    } else {

        $('.hide-study').fadeOut(0).delay(500).fadeIn();
        $('.hide-translate-false').fadeIn().delay(200).fadeOut(0);

    }

}

(function () {
    'use strict';

/**
 * Main page animation
 */

$('#main-btn').click(function () {
    $('#download').fadeOut(0);
    $('#show-btn').fadeIn();
});

$('#text').click(function () {
    $('#show-btn').fadeOut(0);
    $('#upload-text-form').fadeIn();
});

$('#file').click(function () {
    $('#show-btn').fadeOut(0);
    $('#upload-file-form').fadeIn();
});

$('#load-btn').click(function () {
    $('#load-btn').html('<i class="fas fa-circle-notch fa-spin"></i>');
});

$('#load-file-btn').click(function () {
    $('#load-file-btn').html('<i class="fas fa-circle-notch fa-spin"></i>');
});

$('#back').click(function () {
    $('#upload-file-form').fadeOut(0);
    $('#upload-text-form').fadeOut(0);
    $('#show-btn').fadeIn();
});

$('#back-file').click(function () {
    $('#upload-file-form').fadeOut(0);
    $('#upload-text-form').fadeOut(0);
    $('#show-btn').fadeIn();
});

/**
 * Translate ajax
 */

$('.word-link').bind('click', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function () {
        var id = url.substring(url.lastIndexOf('=') + 1);
        $('.close').click();
        $('#'+id).hide(100);
    });
});

$('#learn').click(function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.getJSON(url, function (data) {
        $('#learn-word').html(data.infinitive);
        $.each(data.mistakes, function (key, value) {
            var div = document.createElement('div');
            div.innerHTML = "<div class='col-md-2'>" +
                                "<a id='translate' class='btn btn-info' " +
                                "href='#' onclick='checkAnswer(" + data.id + "," + value.id + ")'> " +
                                value.translate + "</a>" +
                            "</div>";
           $('#translates').append(div);
        });
        $('.main-study').fadeOut(0);
        $('.hide-study').fadeIn();
        $('.hide-end-button').fadeIn();
    });
});

}());


