<render>
    <div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

        <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

        <div class="{{$viewClass['field']}} picker-{{ $column }}">

            @include('admin::form.error')

            <div class="picker-file-preview {{ empty($preview) ? 'hide' : '' }}">
                @foreach($preview as $item)
                <div class="file-preview-frame" data-val="{!! $item['value'] !!}">
                    <div class="file-content">
                        @if($item['is_file'])
                            <i class="fa fa-file-text-o"></i>
                        @else
                            <img src="{{ $item['url'] }}"/>
                        @endif
                    </div>
                    <div class="file-caption-info">{{ basename($item['url']) }}</div>
                    <div class="file-actions">
                        <a class="btn btn-default btn-sm remove">
                            <i class="fa fa-trash"></i>
                        </a>
                        <a class="btn btn-default btn-sm" target="_blank" download="{{ basename($item['url']) }}" href="{{ $item['url'] }}">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="input-group">
                <input {!! $attributes !!} />
                <span class="input-group-btn">
                  {!! $btn !!}
                </span>
            </div>

            @include('admin::form.help-block')

        </div>
    </div>
</render>

<template>
    <template id="file-preview">
        <div class="file-preview-frame" data-val="_val_">
            <div class="file-content">
                <i class="fa fa-file-text-o"></i>
            </div>
            <div class="file-caption-info">_name_</div>
            <div class="file-actions">
                <a class="btn btn-default btn-sm remove">
                    <i class="fa fa-trash"></i>
                </a>
                <a class="btn btn-default btn-sm" target="_blank" download="_name_" href="_url_">
                    <i class="fa fa-download"></i>
                </a>
            </div>
        </div>
    </template>
    <template id="image-preview">
        <div class="file-preview-frame" data-val="_val_">
            <div class="file-content">
                <img src="_url_">
            </div>
            <div class="file-caption-info">_name_</div>
            <div class="file-actions">
                <a class="btn btn-default btn-sm remove">
                    <i class="fa fa-trash"></i>
                </a>
                <a class="btn btn-default btn-sm" target="_blank" download="_name_" href="_url_">
                    <i class="fa fa-download"></i>
                </a>
            </div>
        </div>
    </template>
</template>
