<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}}">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="{{$class}}">
            <p>{!! $value !!}</p>
        </div>
        <textarea name="{{$name}}" class="d-none" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{!! $value !!}</textarea>
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="wangEditor" @script>
    var editor = new wangEditor(this);
    var textarea = $(this).parent().find('textarea');
    editor.config.onchange = function (html) {
        textarea.val(html);
    };
    editor.config.zIndex = 500;

    @isset($config)
        Object.assign(editor.config, @json($config));
    @endisset

    editor.create();
</script>
