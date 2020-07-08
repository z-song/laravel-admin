@admin_assets('icheck')

<div class="form-group">
    <label>{{ $label }}</label>
    <div>
    @foreach($options as $option => $label)
        <span class="icheck">
            <label class="checkbox-inline">
                <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="{{$class}}" {{ in_array($option, (array)old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        </span>
    @endforeach
    </div>
    <input type="hidden" name="{{$name}}[]">
    @include('admin::actions.form.help-block')
</div>

<script require="icheck">
    var $checkbox = $('{{ $selector }}');
    $checkbox.iCheck({checkboxClass:'icheckbox_minimal-blue'});

    @if($canCheckAll)
    $('.{{ $checkAllClass }}').iCheck({
        checkboxClass:'icheckbox_minimal-blue'
    }).on('ifChanged', function () {
        if (this.checked) {
            $checkbox.iCheck('check');
        } else {
            $checkbox.iCheck('uncheck');
        }
    });
    @endif
</script>
