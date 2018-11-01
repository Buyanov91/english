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