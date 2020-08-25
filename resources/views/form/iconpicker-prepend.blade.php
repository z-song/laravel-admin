<button id="@id" class="icon btn btn-@color {{ $class }}" data-icon="{{ $value ?: $default }}" name="{{ $name }}"></button>

<script require="iconpicker" @script>
    $(this).iconpicker({
        arrowClass: 'btn-@color',
        selectedClass: 'btn-@color',
        unselectedClass: '',
        placement: 'right',
    });
</script>
