<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Scaffold</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

        <form method="post" id="scaffold">

            <div class="box-body">

                <div class="form-horizontal">

                <div class="form-group">

                    <label for="inputTableName" class="col-sm-1 control-label">Table name</label>

                    <div class="col-sm-4">
                        <input type="text" name="table_name" class="form-control" id="inputTableName" placeholder="table name" value="{{ old('table_name') }}">
                    </div>

                    <span class="help-block hide" id="table-name-help">
                        <i class="fa fa-info"></i>&nbsp; Table name can't be empty!
                    </span>

                </div>
                <div class="form-group">
                    <label for="inputModelName" class="col-sm-1 control-label">Model</label>

                    <div class="col-sm-4">
                        <input type="text" name="model_name" class="form-control" id="inputModelName" placeholder="model" value="{{ old('model_name', "App\\Models\\") }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputControllerName" class="col-sm-1 control-label">Controller</label>

                    <div class="col-sm-4">
                        <input type="text" name="controller_name" class="form-control" id="inputControllerName" placeholder="controller" value="{{ old('controller_name', "App\\Admin\\Controller\\") }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-1 col-sm-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" checked value="create_migration" name="create_option[]" /> make migration
                            </label>
                            <label>
                                <input type="checkbox" checked value="create_model" name="create_option[]" /> make model
                            </label>
                            <label>
                                <input type="checkbox" checked value="create_controller" name="create_option[]" /> make controller
                            </label>
                        </div>
                    </div>
                </div>

                </div>

                <hr />

                <h4>Fields</h4>

                <table class="table table-hover" id="table-fields">
                    <tbody>
                    <tr>
                        <th style="width: 200px">Field name</th>
                        <th>Type</th>
                        <th>Nullable</th>
                        <th>Key</th>
                        <th>Default value</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="fields[0][name]" class="form-control" placeholder="field name" />
                        </td>
                        <td>
                            <select style="width: 200px" name="fields[0][type]">
                                @foreach($dbTypes as $type)
                                    <option value="{{ $type }}">{{$type}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="checkbox" name="fields[0][nullable]" /></td>
                        <td>
                            <select style="width: 150px" name="fields[0][key]">
                                {{--<option value="primary">Primary</option>--}}
                                <option value="" selected>NULL</option>
                                <option value="unique">Unique</option>
                                <option value="index">Index</option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control" placeholder="default value" name="fields[0][default]"></td>
                        <td><a class="btn btn-sm btn-danger table-field-remove"><i class="fa fa-trash"></i> remove</a></td>
                    </tr>
                    </tbody>
                </table>

                <hr style="margin-top: 0;"/>

                <div class='form-inline margin' style="width: 100%">

                    <div class="form-group">
                        <label for="inputPrimaryKey">PrimaryKey name</label>
                        <input type="text" name="primary_key" class="form-control" id="inputPrimaryKey" placeholder="PrimaryKey name" value="id" style="width: 100px;">
                    </div>

                    <div class='form-group' style="margin-left: 20px;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" checked name="use_timestamps"> Created_at & Updated_at
                            </label>
                            <label>
                                <input type="checkbox" name="use_soft_deletes"> Soft deletes
                            </label>
                        </div>
                    </div>

                    <div class='form-group pull-right' style="margin-right: 10px;">
                        <button type="button" class="btn btn-sm btn-success" id="add-table-field"><i class="fa fa-save"></i> new</button>
                    </div>

                </div>

                {{--<div class="row margin">--}}
                    {{----}}
                    {{--<div class="form-group pull-left">--}}

                        {{--<div class="checkbox">--}}
                            {{--<label>--}}
                                {{--<input type="checkbox" checked name="use_timestamps"> Created_at & Updated_at--}}
                            {{--</label>--}}
                            {{--<label>--}}
                                {{--<input type="checkbox" name="use_soft_deletes"> Soft deletes--}}
                            {{--</label>--}}
                        {{--</div>--}}

                        {{--<a class="btn btn-sm btn-success pull-right" id="add-table-field"><i class="fa fa-save"></i> 新增</a>--}}

                    {{--</div>--}}


                {{--</div>--}}

                {{--<hr />--}}

                {{--<h4>Relations</h4>--}}

                {{--<table class="table table-bordered">--}}
                    {{--<tbody><tr>--}}
                        {{--<th style="width: 200px">Relation name</th>--}}
                        {{--<th>Type</th>--}}
                        {{--<th>With Pivot</th>--}}
                        {{--<th>forignKey</th>--}}
                        {{--<th>OtherKey</th>--}}
                        {{--<th>Action</th>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td><input type="text" class="form-control" placeholder="field name" value="id"></td>--}}
                        {{--<td>--}}
                            {{--<select style="width: 150px">--}}
                                {{--<option>HasOne</option>--}}
                                {{--<option>BelongsTo</option>--}}
                                {{--<option>HasMany</option>--}}
                                {{--<option>BelongsToMany</option>--}}
                            {{--</select>--}}
                        {{--</td>--}}
                        {{--<td><input type="checkbox" checked></td>--}}
                        {{--<td><input type="text" class="form-control" placeholder="default value"></td>--}}
                        {{--<td><input type="text" class="form-control" placeholder="default value"></td>--}}
                        {{--<td><a class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> 删除</a></td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td><input type="text" class="form-control" placeholder="field name" value="id"></td>--}}
                        {{--<td>--}}
                            {{--<select style="width: 150px">--}}
                                {{--<option>HasOne</option>--}}
                                {{--<option>BelongsTo</option>--}}
                                {{--<option>HasMany</option>--}}
                            {{--</select>--}}
                        {{--</td>--}}
                        {{--<td><input type="checkbox" checked></td>--}}
                        {{--<td><input type="text" class="form-control" placeholder="default value"></td>--}}
                        {{--<td><input type="text" class="form-control" placeholder="default value"></td>--}}
                        {{--<td><a class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> 删除</a></td>--}}
                    {{--</tr>--}}
                    {{--</tbody>--}}
                {{--</table>--}}

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">submit</button>
            </div>

            {{ csrf_field() }}

            <!-- /.box-footer -->
        </form>


    </div>

</div>

<template id="table-field-tpl">
    <tr>
        <td>
            <input type="text" name="fields[__index__][name]" class="form-control" placeholder="field name" />
        </td>
        <td>
            <select style="width: 200px" name="fields[__index__][type]">
                @foreach($dbTypes as $type)
                    <option value="{{ $type }}">{{$type}}</option>
                @endforeach
            </select>
        </td>
        <td><input type="checkbox" name="fields[__index__][nullable]" /></td>
        <td>
            <select style="width: 150px" name="fields[__index__][key]">
                <option selected>NULL</option>
                <option value="unique">Unique</option>
                <option value="index">Index</option>
            </select>
        </td>
        <td><input type="text" class="form-control" placeholder="default value" name="fields[__index__][default]"></td>
        <td><a class="btn btn-sm btn-danger table-field-remove"><i class="fa fa-trash"></i> remove</a></td>
    </tr>
</template>

<script>

$(function () {

    $('input[type=checkbox]').iCheck({checkboxClass:'icheckbox_minimal-blue'});
    $('select').select2();

    var fields_count = 1;

    $('#add-table-field').click(function (event) {
        $('#table-fields tbody').append($('#table-field-tpl').html().replace(/__index__/g, fields_count));
        $('select').select2();
        $('input[type=checkbox]').iCheck({checkboxClass:'icheckbox_minimal-blue'});

        fields_count++;
    });

    $('#table-fields').on('click', '.table-field-remove', function(event) {
        $(event.target).closest('tr').remove();
    });

    $('#scaffold').on('submit', function (event) {

        //event.preventDefault();

        if ($('#inputTableName').val() == '') {
            $('#inputTableName').closest('.form-group').addClass('has-error');
            $('#table-name-help').removeClass('hide');

            return false;
        }

        return true;
    });
});

</script>