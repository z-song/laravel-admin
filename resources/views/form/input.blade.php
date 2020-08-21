<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="input-group">

            @if ($prepend)
                <div class="input-group-prepend">
                {!! $prepend !!}
                </div>
            @endif

            <input {!! $attributes !!} />

            @if ($append)
                <span class="input-group-append">{!! $append !!}</span>
            @endif

            @if($picker)
                <span class="input-group-append">
                    <button type="button" class="btn btn-@theme text-white" data-toggle="modal" data-target="#{{ $picker->modal }}">
                        <i class="fa fa-folder-open"></i>  {{ admin_trans('admin.browse') }}
                    </button>
                </span>
            @endif

        </div>

        @include('admin::form.help-block')

    </div>
</div>
