<div class="{{$viewClass['form-group']}}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group" style="width: 250px;">
            <input {!! $attributes !!} />
            <span class="input-group-addon clearfix" style="padding: 1px;">
                <img @el src="{{ captcha_src() }}" style="height:30px;cursor: pointer;"  title="Click to refresh"/>
            </span>
        </div>
        @include('admin::form.help-block')
    </div>
</div>

<script>
    @el.click(function () {
        $(this).attr('src', $(this).attr('src')+'?'+Math.random());
    });
</script>
