@extends('admin::admin')

@section('content')

    <div class="row">
        {!! $infoBox !!}
    </div>

    <div class="row">
        <div class="col-md-6">
            @foreach($left as $widget)
                {!! $widget !!}
            @endforeach
        </div>
        <div class="col-md-6">
            @foreach($right as $widget)
                {!! $widget !!}
            @endforeach
        </div>
    </div>

    <div class="row">
        @foreach($bottom as $widget)
            {!! $widget !!}
        @endforeach
    </div>

@endsection