<!doctype html>
<html>

<head>
    <title>Line Chart</title>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.js"></script>
    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>

<body>
    <div style="width:75%;">
        <canvas id="canvas"></canvas>
    </div>

    <script>
        (function() {
            const timeFormat = 'YYYY-MM-DD';

            let config = {
                type: 'line',
                data: {!! json_encode($chartData, JSON_PRETTY_PRINT) !!},
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: "Chart.js Time Scale"
                    },
                    scales: {
                        xAxes: [{
                            type: "time",
                            time: {
                                format: timeFormat,
                                tooltipFormat: 'll'
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'value'
                            }
                        }]
                    }
                }
            };

            window.onload = function () {
                let ctx = document.getElementById("canvas").getContext("2d");
                window.myLine = new Chart(ctx, config);
            };

        })();

    </script>

</body>

</html>
