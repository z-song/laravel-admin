<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}" id="{{$id}}">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
        @foreach($options as $option => $label)
            <label class="btn btn-@color {{ false !== array_search($option, array_filter($value ?? [])) || ($value === null && in_array($option, $checked)) ?'active':'' }}">
                <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="d-none {{$class}}" {{ false !== array_search($option, array_filter($value ?? [])) || ($value === null && in_array($option, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        @endforeach
        </div>
        <input type="hidden" name="{{$name}}[]">
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

