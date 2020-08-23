<div class="{{$viewClass['form-group']}}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="checkbox" class="@id" {{ $value == $state['on']['value'] ? 'checked' : '' }} {!! $attributes !!} />

        <input type="hidden" class="{{$class}}" name="{{$name}}" value="{{ $value }}" />

        @include('admin::form.help-block')

    </div>
</div>

<script require="toggle">
    $('.@id').bootstrapToggle().change(function () {
        $('{{$selector}}').val(this.checked ? '{{ $state['on']['value'] }}':'{{$state['off']['value']}}').trigger('change');
    });
</script>
