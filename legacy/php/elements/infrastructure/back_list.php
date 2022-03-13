<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

if(isset($data['infrastructurable'])) {
    $infrastructurable = $data['infrastructurable'];
}
?>

    <?php if(Gate::check('manageInfrastructure', $infrastructurable)): ?>
    <div class="cta-title pull-right-cta">
        <a href="<?= route('infrastructure.select-group',
            $infrastructurable->selectGroupRouteParameter()) ?>"
           class="btn btn-primary btn-cta">
            <i class="icon-plus icon-white"></i> Ajouter une infrastructure</a>
    </div>
    <?php endif; ?>

    <section>
        <div id="infrastructures" class="titre-vert anchor">
          <h1>Infrastructures</h1>
        </div>

        <div class="alert alert-tips">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Les infrastructures sont des éléments bâtis ou du roleplay qui permettent d'influencer l'économie.
            <a href="http://vasel.yt/wiki/index.php?title=GO/Infrastructures"
               class="guide-link">En savoir plus sur les infrastructures ? GO!</a>
        </div>

        <?php if(count($infrastructurable->infrastructuresAll)): ?>

        <table class="table table-hover">

        <thead>
          <tr class="tablehead">
            <th width="5%" scope="col">
                <a href="#" rel="clickover" title="Statut de votre infrastructure"
                   data-content="L'infrastructure est modérée par les juges tempérants et
                    peut-être refusée"><i class="icon-globe"></i></a></th>
            <th colspan="2" width="60%" scope="col">Nom</th>
            <th width="23%" scope="col">Date</th>
            <th width="4%" scope="col">&nbsp;</th>
            <th width="4%" scope="col">&nbsp;</th>
            <th width="4%" scope="col">&nbsp;</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach($infrastructurable->infrastructuresAll as $infrastructure): ?>
          <tr>
            <td><img src="<?= url('assets/img/statutinfra_' .
                    $infrastructure->ch_inf_statut) ?>.png" alt="Statut"></td>
            <td><img src="<?= e($infrastructure->ch_inf_lien_image) ?>"
                     alt="Infrastructure : <?= e($infrastructure->nom_infra) ?>"
                     style="width: 120px;"></td>
            <td><?= e($infrastructure->nom_infra) ?><br>
                <small><?= e($infrastructure->infrastructureOfficielle
                        ->ch_inf_off_nom) ?></small></td>
            <td><?= date('d/m/Y', strtotime($infrastructure->ch_inf_date)) ?></td>
            <td>
                <a class="btn modal-fullscreen"
                   data-toggle="modal" data-target="#Modal-Monument"
                   href="<?= url('php/infrastructure-modal.php?ch_inf_id='
                       . e($infrastructure->ch_inf_id)) ?>"
                   title="Voir l'infrastructure"><i class="icon-eye-open"></i></a>
            </td>

            <?php if(Gate::check('manageInfrastructure',
                    $infrastructure->infrastructurable)): ?>
                <td>
                    <a class="btn btn-primary"
                       href="<?= route('infrastructure.edit',
                           ['infrastructure_id' => $infrastructure->ch_inf_id]) ?>"
                       title="Modifier l'infrastructure">
                        <i class="icon-pencil icon-white"></i></a>
                </td>
                <td>
                    <a class="btn btn-danger"
                       data-toggle="modal" data-target="#Modal-Monument"
                       href="<?= route('infrastructure.delete',
                           ['infrastructure_id' => $infrastructure->ch_inf_id]) ?>"
                       title="Supprimer l'infrastructure">
                        <i class="icon-trash icon-white"></i></a>
                </td>
            <?php else: ?>
                <td> </td>
                <td> </td>
            <?php endif; ?>

          </tr>
          <?php endforeach; ?>
        </tbody>

        </table>

        <?php else: ?>

        <div class="well">
            <div class="alert alert-info">
                <i class="icon-remove-sign"></i>
                Pas d'infrastructures pour le moment !
            </div>
        </div>

        <?php endif; ?>

    </section>
