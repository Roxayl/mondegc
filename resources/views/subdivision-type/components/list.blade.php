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
        <div class="pull-right-cta cta-title">
            <a href="{{ route('subdivision-type.edit', ['subdivisionType' => $subdivisionType]) }}" class="btn btn-primary btn-cta">
                <i class="icon-pencil icon-white"></i>
            </a>
        </div>
        <h3>{{ $subdivisionType->type_name }}</h3>
        @if(! count($subdivisionType->subdivisions))
            Il n'y a pas encore de {{ Str::lcfirst(Str::plural($subdivisionType->type_name)) }}.
        @else
            <table class="table table-hover">
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
                        <td>
                            <a class="btn btn-primary" href="<?= route('subdivision.create', ['subdivisionType' => $subdivisionType]) ?>">
                                <i class="icon-pencil icon-white"></i>
                            </a>
                            <a class="btn btn-danger" href="<?= route('subdivision.delete', ['subdivisionType' => $subdivisionType]) ?>">
                                <i class="icon-trash icon-white"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</section>
