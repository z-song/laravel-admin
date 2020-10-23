<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group">
            <button id="@id" class="icon btn btn-@color {{ $class }}" data-icon="{{ $value ?: $default }}" name="{{ $name }}"></button>
        </div>
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="iconpicker" @script>
    $(this).iconpicker({
        arrowClass: 'btn-@color',
        selectedClass: 'btn-@color',
        unselectedClass: '',
        placement: 'right',
    });
</script>
