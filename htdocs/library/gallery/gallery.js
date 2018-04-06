function gallery(id) {
	var _album = $('.concert-photo-album'),
        _photo;

    _album.html('');

    $.get('/site/get-concert-photos', {
        'albumId': id
    }, function(response) {
        $.each(response, function (index, val) {
            _photo = $('<a/>', {
                'href': val.normal,
                'data-lightbox': 'was'
            });

            _album.prepend(_photo);
        });

        _album.find('a').first().click();
    }, 'json');
}