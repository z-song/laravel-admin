<div class="{{$viewClass['form-group']}}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="text" class="{{$class}}" name="{{$name}}" data-from="{{ old($column, $value) }}" {!! $attributes !!} />

        @include('admin::form.help-block')

    </div>
</div>

<script require="rangeSlider">
    $('{{ $selector }}').ionRangeSlider(@json($options));
</script>
