$(document).ready(function() {

  $('.article-slider').slick({
	  slidesToShow: 3,
	  slidesToScroll: 1,
	  dots: true,
	  responsive: [
	  	{
	  		breakpoint: 1024,
		        settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1,
		        infinite: false,
		        dots: true
		      }
	  	}
	  ]
 	});

  $('.section-contest-slider').slick({
	  centerMode: true,
	  slidesToShow: 4,
	  slidesToScroll: 1,
	   responsive: [
	  	{
	  		breakpoint: 1024,
		        settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1,
		        infinite: true,
		        centerMode: false,
		        dots: false
		      }
	  	}
	  ]
 	});
  $('.image-duotone-list img.gradient').duotone();

	$(".make-choice-item").on("swipeleft",function(){
		console.log('aaa');
	});


	$('.make-choice-nav a').on('click', function(e) {
		e.preventDefault();
		var order = $(this).attr('data-order');
		$('.make-choice-nav a').removeClass('active');
		$(this).addClass('active');
		$('.make-choice-items .make-choice-item').removeClass('active').hide();
		$('.make-choice-items .make-choice-item[data-order="' + order + '"]').addClass('active').fadeIn();
    $('.landing-flavors-info').removeClass('active');
		$('.landing-flavors-info[data-order="' + order + '"]').addClass('active');
	});


	$('.sandwich').on('click', function(e) {
		e.preventDefault();
		$(this).toggleClass('open');
		$('.section-header-menu, .section-header-auth').fadeToggle(300);
	});


	$('.modalWindowLink').on('click', function (e) {
	    e.preventDefault();
	    var target = $(this).attr('href');
	    dataImg = $(this).data('image');
	    dataVideo = $(this).data('video');
	    $('.modalWindow').not('.not-really-modalWindow').hide();
	    $('#modalsBg').fadeIn();
	    $(target).fadeIn();
	    //$('body').css('overflow-y', 'hidden');
	    if (dataImg) {
	    	$(target).find('.source').html('<img src='+dataImg+' alt="" />');
	    } else if (dataVideo) {
	    	$(target).find('.source').html('<video controls=""><source src="'+dataVideo+'" type="video/mp4"/></video>');
	    	var player = new MediaElementPlayer('#instagram .source video', {features: ['playpause','progress']});
			player.play();
	    }
	});

	$('.chat-button .chat-button-top, .chat-button .icon').on('click', function(e) {
		$(this).parents('.chat-button').addClass('opened');
		$(this).parents('.chat-button').find('button').fadeIn(200);
		$('.chat-wrapper').fadeIn(400);
		checkChatSize();
	});

	$('.chat-button .modalWindow-close').on('click', function(e) {
		$(this).parents('.chat-button').removeClass('opened');
		$(this).parents('.chat-button').find('button').fadeOut(200);
		$('.chat-wrapper').fadeOut(400);
	});

	$('.scroll-link').click(function(e) {
		e.preventDefault()
		var target = $(this).attr('href');
	    $('html, body').animate({
	        scrollTop: $(target).offset().top
	    }, 500);
	});


	$('.like-counter').on('click', function (e) {
		e.preventDefault();
	});

	$('.modalWindow .modalWindow-close').on('click', function (e) {
	    e.preventDefault();
	    $(this).parent().fadeOut();
	    $('#modalsBg').fadeOut();
	    //$('body').css('overflow-y', 'visible');
	});

	$('.participant-filter a').on('click', function(e) {
		e.preventDefault();
		$(this).toggleClass('up');
	});


	$('.landing-flavors-bottle').on('click', function(e) {
		e.preventDefault();
		var order = $(this).attr('data-order');
		$('.landing-flavors-bottle').removeClass('active');
		$(this).addClass('active');
		$('.landing-flavors-info-tabs .landing-flavors-info').removeClass('active');
		$('.landing-flavors-info-tabs .landing-flavors-info[data-order="' + order + '"]').addClass('active');
	});

	/* video player */
	$('.video-block .btn-play').on('click', function(e) {
		var player = new MediaElementPlayer('#food-video video', {features: ['playpause','progress','volume']});
		player.play();
	});

	$('.modalWindow#food-video').on('click', function(e) {
		var player = new MediaElementPlayer('#food-video video', {features: ['playpause','progress','volume']});
		player.pause();
	});

});


function checkChatSize() {
	var scrollTop     = $(window).scrollTop(),
	element = $('.chat-wrapper');
	if (element.is(":visible")) {
    	var elementOffsetTop = element.offset().top,
    	elementOffsetBottom = element.offset().bottom,
   		distance      = (elementOffsetTop - scrollTop);
   		if (distance < 100) {
   			$('.chat-wrapper .dialog').height($('.chat-wrapper .dialog').height()+distance-100).addClass('scrolling').jScrollPane({autoReinitialise: true, showArrows: true}).bind('mousewheel',function(e){e.preventDefault();});
   		} else {
   			$('.chat-wrapper .dialog').css('height', 'auto').jScrollPane().data('jsp').destroy();
   			$('.chat-wrapper .dialog').jScrollPane({autoReinitialise: true, showArrows: true}).bind('mousewheel',function(e){e.preventDefault();});
   		}
    }

}


$(window).resize( $.throttle( 250,  checkChatSize) );


$(window).scroll(function(){
 scroll = $(window).scrollTop();
  if (scroll >= 1) {
    $('.header').addClass('fixed');
  }
  else  {
     $('.header').removeClass('fixed');
  }
});
