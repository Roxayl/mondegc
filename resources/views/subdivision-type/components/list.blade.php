<div class="pull-right-cta cta-title">
    <a href="{{ route('subdivision-type.create', ['pays' => $pays]) }}" class="btn btn-primary btn-cta">
        <i class="icon-plus-sign icon-white"></i> Créer un type de subdivision
    </a>
</div>

<section>
    <div id="subdivisions" class="titre-vert anchor">
        <h1>Subdivisions</h1>
    </div>

    @foreach($pays->subdivisionTypes as $subdivisionType)
        <h3>{{ $subdivisionType->type_name }}</h3>
        <div class="pull-right" style="margin-top: -38px; margin-right: 10px;   ">
            <a href="{{ route('subdivision-type.edit', ['subdivisionType' => $subdivisionType]) }}" class="btn btn-primary btn-cta">
                <i class="icon-pencil icon-white"></i>
            </a>
            <a href="{{ route('subdivision.create', ['pays' => $pays, 'subdivisionTypeId' => $subdivisionType->id]) }}"
               class="btn btn-primary btn-cta">
                <i class="icon-plus-sign icon-white"></i> Ajouter une subdivision
            </a>
            <form action="{{ route('subdivision-type.delete', ['subdivisionType' => $subdivisionType]) }}" method="POST" style="display: inline;">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-cta">
                    <i class="icon-trash icon-white"></i>
                </button>
            </form>
        </div>
        @if(! count($subdivisionType->subdivisions))
            <div class="well">
                Il n'y a pas encore de {{ Str::lcfirst(Str::plural($subdivisionType->type_name)) }}.
                <a href="{{ route('subdivision.create', ['pays' => $pays, 'subdivisionTypeId' => $subdivisionType->id]) }}">
                    En créer une</a>.
            </div>
        @else
            <table class="table table-hover" style="width: 100%;">
                <thead>
                <tr class="tablehead">
                    <th>Nom</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($subdivisionType->subdivisions as $subdivision)
                    <tr>
                        <td><?= e($subdivision->name) ?></td>
                        <td style="text-align: right;">
                            <a class="btn btn-primary"
                               href="<?= route('subdivision.edit', ['subdivision' => $subdivision]) ?>">
                                <i class="icon-pencil icon-white"></i>
                            </a>
                            <a class="btn btn-danger"
                               href="<?= route('subdivision.delete', ['subdivision' => $subdivision]) ?>">
                                <i class="icon-trash icon-white"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        @if(! $loop->last)
            <hr style="margin: 12px 10px;">
        @endif
    @endforeach
</section>
