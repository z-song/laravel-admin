<div id="has-many-{{$column}}" class="box has-many-{{$column}}" style="margin-bottom: 0;">
    <div class="box-header with-border" style="margin-bottom: 20px;">
        <h3 class="box-title">{{ $label }}</h3>
        <div class="box-tools pull-right">
            <div class="btn btn-success btn-sm add"><i class="fa fa-save"></i>&nbsp;New</div>
            <div class="btn btn-primary btn-sm" data-action="collapse-all"><i class="fa fa-minus-square-o"></i>&nbsp;{{ trans('admin::lang.collapse') }}</div>
            <div class="btn btn-primary btn-sm" data-action="expand-all" style="display:none;"><i class="fa fa-plus-square-o"></i>&nbsp;{{ trans('admin::lang.expand') }}</div>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body" style="padding:0;">
        <div id="has-many-{{$column}}-forms" class="has-many-{{$column}}-forms">
            @foreach($groups as $pk => $group)

                <div class="has-many-{{$column}}-form">
                    <div class="box" style="border-top:none;">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $pk }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool remove"><i class="fa fa-times"></i></button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            @foreach($group->fields() as $field)
                                {!! $field->render() !!}
                            @endforeach
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            @endforeach
        </div>
        <template class="{{$column}}-tpl">
            <div class="has-many-{{$column}}-form has-many-new-form">
                <div class="box" style="border-top:none; ">
                    <div class="box-header with-border">
                        <h3 class="box-title"><small class="label bg-green"><i class="fa fa-save"></i>&nbsp;New</small></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool remove"><i class="fa fa-times"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! $template !!}
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </template>
    </div>
</div>