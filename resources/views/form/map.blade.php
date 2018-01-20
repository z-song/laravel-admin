<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id['lat']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')
{{--{{ dd($column) }}--}}
        <input id="pac-input" type="text" />
        <div id="map_{{$id['lat'].$id['lng']}}" style="width: 100%;height: 300px"></div>
        <input type="hidden" id="{{$id['lat']}}" name="{{$column['lat']}}" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
        <input type="hidden" id="{{$id['lng']}}" name="{{$column['lng']}}" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />
        {{--<input type="hidden" id="{{$id['lat']}}" name="{{$name['lat']}}" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />--}}
        {{--<input type="hidden" id="{{$id['lng']}}" name="{{$name['lng']}}" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />--}}

        @include('admin::form.help-block')

    </div>
</div>
