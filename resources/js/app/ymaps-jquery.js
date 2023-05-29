import $ from "jquery";

// GLOBALS
window.current_opened_location_id = null;

// CONSTS
const YMAPS_MAX_ZOOM = 16;
const YMAPS_CITY_VIEW_ZOOM = 10;
const SPB_CENTER_COORDS = [59.944600, 30.367062];

class YMapsJQuery {

    myMap;
    MyIconContentLayout;
    myPlacemark;
    myPlacemarkWithContent;

    init() {
        ymaps.ready(() => {
            // Создаем карту
            this.myMap = new ymaps.Map('map', {
                center: SPB_CENTER_COORDS,
                zoom: YMAPS_CITY_VIEW_ZOOM
            }, {
                autoFitToViewport: 'always',
                searchControlProvider: 'yandex#search'
            });

            this.showUserLocation();

            // Создаем балун, возникацющий при клике на карте
            this.myMap.events.add('click', function (e) {
                let coords = e.get('coords');
                window.ADD_LOCATION_COORDS_GLOBAL = coords;

                let baloon = e.get('map').balloon.open(
                    coords,
                    '<div style="margin: 10px; width: 100%; text-align: center;">' +
                    'Хотите создать здесь локацию?' +
                    '</div>' +
                    '<div style="margin: 10px; width: 100%; text-align: center;">' +
                    '<button onclick="openModal(\'add-lacation\');ymapsjq.myMap.balloon.close();" style="padding:10px" id="my-button"> Создать локацию </button>' +
                    '</div>', {
                        closeButton: true
                    }
                );
            });
        });
    }

    showUserLocation() {
        var location = ymaps.geolocation.get({
            mapStateAutoApply: true,
            autoReverseGeocode: false
        });

        // Добавляем геолокацию юзера на карту
        location.then(
            function(result) {
                ymapsjq.myMap.geoObjects.add(result.geoObjects);
                ymapsjq.myMap.setZoom(YMAPS_MAX_ZOOM);
                ymapsjq.myMap.setCenter(result.geoObjects.position);
            },
            function(err) {
                console.log('Ошибка определния геопозиции пользователя: ' + err);
            }
        );
    }

    getUserLocation() {
        return new Promise((resolve, reject) => {
            ymaps.geolocation.get({
                mapStateAutoApply: true,
                autoReverseGeocode: false
            })
            .then(
                result => {
                    let data = result.geoObjects.get(0).geometry.getCoordinates();
                    let response = {
                        success: true,
                        data: data
                    };
                    resolve(response);
                },
                error => {
                    let response = {
                        success: false,
                        error: error
                    };
                    reject(response);
                }
            )
            .catch(error => {
                let response = {
                    success: false,
                    error: error
                };
                reject(response);
            });
        });
    }

    onMapClick(e) {

    }

}

class YMapsMarkers {

    currentMarker;
    buildRouteFromMakersButton = null;

    addMarkersGroup(route_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: `/api/ymaps/markers/route/${route_id}`,
                success: function (data) {
                    ymapsmarkers.removeAllMarkers();

                    var markers_existing = data.data;
                    var markers_coords = [];

                    markers_existing.forEach(marker => {
                        if (marker === null) return false;

                        var coords = [];
                        coords.push(marker.lat);
                        coords.push(marker.lng);
                        ymapsmarkers.addMarker(marker.location_id, coords);
                        markers_coords.push(coords);
                    });

                    ymapsmarkers.setMarkersGroupBounds();
                    ymapsmarkers.removeMarkersGroupControls();
                    ymapsrouting.removeRouteControls();

                    ymapsmarkers.buildRouteFromMakersButton = new ymaps.control.Button({
                        data: {
                            content: "Построить маршрут",
                        },
                        options: {
                            maxWidth: 250
                        }
                    });
                    ymapsmarkers.buildRouteFromMakersButton.events.add('click', function () {
                        ymapsrouting.buildMultiRoute(markers_coords);
                    });

                    ymapsjq.myMap.controls.add(ymapsmarkers.buildRouteFromMakersButton);

                    resolve(data);
                },
                error: function (error) {
                    reject(error);
                }
            }).fail(function (error) {
                reject(error);
            });
        });
    }

    setMarkersGroupBounds() {
        var centerAndZoom = ymaps.util.bounds.getCenterAndZoom(
            ymapsjq.myMap.geoObjects.getBounds(),
            ymapsjq.myMap.container.getSize(),
            ymapsjq.myMap.options.get('projection')
        );

        if (ymapsjq.myMap.geoObjects.getLength() < 2) centerAndZoom.zoom = YMAPS_MAX_ZOOM;

        ymapsjq.myMap.setCenter(centerAndZoom.center, centerAndZoom.zoom);
    }

    addMarker(location_id, coords) {
        let that = this;
        let new_marker = new ymaps.Placemark(coords, {
            //name: 'Открыть карту с меткой',
            //iconCaption: 'Скамейка'
        }, {
            //balloonContentLayout: BalloonContentLayout,
            //balloonPanelMaxMapArea: 0,
            preset: 'islands#redDotIconWithCaption'
        });
        new_marker.events.add('click', e => {
            this.currentMarker = e.get("target");
            window.current_opened_location_id = location_id;
            openModal('view-lacation');
        });
        ymapsjq.myMap.geoObjects.add(new_marker);
    }

    saveMarker(location_id, coords) {
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

    markersDistance(x1, y1, x2, y2) {
        return Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
    }

    removeAllMarkers() {
        ymapsjq.myMap.geoObjects.removeAll();
    }

    removeMarkersGroupControls() {
        if (this.buildRouteFromMakersButton != null) ymapsjq.myMap.controls.remove(this.buildRouteFromMakersButton);
    }

    deleteMarker() {
        ymapsjq.myMap.geoObjects.remove(this.currentMarker);
    }
}

class YMapsRouting {

    routingModeListbox = null;
    cancelRouteButton = null;
    multiRoute = null;

    removeRouteControls() {
        if (this.routingModeListbox != null) ymapsjq.myMap.controls.remove(this.routingModeListbox);
        if (this.cancelRouteButton != null) ymapsjq.myMap.controls.remove(this.cancelRouteButton);
    }

    async buildRouteUrl(marker_coords) {
        let userLocation = await ymapsjq.getUserLocation();
        console.log(userLocation)

        return `yandexmaps://maps.yandex.ru/?rtext=${userLocation.data[0]},${userLocation.data[1]}~${marker_coords[0]},${marker_coords[1]}&rtt=mt`;
    }

    async buildMultiRoute(markers_coords) {
        let userLocation = await ymapsjq.getUserLocation();
        let referencePoints = markers_coords;
        let viaIndexes = [2];

        if (userLocation.success) referencePoints = [userLocation.data].concat(markers_coords);

        // Сортировка точек на карте по удаленности от юзера
        let referencePointsSorted = this.sortRouteMarkersByDistanceFromUser(userLocation, referencePoints);

        // Создаем мультимаршрут и настраиваем его внешний вид с помощью опций.
        this.multiRoute = new ymaps.multiRouter.MultiRoute({
            referencePoints: referencePointsSorted,
            params: {viaIndexes: viaIndexes}
        }, {
            // Внешний вид путевых точек.
            wayPointStartIconColor: "#333",
            wayPointStartIconFillColor: "#B3B3B3",
            // Задаем собственную картинку для последней путевой точки.
            wayPointFinishIconLayout: "default#image",
            wayPointFinishIconImageHref: "images/icon/finish_flag.png",
            wayPointFinishIconImageSize: [30, 30],
            wayPointFinishIconImageOffset: [-15, -15],
            // Позволяет скрыть иконки путевых точек маршрута.
            // wayPointVisible:false,

            // Внешний вид транзитных точек.
            viaPointIconRadius: 7,
            viaPointIconFillColor: "#000088",
            viaPointActiveIconFillColor: "#E63E92",
            // Транзитные точки можно перетаскивать, при этом
            // маршрут будет перестраиваться.
            viaPointDraggable: true,
            // Позволяет скрыть иконки транзитных точек маршрута.
            // viaPointVisible:false,

            // Внешний вид точечных маркеров под путевыми точками.
            pinIconFillColor: "#000088",
            pinActiveIconFillColor: "#B3B3B3",
            // Позволяет скрыть точечные маркеры путевых точек.
            // pinVisible:false,

            // Внешний вид линии маршрута.
            routeStrokeWidth: 2,
            routeStrokeColor: "#000088",
            routeActiveStrokeWidth: 6,
            routeActiveStrokeColor: "#E63E92",

            // Внешний вид линии пешеходного маршрута.
            routeActivePedestrianSegmentStrokeStyle: "solid",
            routeActivePedestrianSegmentStrokeColor: "#00CDCD",

            // Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
            boundsAutoApply: true
        });

        // Создадим собственный макет выпадающего списка, а также добавим кнопку удаления маршрута
        this.routingModeListbox = this.createRoutingModeListbox(this.multiRoute);
        this.cancelRouteButton = new ymaps.control.Button("Отмена");

        // Объявляем обработчики для кнопок.
        this.cancelRouteButton.events.add('click', function () {
            ymapsjq.myMap.geoObjects.remove(ymapsrouting.multiRoute);
            ymapsrouting.removeRouteControls();
            ymapsmarkers.setMarkersGroupBounds();
        });

        // Добавляем мультимаршрут на карту.
        ymapsjq.myMap.geoObjects.add(this.multiRoute);

        // Проверяем, есть ли кнопки управления маршрутом на карте. Если есть, удаляем
        this.removeRouteControls();

        // Удаляем кнопку построения маршрута
        ymapsmarkers.removeMarkersGroupControls();

        // Добавляем кнопки на карту.
        ymapsjq.myMap.controls.add(this.cancelRouteButton);
        ymapsjq.myMap.controls.add(this.routingModeListbox, {float: 'left'});

        setTimeout(() => {
            ymapsjq.showUserLocation();
        }, 2000);
    }

    sortRouteMarkersByDistanceFromUser(user_location, markers) {
        let distances_with_indexes = [];

        for (let index in markers) {
            let current_marker = markers[index];
            let distance_with_index = {};
            distance_with_index.index = index;
            distance_with_index.distance = ymapsmarkers.markersDistance(user_location.data[0], user_location.data[1], current_marker[0], current_marker[1]);
            distances_with_indexes.push(distance_with_index);
        }

        function compare( a, b ) {
            if ( a.distance < b.distance ){
                return -1;
            }
            if ( a.distance > b.distance ){
                return 1;
            }

            return 0;
        }

        distances_with_indexes.sort(compare);

        let markersSorted = [];
        distances_with_indexes.forEach((distance_with_index) => {
            markersSorted.push(markers[distance_with_index.index]);
        });

        return markersSorted;
    }

    createRoutingModeListbox(route) {
        var ListBoxLayout = ymaps.templateLayoutFactory.createClass(
            "<button id='my-listbox-header' class='dropdown-toggle ymaps-float-button ymaps-text-container' data-toggle='dropdown'>" +
            "{{data.title}} <span class='caret'></span>" +
            "</button>" +
            // Этот элемент будет служить контейнером для элементов списка.
            // В зависимости от того, свернут или развернут список, этот контейнер будет
            // скрываться или показываться вместе с дочерними элементами.
            "<ul id='my-listbox'" +
            " class='dropdown-menu ymaps-listbox-panel' role='menu' aria-labelledby='dropdownMenu'" +
            " style='display: {% if state.expanded %}block{% else %}none{% endif %};'></ul>", {

                build: function() {
                    // Вызываем метод build родительского класса перед выполнением
                    // дополнительных действий.
                    ListBoxLayout.superclass.build.call(this);

                    this.childContainerElement = $('#my-listbox').get(0);
                    // Генерируем специальное событие, оповещающее элемент управления
                    // о смене контейнера дочерних элементов.
                    this.events.fire('childcontainerchange', {
                        newChildContainerElement: this.childContainerElement,
                        oldChildContainerElement: null
                    });
                },

                // Переопределяем интерфейсный метод, возвращающий ссылку на
                // контейнер дочерних элементов.
                getChildContainerElement: function () {
                    return this.childContainerElement;
                },

                clear: function () {
                    // Заставим элемент управления перед очисткой макета
                    // откреплять дочерние элементы от родительского.
                    // Это защитит нас от неожиданных ошибок,
                    // связанных с уничтожением dom-элементов в ранних версиях ie.
                    this.events.fire('childcontainerchange', {
                        newChildContainerElement: null,
                        oldChildContainerElement: this.childContainerElement
                    });
                    this.childContainerElement = null;
                    // Вызываем метод clear родительского класса после выполнения
                    // дополнительных действий.
                    ListBoxLayout.superclass.clear.call(this);
                }
            });

        // Также создадим макет для отдельного элемента списка.
        var ListBoxItemLayout = ymaps.templateLayoutFactory.createClass(
            "<li class='ymaps-listbox-list-item'><a class='ymaps-list-item-text'>{{data.content}}</a></li>"
        );

        // Создадим 2 пункта выпадающего списка
        var listBoxItems = [
            new ymaps.control.ListBoxItem({
                data: {
                    content: 'Пешком',
                    mode: 'pedestrian',
                }
            }),
            new ymaps.control.ListBoxItem({
                data: {
                    content: 'На машине',
                    mode: 'auto',
                }
            }),
            new ymaps.control.ListBoxItem({
                data: {
                    content: 'На транспорте',
                    mode: 'masstransit',
                }
            })
        ];

        // Теперь создадим список, содержащий 2 пункта.
        var listBox = new ymaps.control.ListBox({
            items: listBoxItems,
            data: {
                title: 'Как передвигаться'
            },
            options: {
                // С помощью опций можно задать как макет непосредственно для списка,
                layout: ListBoxLayout,
                // так и макет для дочерних элементов списка. Для задания опций дочерних
                // элементов через родительский элемент необходимо добавлять префикс
                // 'item' к названиям опций.
                itemLayout: ListBoxItemLayout
            }
        });

        listBox.events.add('click', function (e) {
            // Получаем ссылку на объект, по которому кликнули.
            // События элементов списка пропагируются
            // и их можно слушать на родительском элементе.
            var item = e.get('target');
            // Клик на заголовке выпадающего списка обрабатывать не надо.
            if (item != listBox) {
                route.model.setParams({routingMode: item.data.get('mode')}, true);
                listBox.state.set('expanded', false);
                listBox.data.set('title', item.data.get('content'));
            }
        });

        return listBox;
    }
}

window.ymapsjq = new YMapsJQuery();
window.ymapsmarkers = new YMapsMarkers();
window.ymapsrouting = new YMapsRouting();
