/**
 * Created by Павел on 01.08.2017.
 */
new function() {
    'use strict';

    var _self = this;

    this.timeout = [];
    this.isShow = false;
    this.onPoint = false;

    this.info = function(top, left, id) {
        showDetails(top, left, id);
    };

    this.closeInfo = function() {
        $('.tour-map').find('.tour-map__details').remove();
    };

    this.detail = function(id) {
        popup('details', data[id]);
    };

    var showDetails = function (top, left, id) {
        var $self = $('.tour-map');
        var dataTour = data[id].point;

        var $details = $('<div/>');
        $details.addClass('tour-map__details');

        var $image = $('<img/>');
        $image.attr('src', dataTour.image);
        $image.addClass('tour-map__details-image');

        $details.append($image);

        var $city = $('<div/>');
        $city.addClass('tour-map__details-city');
        $city.text(dataTour.name);

        $details.append($city);

        var $date = $('<div/>');
        $date.addClass('tour-map__details-date');
        $date.text(dataTour.date);

        $details.append($date);

        var $address = $('<div/>');
        $address.addClass('tour-map__details-address');
        $address.text(dataTour.place);

        $details.append($address);

        var $more = $('<div/>');
        $more.addClass('tour-map__details-more');
        $more.text('Подробнее');

        if (dataTour.was && dataTour.album_id) {
            $more.attr('onclick', 'gallery(' + dataTour.album_id + ')');
        } else {
            $more.attr('onclick', 'popups.detail(' + id + ')');
        }

        $details.append($more);
        $details.css({
            top: top - 1 + 'px',
            left: left + 50 + 'px'
        });

        $self.find('.tour-map__details').remove();
        $self.append($details);

        var posRight = $details.offset().left + $details.width();
        var posBottom = $details.offset().top + $details.height();

        if (posRight > $details.parent().width()) {
            $details.addClass('tour-map__details_left');

            $details.css({
                left: left - 450  + 'px'
            });
        }

        if (posBottom > $details.parent().height()) {
            $details.addClass('tour-map__details_bottom');

            $details.css({
                top: top - 48 - $details.height() + 'px',
            });
        }

        $('.tour-map__details').on('mouseleave', function () {
            _self.timeout.push(setTimeout(function () {
                _self.closeInfo();
            }, 1500));
        });

        $('.tour-map__details').on('mousemove', function () {
            if (_self.isShow) {
                _self.timeout.forEach(function(val) {
                    clearTimeout(val);
                });
            }
        });
    };

    this.parseDate = function(date) {

        if(!date) {
            return '';
        }

        var result = '',
            aMonth = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

        date += ' GMT';

        var d = Date.parse(date);

        result += d.getDate()
            + ' ' + aMonth[d.getMonth()]
            + ' ' + d.getFullYear()
            + ' в ' + d.getUTCHours()
            + ':' + ((d.getUTCSeconds() == 0) ? '00' : d.getUTCSeconds());

        return result;
    };

    window.popups = this;
};