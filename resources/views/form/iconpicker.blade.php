<div class="{{$viewClass['form-group']}}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group">
            <button id="@id" class="icon btn btn-@theme {{ $class }}" data-icon="{{ $value ?: $default }}" name="{{ $name }}"></button>
        </div>
        @include('admin::form.help-block')
    </div>
</div>

<script require="iconpicker">
    $('{{ $selector }}').iconpicker({
        arrowClass: 'btn-@theme',
        selectedClass: 'btn-@theme',
        unselectedClass: '',
        placement: 'right',
    });
</script>
