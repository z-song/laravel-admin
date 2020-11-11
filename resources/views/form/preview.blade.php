<style>
    .close-{{ $column }}-preview {
        position: relative;
        top: -42px;
        right: -38px;
    }
</style>
<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}}">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <img src="{{ $preview ?: ($default ?: '') }}" alt="{{$label}}" class="{{$class}}" style="width: 38px;">

        <input type="file" class="form-control d-none" name="{{$name}}"/>
        @if($settings['showRemove'])
            <span class="close-{{ $column }}-preview text-danger"><i class="fas fa-times"></i></span>
        @endif
    </div>

    <input type="hidden" class="form-control" name="{{$name}}" value="{{ $value ?: ($default ?: '') }}"/>
</div>

<script @script>
    var _this = $(this);
    $(this).on('click', function () {
        _this.next().click();
    });
    var this_hidden = $(this).parents('.field-control:first').next();
    $(this).next().on('change', function () {
        var file = this.files[0];
        if (file) {
            var objUrl = getObjectURL(file);
            _this.attr('src', objUrl);
            this_hidden.prop('disabled', true);
        }
    });

    @if($settings['showRemove'])
    $(this).next().next().on('click', function () {
        this_hidden.prop('disabled', true);
        _this.attr('src', '{{$default ?: ''}}');
    });
    @endif

    function getObjectURL (file) {
        var url = null;
        if (window.createObjectURL !== undefined) { // basic
            url = window.createObjectURL(file);
        } else if (window.URL !== undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file);
        } else if (window.webkitURL !== undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file);
        }
        return url;
    }
</script>
