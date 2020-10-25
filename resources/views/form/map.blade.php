<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id['lat']}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <div id="map_{{$id['lat'].$id['lng']}}" style="width: 100%;height: 300px"></div>
        <input type="hidden" id="{{$id['lat']}}" name="{{$name['lat']}}" value="{{ $value['lat'] }}" {!! $attributes !!} />
        <input type="hidden" id="{{$id['lng']}}" name="{{$name['lng']}}" value="{{ $value['lng'] }}" {!! $attributes !!} />

        @include('admin::form.help-block')

    </div>
</div>
