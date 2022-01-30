
<canvas id="eco-line-chart" style="width: 100%; height:640px;"></canvas>

<script>
    (function () {
        const timeFormat = 'YYYY-MM-DD';

        let config = {
            type: 'line',
            data: {!! json_encode($chartData, JSON_PRETTY_PRINT) !!},
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: "Historique des ressources pour {{ Str::ucfirst($resourceName) }}"
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
                            labelString: '{{ $resourceName }}'
                        }
                    }]
                }
            }
        };

        window.onload = function () {
            let ctx = document.getElementById("eco-line-chart").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };

    })();

</script>

<div class="clearfix"></div>
