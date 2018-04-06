/**
 * Created by Павел on 11.08.2017.
 */
function filterForm() {
    var $self = $(this);

    var     filter = function() {
        var city = '',
            month = '',
            _cityVal,
            _monthVal,
            concertPull = [];

        city = $self.find('input[name="city"]').val();
        month = $self.find('input[name="month"]').val();

        $('.events__city_h').each(function(index, val) {
            var val = $(val);
            var data = val.data();

            if (city === 'all' || city === data.eventsCity) {
                _cityVal = true;
            } else {
                _cityVal = false;
            }

            if (month === 'all' || month === data.eventsMonth) {
                _monthVal = true;
            } else {
                _monthVal = false;
            }

            if (_cityVal && _monthVal) {
                concertPull.push(val.clone());
            }
        });

        $('.custom-slider__wrapper').html(concertPull);
    };

    var closeOpenLists = function() {
        $('.select').removeClass('scroll-pane__active');
    };

    $('.select__type').click(function(e) {
        e.stopPropagation();

        var _has,
            $self = $(this);

        if (!$self.next().hasClass('scroll-pane__active')) {
            _has = true;
        }

        closeOpenLists();

        if (_has) {
            $self.next().toggleClass('scroll-pane__active');
        }
    });

    $('.scroll-pane__item').click(function() {
        var $self = $(this);

        $self.parents('.scroll-pane__wrapper').find('.select__type').html($self.text());
        $self.parents('.scroll-pane__wrapper').find('input').val($self.data('value'));

        filter();
    });

    $('body').click(function() {
        closeOpenLists();
    });
}