<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <input type="file" class="{{$class}}" name="{{$name}}[]" {!! $attributes !!} />
        @isset($sortable)
        <input type="hidden" class="{{$class}}_sort" name="{{ $sort_flag."[$name]" }}"/>
        @endisset

        @include('admin::form.help-block')

    </div>
</div>

<script require="fileinput">
    var $input = $("input{{ $selector }}");
    $input.fileinput(@json($options));

    @if($settings['showRemove'])
    $input.on('filebeforedelete', function() {
        return new Promise(function(resolve, reject) {
            var remove = resolve;
            swal({
                title: "{{ trans('admin.delete_confirm') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ trans('admin.confirm') }}",
                showLoaderOnConfirm: true,
                cancelButtonText: "{{ trans('admin.cancel') }}",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        resolve(remove());
                    });
                }
            });
        });
    });
    @endif

    @if($settings['showDrag'])
    $input.on('filesorted', function(event, params) {
        var order = [];
        params.stack.forEach(function (item) {
            order.push(item.key);
        });
        $("input{{ $selector }}_sort").val(order);
    });
    @endif
</script>
