<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="file" class="form-control {{$class}}" name="{{$name}}" {!! $attributes !!} />
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="fileinput" @script>

    $(this).fileinput({!! $options !!});

    @if($settings['showRemove'])
    $(this).on('filebeforedelete', function() {
        return new Promise(function(resolve, reject) {
            var remove = resolve;
            $.admin.swal.fire({
                title: "{{ admin_trans('admin.delete_confirm') }}",
                icon: "warning",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ admin_trans('admin.confirm') }}",
                cancelButtonText: "{{ admin_trans('admin.cancel') }}",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        resolve(remove());
                    });
                }
            });
        });
    });
    @endif
</script>
