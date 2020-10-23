<div {!! admin_attrs($group_attrs) !!}>
    <label class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="card card-solid card-default m-0">
            <!-- /.card-header -->
            <div class="card-body py-2 px-4">
                {!! $value !!}&nbsp;
            </div><!-- /.card-body -->
        </div>
        @include('admin::form.help-block')
    </div>
</div>
