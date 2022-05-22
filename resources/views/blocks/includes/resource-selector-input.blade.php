@php
    $resourceName = $resourceList[$i];
    $label = "resource_selector_{$uniqid}_{$resourceName}";
@endphp

<div class="form-control">
    <label for="{{ $label }}">
        <img src="{{ url('assets/img/ressources/' . $resourceName . '.png') }}" alt="{{ $resourceName }}"
             style="width: 16px;">
        {{ Str::ucfirst($resourceName) }}
    </label>
    <input type="number" value="{{ $oldValues[$resourceName] }}"
           name="{{ $resourceName }}" id="{{ $label }}"/>
</div>
