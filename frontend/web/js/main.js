
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
    $('#load-btn').html('<i class="fas fa-circle-notch fa-spin"></i>').addClass('disabled');
});

$('#load-file-btn').click(function () {
    $('#load-file-btn').html('<i class="fas fa-circle-notch fa-spin"></i>').addClass('disabled');
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
            var div = "<div><a id='" + value.id + "' class='btn btn-info' " +
                        "href='javascript:' onclick='checkAnswer(" + data.id + "," + value.id + ")'> " +
                        value.translate + "</a></div>";
           $('#translates').append(div);
        });
        $('.main-study').fadeOut(0);
        $('.hide-study').fadeIn();
        $('.hide-end-button').fadeIn();
    });
});

}());

function checkAnswer(id, answer_id) {
    if (id === answer_id) {
        $('#' + answer_id).removeClass('btn-info').addClass('btn-success disabled');
        var url = "/learning/answer?infinitive_id=" + id + "&answer_id=" + answer_id;
        $.getJSON(url, function (data) {
            if( !data.infinitive ) {
                location.reload();
            }
            $('#translates').children().remove();
            $('#learn-word').html(data.infinitive);
            $.each(data.mistakes, function (key, value) {
                var div =   "<div><a id='" + value.id + "' class='btn btn-info' " +
                                "href='javascript:' onclick='checkAnswer(" + data.id + "," + value.id + ")'> " +
                                value.translate + "</a></div>";
                $('#translates').append(div);
            });
        });
    } else {
        $('#' + answer_id).removeClass('btn-info').addClass('btn-danger disabled');
    }

}


