<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
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
                    <button type="button" class="btn btn-@color text-white" data-toggle="modal" data-target="#{{ $picker->modal }}">
                        <i class="fa fa-folder-open"></i>  {{ admin_trans('admin.browse') }}
                    </button>
                </span>
            @endif
        </div>

        @include('admin::form.error')
        @include('admin::form.help-block')

    </div>
</div>

@if($inputmask)
    <script require="inputmask" @script>
        $(this).inputmask({!! json_encode_options($inputmask)  !!});
    </script>
@endif
