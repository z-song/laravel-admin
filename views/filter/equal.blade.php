<div class="input-group input-group-sm">
    <span class="input-group-addon"><strong>{{$label}}</strong></span>
    @include('admin::filter.' . $field->name())
</div>