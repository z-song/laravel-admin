<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="text" class="form-control {{$class}}" name="{{$name}}" data-from="{{ $value }}" {!! $attributes !!} />
        @include('admin::form.error')
        @include('admin::form.help-block')

    </div>
</div>

<script require="rangeSlider" @script>
    $(this).ionRangeSlider(@json($options));
</script>
