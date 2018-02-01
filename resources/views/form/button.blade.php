@if($fieldWidth)
    <div class="col-md-{{$fieldWidth}}">
@endif
<div class="{{$viewClass['form-group']}}">

    <label class="{{$viewClass['label']}}{{$controlLabel ? ' control-label' : ''}}"></label>

    <div class="{{$viewClass['field']}}">
        <input type='button' value='{{$label}}' class="btn {{ $class }}" {!! $attributes !!} />
    </div>
</div>
@if($fieldWidth)
    </div>
@endif