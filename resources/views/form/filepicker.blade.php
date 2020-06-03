<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}} picker-{{ $column }}">

        @include('admin::form.error')

        <div class="picker-file-preview">
            @foreach($preview as $item)
            <div class="file-preview-frame">
                <div class="file-content">
                    <i class="glyphicon glyphicon-file"></i>
                </div>
                <div class="file-caption-info">{{ $item }}</div>
                <div class="file-actions">
                    <a class="btn btn-default btn-sm"><i class="fa fa-trash"></i></a>
                    <a class="btn btn-default btn-sm"><i class="fa fa-download"></i></a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="input-group">

            <input {!! $attributes !!} />

            @isset($btn)
                <span class="input-group-btn">
                  {!! $btn !!}
                </span>
            @endisset

        </div>

        @include('admin::form.help-block')

    </div>
</div>
