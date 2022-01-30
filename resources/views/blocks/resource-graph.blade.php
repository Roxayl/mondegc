
<div class="pull-right" style="margin-top: -48px; margin-right: -30px;">
    <button class="btn btn-primary" id="{{ $graphId }}-toggle-legend-btn">
        Afficher/masquer la légende
    </button>
</div>
<div class="clearfix"></div>

<canvas id="{{ $graphId }}" style="width: 100%; height:578px;"></canvas>

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
            let ctx = document.getElementById("{{ $graphId }}").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };

        let legendBtn = $

        $(document).on('click', '#{{ $graphId }}-toggle-legend-btn', function(ev) {
            ev.preventDefault();
            try {
                // toggle visibility of legend
                window.myLine.options.legend.display = !window.myLine.options.legend.display;
                window.myLine.update();
            } catch(err) {
                console.error(err);
            }
        });

    })();

</script>

<div class="clearfix"></div>
