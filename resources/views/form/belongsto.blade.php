<div {!! admin_attrs($group_attrs) !!}>

<label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        <input type="hidden" name="{{$name}}"/>

        <select class="form-control {{$class}} d-none" style="width: 100%;" name="{{$name}}" {!! $attributes !!} >
            <option value=""></option>
            @foreach($options as $select => $option)
                <option value="{{$select}}" {{ $select == $value ?'selected':'' }}>{{$option}}</option>
            @endforeach
        </select>

        <div class="belongsto-{{ $class }}">
            {!! $table->render() !!}
            <template class="empty">
                @include('admin::table.empty-table')
            </template>
        </div>

        @include('admin::form.error')
        @include('admin::form.help-block')

    </div>
</div>

<template>
    <div class="modal fade belongsto" id="{{ $modal }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 5px;">
                <div class="modal-header">
                    <h4 class="modal-title">{{ admin_trans('admin.choose') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loading text-center">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ admin_trans('admin.cancel') }}</button>
                    <button type="button" class="btn btn-primary submit">{{ admin_trans('admin.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .belongsto.modal tr {
        cursor: pointer;
    }
    .belongsto.modal .card {
        border-top: none;
        margin-bottom: 0;
        box-shadow: none;
    }

    .belongsto.modal .loading {
        margin: 50px;
    }

    .belongsto.modal .table-table .empty-table {
        padding: 20px !important;
    }

    .belongsto.modal .table-table .empty-table svg {
        width: 60px !important;
        height: 60px !important;
    }

    .belongsto.modal .table-box .box-footer {
        border-top: none !important;
    }
</style>

<script require="select2">
    var table = $('.belongsto-field-{{ $column }}');
    var modal = $('#{{ $modal }}');
    //    var table = table.find('.table-table');
    var selected = $("{{ $selector }}").val();
    var row = null;

    // open modal
    table.find('.select-relation').click(function (e) {
        modal.modal('show');
        e.preventDefault();
    });

    // remove row
    table.on('click', '.table-row-remove', function () {
        selected = null;
        $(this).parents('tr').remove();
        $("{{ $selector }}").val(null);

        var empty = $('.belongsto-{{ $column }}').find('template.empty').html();

        table.find('tbody').append(empty);
    });

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index, el) {
                if ($(el).val() == selected) {
                    $(el).prop('checked', true);
                }
            });
        });
    };

    var update = function (callback) {

        $("{{ $selector }}")
            .select2({data: [selected]})
            .val(selected)
            .trigger('change')
            .next()
            .addClass('d-none');

        if (row) {
            row.find('td:last a').removeClass('d-none');
            row.find('td:first').remove();
            table.find('tbody').empty().append(row);
        }

        callback();
    };

    modal.on('show.bs.modal', function (e) {
        load("{!! $url !!}");
    }).on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('click', 'tr', function (e) {
        $(this).find('input.select').each(function(){
            this.checked = !this.checked;
        }).trigger('change');
        e.preventDefault();
    }).on('submit', '.box-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        return false;
    }).on('change', 'input.select', function (e) {
        if (this.checked) {
            row = $(e.target).parents('tr');
            selected = $(this).val();
        }
    }).find('.modal-footer .submit').click(function () {
        update(function () {
            modal.modal('toggle');
        });
    });
</script>
