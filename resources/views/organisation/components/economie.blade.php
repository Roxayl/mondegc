
@inject('helperService', 'App\Services\HelperService')

@if($organisation->hasEconomy())

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
    $resources = $organisation->resources();
    @endphp
    {!! \App\Services\HelperService::renderLegacyElement('temperance/resources', [
        'resources' => $resources
    ]) !!}
    <div class="clearfix"></div>

    <div class="well">

        @if($organisation->membersGenerateResources())

        <!-- Balance économique -->
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" href="#economie-pays">
                Balance économique par pays membre
            </a>
          </div>
          <div id="economie-pays" class="accordion-body collapse">
            <div class="accordion-inner">

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
                            'resources' => $thisPays->resources()
                        ]) !!}
                </div>
            @endforeach

            </div>
          </div>
        </div>

        @endif

        <!-- Infras -->
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse"
               href="#economie-infrastructures">
                Infrastructures
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
                        ->infrastructure_officielle->ch_inf_off_icone,
                    'overlay_text' => $infrastructure
                        ->infrastructure_officielle->ch_inf_off_nom,
                    'image' => $infrastructure->ch_inf_lien_image,
                    'nom' => $infrastructure->nom_infra,
                    'description' => \Illuminate\Support\Str::limit(
                        $infrastructure->ch_inf_commentaire),
                ]); !!}
            @endforeach
            </div>
          </div>
        </div>

    </div> <!-- end .well -->

    <div class="modal container fade" id="Modal-Monument"></div>

@endif
