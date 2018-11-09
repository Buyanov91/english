(function () {
    'use strict';

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

$('.word-link').bind('click', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function () {
        $('.close').click();
    });
});

$('#learn').click(function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.getJSON(url, function (data) {
        $('#learn-word').html(data.infinitive);
        $('#translate-word').html(data.translate);
        $('#known').attr('href', 'learning/know?status=1&infinitive_id='+data.id);
        $('#unknown').attr('href', 'learning/know?status=0&infinitive_id='+data.id);
        $('.main-study').fadeOut(0);
        $('.hide-study').fadeIn();
        $('.hide-end-button').fadeIn();
    });
});

$('#translate').click(function () {
    $('.hide-study').fadeOut(0);
    $('.hide-translate').fadeIn();
});

$('#known, #unknown').click(function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.getJSON(url, function (data) {
        $('#learn-word').html(data.infinitive);
        $('#translate-word').html(data.translate);
        $('#known').attr('href', 'learning/know?status=1&infinitive_id='+data.id);
        $('#unknown').attr('href', 'learning/know?status=0&infinitive_id='+data.id);
        $('.hide-translate').fadeOut(0);
        $('.hide-study').fadeIn();
    });
});

}());


