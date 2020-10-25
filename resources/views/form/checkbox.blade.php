<div {!! admin_attrs($group_attrs) !!}>
    <label for="@id" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}" id="@id">
        @if($canCheckAll)
            <span class="icheck-@color">
                <input type="checkbox" id="@id" class="{{ $checkAllClass }}"/>
                <label for="@id">
                    &nbsp;{{ __('admin.all') }}
                </label>
            </span>
            <hr style="margin-top: 10px;margin-bottom: 0;">
        @endif
        @foreach($options as $option => $label)
                {!! $inline ? admin_color('<span class="icheck-%s">') : admin_color('<div class="radio icheck-%s">') !!}
                <input type="checkbox" id="@id" name="{{$name}}[]" value="{{$option}}" class="{{$class}}" {{ false !== array_search($option, array_filter($value ?? [])) || ($value === null && in_array($option, $checked)) ?'checked':'' }} {!! $attributes !!} />
                <label for="@id">&nbsp;{{$label}}&nbsp;&nbsp;</label>
            {!! $inline ? '</span>' :  '</div>' !!}
        @endforeach
        <input type="hidden" name="{{$name}}[]">
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="icheck">
    @if($canCheckAll)
    $('.{{ $checkAllClass }}').change(function () {
        $checkbox = $(this).parents('.form-group').find('{{ $selector }}');
        if (this.checked) {
            $checkbox.prop('checked', true).trigger('change');
        } else {
            $checkbox.prop('checked', false).trigger('change');
        }
    });
    @endif
</script>
