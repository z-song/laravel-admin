<button id="@id" class="icon btn btn-@theme {{ $class }}" data-icon="{{ $value ?: $default }}" name="{{ $name }}"></button>

<script require="iconpicker">
    $('{{ $selector }}').iconpicker({
        arrowClass: 'btn-@theme',
        selectedClass: 'btn-@theme',
        unselectedClass: '',
        placement: 'right',
    });
</script>
