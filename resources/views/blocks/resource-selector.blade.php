<div class="row">
    <div class="span5 offset1">
        @for($i = 0; $i < $leftColumnCount; $i++)
            @include('blocks.includes.resource-selector-input')
        @endfor
    </div>
    <div class="span5">
        @for($i = $leftColumnCount; $i < $rightColumnCount + $leftColumnCount; $i++)
            @include('blocks.includes.resource-selector-input')
        @endfor
    </div>
</div>
