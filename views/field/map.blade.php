<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id['lat']}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::field.error')

        <div id="map_{{$id['lat'].$id['lng']}}" style="width: 100%;height: 300px"></div>
        <input type="hidden" id="{{$id['lat']}}" name="{{$name['lat']}}" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
        <input type="hidden" id="{{$id['lng']}}" name="{{$name['lng']}}" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />

        @include('admin::field.help-block')

    </div>
</div>
