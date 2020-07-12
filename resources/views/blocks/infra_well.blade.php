
<div class="infra-well infra-type-{{$data['type']}} ?>">
    @if(isset($data['overlay_text']) || isset($data['overlay_image']))
    <div class="infra-overlay" style="@if(isset($data['overlay_image'])) 'padding-left: 32px;' @endif">
        @if(isset($data['overlay_image']))
            <img src="{{$data['overlay_image']}}">
        @endif
        @if(isset($data['overlay_text'])) $data['overlay_text'] @endif
    </div>
    @endif
    <div class="infra-image" style="background-image: url('{{$data['image']}}');"></div>
    <div class="infra-text">
        <a href="{{$data['url']}}"><h4>{{$data['nom']}}</h4></a>
        <p>{{$data['description']}}</p>
    </div>
</div>
