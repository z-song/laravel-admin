<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        

        <input type="hidden" name="{{$name}}" value="{{ old($column, $value) }}" />
        <iframe ID='eWebEditor_{{$name}}' src='/ewebeditor/ewebeditor.htm?id={{$name}}&style=fzjk_cmct_cn' frameborder=0 scrolling=no width='100%' HEIGHT='350'></iframe>

    </div>
</div>