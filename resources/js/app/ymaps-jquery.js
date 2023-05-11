import $ from "jquery";

// GLOBALS
window.current_opened_location_id = null;

class YMapsJQuery {

    myMap;
    current_marker;
    MyIconContentLayout;
    myPlacemark;
    myPlacemarkWithContent;

    init() {
        ymaps.ready(() => {
            this.myMap = new ymaps.Map('map', {
                center: [59.944600, 30.367062],
                zoom: 15
            }, {
                autoFitToViewport: 'always',
                searchControlProvider: 'yandex#search'
            }),

                // Создаём макет содержимого.
            this.MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
            ),

            this.myPlacemark = new ymaps.Placemark(this.myMap.getCenter(), {
                hintContent: 'Собственный значок метки',
                balloonContent: 'Это красивая метка'
            }, {
                // Опции.
                // Необходимо указать данный тип макета.
                iconLayout: 'default#image',
                // Своё изображение иконки метки.
                iconImageHref: 'images/myIcon.gif',
                // Размеры метки.
                iconImageSize: [30, 42],
                // Смещение левого верхнего угла иконки относительно
                // её "ножки" (точки привязки).
                iconImageOffset: [-5, -38]
            }),

            this.myPlacemarkWithContent = new ymaps.Placemark([55.661574, 37.573856], {
                hintContent: 'Собственный значок метки с контентом',
                balloonContent: 'А эта — новогодняя',
                iconContent: '12'
            }, {
                // Опции.
                // Необходимо указать данный тип макета.
                iconLayout: 'default#imageWithContent',
                // Своё изображение иконки метки.
                iconImageHref: 'images/ball.png',
                // Размеры метки.
                iconImageSize: [48, 48],
                // Смещение левого верхнего угла иконки относительно
                // её "ножки" (точки привязки).
                iconImageOffset: [-24, -24],
                // Смещение слоя с содержимым относительно слоя с картинкой.
                iconContentOffset: [15, 15],
                // Макет содержимого.
                iconContentLayout: this.MyIconContentLayout
            });

            this.myMap.geoObjects
                .add(this.myPlacemark)
                .add(this.myPlacemarkWithContent);

            this.myMap.events.add('click', function (e) {
                let coords = e.get('coords');
                window.ADD_LOCATION_COORDS_GLOBAL = coords;
                //ymapsjq.addMarker(id, coords);
                //ymapsjq.saveMarker(coords);

                let baloon = e.get('map').balloon.open(coords,
                    '<div style="margin: 10px; width: 100%; text-align: center;">' +
                    'Хотите создать здесь локацию?' +
                    '</div>' +
                    '<div style="margin: 10px; width: 100%; text-align: center;">' +
                    '<button onclick="openModal(\'add-lacation\');ymapsjq.myMap.balloon.close();" style="padding:10px" id="my-button"> Создать локацию </button>' +
                    '</div>', {
                    // Опция: не показываем кнопку закрытия.
                    closeButton: true
                });
            });

            var location = ymaps.geolocation.get({
                autoReverseGeocode: false
            });

            // Асинхронная обработка ответа.
            location.then(
                function(result) {
                    // Добавление местоположения на карту.
                    this.myMap.geoObjects.add(result.geoObjects)
                },
                function(err) {
                    console.log('Ошибка: ' + err)
                }
            );

            $.ajax({
                type: "GET",
                url: "/api/ymaps/markers",
                success: function(data){
                    var markers_existing = data.data;

                    markers_existing.forEach(marker => {
                        var coords = [];
                        coords.push(marker.lat);
                        coords.push(marker.lng);
                        ymapsjq.addMarker(marker.location_id, coords);
                    });
                },
                error: function(){

                }
            }).fail(function(){
                alert("Что-то пошло не так, попробуйте обновить страницу.");
            });
        });
    }

    onMapClick(e) {

    }

    addMarker(location_id, coords) {
        let that = this;
        /*let BalloonContentLayout = ymaps.templateLayoutFactory.createClass(
            '<div style="margin: 10px; width: 100%; text-align: center;">' +
            'Хотите открыть локацию?' +
            '</div>' +
            '<div style="margin: 10px; width: 100%; text-align: center;">' +
            '<button style="padding:10px" id="my-button"> Открыть локацию </button>' +
            '</div>', {
                build() {
                    BalloonContentLayout.superclass.build.call(this);
                    document.getElementById(`my-button`).addEventListener("click", e => {
                        BalloonContentLayout.superclass.clear.call(this);
                    });
                },
                clear() {
                    BalloonContentLayout.superclass.clear.call(this);
                },
            }
        );*/

        let new_marker = new ymaps.Placemark(coords, {
            //name: 'Открыть карту с меткой',
            //iconCaption: 'Скамейка'
        }, {
            //balloonContentLayout: BalloonContentLayout,
            //balloonPanelMaxMapArea: 0,
            preset: 'islands#redDotIconWithCaption'
        });
        new_marker.events.add('click', e => {
            this.current_marker = e.get("target");
            window.current_opened_location_id = location_id;
            openModal('view-lacation');
        });
        this.myMap.geoObjects.add(new_marker);
    }

    saveMarker(location_id, coords) {
        console.log(location_id)
        $.ajax({
            type: "POST",
            url: "/api/ymaps/markers/store",
            data: {
                location_id: location_id,
                lat: coords[0],
                lng: coords[1]
            },
            success(data){

            },
            error(){
                alert("Что-то пошло не так, локация не сохранена!");
            }
        }).fail(function(){
            alert("Что-то пошло не так, локация не сохранена!");
        });
    }

    onMarkerClick(e){

    }

    deleteMarker() {
        this.myMap.geoObjects.remove(this.current_marker);
    }
}

window.ymapsjq = new YMapsJQuery();
