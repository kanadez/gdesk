
ymaps.ready(function () {
  var myMap = new ymaps.Map('map', {
      center: [55.751574, 37.573856],
      zoom: 10
    }, {
      searchControlProvider: 'yandex#search'
    }),

    // Создаём макет содержимого.
    MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
      '<div style="color: #FFFFFF; font-weight: bold;" >$[properties.iconContent]</div>'
    ),
    MyIconContentLayout2 = ymaps.templateLayoutFactory.createClass(
      '<div data-target="search" class="toggler"  style="position: absolute; z-index: 10; width: 38px; height: 38px">$[properties.iconContent]</div>'
    ),

    myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
      iconContent: ''
    }, {

      iconLayout: 'default#image',
      iconImageHref: 'images/icon/i-marker.svg',
      iconImageSize: [38, 38],
      iconImageOffset: [-5, -38],
      iconContentLayout: MyIconContentLayout
    }),

    myPlacemarkWithContent = new ymaps.Placemark([55.862780, 37.573856], {
      iconContent: '',
      locationUrl: 'http://yandex.ru',
      class: 'search'

    }, {
      class: 'search',
      iconLayout: 'default#imageWithContent',
      iconImageHref: 'images/icon/i-marker.svg',
      iconImageSize: [38, 38],
      iconImageOffset: [-24, -24],
      iconContentOffset: [15, 15],
      iconContentLayout: MyIconContentLayout2
    });

  myMap.geoObjects
    .add(myPlacemark);
  myPlacemark.events.add('click', function () {
    $('.main__info._hide').fadeToggle();
  });
  myMap.geoObjects
    .add(myPlacemarkWithContent);
  myPlacemarkWithContent.events.add('click', function () {
  //
  //   // window.location.href = target.properties.get('locationUrl');
  //   window.location = 'edit-location.html';
  //   //a(href="javascript:;" data-target='search').a-header.toggler
    $("#edit").toggleClass('_active');
    $("#edit").closest('.modal-backdrop').fadeIn();
    $("#edit").closest('body').toggleClass('_modal-open');
  });







});
