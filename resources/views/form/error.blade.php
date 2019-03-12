@if(is_array($errorKey))
    @foreach(array_unique(call_user_func_array('array_merge',array_map(function($v)use($errors){
        return $errors->get($v);
        },
        array_map(function($k,$v){
            return "{$v}{$k}";
            },array_keys($errorKey),
            $errorKey)))) as $message)
        <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
    @endforeach
@else
    @if($errors->has($errorKey))
        @foreach($errors->get($errorKey) as $message)
            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
        @endforeach
    @endif
@endif