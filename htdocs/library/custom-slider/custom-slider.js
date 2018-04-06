/**
 * Created by Павел on 14.08.2017.
 */
function customSlider() {
    var _wrapper = $('.custom-slider__wrapper');

    var _items,
        _cnt,
        _rowCnt = 5;

    var _moveToPrev = function() {
        var __moveItem = _items.last();
        var __clone = __moveItem.clone();
        _wrapper.prepend(__clone);
        __moveItem.remove();
    };

    var _moveToNext = function() {
        var __moveItem = _items.first();
        var __clone = __moveItem.clone();
        _wrapper.append(__clone);
        //__moveItem.css('margin-left', '-' + __moveItem.outerWidth() + 'px');
        __moveItem.remove();
        //_removeItem(__moveItem);
    };

    /*var _removeItem = function(item) {
        setTimeout(function() {
            item.remove();
        }, 1000);
    };*/

    var _checkActiveItems = function() {
        _items = $('.events__city');
        _cnt = 0;

        _items.each(function(item) {
            if(!$(item).hasClass('events_hide')) {
                ++_cnt;
            }
        });

        if (_cnt > _rowCnt) {
            return true;
        }

        return false;
    };

    $('.custom-slider__arrow').on('click', function() {
        if (_checkActiveItems()) {
            var __deriction = $(this).data('scroll-to');

            if (__deriction === 'prev') {
                _moveToPrev();
            } else {
                _moveToNext();
            }
        }
    });
}