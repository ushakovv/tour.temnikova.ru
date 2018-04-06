function dropMenu (params) {
	var self = this;
	$(self).on('click', function() {
		var close = function() {
			$(self).removeClass(params.data.classActive);
			$('[data-id="' + params.data.menuId + '"]').hide();
			$(window).off('resize', close);
			$(document).off('click', closeOnOverlay);
		}

		var open = function() {
			$(self).addClass(params.data.classActive);
			$(window).one('resize', close);
			$('[data-id="' + params.data.menuId + '"]').css({
				top: $(self).position().top + $(self).height() + 10,
				left: $(self).position().left + $(self).width()/2
			});
			$('[data-id="' + params.data.menuId + '"]').show();
			$(document).on('click', closeOnOverlay);
		}

		var closeOnOverlay = function (e) {
			if ($(e.target).closest('[data-id="' + params.data.menuId + '"], [data-menu-id="' + params.data.menuId + '"]').length == 0) {
				close();
			}
		}

		if ($('[data-id="' + params.data.menuId + '"]').is(":visible")) {
			close()
		} else {
			open();
		}
	});
}