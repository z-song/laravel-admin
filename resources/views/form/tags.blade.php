@if($fieldWidth)
    <div class="col-md-{{$fieldWidth}}">
@endif
<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}}{{$controlLabel ? ' control-label' : ''}}">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!} >
            @foreach($value as $select)
                <option value="{{$select}}" selected>{{$select}}</option>
            @endforeach
        </select>
        <input type="hidden" name="{{$name}}[]" />

        @include('admin::form.help-block')

    </div>
</div>
@if($fieldWidth)
    </div>
@endif