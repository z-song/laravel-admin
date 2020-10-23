<div {!! admin_attrs($group_attrs) !!}>

    <label class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>{{ __('Key') }}</th>
                <th>{{ __('Value') }}</th>
                <th style="width: 90px;"></th>
            </tr>
            </thead>

            <tbody class="kv-{{$column}}-table">
            @foreach(($value ?: []) as $k => $v)
            <tr>
                <td>
                    <div class="form-group">
                        <input name="{{ $name }}[keys][]" value="{{ $k }}" class="form-control" required/>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input name="{{ $name }}[values][]" value="{{ $v }}" class="form-control  {{ $class }} values" />
                        <div class="{{$class}} values-error d-none validation-error">
                            <label class="col-form-label text-danger"><i class="fas fa-bell"></i></label>
                        </div>
                    </div>
                </td>

                <td class="form-group">
                    <span class="{{$column}}-remove btn btn-warning btn-sm float-right">
                        <i class="fa fa-trash">&nbsp;</i>{{ __('admin.remove') }}
                    </span>
                </td>
            </tr>
            @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td></td>
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
                    <input name="{{ $name }}[keys][]" class="form-control" required/>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input name="{{ $name }}[values][]" class="form-control {{ $class }} values" />
                    <div class="{{$class}} values-error d-none validation-error">
                        <label class="col-form-label text-danger"><i class="fas fa-bell"></i></label>
                    </div>
                </div>
            </td>
            <td class="form-group">
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
        $('tbody.kv-{{ $column }}-table').append(tpl);
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
