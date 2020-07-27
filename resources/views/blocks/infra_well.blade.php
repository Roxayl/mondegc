
<div class="infra-well infra-type-{{$data['type']}} ?>">

    @if(isset($data['overlay_text']) || isset($data['overlay_image']))
    <div class="infra-overlay"
         style="@if(isset($data['overlay_image'])) 'padding-left: 32px;' @endif">
        @if(isset($data['overlay_image']))
            <img src="{{$data['overlay_image']}}">
        @endif
        @if(isset($data['overlay_text'])) {{$data['overlay_text']}} @endif
    </div>
    @endif

    <div class="infra-image" style="background-image: url('{{$data['image']}}');"></div>

    <div class="infra-text">

        @if(isset($data['dropdown']) && count($data['dropdown']))
        <div class="dropdown pull-right" style="margin-top: -5px;">
            <a href="#" class="btn btn-primary notification-toggle-btn" type="submit"
                 title="Notifications" class="button" data-toggle="dropdown">
                    <i class="icon-chevron-down icon-white"></i>
            </a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                @foreach($data['dropdown'] as $key => $dropdown)
                    @if($dropdown['type'] === 'link')
                        <li>
                            <a href="{{$dropdown['url']}}"
                            @if(isset($dropdown['popup']))
                              data-toggle="modal" data-target="#modal-container"
                            @endif
                            >{{$dropdown['text']}}</a>
                        </li>
                    @elseif($dropdown['type'] === 'form')
                        <li>
                            <form method="POST" action="{{$dropdown['action']}}">
                                @csrf
                                @if(in_array($dropdown['method'], ['PUT', 'PATCH', 'DELETE']))
                                    @method($dropdown['method'])
                                @endif
                                @foreach($dropdown['data'] as $dataName => $dataValue)
                                        <input type="hidden" name="{{$dataName}}"
                                               value="{{$dataValue}}">
                                @endforeach
                                <button type="submit" class="btn btn-primary"
                                    >{{ $dropdown['button'] }}</button>
                            </form>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif

        <a href="{{$data['url']}}"><h4>{{$data['nom']}}</h4></a>
        <p>
            @if(isset($data['description_escape']) && $data['description_escape'] === false)
                {!! $data['description'] !!}
            @else
                {{$data['description']}}
            @endif
        </p>
    </div>

</div>
