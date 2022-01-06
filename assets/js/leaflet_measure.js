(function (L, undefined) {
    /**
     * Espacio de nombres base para herramientas de medición.
     */
    L.Measure = L.Class.extend({
        initialize: function (map, options) {
            this._map = map;
            options = options || {};
            options.editOptions = options.editOptions || {};
            this.options = options;
            if (!this._map.editTools) {
                this._map.editTools = new L.Editable(map, options.editOptions);
            }

            if (!this._map.measureTools) {
                this._map.measureTools = this;
            }
            this.markerTool = L.Measure.marker(map, {});
            this.circleTool = L.Measure.circle(map, {});
            this.rectangleTool = L.Measure.rectangle(map, {});
            this.polylineTool = L.Measure.polyline(map, {});
            this.polygonTool = L.Measure.polygon(map, {});
        },

        getEditTools: function () {
            return this._map.editTools;
        },

        getMeasureLayerGroup: function () {
            //       return this._map._measureLayerGroup;
            this._map.editTools.featuresLayer;
        },

        stopMeasuring: function () {
            this.markerTool.stopMeasure();
            this.circleTool.stopMeasure();
            this.rectangleTool.stopMeasure();
            this.polylineTool.stopMeasure();
            this.polygonTool.stopMeasure();
        }
    });

    /*
     El método de fábrica para crear la instancia base.
     */
    L.measure = function (map, options) {
        return new L.Measure(map, options);
    };

    L.Measure.imagePath = (function () {
        var scripts = document.getElementsByTagName('script'),
            leafletRe = /[\/^]leaflet_measure\.js\??/;


        var i, len, src, path;

        for (i = 0, len = scripts.length; i < len; i++) {
            src = scripts[i].src || '';

            if (src.indexOf('ember') >= 0) {
                return '/assets/images';
            } else {
                if (src.match(leafletRe)) {
                    path = src.split(leafletRe)[0];
                    return (path ? path + '../../' : '') + 'imgs';
                }
            }
        }
    }());

    /**
     * Una impureza que anula los métodos básicos del folleto. Herramientas editables de complementos, convirtiéndolas en herramientas de medición.
     */
    L.Measure.Mixin = {

        /**
         * El número de caracteres después del separador decimal para mediciones en metros.
         */
        precision: 2,

        /**
         Inicializa una nueva herramienta de medición.
         @param {Object} map Mapa utilizado.
         */
        initialize: function (map, options) {
            this._map = map;
            this.setEvents();
        },

        setPrecition: function (precision) {
            this.precision = precision;
        },

        stopMeasure: function () {
            this._hideMouseMarker();

            this._map.editTools.stopDrawing();
        },

        _setMouseMarker: function () {
            if (typeof this._map._mouseMarker === 'undefined') {
                var tooltipOptions = {
                    sticky: true,
                    pane: 'popupPane',
                    className: 'leaflet-draw-tooltip'
                };
                var imagePath = L.Measure.imagePath;
                var popupMarkerIcon = L.icon({
                    iconUrl: imagePath + '/popupMarker.png',
                    iconSize: [1, 1]
                });
                this._map._mouseMarker = L.marker(this._map.getCenter());
                this._map._mouseMarker.setIcon(popupMarkerIcon);
                this._map._mouseMarker.addTo(this._map);
                this._map._mouseMarker.bindTooltip('', tooltipOptions);
            }
        },

        _hideMouseMarker: function () {
            var mouseMarker = this._map._mouseMarker;
            if (typeof mouseMarker !== 'undefined') {
                mouseMarker.closeTooltip();
            }
        },

        _getLabelContent: function (layer, latlng) {
            return '';
        },

        _labelledMarkers: function (editor) {
            return [];
        },

        _unlabelledMarkers: function (editor) {
            return [];
        },

        /**
         Método para actualizar etiquetas que contienen resultados de medición.
         @param {Object} e Evento.
         @param {Object} layer Capa editable.
         */
        _updateLabels: function (e) {
            var layer = e.layer;
            var editor = layer.editor;
            var unlabelledMarkers = this._unlabelledMarkers(editor, e);
            for (var i = 0; i < unlabelledMarkers.length; i++) {
                var marker = unlabelledMarkers[i];
                if (marker && marker.getTooltip()) {
                    marker.closeTooltip();
                }
            }
            var labelledMarkers = this._labelledMarkers(editor, e);
            for (var i = 0; i < labelledMarkers.length; i++) {
                var marker = labelledMarkers[i];
                var latlng = marker.latlng;
                var labelText = this._getLabelContent(layer, latlng, e);
                this._showLabel(marker, labelText, latlng);
            }

            // Actualizar información sobre herramientas del objeto medido
            this._updateMeasureLabel(layer, e);
        },

        _showLabel: function (marker, labelText, latlng) {
            if (!marker.getTooltip()) {
                marker.bindTooltip(labelText, {
                    permanent: true,
                    opacity: 0.75
                }).addTo(this._map);
            } else {
                marker.setTooltipContent(labelText);
            }
            if (latlng) {
                marker._tooltip.setLatLng(latlng);
            }
            marker.openTooltip();
        },

        /**
         El método para actualizar la etiqueta principal del objeto medido
         @param {Object} layer Capa editable.
         */
        _updateMeasureLabel: function (layer, e) {},


        /**
         El controlador de eventos que señala el movimiento del cursor del mouse durante el dibujo de medición.
         @param {String} text Texto mostrado.
         */
        _onMouseMove: function (e, text) {
            this._showPopup(text, e.latlng);
        },

        _showPopup: function (text, latlng) {
            this._map._mouseMarker.setTooltipContent(text);
            if (!this._map._mouseMarker.isTooltipOpen()) {
                this._map._mouseMarker.openTooltip();
            }
            this._map._mouseMarker.setLatLng(latlng);
        },

        _closePopup: function () {
            this._map._mouseMarker.closeTooltip();
        },


        _setMeasureEventType: function (e, type) {
            e._measureEventType = type;
        },

        _getMeasureEventType: function (e) {
            return e._measureEventType;
        },

        /**
         Un controlador de eventos que señala la edición de la capa.
         */
        _fireEvent: function (e, type) {
            var layer = e.layer;
            var layerType = this._layerType(layer);
            var measureEvent = 'measure:' + type;
            this._setMeasureEventType(e, measureEvent);
            if (type === 'created') {
                //         this._map._measureLayerGroup.addLayer(layer);
                layer.on('remove', function (e) {
                    this.disableEdit();
                });
            }
            if (type !== 'move') {
                this._updateLabels(e);
            }

            this._map.fire(measureEvent, {
                e: e,
                measurer: this,
                layer: layer,
                layerType: layerType
            });
            return true;
        },

        _layerType: function (layer) {
            var layerType;
            if (layer instanceof L.Marker) {
                layerType = 'Marker';
            } else if (layer instanceof L.Circle) {
                layerType = 'Circle';
            } else if (layer instanceof L.Rectangle) {
                layerType = 'Rectangle';
            } else if (layer instanceof L.Polygon) {
                layerType = 'Polygon';
            } else if (layer instanceof L.Polyline) {
                layerType = 'Polyline';
            } else {
                layerType = 'unknown';
            }
            return layerType;
        },

        eventsOn: function (prefix, eventTree, offBefore) {
            for (var eventSubName in eventTree) {
                var func = eventTree[eventSubName];
                var eventName = prefix + eventSubName;
                if (typeof func == 'function') {
                    if (!!offBefore) {
                        this.measureLayer.off(eventName);
                    }
                    this.measureLayer.on(eventName, func, this);
                } else {
                    this.eventsOn(eventName + ':', func, offBefore);
                }
            }
        },

        eventsOff: function (prefix, eventTree) {
            for (var eventSubName in eventTree) {
                var func = eventTree[eventSubName];
                var eventName = prefix + eventSubName;
                if (typeof func == 'function' && this.measureLayer) {
                    this.measureLayer.off(eventName);
                } else {
                    this.eventsOff(eventName + ':', func);
                }
            }
        }
    };

    /**
     Mixins para métodos de objeto
     El árbol mixin repite el árbol de clase de objeto Leaflet 1.0.0-rc3
     L.Layer + -> L.Marker
     + -> L.Path + -> L.Polyline -> L.Polygon -> L.Rectangle
     + -> L.CircleMarker -> L.Circle
     */

    /**
     Impureza que proporciona soporte para métodos básicos de edición de marcadores
     */
    L.Measure.Mixin.Marker = {

        distanceMeasureUnit: {
            meter: ' м',
            kilometer: ' км'
        },

        /**
         Enumera las coordenadas del punto, que puede tomar cualquier valor válido
         a los valores que se encuentran en los segmentos [-90; 90], para latitud, [-180, 180] para la longitud.
         @param {Object} latlng Punto que contiene coordenadas.
         @returns {Number} Punto con coordenadas corregidas.
         */
        getFixedLatLng: function (latlng) {
            var getFixedCoordinate = function (coordinate, periodRadius) {
                var divCoordinate = Math.floor(Math.abs(coordinate) / periodRadius);
                var fixCoefficient = divCoordinate % 2 ? (divCoordinate + 1) : divCoordinate;

                return (coordinate >= 0) ? coordinate - (periodRadius * fixCoefficient) : coordinate + (periodRadius * fixCoefficient);
            };

            return L.latLng(getFixedCoordinate(latlng.lat, 90), getFixedCoordinate(latlng.lng, 180));
        },

        /**
         Obtenga una representación textual de las medidas realizadas.
         @param {Object} e Argumentos de método.
         @param {Object} e.value Resultado de la medición en metros.
         @param {Object} e.dimension Dimensión de medición (1 - distancias lineales, 2- áreas).
         @returns {string} Representación textual de las medidas realizadas.
         */
        getMeasureText: function (e) {
            var value = parseFloat(e.value.toFixed(this.precision));
            var metersInOneKm = Math.pow(1000, e.dimension);
            var kmPrecition = this.precision + e.dimension * 3;
            var valueInKm = parseFloat((value / metersInOneKm).toFixed(kmPrecition));

            var dimensionText = (e.dimension > 1) ? '<sup>' + e.dimension + '</sup>' : '';
            var kmRoundingBound = 1.0 / Math.pow(10, e.dimension - 1);

            return (valueInKm >= kmRoundingBound) ?
            valueInKm.toFixed(kmPrecition) + this.distanceMeasureUnit.kilometer + dimensionText :
            value.toFixed(this.precision) + this.distanceMeasureUnit.meter + dimensionText;
        },

        /**
         Calcula la distancia entre dos puntos (en metros) con una precisión dada.
         @param {Object} e Argumentos de método.
         @param {Object} e.latlng1 Primer punto.
         @param {Object} e.latlng2 El segundo punto.
         @returns {Number} La distancia resultante (en metros).
         */
        getDistance: function (e) {
            return parseFloat(e.latlng1.distanceTo(e.latlng2).toFixed(this.precision));
        },

        /**
         Calcula la distancia entre dos puntos y devuelve su representación de texto con una precisión dada.
         @param {Object} e Argumentos de método.
         @param {Object} e.latlng1 Primer punto.
         @param {Object} e.latlng2 El segundo punto.
         @returns {String} Representación textual de la distancia.
         */
        getDistanceText: function (e) {
            return this.getMeasureText({
                value: this.getDistance(e),
                dimension: 1
            });
        },

    };

    /**

     Impureza que proporciona soporte para métodos básicos de edición de rutas
     */
    L.Measure.Mixin.Path = {

        getLatLngs: function (layer) {
            return layer.editor.getLatLngs();
        },

        /**
         Método para obtener el número de vértices de una figura.
         @param {Object} layer Una capa con geometría que representa las medidas a realizar.
         @returns {Number} El número de vértices.
         */
        numberOfVertices: function (layer) {
            return this.getLatLngs(layer).length;
        },

        /**

         Método para obtener el perímetro de puntos
         @param {Object} layer Una capa con geometría que representa las medidas a realizar.
         @returns {Number} Perimeter.
         */
        _getPerimeter: function (latlngs) {
            var distance = 0;
            var currentInc = 0;
            for (var i = 1; i < latlngs.length; i++) {
                var prevLatLng = latlngs[i - 1];
                var currentLatLng = latlngs[i];
                currentInc = this.getDistance({
                    latlng1: prevLatLng,
                    latlng2: currentLatLng
                });
                distance += currentInc;
            }

            return distance;
        },

        /**

         Método para obtener el perímetro de puntos
         @param {Object} layer Una capa con geometría que representa las medidas a realizar.
         @returns {Number} Perimeter.
         */
        getPerimeter: function (layer) {
            var latlngs = this.getLatLngs(layer);
            distance = this._getPerimeter(latlngs);

            return distance;
        },

        /**
         Método para obtener el perímetro de puntos
         @param {Object} layer Una capa con geometría que representa las medidas a realizar.
         @returns {Number} String} Una representación textual del perímetro.
         */
        getPerimeterText: function (layer) {
            return this.getMeasureText({
                value: this.getPerimeter(layer),
                dimension: 1
            });
        },

    };

    /**

     Impureza, brindando soporte para los principales métodos de edición de una línea discontinua
     */
    L.Measure.Mixin.Polyline = {};

    /**
     Impureza que proporciona soporte para métodos básicos de edición de polígonos
     */
    L.Measure.Mixin.Polygon = {


        getLatLngs: function (layer) {
            return layer.editor.getLatLngs()[0];
        },

        /**
         Método para obtener el perímetro de puntos
         @param {Object} layer Una capa con geometría que representa las medidas a realizar.
         @returns {Number} Perimeter.
         */
        getPerimeter: function (layer) {
            var latlngs = this.getLatLngs(layer).slice();
            latlngs.push(latlngs[0]);
            distance = this._getPerimeter(latlngs);

            return distance;
        },

        /**
         * Calcula el área de un polígono (en metros) con una precisión dada.
         * @param {Object} layer Una capa con geometría que representa las medidas a realizar.
         * @param {Object} Latlng Point.
         * @returns {Number} Polygon flank (en metros).
         */
        getArea: function (layer, latlng) {
            var latlngs = this.getLatLngs(layer).slice();
            if (latlng) {
                latlngs.push(latlng);
            }

            return distance = parseFloat(this.geodesicArea(latlngs).toFixed(this.precision));
        },

        /**
         Вычисляет площадь многоугольника возвращает её текстовое представление с заданной точностью.
         @param {Object} layer Слой с геометрией, представляющей производимые измерения.
         @param {Object} latlng Точка.
         @returns {Number} Текстовое представление площади.
         */
        getAreaText: function (layer, latlng) {
            return this.getMeasureText({
                value: this.getArea(layer, latlng),
                dimension: 2
            });
        },

        /**
         Вычисляет площадь многоугольника согласно релизации  https://github.com/openlayers/openlayers/blob/master/lib/OpenLayers/Geometry/LinearRing.js#L270*
         Возможно требует доработок для многоугольников с пересекающимися гранями и составных многоугольников с дырами (Holes)
         @param {Object} latLngs  Массив точек многоугольника.
         @returns {Number} Полощадь многоугольника (в метрах).
         */
        geodesicArea: function (latLngs) {
            const DEG_TO_RAD = 0.017453292519943295;;
            var pointsCount = latLngs.length,
                area = 0.0,
                d2r = DEG_TO_RAD,
                p1, p2;

            if (pointsCount > 2) {
                for (var i = 0; i < pointsCount; i++) {
                    p1 = latLngs[i];
                    p2 = latLngs[(i + 1) % pointsCount];
                    area += ((p2.lng - p1.lng) * d2r) *
                    (2 + Math.sin(p1.lat * d2r) + Math.sin(p2.lat * d2r));
                }
                area = area * 6378137.0 * 6378137.0 / 2.0;
            }

            return Math.abs(area);
        },

    };

    /**
     Примесь, обеспечивающая поддержку основных методов редактирования прямоугольника
     */
    L.Measure.Mixin.Rectangle = {};

    /**
     Примесь, обеспечивающая поддержку основных методов редактирования прямоугольника
     */
    L.Measure.Mixin.CircleMarker = {};

    /**
     Примесь, обеспечивающая поддержку основных методов измерения круга
     */
    L.Measure.Mixin.Circle = {

        /**
         Возвращает текстовое представление для радиуса с заданной точностью.
         @param {Object} layer Слой с геометрией, представляющей производимые измерения.
         @returns {Number} Текстовое представление радиуса.
         */
        getRadiusText: function (layer) {
            var radius = layer.getRadius();
            if (radius < 0) return '';
            return this.getMeasureText({
                value: radius,
                dimension: 1
            });
        },

        /**
         Возвращает текстовое представление для диаметра с заданной точностью.
         @param {Object} layer Слой с геометрией, представляющей производимые измерения.
         @returns {String} Текстовое представление диаметра.
         */
        getDiameterText: function (layer) {
            return this.getMeasureText({
                value: 2 * layer.getRadius(),
                dimension: 1
            });
        },

        /**
         Возвращает текстовое представление для периметра с заданной точностью.
         TODO: УЧЕСТЬ СФЕРИЧНОСТЬ - ВОЗМОЖНО СТОИТ ПЕРЕВЕСТИ В МНОГОУГОЛЬНИК?
         @param {Object} layer Слой с геометрией, представляющей производимые измерения.
         @returns {String} Текстовое представление периметра.
         */
        getPerimeterText: function (layer) {
            return this.getMeasureText({
                value: 2 * Math.PI * layer.getRadius(),
                dimension: 1
            });
        },



        /**
         Возвращает текстовое представление площади круга с заданной точностью.
         TODO - УЧЕСТЬ СФЕРИЧНОСТЬ - ВОЗМОЖНО СТОИТ ПЕРЕВЕСТИ В МНОГОУГОЛЬНИК?
         @param {Object} e Аргументы метода.
         @param {Object} e.radius Значение радиуса в метрах.
         @returns {Number} Текстовое представление радиуса.
         */
        getAreaText: function (layer) {
            var radius = layer.getRadius();
            var area = Math.PI * radius * radius;
            return this.getMeasureText({
                value: area,
                dimension: 2
            });
        },
    };

    /**
     Примесь, обеспечивающая поддержку событий измерения маркера
     */
    L.Measure.Mixin.MarkerEvents = {
        /**
         Метод, обеспечивающий в момент инициализации перехват основных событий редактирования

         Порядок событий в Leaflet.Editable:

         До первого клика
         editable:created
         editable:enable
         editable:drawing:start
         editable:drawing:move

         1-й клик  и последующие клики
         editable:created
         editable:drawing:mousedown
         editable:drawing:click
         editable:drawing:clicked
         editable:drawing:commit
         editable:drawing:end
         Перетаскивание вершины:

         editable:editing
         editable:dragstart
         editable:drag
         editable:dragend
         */
        setEvents: function (map, options) {
            this.editableEventTree = {
                drawing: {
                    move: this._setMove,
                    commit: this._setCommit,
                },
                drag: this._setDrag,
                dragstart: this._setDragStart,
                dragend: this._setDragend
            };
        },

        _setMove: function (e) {
            if (this.isDragging && this.measureLayer.getTooltip() && this.measureLayer.isTooltipOpen()) {
                this.measureLayer.closeTooltip();
            }

            var text = this.isDragging ? this.popupText.drag : this.popupText.move;
            var labelContent = this._getLabelContent(e.layer, e.latlng).trim();
            if (labelContent.length > 0) {
                text += '<br>' + labelContent;
            }

            this._onMouseMove(e, text);
            this._fireEvent(e, 'move');
        },

        _setDrag: function (e) {
            this._fireEvent(e, 'edit:drag');
        },

        _setDragStart: function (e) {
            if (e.layer.getTooltip()) {
                e.layer.closeTooltip();
            }

            this.isDragging = true;
        },

        _setDragend: function (e) {
            this._closePopup();
            if (this.measureLayer.getTooltip() && !this.measureLayer.isTooltipOpen()) {
                this.measureLayer.openTooltip();
            }

            this.isDragging = false;
            this._fireEvent(e, 'editend');
            e.layer.openTooltip();
        },

        _setCommit: function (e) {
            this._closePopup();
            this._fireEvent(e, 'created');
        },
    },

    /**
     Класс, обеспечивающая поддержку основных cобытий редактирования маркера
     */
        L.Measure.Marker = L.Class.extend({
            includes: [L.Measure.Mixin, L.Measure.Mixin.Marker, L.Measure.Mixin.MarkerEvents],

            popupText: {
                move: 'Haga clic en el mapa para arreglar el marcador.',
                drag: 'Suelte el botón del mouse para bloquear el marcador.'
            },

            /**
             Инициализация режима перемщения маркера Marker
             */
            startMeasure: function (options) {
                this._setMouseMarker();
                var imagePath = L.Measure.imagePath;
                this.options = {
                    icon: L.icon({
                        iconUrl: imagePath + '/marker-icon.png',
                        iconRetinaUrl: imagePath + '/marker-icon-2x.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowUrl: imagePath + '/marker-shadow.png',
                        shadowSize: [41, 41]
                    })
                };

                options = options ? L.setOptions(this, options) : this.options;
                this.measureLayer = this._map.editTools.startMarker(undefined, options);
                this.eventsOn('editable:', this.editableEventTree, true);
                this.isDragging = false;
            }
        });

    /**
     Примесь, обеспечивающая поддержку событий измерения круга и прямоугольника
     */
    L.Measure.Mixin.CircleRectangleEvents = {
        /**
         Метод, обеспечивающий в момент инициализации перехват основных событий редактирования

         Порядок событий в Leaflet.Editable:
         До первого клика
         editable:enable
         editable:drawing:start
         editable:drawing:move
         1-й клик
         editable:drawing:mousedown
         editable:drawing:commit
         editable:drawing:end
         Перемещение, изменение размера круга
         editable:vertex:dragstart
         editable:drawing:move
         editable:vertex:drag
         editable:editing
         Отпуск клавиши
         editable:vertex:dragendmeasureLayer
         */
        setEvents: function (map, options) {
            this.editableEventTree = {
                drawing: {
                    move: this._setMove,
                    end: this._setDrawingEnd
                },
                vertex: {
                    dragstart: this._setDragstart,
                    drag: this._setDrag,
                    dragend: this._setDragend
                }
            };
        },

        _setMove: function (e) {
            if (!this.create && !this.isDragging) {
                var text = this.popupText.move;
                var labelContent = this._getLabelContent(e.layer, e.latlng).trim();
                if (labelContent.length > 0) {
                    text += '<br>' + labelContent;
                }

                this._onMouseMove(e, text);
                this._fireEvent(e, 'move');
            }
        },

        _setDrawingEnd: function (e) {
            this.create = true;
            this._fireEvent(e, 'create');
        },

        _setDragstart: function (e) {
            if (e.vertex.getTooltip()) {
                e.vertex.closeTooltip();
            }

            this.isDragging = true;
        },

        _setDragend: function (e) {
            this._closePopup();
            if (this.create) {
                this._fireEvent(e, 'created');
                this.create = false;
            } else {
                this._fireEvent(e, 'editend');
            }

            this.create = false;
            this.isDragging = false;
            e.vertex.openTooltip();
        },

        _setDrag: function (e) {
            var text = this.popupText.drag;
            var labelContent = this._getLabelContent(e.layer, e.latlng).trim();
            if (labelContent.length > 0) {
                text += '<br>' + labelContent;
            }

            this._onMouseMove(e, text);
            if (this.create) {
                this._fireEvent(e, 'create:drag');
            } else {
                this._fireEvent(e, 'edit:drag');
            }

        },
    };

    /**
     Класс, обеспечивающий поддержку основных cобытий редактирования круга
     */
    L.Measure.Circle = L.Class.extend({
        includes: [L.Measure.Mixin, L.Measure.Mixin.Marker, , L.Measure.Mixin.Path, L.Measure.Mixin.CircleMarker, L.Measure.Mixin.Circle, L.Measure.Mixin.CircleRectangleEvents],

        popupText: {
            move: 'Mantenga presionado el botón del mouse y mueva el cursor para dibujar un círculo',
            drag: 'Suelte el botón del mouse para bloquear el círculo.'
        },

        options: {
            stroke: true,
            color: 'green',
            weight: 2,
            opacity: 0.5,
            fill: true,
        },

        startMeasure: function (options) {
            this._setMouseMarker();
            options = options || this.options;
            this.measureLayer = this._map.editTools.startCircle(undefined, options);
            this.measureLayer.setRadius(-1);
            this.eventsOn('editable:', this.editableEventTree, true);
            this.create = false;
            this.isDragging = false;
        }
    });

    /**
     Класс, обеспечивающий поддержку основных cобытий редактирования прямоугольника
     */
    L.Measure.Rectangle = L.Class.extend({
        includes: [L.Measure.Mixin,
            L.Measure.Mixin.Marker, L.Measure.Mixin.Path, L.Measure.Mixin.Polyline, L.Measure.Mixin.Polygon, L.Measure.Mixin.Rectangle,
            L.Measure.Mixin.CircleRectangleEvents
        ],

        popupText: {
            move: 'Mantenga presionado el botón del mouse y mueva el cursor para dibujar un rectángulo',
            drag: 'Suelte el botón del mouse para arreglar el rectángulo..'
        },

        options: {
            stroke: true,
            color: 'green',
            weight: 2,
            opacity: 0.5,
            fill: true,
        },

        startMeasure: function (options) {
            this._setMouseMarker();
            options = options ? L.setOptions(this, options) : this.options;
            this.measureLayer = this._map.editTools.startRectangle(undefined, options);
            this.eventsOn('editable:', this.editableEventTree, true);
            this.create = false;
            this.isDrawing = false;
        }
    });

    /**
     Примесь, обеспечивающая поддержку событий измерения ломаной и многоугольника
     */
    L.Measure.Mixin.PolylinePolygonEvents = {
        /**
         Метод, обеспечивающий в момент инициализации перехват основных событий редактирования

         Порядок событий в Leaflet.Editable:
         До первого клика
         editable:enable
         editable:shape:new
         editable:drawing:start
         editable:drawing:move
         1-й клик и последующие клики
         editable:drawing:mousedown
         editable:drawing:click
         editable:editing
         editable:drawing:clicked
         Commit:
         editable:vertex:mousedown
         editable:vertex:click
         editable:vertex:clicked
         editable:drawing:commit
         editable:drawing:end
         Перетаскивание вершины:
         editable:vertex:dragstart
         editable:drawing:move
         editable:vertex:dragend
         Удаление вершины:
         editable:vertex:click
         editable:vertex:rawclick_closePopup
         editable:vertex:deleted
         editable:vertex:clicked
         Перетаскивание срединного маркера
         editable:middlemarker:mousedown
         editable:vertex:dragstart
         editable:drawing:move
         editable:vertex:dragend
         */
        setEvents: function (map, options) {
            this.editableEventTree = {
                vertex: {
                    dragstart: this._setDragStart,
                    drag: this._setDrag,
                    dragend: this._setDragEnd,
                    deleted: this.setVertexDeleted
                },
                drawing: {
                    move: this._setMove,
                    clicked: this._setClicked,
                    commit: this._setCommit,
                    mousedown: this._setMouseDown,
                    end: this.disable
                }
            };
        },

        _setMove: function (e) {
            var text;
            var nPoints = this.numberOfVertices(e.layer);
            if (nPoints == 0) {
                text = this.popupText.move;
                this._fireEvent(e, 'move');
            } else {
                if (!this.isDragging) {
                    text = this.popupText.add;
                    var labelContent = this._getLabelContent(e.layer, e.latlng, e).trim();
                    if (labelContent.length > 0) {
                        text += '<br>' + labelContent;
                        this._fireEvent(e, 'create:drag');
                    }
                }
            }

            this._onMouseMove(e, text);
        },

        _setDragStart: function (e) {
            if (e.vertex.getTooltip()) {
                e.vertex.closeTooltip();
            }

            this.measureLayer = e.layer;
            this.isDragging = true;
        },
        _setDragEnd: function (e) {
            this._closePopup();
            this._fireEvent(e, 'editend');
            this.isDragging = false;
            if (e.vertex.getTooltip()) {
                e.vertex.openTooltip();
            }
        },

        _setDrag: function (e) {
            var text = this.popupText.drag;
            var labelContent = this._getLabelContent(e.layer, e.vertex.latlng).trim();
            if (labelContent.length > 0) {
                text += '<br>' + labelContent;
            }

            this._onMouseMove(e, text);
            this._fireEvent(e, 'edit:drag');
        },

        setVertexDeleted: function (e) {
            this.vertexDeleted = true;
            this._fireEvent(e, 'edit');
            this._fireEvent(e, 'editend');
            this.vertexDeleted = false;
        },

        _setMouseDown: function (e) {
            if (this.numberOfVertices(e.layer) <= 1) {
                return;
            }

            var text = this.popupText.commit;
            var latlng = e.latlng ? e.latlng : e.vertex.latlng;
            this._showPopup(text, latlng);
        },

        _setClicked: function (e) {
            this._fireEvent(e, 'create');
            if (this.numberOfVertices(e.layer) <= 2) {
                return;
            }

            var text = this.popupText.commit;
            var latlng = e.latlng ? e.latlng : e.vertex.latlng;
            this._showPopup(text, latlng);
        },

        _setCommit: function (e) {
            this._closePopup();
            this._fireEvent(e, 'created');
        },
    };

    /**
     Класс, обеспечивающий поддержку основных cобытий редактирования ломаной
     */
    L.Measure.Polyline = L.Class.extend({
        includes: [L.Measure.Mixin, L.Measure.Mixin.Marker, L.Measure.Mixin.Path, L.Measure.Mixin.Polyline, L.Measure.Mixin.PolylinePolygonEvents],

        popupText: {
            move: 'Haga clic en el mapa para agregar un vértice inicial.',
            add: 'Haga clic en el mapa para agregar un nuevo vértice.',
            commit: 'Haga clic en el vértice actual para arreglar la línea.',
            drag: 'Suelta el cursor para arreglar la línea.'
        },

        options: {
            stroke: true,
            color: 'green',
            weight: 2,
            opacity: 0.5,
            fill: false,
        },

        startMeasure: function (options) {
            this._setMouseMarker();
            options = options ? L.setOptions(this, options) : this.options;
            this.measureLayer = this._map.editTools.startPolyline(undefined, options);
            this.eventsOn('editable:', this.editableEventTree, true);
            this.isDragging = false;
        }
    });

    /**
     Класс, обеспечивающий поддержку основных cобытий редактирования многоугольника
     */
    L.Measure.Polygon = L.Class.extend({
        includes: [L.Measure.Mixin, L.Measure.Mixin.Marker, L.Measure.Mixin.Path, L.Measure.Mixin.Polyline, L.Measure.Mixin.Polygon, L.Measure.Mixin.PolylinePolygonEvents],

        popupText: {
            move: 'Haga clic en el mapa para agregar un vértice inicial.',
            add: 'Haga clic en el mapa para agregar un nuevo vértice.',
            commit: 'Haga clic en el vértice actual para arreglar el polígono.',
            drag: 'Suelta el cursor para arreglar el polígono.'
        },

        options: {
            stroke: true,
            color: 'green',
            weight: 2,
            opacity: 0.5,
            fill: true,
        },


        startMeasure: function (options) {
            this._setMouseMarker();
            options = options ? L.setOptions(this, options) : this.options;
            this.measureLayer = this._map.editTools.startPolygon(undefined, options);
            this.isDragging = false;
            this.eventsOn('editable:', this.editableEventTree, true);
        }
    });


    /**
     Фабричный метод для создания экземпляра инструмента измерения маркера.
     */
    L.Measure.marker = function (map, options) {
        return new L.Measure.Marker(map, options);
    };

    /**
     Фабричный метод для создания экземпляра инструмента измерения прямоугольника.
     */
    L.Measure.rectangle = function (map, options) {
        return new L.Measure.Rectangle(map, options);
    };

    /**
     Фабричный метод для создания экземпляра инструмента измерения круга.
     */
    L.Measure.circle = function (map, options) {
        return new L.Measure.Circle(map, options);
    };

    /**
     Фабричный метод для создания экземпляра инструмента измерения ломаной.
     */
    L.Measure.polyline = function (map, options) {
        return new L.Measure.Polyline(map, options);
    };

    /**
     Фабричный метод для создания экземпляра инструмента измерения многоугольника.
     */
    L.Measure.polygon = function (map, options) {
        return new L.Measure.Polygon(map, options);
    };

    /*
     Метод при наличии опции basemeasured добавляет к карте свойство measureTools с инициализированными свойстами:
     markerTool
     circleTool
     rectangleTool
     polylineTool
     polygonTool
     */
    L.Map.addInitHook(function () {
        this.whenReady(function () {
            if (this.options.measured) {
                this.measureTools = new L.Measure(this, this.options.measureOptions);
            }
        });
    });

    L.MeasureBase = L.Measure.extend({
        initialize: function (map, options) {
            L.Measure.prototype.initialize.call(this, map, options);
            this.markerBaseTool = L.Measure.markerBase(map, options);
            this.circleBaseTool = L.Measure.circleBase(map, options);
            this.rectangleBaseTool = L.Measure.rectangleBase(map, options);
            this.polylineBaseTool = L.Measure.polylineBase(map, options);
            this.polygonBaseTool = L.Measure.polygonBase(map, options);
        },

        stopMeasuring: function () {
            L.Measure.prototype.stopMeasuring.call(this);

            this.markerBaseTool.stopMeasure();
            this.circleBaseTool.stopMeasure();
            this.rectangleBaseTool.stopMeasure();
            this.polylineBaseTool.stopMeasure();
            this.polygonBaseTool.stopMeasure();
        }
    });

    /*
     Фабричный метод для создания базового экземпляра.
     */
    L.measureBase = function (map, options) {
        return new L.MeasureBase(map, options);
    };

    /**
     Класс инструмента для измерения координат.
     */
    L.Measure.MarkerBase = L.Measure.Marker.extend({

        basePopupText: {
            labelPrefix: '<b>',
            labelPostfix: '</b>',
            captions: {
                northLatitude: ' N. ',
                southLatitude: ' S. ',
                eastLongitude: ' E. ',
                westLongitude: ' O. ',
                x: 'X: ',
                y: 'Y: '
            }
        },

        /**
         Количество знаков после десятичного разделителя для измерений в метрах.
         */
        precision: 5,

        /*
         Метод для получения маркеров инструмента редактирования, имеющих метки
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив помеченных маркеров инструмента редактирования.
         */
        _labelledMarkers: function (editor, e) {
            return [];
        },

        /*
         Метод для получения маркеров инструмента редактирования, не имеющих меток
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив не помеченных маркеров инструмента редактирования.
         */
        _unlabelledMarkers: function (editor, e) {
            return [];
        },

        /**
         Метод для получения текстового описания результатов измерений.
         */
        _getLabelContent: function (layer, latlng, e) {
            var crs = this.options.crs;
            var precision = this.options.precision || this.precision;
            var captions = this.options.captions || this.basePopupText.captions;
            var displayCoordinates = this.options.displayCoordinates || false;

            latlng = latlng || layer.getLatLng();
            var fixedLatLng = this.getFixedLatLng(latlng);

            if (crs) {
                var point = crs.project(fixedLatLng);
                if (point) {
                    if (displayCoordinates) {
                        return captions.x + point.x.toFixed(precision) + ' ' +
                        captions.y + point.y.toFixed(precision);
                    }

                    return Math.abs(point.y).toFixed(precision) + (point.y >= 0 ? captions.northLatitude : captions.southLatitude) +
                    Math.abs(point.x).toFixed(precision) + (point.x >= 0 ? captions.eastLongitude : captions.westLongitude);
                }
            }

            return Math.abs(fixedLatLng.lat).toFixed(precision) + (fixedLatLng.lat >= 0 ? captions.northLatitude : captions.southLatitude) +
            Math.abs(fixedLatLng.lng).toFixed(precision) + (fixedLatLng.lng >= 0 ? captions.eastLongitude : captions.westLongitude);
        },

        /**
         Метод обновления основного лейбла измеряемого объекта
         @param {Object} layer Редактируемый слой.
         */
        _updateMeasureLabel: function (layer, e) {
            if (this._getMeasureEventType(e).substr(-5) !== ':drag') {
                var text = this.basePopupText.labelPrefix + this._getLabelContent(layer, e.latlng, e) + this.basePopupText.labelPostfix;
                this._showLabel(layer, text);
            }
        },

    });

    /**
     Фабричный метод для создания экземпляра инструмента измерения координат.
     */
    L.Measure.markerBase = function (map, options) {
        return new L.Measure.MarkerBase(map, options);
    };


    /**
     Класс инструмента для измерения радиуса.
     */
    L.Measure.CircleBase = L.Measure.Circle.extend({

        basePopupText: {
            labelPrefix: '<b>Radio: ',
            labelPostfix: '</b>',
        },
        /*
         Метод для получения маркеров инструмента редактирования, имеющих метки
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив помеченных маркеров инструмента редактирования.
         */
        _labelledMarkers: function (editor, e) {
            var latlngs = editor.getLatLngs();
            var markers = [];
            switch (this._getMeasureEventType(e)) {
                case 'measure:create:drag':
                case 'measure:edit:drag':
                    break;
                default:
                    markers.push(latlngs[1].__vertex)
            }

            return markers;
        },

        /*
         Метод для получения маркеров инструмента редактирования, не имеющих меток
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив не помеченных маркеров инструмента редактирования.
         */
        _unlabelledMarkers: function (editor, e) {
            var latlngs = editor.getLatLngs();
            var markers = [];
            markers.push(latlngs[0].__vertex)
            switch (this._getMeasureEventType(e)) {
                case 'measure:create:drag':
                case 'measure:edit:drag':
                    markers.push(latlngs[1].__vertex)
                    break;
            }

            return markers;
        },


        /**
         Метод для получения текстового описания результатов измерений.
         @param {Object} layer Слой с геометрией, представляющей производимые измерения.
         @param {Object} latlng Точка геометрии, для которой требуется получить текстовое описание измерений.
         */
        _getLabelContent: function (layer, latlng, e) {
            var radiusText = this.getRadiusText(layer);
            var ret = radiusText.length > 0 ? this.basePopupText.labelPrefix + radiusText + this.basePopupText.labelPostfix : '';
            return ret;
        },

    });

    /**
     Фабричный метод для создания экземпляра инструмента измерения радиуса.
     */
    L.Measure.circleBase = function (map, options) {
        return new L.Measure.CircleBase(map, options);
    };

    /**
     Класс инструмента для измерения площади прямоугольника.
     */
    L.Measure.RectangleBase = L.Measure.Rectangle.extend({

        /*
         Метод для получения маркеров инструмента редактирования, имеющих метки
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив помеченных маркеров инструмента редактирования.
         */
        _labelledMarkers: function (editor) {
            var latlngs = editor.getLatLngs()[0];
            var markers = [];
            return markers;
        },

        /*
         Метод для получения маркеров инструмента редактирования, не имеющих меток
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив не помеченных маркеров инструмента редактирования.
         */
        _unlabelledMarkers: function (editor) {
            var latlngs = editor.getLatLngs()[0];
            var markers = [];
            for (var i = 0, len = latlngs.length; i < len; i++) {
                markers.push(latlngs[i].__vertex);
            }
            return markers;
        },

        /**
         Метод для получения текстового описания результатов измерений.
         @param {Object} layer Слой с геометрией, представляющей производимые измерения.
         @param {Object} latlng Точка геометрии, для которой требуется получить текстовое описание измерений.
         */
        _getLabelContent: function (layer, latlng) {
            return '';
        },

        /**
         Метод обновления основного лейбла измеряемого объекта
         @param {Object} layer Редактируемый слой.
         */
        _updateMeasureLabel: function (layer, e) {
            var center = layer.getCenter();
            //       var latlngs = layer.editor.getLatLngs()[0];
            var areaText = 'Área: ' + this.getAreaText(layer);
            areaText = '<b>' + areaText + '</b>';
            this._showLabel(layer, areaText, center);
        },

    });

    /**
     *   Фабричный метод для создания экземпляра инструмента измерения площади прямоугольника.
     */
    L.Measure.rectangleBase = function (map, options) {
        return new L.Measure.RectangleBase(map, options);
    };


    /**
     * Класс инструмента для измерения длины.
     */
    L.Measure.PolylineBase = L.Measure.Polyline.extend({

        basePopupText: {
            distanceLabelPrefix: '<b>',
            distanceLabelPostfix: '</b>',
            incLabelPrefix: '<br/><span class="measure-path-label-incdistance">+',
            incLabelPostfix: '</span></b>',
        },

        /*
         Метод для получения маркеров инструмента редактирования, имеющих метки
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив помеченных маркеров инструмента редактирования.
         */
        _labelledMarkers: function (editor, e) {
            var latlngs = editor.getLatLngs();
            var markers = [];
            var marker;
            switch (this._getMeasureEventType(e)) {
                case 'measure:create:drag':
                case 'measure:edit:drag':
                    marker = e.vertex;
                    break;
            }

            for (var i = 1, len = latlngs.length; i < len; i++) {
                var pathVertex = latlngs[i].__vertex;
                if (pathVertex !== marker) {
                    markers.push(pathVertex);
                }
            }

            return markers;
        },

        /*
         Метод для получения маркеров инструмента редактирования, не имеющих меток.
         @param {Object} editor Инструмент редактирования.
         @returns {Object[]} Массив не помеченных маркеров инструмента редактирования.
         */
        _unlabelledMarkers: function (editor, e) {
            var latlngs = editor.getLatLngs();
            var markers = [];
            markers.push(latlngs[0].__vertex);
            switch (this._getMeasureEventType(e)) {
                case 'measure:create:drag':
                case 'measure:edit:drag':
                    if (e.vertex) {
                        markers.push(e.vertex);
                    }

                    break;
            }

            return markers;
        },

        /**
         Метод для получения текстового описания результатов измерений.
         @param {Object} layer Слой с геометрией, представляющей производимые измерения.
         @param {Object} latlng Точка геометрии, для которой требуется получить текстовое описание измерений.
         @param {Object} e Аргументы метода.
         */
        _getLabelContent: function (layer, latlng, e) {
            var latlngs = layer.editor.getLatLngs().slice();
            for (var index = 0; index < latlngs.length && !latlngs[index].equals(latlng); index++);

            if (index === latlngs.length) {
                latlngs.push(latlng);
            }

            if (index === 0) {
                return '';
            }

            var distance = 0;
            var currentInc = 0;
            for (var i = 1; i <= index; i++) {
                var prevLatLng = latlngs[i - 1];
                var currentLatLng = latlngs[i];
                currentInc = this.getDistance({
                    latlng1: prevLatLng,
                    latlng2: currentLatLng
                });
                distance += currentInc;
            }

            return this.basePopupText.distanceLabelPrefix +
            this.getMeasureText({
                value: distance,
                dimension: 1
            }) +
            this.basePopupText.distanceLabelPostfix +
            this.basePopupText.incLabelPrefix +
            this.getMeasureText({
                value: currentInc,
                dimension: 1
            }) +
            this.basePopupText.incLabelPostfix;
        },
    });

    /**
     Фабричный метод для создания экземпляра инструмента измерения длины.
     */
    L.Measure.polylineBase = function (map, options) {
        return new L.Measure.PolylineBase(map, options);
    };

    /**
     Класс инструмента для измерения площади.
     */
    L.Measure.PolygonBase = L.Measure.Polygon.extend({

        basePopupText: {
            labelPrefix: '<b>Área: ',
            labelPostfix: '</b>',
        },

        /*
         Метод для получения маркеров инструмента редактирования, имеющих метки
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив помеченных маркеров инструмента редактирования.
         */
        _labelledMarkers: function (editor, e) {
            var latlngs = editor.getLatLngs()[0];
            var markers = [];
            var marker;
            switch (this._getMeasureEventType(e)) {
                case 'measure:create:drag':
                case 'measure:edit:drag':
                    break;
                case 'measure:created':
                    marker = latlngs[latlngs.length - 1].__vertex;
                    break;
                default:
                    marker = e.vertex ? e.vertex : latlngs[latlngs.length - 1].__vertex;
            }

            if (marker) {
                markers.push(marker);
            }

            return markers;
        },

        /*
         Метод для получения маркеров инструмента редактирования, не имеющих меток
         @param {Object} editor Инструмент редактирования
         @returns {Object[]} Массив не помеченных маркеров инструмента редактирования.
         */
        _unlabelledMarkers: function (editor, e) {
            var latlngs = editor.getLatLngs()[0];
            var markers = [];
            var marker;
            switch (this._getMeasureEventType(e)) {
                case 'measure:create:drag':
                case 'measure:edit:drag':
                    break;
                case 'measure:created':
                    marker = latlngs[latlngs.length - 1].__vertex;
                    break;
                default:
                    marker = e.vertex ? e.vertex : latlngs[latlngs.length - 1].__vertex;
            }

            for (var i = 0, len = latlngs.length; i < len; i++) {
                var pathVertex = latlngs[i].__vertex;
                if (pathVertex !== marker) {
                    markers.push(pathVertex);
                }
            }

            return markers;
        },

        /**
         Метод для получения текстового описания результатов измерений.
         @param {Object} layer Слой с геометрией, представляющей производимые измерения.
         @param {Object} latlng Точка геометрии, для которой требуется получить текстовое описание измерений.
         @param {Object} e Аргументы метода.
         @returns {String} Содержимое метки
         */
        _getLabelContent: function (layer, latlng, e) {
            var latlngs = layer.editor.getLatLngs()[0].slice();
            var mouseLatlng;

            // Non drag.
            if (e && !e.vertex) {
                eventLatlng = e.latlng;
                for (var index = 0; index < latlngs.length && !latlngs[index].equals(eventLatlng); index++);
                if (index === latlngs.length) {
                    mouseLatlng = eventLatlng;
                }
            }

            var ret = this.basePopupText.labelPrefix + this.getAreaText(layer, mouseLatlng) + this.basePopupText.labelPostfix;

            return ret;
        },
    });

    /**
     Фабричный метод для создания экземпляра инструмента измерения площади.
     */
    L.Measure.polygonBase = function (map, options) {
        return new L.Measure.PolygonBase(map, options);
    };

    /*
     Метод при наличии опции basemeasured добавляет к карте свойство measureTools с инициализированными свойстами:
     markerBaseTool
     circleBaseTool
     rectangleBaseTool
     polylineBaseTool
     polygonBaseTool
     markerTool
     circleTool
     rectangleTool
     polylineTool
     polygonTool
     */
    L.Map.addInitHook(function () {
        this.whenReady(function () {
            if (this.options.basemeasured) {
                this.measureTools = new L.MeasureBase(this, this.options.measureOptions);
            }
        });
    });

})(L);