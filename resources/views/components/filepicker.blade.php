
<template>
    <div class="modal fade picker" id="{{ $modal }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 5px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{ admin_trans('admin.choose') }}</h4>
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

<script>

var pickInput = $("{{ $selector }}");
var separator = '{{ $separator }}';
var modal = $('#{{ $modal }}');
var value;
var refresh = function () {};

modal.on('show.bs.modal', function (e) {
    load("{!! $url !!}");
}).on('click', '.page-item a, .filter-box a', function (e) {
    load($(this).attr('href'));
    e.preventDefault();
}).on('click', 'tr', function (e) {
    $(this).find('input.select').iCheck('toggle');
    e.preventDefault();
}).on('submit', '.box-header form', function (e) {
    load($(this).attr('action')+'&'+$(this).serialize());
    return false;
})

@if($multiple)

    var updateValue = function () {
        value = pickInput.val().split(separator).filter(function (val) {
            return val != '';
        });
    };

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('input.select').iCheck({
                radioClass:'iradio_minimal-blue',
                checkboxClass:'icheckbox_minimal-blue'
            });
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index, el) {
                if ($.inArray($(el).val().toString(), value) >=0 ) {
                    $(el).iCheck('toggle');
                }
            });
        });
    };

    modal.on('ifChecked', 'input.select', function (e) {
        if ($(this).val().length == 0) {
            return;
        }

        if (value.indexOf($(this).val()) < 0) {
            value.push($(this).val());
        }
    }).on('ifUnchecked', 'input.select', function (e) {
        var val = $(this).val();
        var index = value.indexOf(val);
        if (index !== -1) {
            value.splice(index, 1);
        }
    }).find('.modal-footer .submit').click(function () {
        pickInput.val(value.join(separator));
        modal.modal('toggle');

        refresh();
    });

@else

    var updateValue = function () {
        value = pickInput.val();
    };

    var load = function (url) {
        $.get(url, function (data) {
            modal.find('.modal-body').html(data);
            modal.find('input.select').iCheck({
                radioClass:'iradio_minimal-blue',
                checkboxClass:'icheckbox_minimal-blue'
            });
            modal.find('.box-header:first').hide();

            modal.find('input.select').each(function (index, el) {
                if ($(el).val() == value) {
                    $(el).iCheck('toggle');
                }
            });
        });
    };

    modal.on('ifChecked', 'input.select', function (e) {
        value = $(this).val();
    }).find('.modal-footer .submit').click(function () {
        pickInput.val(value);
        modal.modal('toggle');

        refresh();
    });

@endif

updateValue();

$('.picker-file-preview').on('click', 'a.remove', function () {
    var preview = $(this).parents('.file-preview-frame');
    var current = preview.data('val');

    preview.addClass('hide');

    var input = pickInput.val().split(separator);

    var index = input.indexOf(current);value
    if (index !== -1) {
        input.splice(index, 1);
    }

    pickInput.val(input.join(separator));

    updateValue();

    if (input.length === 0) {
        $(this).parents('.picker-file-preview').addClass('hide');
    }
});

@if($is_file)
refresh = function () {

    var values = (typeof value == 'string') ? [value] : value;
    var preview = pickInput.parent().siblings('.picker-file-preview');
    var url_tpl = '{{ $url_tpl }}';

    @if($is_image)
    var template = $('template#image-preview')
    @else
    var template = $('template#file-preview')
    @endif

    preview.empty();

    values.forEach(function (item) {
        var url = url_tpl.replace('__URL__', item);
        preview.append(
            template.html()
                .replace(/_url_/g, url)
                .replace(/_val_/g, item)
                .replace(/_name_/g, url.split('/').pop())
        );
    });

    if (values.length > 0) {
        preview.removeClass('hide');
    }
};
@endif

</script>

<style>
    .picker.modal tr {
        cursor: pointer;
    }

    .picker.modal .box {
        border-top: none;
        margin-bottom: 0;
        box-shadow: none;
    }

    @if($is_file)
    .picker-file-preview {
        overflow: hidden;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 8px;
        width: 100%;
        margin-bottom: 5px;
    }

    .picker-file-preview .file-preview-frame {
        margin: 8px;
        border: 1px solid rgba(0, 0, 0, .2);
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, .2);
        padding: 6px;
        float: left;
        text-align: center;
        width: 213px;
    }

    .picker-file-preview .file-content {
        font-size: 6em;
    }

    .picker-file-preview .file-caption-info {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 160px;
        height: 20px;
        margin: auto;
        font-size: 11px;
        color: #777;
    }

    .picker-file-preview .file-actions {
        text-align: right;
        margin-top: 20px;
    }

    .picker-file-preview img {
        max-width: 160px;
        max-height: 160px;
    }
    @endif
</style>

