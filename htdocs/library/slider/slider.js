function slider(params) {
	var self = this;
	var settings = params.data;
	var slidesToShowMiddle;

	if (settings.centerModeMobile) {
		settings.centerModeMobile = settings.centerModeMobile === 'true'
	}

	if (settings.centerMode) {
		settings.centerMode = settings.centerMode === 'true'
	} else {
		slidesToShowMiddle = Math.round(
			(Number(settings.slidesToShowMobile)
			+ Number(settings.slidesToShow))/2
		);
	}

	var initSlider = function () {
		if (!$(self).hasClass('slick-slider')) {

			$(self).slick({
				slidesToShow: Number(settings.slidesToShow),
				arrows: true,
				dots: false,
				centerMode: settings.centerMode,
				centerPadding: '120px',
				initialSlide: settings.initialSlide,
				responsive: [
					{
						breakpoint: 1000,
						settings: {
							slidesToShow: slidesToShowMiddle
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: settings.slidesToShowMobile,
							centerMode: settings.centerModeMobile,
							dots: false
						}
					}
				]
			});
		}
	}

	var stopSlider = function () {
		if ($(self).hasClass('slick-slider')) {
			$(self).slick('unslick');
		}
	}

	if (settings.mobileOff === 'true') {
		if ($(window).width() > 768) {
			initSlider();
		}

		$(window).on('resize', function () {
			if ($(window).width() > 768) {
				initSlider();
			} else {
				stopSlider();
			}
		});
	} else {
		initSlider();
	}
}
