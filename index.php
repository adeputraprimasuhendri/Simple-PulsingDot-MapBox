<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>PulsingDot - MAPBOX</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.css" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <script>
        mapboxgl.accessToken = 'Your Token Here..';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/light-v10',
            zoom: 11,
            center: [106.801853, -6.3036031]
        });

        var size = 100;

        var pulsingDot = {
            width: size,
            height: size,
            data: new Uint8Array(size * size * 4),
            onAdd: function () {
                var canvas = document.createElement('canvas');
                canvas.width = this.width;
                canvas.height = this.height;
                this.context = canvas.getContext('2d');
            },
            render: function () {
                var duration = 1000;
                var t = (performance.now() % duration) / duration;
                var radius = (size / 2) * 0.3;
                var outerRadius = (size / 2) * 0.7 * t + radius;
                var context = this.context;
                context.clearRect(0, 0, this.width, this.height);
                context.beginPath();
                context.arc(
                    this.width / 2,
                    this.height / 2,
                    outerRadius,
                    0,
                    Math.PI * 2
                );
                context.fillStyle = 'rgba(255, 200, 200,' + (1 - t) + ')';
                context.fill();
                context.beginPath();
                context.arc(
                    this.width / 2,
                    this.height / 2,
                    radius,
                    0,
                    Math.PI * 2
                );
                context.fillStyle = 'rgba(255, 100, 100, 1)';
                context.strokeStyle = 'white';
                context.lineWidth = 2 + 4 * (1 - t);
                context.fill();
                context.stroke();
                this.data = context.getImageData(
                    0,
                    0,
                    this.width,
                    this.height
                ).data;
                map.triggerRepaint();
                return true;
            }
        };

        map.on('load', async function () {
            map.addImage('pulsing-dot', pulsingDot, { pixelRatio: 2 });
            map.addSource('points', {
                type: 'geojson',
                data: 'http://localhost/data.php' // Your Geo Data Here..
            });
            map.addLayer({
                'id': 'points',
                'type': 'symbol',
                'source': 'points',
                'layout': {
                    'icon-image': 'pulsing-dot',
                    'text-field': ['get', 'title'],
                    'text-font': ['Open Sans Semibold', 'Arial Unicode MS Bold'],
                    'text-offset': [0, 0.6],
                    'text-anchor': 'top'
                }
            });
        });
    </script>

</body>

</html>