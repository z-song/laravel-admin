<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="file" class="{{$class}}" name="{{$name}}[]" {!! $attributes !!} />
        @isset($sortable)
        <input type="hidden" class="{{$class}}_sort" name="{{ $sort_flag."[$name]" }}"/>
        @endisset

        @include('admin::form.error')
        @include('admin::form.help-block')

    </div>
</div>

@if($settings['showDrag'])
    <script require="sortable" @script>
        window.Sortable = Sortable;
    </script>
@endif

<script require="fileinput" @script>
    $(this).fileinput(@json($options));

    @if($settings['showRemove'])
    $(this).on('filebeforedelete', function() {
        return new Promise(function(resolve, reject) {
            var remove = resolve;
            $.admin.confirm({
                title: "{{ admin_trans('admin.delete_confirm') }}",
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
    $(this).on('filesorted', function(event, params) {
        var order = [];
        params.stack.forEach(function (item) {
            order.push(item.key);
        });
        $("input{{ $selector }}_sort").val(order);
    });
    @endif
</script>
