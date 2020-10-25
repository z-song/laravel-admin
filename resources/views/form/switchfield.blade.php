<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <input type="checkbox" class="form-control {{ $class }}" {{ $value == $state['on']['value'] ? 'checked' : '' }} {!! $attributes !!} />
        <input type="hidden" name="{{$name}}" value="{{ $value }}" />
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="toggle" @script>
    $(this).bootstrapToggle().change(function () {
        $(this).parents('.fields-group')
            .find('input[type=hidden][name={{$name}}]')
            .val(this.checked ? '{{ $state['on']['value'] }}':'{{$state['off']['value']}}');
    });
</script>
