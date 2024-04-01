<section>
    <div id="villes" class="titre-vert anchor">
        <h1>Villes</h1>
    </div>

    <ul class="listes listes-two-columns">
        @foreach($pays->villes as $ville)
            @include('pays.components.ville-two-columns')
        @endforeach
    </ul>
</section>
