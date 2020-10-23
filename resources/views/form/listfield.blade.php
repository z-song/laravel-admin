<div {!! admin_attrs($group_attrs) !!}>
    <label class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <table class="table table-hover">
            <tbody class="list-{{$column}}-table">
            @foreach($value as $k => $v)
                <tr>
                    <td>
                        <div class="form-group">
                            <input name="{{ $column }}[]" value="{{ $v }}" class="form-control {{ $class }}" />
                            @include('admin::form.error')
                        </div>
                    </td>

                    <td style="width: 90px;">
                        <div class="{{$column}}-remove btn btn-warning btn-sm float-right">
                            <i class="fa fa-trash">&nbsp;</i>{{ __('admin.remove') }}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>
                        <div class="{{ $column }}-add btn btn-success btn-sm float-right">
                            <i class="fa fa-save"></i>&nbsp;{{ __('admin.new') }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<template>
    <template class="{{$column}}-tpl">
        <tr>
            <td>
                <div class="form-group">
                    <input name="{{ $column }}[]" class="form-control {{ $class }}" />
                    @include('admin::form.error')
                </div>
            </td>

            <td style="width: 90px;">
                <div class="{{$column}}-remove btn btn-warning btn-sm float-right">
                    <i class="fa fa-trash">&nbsp;</i>{{ __('admin.remove') }}
                </div>
            </td>
        </tr>
    </template>
</template>

<script>
    $('.{{ $column }}-add').on('click', function () {
        var tpl = $('template.{{ $column }}-tpl').html();
        $('tbody.list-{{ $column }}-table').append(tpl);
    });

    $('tbody').on('click', '.{{ $column }}-remove', function () {
        $(this).closest('tr').remove();
    });
</script>

<style>
    td .form-group {
        margin-bottom: 0 !important;
    }
</style>
