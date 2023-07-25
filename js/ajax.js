function openBookInfo(id) {

    $.post('bookHandling.php', {
        id: id
    }, function (book){
        console.log(book);
        $('#title').text(book.title);
        $('#author').text(book.author);
        $('#description').text(book.description);
        $('.cover').html(`<img src="data:image/jpeg;base64,${book.cover}" alt="Image" class="img-fluid">`);

    });

    $('.overlay').fadeIn();
    $(window).on('resize', function () {
        var viewportWidth = $(window).width();

        if (viewportWidth >= 992) {
            $('.pc-view-card').show();
        } else {
            $('.mobile-view-card').show();
        }
    }).trigger('resize');
}

function closeBookInfo(){
    $('.overlay').fadeOut();
    $('.bookInfo').hide();

}
  