var Map={
  isInit: false,
  hInit: function () {
      $(document).on('click', ".on-list", Map.hShowList);
      $(document).on('click', ".on-map", Map.hShowMap);
  },
    hShowList: function () {
      $('.maplist-map').hide();
      $('.map-buy-table').show();
      $('.on-map').removeClass('maplist-active');
      $('.on-list').addClass('maplist-active');
    },
    hShowMap: function () {
      $('.maplist-map').show();
      $('.map-buy-table').hide();
      $('.on-list').removeClass('maplist-active');
      $('.on-map').addClass('maplist-active');
      if(!Map.isInit){
      Map.initMap();
      }
      Map.isInit = true;
    },
  initMap: function () {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 55.74867169, lng: 37.57408105},
      zoom: 13,
      disableDefaultUI: true,
      zoomControl: true,
      scrollwheel: false,
      styles: MapStyle
    });
    var contentString = '<div id="content" class="buble-map-content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 id="firstHeading" class="firstHeading">Мега</h1>'+
      '<div id="bodyContent">'+
      '<p>Метро: Жулебино</p>'+
      '<p>Адрес: ул. Ленина д.235</p>'+
      '<p>Часы работы: пн-пт 8-20, сб-вс 10-21</p>'+
      '</div>'+
      '</div>';

    var infowindow = new InfoBubble({
      content: contentString,
      backgroundColor: '#000000',
      borderColor: '#000000',
      padding: '20',
      closeSrc: 'i/info-bubble-close.png'
    });
    var MarkerImage = {
      url: 'i/tobe-red-flag.png',
      // size: new google.maps.Size(20, 32),
    };
    var Marker = new google.maps.Marker({
      position: {lat: 55.74867169, lng: 37.57408105},
      map: map,
      icon: MarkerImage

    });
    Marker.addListener('click', function() {
    infowindow.open(map, Marker);
    });
  }
}

$(document).ready(function() {
  Map.hInit();
  $(".chosen-select").chosen({
    no_results_text: "Город не найден",
    width: "360px",
    search_contains: "true"
  });
  $('.chosen-search > input').attr("placeholder", "Введи название города");

    // $('.chosen-results').jScrollPane();

  $('.chosen-select').on('chosen:showing_dropdown', function(evt, params) {
  $('.chosen-results').data().jsp = '';
    $('.chosen-results').jScrollPane();
  });
});
