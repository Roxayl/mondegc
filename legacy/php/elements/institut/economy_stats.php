<?php

use Carbon\Carbon;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\View\Components\ResourceHistory\GraphPerResource;
use Illuminate\Support\Str;

?>

    <div class="row-fluid">
        <div class="span10 offset1">
            <div class="row-fluid">
            <?php foreach(config('enums.resources') as $key => $resource): ?>
                <a href="<?= url("economie.php?cat=" . e($resource)) ?>"
                   class="span3 resource-small-inline-block token-<?= $resource ?>"
                   style="margin-right: 0;
                        <?php if($resource === $data['selectedResource']): ?>
                            border: 1px solid <?= getResourceColor($resource) ?>; border-radius: 5px;
                        <?php endif; ?>
                     ">
                    <img src="<?= url("assets/img/ressources/" . e($resource) . ".png") ?>"
                         alt="<?= Str::ucfirst($resource) ?>">
                    <?= e($resource) ?>
                </a>

                <?php if(($key + 1) % 4 === 0): ?>
                    </div>
                    <div class="row-fluid">
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
        </div>
        <div class="span1"></div>
    </div>

    <h3 id="ressources-instantane">Instantané</h3>

    <div class="chart-container">
       <canvas id="eco-chart" style="width: 100%; height:320px;"></canvas>
    </div>

    <ul class="listes">

    <?php
    foreach($data['paysList'] as $key => $pays):
    ?>
        <li class="row-fluid">
            <div class="span6 offset2">
                <strong><?= $key + 1 ?></strong>
                <img src="<?= e($pays['ch_pay_lien_imgdrapeau']) ?>"
                     class="img-menu-drapeau" alt="Drapeau">
                <a href="<?= url("page-pays.php?ch_pay_id={$pays['ch_pay_id']}") ?>">
                    <?= e($pays['ch_pay_nom']) ?>
                </a>
                <?php if(!is_null($pays['alliance'])): ?>
                    <br>
                    <small style="padding-left: 28px;">
                        <img src="<?= e($pays['alliance']->getFlag()) ?>" alt="Drapeau alliance"
                             class="img-menu-drapeau">
                        Membre de
                        <a href="<?= e($pays['alliance']->accessorUrl()) ?>">
                            <?= e($pays['alliance']->name) ?></a>
                    </small>
                <?php endif; ?>
            </div>
            <div class="span4">
                <div class="resource-small-inline-block
                            token-<?= htmlspecialchars($data['selectedResource']) ?>"
                     title="<?= Str::ucfirst($data['selectedResource']) ?>">
                    <img src="<?= url("assets/img/ressources/" .
                                htmlspecialchars($data['selectedResource']) . ".png") ?>"
                         alt="<?= Str::ucfirst($data['selectedResource']) ?>">
                    <?= number_format((float)$pays['resources'][$data['selectedResource']],
                        0, ',', '&#160;') ?>
                </div>
            </div>
        </li>

        <?php if($key + 1 === 15): ?>
            </ul>

            <div class="well pull-center">
                <a href="#" id="stats-display-more" class="btn btn-primary">
                    <i class="icon-chevron-down icon-white"></i>
                    Voir plus de pays</a>
            </div>
            <div id="stats-more-container" style="display: none;">
                <ul class="listes">
        <?php endif; ?>

    <?php
    endforeach;
    ?>
    </ul>

    </div>

    <h3 id="ressources-historique">Historique</h3>

    <div class="chart-container" style="width: 100%; height:588px;">
        <?php
        $resourceables = Pays::visible()->get();
        echo (new GraphPerResource($resourceables, $data['selectedResource'], Carbon::now()->subYear()))
            ->setGraphId('eco-line-chart')
            ->render();
        ?>
    </div>

    <div class="clearfix"></div>

    <script type="text/javascript">

    <?php
    $graph_colors_list = array();
    $graph_color_start = -0.250;
    for($i = 0; $i < count($data['paysList']) + 1; $i++) {
        $graph_colors_list[] = adjustBrightness(getResourceColor($data['selectedResource']),
            $graph_color_start);
        $graph_color_start += 0.016;
    }
    ?>

    (function($, window, Chart, document, undefined) {

        $('#stats-display-more').on('click', function(ev) {
            ev.preventDefault();
            $('#stats-more-container').show();
            $(ev.target).closest('.well').hide();
            return false;
        });

        var chartColors = <?= json_encode($graph_colors_list) ?>;
        var i = 0;

        var getColor = function() {

            var length = chartColors.length;
            i++;
            var returnValue = chartColors[i];
            if(i + 1 >= length)
                i = 0;
            return returnValue;

        };

        var colorArray = [];
        for(var j = 0; j < <?= count($data['paysList']) ?>; j++){
            colorArray.push(getColor());
        }

        var ctx = $("#eco-chart");
        var ecoChart = new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: [{
                    data: <?= json_encode($data['graph_ressources']); ?>,
                    backgroundColor: colorArray,
                    label: "<?= $cat ?>"
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: <?= json_encode(__s($data['graph_country'])); ?>
            },
            options: {
                scales: {
                    xAxes: [{
                        gridLines: {
                            offsetGridLines: true
                        },
                        ticks: {
                            display: false
                        }
                    }]
                },
                legend: {
                    display: false
                }
            }
        });

    })(jQuery, window, Chart, document);

    </script>
