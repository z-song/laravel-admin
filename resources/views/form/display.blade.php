<div class="{{$viewClass['form-group']}}">
    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="card card-solid card-default no-margin">
            <!-- /.card-header -->
            <div class="card-body p-2">
                {!! $value !!}&nbsp;
            </div><!-- /.card-body -->
        </div>

        @include('admin::form.help-block')

    </div>
</div>
