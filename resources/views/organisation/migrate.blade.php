
@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">Migrer {{$organisation->name}} vers un nouveau type
        d'organisation</h3>
@endsection

@section('modal_body')

    <div class="row-fluid">

        @foreach($organisation::$types as $type)

        @php $currentType = $organisation->type === $type; @endphp

        <div class="span4 org-container org-{{ $organisation::$types[$type] }}"
            @if($currentType) style="border: 3px solid cornflowerblue" @endif >

            <div class="org-header">
                <h2>{{ __("organisation.types.{$type}") }}</h2>
            </div>

            <div class="org-description">
                @if(!empty(__("organisation.types.{$type}-prerequisites")))
                    Critères :
                    <ul>
                    @foreach(__("organisation.types.{$type}-prerequisites")
                        as $criteria)
                        <li>{{ $criteria }}</li>
                    @endforeach
                    </ul>
                @endif

                @if(!$currentType)
                    <form action="{{ route('organisation.run-migration',
                        ['organisation' => $organisation->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit" class="btn btn-primary">
                            Migrer vers une {{ __("organisation.types.{$type}") }}
                        </button>
                    </form>
                @else
                <p>Type actuel</p>
                @endif
            </div>

        </div>

        @endforeach

    </div>

@endsection
