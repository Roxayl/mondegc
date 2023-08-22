
@inject('helperService', 'Roxayl\MondeGC\Services\HelperService')

@section('scripts')
    @parent

    @if($organisation->membersGenerateResources())
    <script type="text/javascript">
        (function($) {
            let $resourceTooltip = $('#org-country-resource-tooltip');
            if($resourceTooltip.length) {
                $resourceTooltip.tooltip();
            }
        })(jQuery);
    </script>
    @endif
@endsection

@if($organisation->hasEconomy())

    @can('manageInfrastructure', $organisation)
    <div class="cta-title pull-right-cta">
        <a href="{{ route('organisation.edit',
            ['organisation' => $organisation->id]) . '#infrastructures' }}"
           class="btn btn-primary btn-cta">
            <i class="icon-white icon-adjust"></i>
            Gérer les infrastructures</a>
    </div>
    @endcan

    <div id="economie" class="titre-vert anchor">
        <h1>Économie</h1>
    </div>

    @php
    $resources = $organisation->resources();
    @endphp
    {!! \Roxayl\MondeGC\Services\HelperService::renderLegacyElement('temperance/resources', [
        'resources' => $resources
    ]) !!}
    <div class="clearfix"></div>

    <div class="well">

        <!-- Balance économique -->
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" href="#economie-pays">
                Détail des ressources
            </a>
          </div>
          <div id="economie-pays" class="accordion-body collapse">
            <div class="accordion-inner">
                
            <div class="pull-right">
                <a href="http://vasel.yt/wiki/index.php?title=GO/Organisations_internationales#Types_d.27organisations"
                   class="guide-link">Comment sont calculées les ressources ? GO!</a>
            </div>

            <h4>Ressources issues de l'organisation</h4>
            <div>
                <img src="{{ $organisation->flag }}"
                     class="img-menu-drapeau"
                     alt="{{ $organisation->name }}">
                <a href="{{ route('organisation.showslug', $organisation->showRouteParameter()) }}">
                    {{ $organisation->name }}</a>
                {!! $helperService::renderLegacyElement(
                    'temperance/resources_small', [
                        'resources' => $organisation->organisationResources()
                    ]) !!}
            </div>

            <div style="margin-top: 5px;">
                <small>dont issues des infrastructures :</small>
                {!! $helperService::renderLegacyElement(
                    'temperance/resources_small', [
                        'resources' => $organisation->infrastructureResources()
                    ]) !!}
            </div>

            <div style="margin-top: 5px;">
                <small>dont issues du roleplay :</small>
                {!! $helperService::renderLegacyElement(
                    'temperance/resources_small', [
                        'resources' => $organisation->roleplayResources()
                    ]) !!}
            </div>

            <div style="margin-top: 5px;">
                <small>Ressources octroyées à chaque pays membre :</small>
                {!! $helperService::renderLegacyElement(
                    'temperance/resources_small', [
                        'resources' => $organisation->perCountryResources()
                    ]) !!}
            </div>

            @if($organisation->membersGenerateResources())
                <h4>
                    Ressources générées par les pays membres
                    <small>
                        <i class="icon-info-sign" rel="tooltip" id="org-country-resource-tooltip"
                           data-original-title="Ces données totalisent les ressources générées directement par les pays
                            sans l'influence générée par les organisations dont elles sont membres. Elles sont
                            additionnées pour obtenir les ressources de l'organisation."
                        ></i>
                    </small>
                </h4>

                <div style="margin-bottom: 7px;">
                    <small>Total pays membres :</small>
                    {!! $helperService::renderLegacyElement(
                        'temperance/resources_small', [
                            'resources' => $organisation->paysResources()
                        ]) !!}
                </div>

                @foreach($organisation->members as $thisMember)
                    @php $thisPays = $thisMember->pays; @endphp
                    <div>
                        <img src="{{ $thisPays->ch_pay_lien_imgdrapeau }}"
                             class="img-menu-drapeau"
                             alt="{{ $thisPays->ch_pay_nom }}">
                        <a href="{{ url('page-pays.php?ch_pay_id=' . $thisPays->ch_pay_id) }}">
                            {{ $thisPays->ch_pay_nom }}</a>
                        {!! $helperService::renderLegacyElement(
                            'temperance/resources_small', [
                                'resources' => $thisPays->withoutAllianceResources()
                            ]) !!}
                    </div>
                @endforeach
            @endif

            </div>
          </div>
        </div>

        <!-- Infras -->
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse"
               href="#economie-infrastructures">
                Infrastructures de l'organisation
                @if($organisation->infrastructures->count())
                    <span class="badge badge-info">
                        {{ $organisation->infrastructures->count() }}
                    </span>
                @endif
            </a>
          </div>
          <div id="economie-infrastructures" class="accordion-body collapse">
            <div class="accordion-inner">
            @foreach($organisation->infrastructures as $infrastructure)
                {!! $helperService::renderLegacyElement('infrastructure/well', [
                    'id' => $infrastructure->ch_inf_id,
                    'type' => 'infra',
                    'overlay_image' => $infrastructure
                        ->infrastructureOfficielle->ch_inf_off_icone,
                    'overlay_text' => $infrastructure
                        ->infrastructureOfficielle->ch_inf_off_nom,
                    'image' => $infrastructure->ch_inf_lien_image,
                    'nom' => $infrastructure->nom_infra,
                    'description' => $infrastructure->wellDescription(),
                    'unescape_description' => true,
                ]); !!}
            @endforeach
            </div>
          </div>
        </div>

    </div> <!-- end .well -->

    <div class="modal container fade" id="Modal-Monument"></div>

    <div class="clearfix"></div>

@endif
