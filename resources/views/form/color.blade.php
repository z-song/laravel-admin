<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group" style="width: 200px">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-palette fa-fw"></i>
                </span>
            </div>
            <input {!! $attributes !!} />
        </div>
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="colorpicker" @script>
    $(this).parent().colorpicker(@json($options));
</script>
