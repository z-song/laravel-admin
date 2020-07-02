<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <input type="checkbox" class="{{$class}} la_checkbox" {{ old($column, $value) == 'on' ? 'checked' : '' }} {!! $attributes !!} />
        <input type="hidden" class="{{$class}}" name="{{$name}}" value="{{ old($column, $value) }}" />

        @include('admin::form.help-block')

    </div>
</div>

<script require="bootstrapSwitch">
    $('{{ $selector }}.la_checkbox').bootstrapSwitch({
        size:'{{ $size }}',
        onText: '{{ $states['on']['text'] }}',
        offText: '{{ $states['off']['text'] }}',
        onColor: '{{ $states['on']['color'] }}',
        offColor: '{{ $states['off']['color'] }}',
        onSwitchChange: function(event, state) {
            $(event.target).closest('.bootstrap-switch').next().val(state ? 'on' : 'off').change();
        }
    });
</script>
