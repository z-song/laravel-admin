@if(is_array($column))
    @foreach($column as $key => $col)
        @if($errors->has($col.$key))
            @foreach($errors->get($col.$key) as $message)
                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
            @endforeach
        @endif
    @endforeach
@else
    @if($errors->has($column))
        @foreach($errors->get($column) as $message)
            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
        @endforeach
    @endif
@endif