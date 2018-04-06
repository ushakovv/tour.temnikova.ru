/**
 * Created by Павел on 28.07.2017.
 */
(function() {
   'use strict';

    var canvas = document.getElementById("canvas");
    var ctx    = canvas.getContext("2d");

    var width  = canvas.offsetWidth;
    var height = canvas.offsetHeight;

    var mapImg = 'data:image/png;base64,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';

    var wasImgUrl = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABUCAMAAAAWJ4GVAAABMlBMVEX///8AAADZ2dn////////////////////////////////////////////////////////////////////////19fX///////////////////+pqamysrKpqam1tbWwsLDq6urh4eHT09PNzc319fXy8vLo6Oj////c3NzW1tbNzc25ubmgoKDGxsbw8PDT09Ps7Ozm5ubh4eHNzc2oqKi1tbXIyMjCwsK9vb3V1dWsrKyzs7Pz8/PR0dHw8PDNzc3f39/AwMDBwcHc3NzOzs7FxcXc3Nz4+PjV1dXp6en39/fb29vr6+vIyMjT09PR0dG0tLTOzs65ubmvr6/MzMzExMSoqKjCwsLZ2dm/v7+9vb2srKzg4ODW1tahoaHp6enj4+Pc3NylpaXt7e3n5+fl5eV4HKRhAAAATXRSTlM9AM84Ah0hPBcxKwwJJBoQBzo0LgREEzsmHxzw7+WypJaQeGhYTkwj/v309Ozr4+He2dTU1MbEubSzq6mkop+VlJF/fnBvbmRiYV9WVWDYyeYAAAMsSURBVFjDvdjnbtswFIbhjyEVyZLiEY/YSTPa7L333rNN967TJu3930IZxUYii+dYcoG+v2wDfoBjUwBJCDrlOe1uSuaAnEy57Y6nBB0JZdOuREPSTWeTQcrJw5ztOio2VLAkmKRViAWp7oBhqW7VHMp0IkadmSaQ6kDMOhQH+SnErtOnoYxEgnIZCnJsJMp2zJCDxDkmqGQnh+xSFMpQDi9lGiFfoqWkH4YU+7+fnoIspUIQvw63triV+RTywDYzAybvEeIHw8Xd3QXY4epQGmw7d3c7YErXoYLkobmbmzkwyUINsnhn6EY3BCbrAVKShwZ+6wbAJFUAOeCb/6ObB5cTQPkmk/0MYmfL30NZ8A18DxoAV1ZDafAtPEAL4EpryOWd4bFfQWPDYHIFVBcPPf9c6zmYuhQ88LXVoTZweXAQbq8t3PiPWuNt4fbCCwCjkVmuX8XoOjypZlw0dj573bTZc4RzkUKk4vqbJq0XEU4zEoYOJ94zTRwikkQOpq4WX5ItXiGSZqi2KWcbCTuZ/mBo+oRck/TDsfIp0sowyUjQ7X5paBdUEp0M9OJrQy9ApJk8A62+bWgVRJrpoJ3iZCM0WYQ5zaSZyV5HombTTIaG1qLQGogyUDYJPXtX69njK5izFUSKco7q314eGVmuvz6CsZSAqFBQ37eH9qHbr73pg7GKhjwK6vl4X+8Zgs56g7c9MOZpSBBLcjD4Yl8RtYp9wQeDxuUoBMg9xObt7W3PAZ500KM/2oQhK4CythHqrVaXLhHqcqla7UU0OxtAomycrFrtR6T+atUwW1k8QL5tgPqnjk3+8VSUt/0AIva0GyMwNrJh3tdCEHu/+MlCDdI5/wI5Tzfs+dadvHgKZWXLg2VDkCi1CpUaj1lWa44VPfi1t+K0m46i5eRO2Xw4Hk3qjFLHdSvp70NBopSLz+RKgoCSXUWkfP6SRVXsOIxdUU2vfXy3ueP6sS6iPLcJ48W+GvPKXZQiO7xEl3WFCrGUFXlZR9VNPBIkREu0w0O8xDs8JCzS4SFe0g4P8RLhEBAnEQ4BcRLhEBAnEQ4BcRLhEBAnEQ4Bcf1P6C/lNsLKaO6sVQAAAABJRU5ErkJggg==';
    var wasImg,
        wasMapImg;

    var pWidth,
        pHeight,
        limitWidth = 650;

    var currentEventId,
        currentEventShow;

    var total,
        _cnt = 0;

    var fontSize = 15;
    var font = fontSize + 'px PT Sans Narrow';

    var _def = {
        maxPointSize: 8,
        currentPoint: 0,
        iterateScaleCnt: 0.2,
        nextIterateCnt: 0,
        startRadius: 6,
        direction: 'up'
    };

    window.requestAnimFrame = (function() {
        return window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimationFrame ||
            function(callback) {
                window.setTimeout(callback, 1000 / 30);
            };
    })();

    function _drawArc(x, y, i, maxI) {

        var startAngle = 0,
            endAngle = 2 * Math.PI,
            counterClockwise = false,
            radius;

        ctx.fillStyle = '#e4186b';

        ctx.beginPath();

        if (pWidth <= limitWidth) {
            _def = {
                maxPointSize: 5,
                startRadius: 3,
            };
        }

        if (i == _def.currentPoint) {
            radius = _def.nextIterateCnt > 0 ? _def.nextIterateCnt : _def.startRadius;

            if (radius <= _def.maxPointSize && _def.direction === 'up') {
                _def.nextIterateCnt = radius += _def.iterateScaleCnt;
                //console.log('up');
            } else {
                radius -= _def.iterateScaleCnt;
                _def.direction = 'down';
                if (radius <= _def.startRadius) {
                    if (maxI > i) {
                        ++_def.currentPoint;
                    } else {
                        _def.currentPoint = 0;
                    }
                    //reset state
                    _def.nextIterateCnt = 0;
                    _def.direction = 'up';
                } else {
                    _def.nextIterateCnt = radius;
                }
            }

        } else {
            radius = _def.startRadius;
        }

        ctx.arc(x, y, radius, startAngle, endAngle, counterClockwise);
        ctx.fill();
    }

    function _drawArcSimple(x, y) {
        var startAngle = 0,
            endAngle = 2 * Math.PI,
            counterClockwise = false,
            radius = 4;

        ctx.beginPath();

        ctx.fillStyle = '#6f7173';
        ctx.arc(x, y, radius, startAngle, endAngle, counterClockwise);
        ctx.fill();
    }

    function _drawArcStroke(x, y) {
        var startAngle = 0,
            endAngle = 2 * Math.PI,
            counterClockwise = false,
            radius = 9;
        ctx.strokeStyle = '#891b4a';
        ctx.arc(x, y, radius, startAngle, endAngle, counterClockwise);
        ctx.stroke();
    }

    function _clearCanvas() {
        ctx.clearRect(0, 0, width, height);
    }

    function _load() {
        // get parent width
        _getDisplaySize();
        // clear canvas before draw new layer
        _clearCanvas();
        // draw bg map at other canvas
        _createMapBg(mapImg);
        // load data from server
        loadData(function() {
            // start animation
            // @TODO Return when needed
            // anim();
            // start draw
            draw();
            // show popup whith shared data events
            _showSharePopup();
        });
    }

    function _showSharePopup() {
        var search = window.location.search.length;

        if (search > 0) {
            var query = window.location.search.replace('?', '').split('=');

            if (query[0] === 'events') {
                for (var i = 0; i < data.length; ++i) {
                    if (data[i].point.id == query[1]) {
                        return popups.detail(i);
                    }
                }
            }
        }
    }

    function _resize() {
        // get parent width
        _getDisplaySize();
        // clear canvas before draw new layer
        _clearCanvas();
        // draw bg map at other canvas
        _createMapBg(mapImg);
        // draw points
        draw();
    }

    function loadData(cb) {
        window.data = [];

        $.get('site/get-point-map', function (result) {
            if (result.success == 'ok') {
                for(var key in result.items){
                    data[key] = {
                        point: result.items[key].point
                    };

                    if (result.items[key]['cashes'].length > 0) {
                        data[key]['cashes'] = result.items[key]['cashes'];
                    }

                    if (data[key].point.tour_dt) {
                        data[key].point.date = popups.parseDate(data[key].point.tour_dt);
                    }
                }

                if (typeof cb === 'function') {
                    cb();
                }
            }
            if (result.success == 'zero') {
                console.log(result.success);
            }
        }, 'json');
    }

    function _maxIteration(list) {
        return list.length - 1;
    }

    function _addListeners() {
        // adds listeners
        window.addEventListener('load', _load, false);
        window.addEventListener('resize', _resize, false);
        canvas.addEventListener('mousemove', _cursor, false);
        canvas.addEventListener('click', _click, false);
    }

    function _click() {
        if (currentEventShow) {
            popups.detail(currentEventId);
        }
    }

    function _cursor(e) {
        if (pWidth <= limitWidth) {
            return;
        }

        var _r = _def.maxPointSize ,
            _x = e.offsetX == undefined ? e.layerX : e.offsetX,
            _y = e.offsetY == undefined ? e.layerY : e.offsetY;

        for (var _i = 0; _i < data.length; ++_i) {
            var val = data[_i]['point'],
                __x = (width * val.coords_x) / 100,
                __y = (height * val.coords_y) / 100,
                __minX = __x - _r,
                __maxX = __x + _r + data[_i]['point']['textWidth'];

            if (__minX <= _x && __maxX >= _x && __y - _r <= _y && __y + _r >= _y) {
                popups.timeout.forEach(function(val) {
                    clearTimeout(val);
                });

                if (!popups.isShow) {
                    if (!val.id) {
                        break;
                    }

                    currentEventId = _i;
                    currentEventShow = true;

                    popups.info(__y, __x, _i);
                    popups.isShow = true;

                    $(canvas).addClass('pointer');

                    break;
                }
            } else {
                if (popups.isShow) {
                    currentEventShow = false;
                    popups.isShow = false;
                    popups.timeout.push(setTimeout(function () {
                        popups.closeInfo();
                        $(canvas).removeClass('pointer');
                    }, 1500));
                }
            }
        }
    }

    function anim() {
        if(_cnt % 2) {
            draw();
        }
        ++_cnt;
        window.requestAnimFrame(anim);
    }

    function draw() {
        // max cnt
        total = _maxIteration(data);
        // draw events at map
        data.forEach(function(event, i) {
            var __x = (width * event.point.coords_x) / 100,
                __y = (height * event.point.coords_y) / 100;

            if (event.point.id) {
                _drawArc(__x, __y, i, total);
                _drawArcStroke(__x, __y);
            } else {
                _drawArcSimple(__x, __y);
            }

            if (pWidth > limitWidth) {
                var textSize = _drawCity(event.point.name, __x, __y);

                event.point.textWidth = textSize.width;
                event.point.textHeight = textSize.height;

                if (event.point.was == 1) {
                    if (wasImg) {
                        // draw map
                        ctx.drawImage(wasImg, 0, 0, wasImg.width, wasImg.height, __x - wasImg.width / 4, __y - wasImg.height / 2 - 5, wasImg.width / 2, wasImg.height / 2);
                    } else {
                        _drawWasConcertFlag(__x, __y);
                    }
                }
            }
        });
    }

    function _getDisplaySize() {
        pWidth = $(canvas).parent().width();
        pHeight = $('.header').outerHeight();
    }

    function _drawCity(text, x, y) {
        var textSize,
            startTextWrite,
            indent = 10;

        ctx.textAlign = 'start';
        ctx.textBaseline = 'middle';
        ctx.fillStyle = '#fff';
        ctx.font = font;

        textSize = _textSize(text);

        if ((textSize.width + x + indent) > width) {
            startTextWrite = x - textSize.width - indent;
        } else {
            startTextWrite = x + indent;
        }

        ctx.fillText(text, startTextWrite, y);

        return textSize;
    }

    function _textSize(text) {
        var data = {};

        data.height = fontSize;
        data.width = ctx.measureText(text).width;

        return data;
    }

    function _drawWasConcertFlag(x, y) {
        var img = new Image();

        img.onload = function(){
            // draw map
            wasImg = this;
            ctx.drawImage(this, 0, 0, this.width, this.height, x - this.width / 4, y - this.height / 2 - 5, this.width / 2, this.height / 2);
        };

        img.src = wasImgUrl;
    }

    function _drawWasMap(img) {
        if (pWidth < img.width) {
            width  = canvas.width = pWidth;
            if (pHeight + img.height > $(window).height()) {
                height = canvas.height = pWidth / 2.5;
            } else {
                height = canvas.height = pWidth / 2;
            }
        } else {
            width  = canvas.width = img.width;
            if (pHeight + img.height > $(window).height()) {
                height = canvas.height = pWidth / 2.5;
            } else {
                height = canvas.height = pWidth / 2;
            }
        }

        // draw map
        ctx.drawImage(img, 0, 0, width, height);
    }

    function _createImg(url) {
        if (wasMapImg) {
            _drawWasMap(wasMapImg);
        } else {
            var img = new Image();

            img.onload = function() {
                wasMapImg = this;

                _drawWasMap(wasMapImg);
            };

            img.src = url;
        }
    }

    // draw bg map
    function _createMapBg(url) {
        _createImg(url);
    }

    // init events
    _addListeners();
})();