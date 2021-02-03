<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}}">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="file" class="form-control {{$class}}" name="{{$name}}" {!! $attributes !!} />
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
    <input type="hidden" class="form-control {{$class}}" name="{{$name}}" value="{{ $value }}"/>
</div>

<script require="fileinput" @script>
    var this_hidden = $(this).parents('.field-control:first').next();
    $(this).fileinput({!! $options !!}).on('change', function () {
        this_hidden.prop('disabled', true);
    });

    @if($settings['showRemove'])
    $(this).on('filebeforedelete', function() {
        return new Promise(function(resolve, reject) {
            var remove = resolve;
            $.admin.confirm({
                title: "{{ admin_trans('admin.delete_confirm') }}",
                preConfirm: function() {
                    this_hidden.val('');
                    return new Promise(function(resolve) {
                        resolve(remove());
                    });
                }
            });
        });
    });
    @endif
</script>
