<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!} >
            @foreach($options as $select => $option)
                <option value="{{$select}}" {{  in_array($select, (array)$value) ?'selected':'' }}>{{$option}}</option>
            @endforeach
        </select>
        <input type="hidden" name="{{$name}}[]" />

        @include('admin::form.help-block')

    </div>
</div>

<script require="duallistbox" @script>
    $(this).bootstrapDualListbox(@json($settings));
    @isset($remote)
    $.ajax(@json($remote)).done(function(data) {
        var value = $(this).data('value') + '';
        if (value) {
            value = value.split(',');
        }
        for (var key in data) {
            var selected =  ($.inArray(key, value) >= 0) ? 'selected' : '';
            $(this).append('<option value="'+key+'" '+selected+'>'+data[key]+'</option>');
        }
        $(this).bootstrapDualListbox('refresh', true);
    });
    @endisset
</script>
