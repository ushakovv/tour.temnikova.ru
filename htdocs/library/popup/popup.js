window.popupCloseAll = function() {
    $('.popup-overlay').click();
    $('.popup-overlay-second').click();
};
window.popup = function (name, params) {
	var $popup = $('[data-name="' + name + '"]');
	var $popupOverlay = $('.popup-overlay');
    var $popupOverlaySecond = $('.popup-overlay-second');
	var $popupClose = $popup.find('.popup__close');
	var $popupBody = $popup.find('.popup__body');
	var popupTempl = $popup.find('script').html();
	var $ponominalu = $('#ponominalu_widget');
	var ponominalu = false;

	params = params || {};

	Mustache.parse(popupTempl);
	var rendered = Mustache.render(popupTempl, params);
	$popupBody.html(rendered);

	var closePopup = function (e) {
		if ($(e.target).closest('.popup__body').length == 0) {
            if (ponominalu) {
                window.location.reload();
            }

			$popupOverlay.off('click');
			$popupClose.off('click');
			$popup.hide();
			$popupOverlay.hide();
			$popupBody.html('');

            $ponominalu.html('');
			
			$('.page').removeClass('page_popup');
		}
	};

    var closeSecondPopup = function (e) {
            e.stopPropagation();

            var $popup = $('.popup_level_t');
            var $popupClose = $popup.find('.popup__close');

            $popupOverlaySecond.off('click');
            $popupClose.off('click');

            $popup.hide();

            $popupOverlaySecond.removeClass('popup-overlay-second_active');
    };

	if (name === 'ponominalu') {
        $popupOverlaySecond.addClass('popup-overlay-second_active');

        $popupClose.on('click', closeSecondPopup);
        $popupOverlaySecond.on('click', closeSecondPopup);
    } else {
        $popupClose.on('click', closePopup);
        $popupOverlay.on('click', closePopup);
    }

    if (name === 'ponominalu-list') {
        ponominalu = true;
    }

    $ponominalu.click(function(e) {
    	e.stopPropagation();
	});

	$popupOverlay.show();
	$popup.show();

	$('.page').addClass('page_popup');

};