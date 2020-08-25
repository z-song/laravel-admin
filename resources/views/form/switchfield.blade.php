<div class="{{$viewClass['form-group']}}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="checkbox" class="{{ $class }}" {{ $value == $state['on']['value'] ? 'checked' : '' }} {!! $attributes !!} />

        <input type="hidden" name="{{$name}}" value="{{ $value }}" />

        @include('admin::form.help-block')

    </div>
</div>

<script require="toggle" @script>
    $(this).bootstrapToggle().change(function () {
        $(this)
            .parent()
            .find('input[type=hidden]')
            .val(this.checked ? '{{ $state['on']['value'] }}':'{{$state['off']['value']}}')
            .trigger('change');
    });
</script>
