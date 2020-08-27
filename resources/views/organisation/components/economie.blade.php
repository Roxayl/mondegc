
@if($organisation->allow_temperance)

    @can('manageInfrastructure', $organisation)
    <div class="cta-title pull-right-cta" style="margin-top: 30px;">
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
    $temperance = $organisation->temperance()->get()->first()->toArray();
    @endphp
    {!! \App\Services\HelperService::renderLegacyElement('temperance/resources', [
        'resources' => $temperance
    ]) !!}
    <div class="clearfix"></div>

    <div class="well">

        <!-- Balance économique -->
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" href="#economie-pays">
                Balance économique par pays membre
            </a>
          </div>
          <div id="economie-pays" class="accordion-body collapse">
            <div class="accordion-inner">

            @php
            $temperancePays = $organisation->membersWithTemperance()->toArray();
            @endphp
            @foreach($temperancePays as $thisPays)
                @php
                    $paysResources = $thisPays['temperance'][0];
                @endphp
                <div>
                    <img src="{{ $thisPays['pays']['ch_pay_lien_imgdrapeau'] }}"
                         class="img-menu-drapeau"
                         alt="{{ $thisPays['pays']['ch_pay_nom'] }}">
                    <a href="{{ url('page-pays.php?ch_pay_id=' . $thisPays['pays_id']) }}">
                        {{ $thisPays['pays']['ch_pay_nom'] }}</a>
                    {!! $helperService::renderLegacyElement(
                        'temperance/resources_small', [
                            'resources' => $paysResources
                        ]) !!}
                </div>
            @endforeach

            </div>
          </div>
        </div>

        <!-- Infras -->
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse"
               href="#economie-infrastructures">
                Infrastructures
            </a>
          </div>
          <div id="economie-infrastructures" class="accordion-body collapse">
            <div class="accordion-inner">
            @foreach($organisation->infrastructures as $infrastructure)
                @include('blocks.infra_well', ['data' => [
                    'type' => 'infra',
                    'overlay_image' =>
                        $infrastructure->infrastructure_officielle->ch_inf_off_icone,
                    'overlay_text' =>
                        $infrastructure->infrastructure_officielle->ch_inf_off_nom,
                    'image' => $infrastructure->ch_inf_lien_image,
                    'nom' => $infrastructure->nom_infra,
                    'url' => '',
                    'description' => $infrastructure->ch_inf_commentaire,
                    'dropdown' => [
                        [
                            'type' => 'link',
                            'url'  => route('infrastructure.edit',
                                ['infrastructure_id' => $infrastructure->ch_inf_id]),
                            'text' => "Modifier l'infrastructure",
                        ],
                    ],
                ]])
            @endforeach
            </div>
          </div>
        </div>

    </div> <!-- end .well -->

@endif
