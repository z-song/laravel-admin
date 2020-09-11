<span class="table-selector" data-toggle="modal" data-target="#{{ $modal }}" key="{{ $key }}" data-val="{{ $original }}">
   <a href="javascript:void(0)" class="text-muted">

       <span class="text">{!! $value !!}</span>
       <i class="far fa-check-square" style="visibility: hidden;"></i>&nbsp;&nbsp;
   </a>
</span>

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

    .table-table tr:hover .table-selector>a>i {
        visibility: visible !important;
    }

</style>

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
                    <button type="button" class="btn btn-@color submit">{{ admin_trans('admin.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    var $modal = $('#{{ $modal }}');
    var $related = null;

    var update = function (callback) {
        $.put({
            url: "{{ $resource }}/" + $related.attr('key'),
            data: {'{{ $name }}': selected.length ? selected : [''], __inline_edit: 1},
        }).done(function (data) {
            callback(data);
        });
    };

@if($relation == \Encore\Admin\Table\Displayers\BelongsTo::class)
    var selected = null;

    var load = function (url) {
        $.get(url, function (data) {
            $modal.find('.modal-body').html(data);
            $modal.find('.card-header:first').hide();

            $modal.find('input.select').each(function (index,    el) {
                if ($(el).val() == selected) {
                    $(el).prop('checked', !$(el).prop('checked'));
                }
            });
        });
    };

    $modal.on('show.bs.modal', function (e) {
        $related = $(e.relatedTarget);
        selected = $related.data('val');
        load("{!! $url !!}");
    }).on('change', 'input[type=radio]', function () {
        if (this.checked) {
            selected = $(this).val();
        }
    }).find('.modal-footer .submit').click(function () {
        update(function (data) {
            $related.data('val', selected);
            $related.find('.text').html(data.display["{{ $name }}"]);
            $related.find('a').toggleClass('text-green text-muted');

            setTimeout(function () {
                $related.find('a').toggleClass('text-green text-muted');
            }, 2000);

            $modal.modal('toggle');

            $.admin.toastr.success(data.message);
        });
    });

@else

    var selected = [];

    var load = function (url) {
        $.get(url, function (data) {
            $modal.find('.modal-body').html(data);
            $modal.find('.card-header:first').hide();

            $modal.find('input.select').each(function (index, el) {
                if ($.inArray($(el).val().toString(), selected) >=0 ) {
                    $(el).prop('checked', !$(el).prop('checked'));
                }
            });
        });
    };

    $modal.on('show.bs.modal', function (e) {
        $related = $(e.relatedTarget);
        selected = $related.data('val').map(function (value) {
            return value.toString();
        });

        load("{!! $url !!}");
    }).on('change', 'input.select', function () {
        var val = $(this).val().toString();
        if (this.checked) {
            if (selected.indexOf(val) < 0) {
                selected.push(val);
            }
        } else {
            var index = selected.indexOf(val);
            if (index !== -1) {
                selected.splice(index, 1);
            }
        }
    }).find('.modal-footer .submit').click(function () {
        update(function (data) {
            $related.data('val', selected);
            $related.find('.text').html(data.display["{{ $name }}"]);
            $related.find('a').toggleClass('text-green text-muted');

            setTimeout(function () {
                $related.find('a').toggleClass('text-green text-muted');
            }, 2000);

            $modal.modal('toggle');

            $.admin.toastr.success(data.message);
        });
    });
@endif
    $modal.on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('click', 'tr', function (e) {
        $(this).find('input.select').each(function(){
            this.checked = !this.checked;
        }).trigger('change');
        e.preventDefault();
    }).on('submit', '.card-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        e.preventDefault();
        return false;
    });

</script>
