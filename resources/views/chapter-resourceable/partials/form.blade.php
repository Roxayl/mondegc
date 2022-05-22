@csrf

@if(! isset($roleplayable))
    <x-blocks.roleplayable-selector/>
@else
    <x-blocks.roleplayable-selector :roleplayable="$roleplayable"/>
@endif


<h4>Attribution des ressources</h4>

<div class="form-control">
    <label for="chapter_resourceable_description">Pourquoi cette entité génère des ressources ?</label>
    <input type="text" name="description" id="chapter_resourceable_description" style="width: 100%"
           placeholder="Expliquez les raisons pour lesquelles cette entité bénéficie de ressources dans le cadre de ce roleplay."/>
</div>

<div class="well">
    @if(! isset($oldValues))
        <x-blocks.resource-selector/>
    @else
        <x-blocks.resource-selector :old-values="$oldValues"/>
    @endif
</div>

<div class="form-control">
    <button type="submit" class="btn btn-primary">
        Valider
    </button>
</div>
