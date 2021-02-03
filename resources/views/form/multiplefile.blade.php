<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}}">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="file" class="{{$class}}" name="{{$name}}[]" {!! $attributes !!} />
        @isset($sortable)
        <input type="hidden" class="{{$class}}_sort" name="{{ $sort_flag."[$name]" }}"/>
        @endisset

        @include('admin::form.error')
        @include('admin::form.help-block')

        <input type="hidden" class="{{$class}}_orig" name="{{$name}}_orig[]" value="{{ json_encode($value) }}"/>

    </div>
    <input type="hidden" class="{{$class}}" name="{{ $old_flag."[$name]" }}" value="{{ isset($value) ? json_encode($value) : '' }}"/>
</div>

@if($settings['showDrag'])
    <script require="sortable" @script>
        window.Sortable = Sortable;
    </script>
@endif

<script require="fileinput" @script>
    $(this).fileinput(@json($options));

    @if($settings['showRemove'])
    $(this).on('filebeforedelete', function(event, id) {
        var old_files_elm = $(this).parents('.field-control:first').next();
        var old_files = JSON.parse(old_files_elm.val());
        old_files.splice(id, 1);
        var old_files_val = JSON.stringify(old_files);
        return new Promise(function(resolve, reject) {
            reject();return;
            var remove = resolve;
            $.admin.confirm({
                title: "{{ admin_trans('admin.delete_confirm') }}",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        resolve(remove());
                        old_files_elm.val(old_files_val);
                    });
                }
            });
        });
    });
    @endif

    @if($settings['showDrag'])
    $(this).on('filesorted', function(event, files) {
        console.log(arguments);
        var order = [];
        files.stack.forEach(function (item) {
            order.push(item.key);
        });
        $("input{{ $selector }}_sort").val(order);
    });
    @endif
</script>
