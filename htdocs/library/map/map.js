function map (params) {
	var self = this;
	var settings = params.data;

    ymaps.ready(function() {
        var map = new ymaps.Map(self, {
            center: [settings.x, settings.y],
            zoom: settings.zoom
        });

        var myPlacemark = new ymaps.Placemark([settings.x, settings.y]);

        map.geoObjects.add(myPlacemark);
	});
}