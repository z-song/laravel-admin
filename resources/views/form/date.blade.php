<div class="{{$viewClass['form-group']}}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">
        {{$label}}
    </label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group"  style="width: 250px;">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="far {{ $icon }} fa-w"></i>
                </span>
            </div>
            <input {!! $attributes !!} />
        </div>
        @include('admin::form.help-block')
    </div>
</div>

<script require="datetimepicker" @script>
    $(this).datetimepicker(@json($options));
</script>
