@php($skin = config('captcha.skin', 'default'))
<div class="row form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">
    <label for="{{$id}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

    <div class="col-sm-{{$width['field']}}">

        @include('admin::form.error')

        <div class="input-group" style="width: 250px;">
            <input {!! $attributes !!} autocomplete="off" />

            <span class="input-group-addon clearfix" style="padding: 1px;">
                <img id="{{$column}}-captcha" src="{{ captcha_src($skin) }}" class="captcha-image" style="height:{{config("captcha.{$skin}.height", 30)}}px;width:{{config("captcha.{$skin}.width", 150)}}px;cursor: pointer;"  title="Click to refresh"/>
            </span>
        </div>

        @include('admin::form.help-block')

    </div>
</div>