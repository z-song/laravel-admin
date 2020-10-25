<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <textarea name="{{$name}}" class="form-control {{$class}}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ $value }}</textarea>

        @if($picker)
        <div class="text-right textarea-picker">
            <button type="button" class="btn btn-@color text-white" data-toggle="modal" data-target="#{{ $picker->modal }}">
                <i class="fa fa-folder-open"></i>  {{ admin_trans('admin.browse') }}
            </button>
        </div>
        @endif
        @include('admin::form.error')
        @include('admin::form.help-block')

    </div>
</div>

@if($picker)
<style>
    .textarea-picker {
        padding: 5px;
        border-bottom: 1px solid #d2d6de;
        border-left: 1px solid #d2d6de;
        border-right: 1px solid #d2d6de;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        background-color: #f1f2f3;
    }

    .textarea-picker .btn {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
    }
</style>
@endif
