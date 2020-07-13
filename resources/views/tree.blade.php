<div class="box">

    <div class="box-header">

        <div class="btn-group">
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="expand" title="{{ trans('admin.expand') }}">
                <i class="fa fa-plus-square-o"></i>&nbsp;{{ trans('admin.expand') }}
            </a>
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="collapse" title="{{ trans('admin.collapse') }}">
                <i class="fa fa-minus-square-o"></i>&nbsp;{{ trans('admin.collapse') }}
            </a>
        </div>

        @if($useSave)
        <div class="btn-group">
            <a class="btn btn-info btn-sm {{ $id }}-save" title="{{ trans('admin.save') }}"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;{{ trans('admin.save') }}</span></a>
        </div>
        @endif

        @if($useRefresh)
        <div class="btn-group">
            <a class="btn btn-warning btn-sm {{ $id }}-refresh" title="{{ trans('admin.refresh') }}"><i class="fa fa-refresh"></i><span class="hidden-xs">&nbsp;{{ trans('admin.refresh') }}</span></a>
        </div>
        @endif

        <div class="btn-group">
            {!! $tools !!}
        </div>

        @if($useCreate)
        <div class="btn-group pull-right">
            <a class="btn btn-success btn-sm" href="{{ url($path) }}/create"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;{{ trans('admin.new') }}</span></a>
        </div>
        @endif

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
    <!-- /.box-body -->
</div>


<script require="nsetable">
    $('#{{ $id }}').nestable(@json($options));

    $('.tree_branch_delete').click(function() {
        var id = $(this).data('id');
        swal({
            title: "{{ admin_trans('admin.delete_confirm') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "{{ admin_trans('admin.confirm') }}",
            showLoaderOnConfirm: true,
            cancelButtonText: "{{ admin_trans('admin.cancel') }}",
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        method: 'POST',
                        url: '{{ $url }}/' + id,
                        data: {
                            _method:'delete',
                        }
                    }).done(function (data) {
                        $.pjax.reload('#pjax-container');
                        toastr.success('{{ admin_trans('admin.delete_succeeded') }}');
                        resolve(data);
                    });
                });
            }
        }).then(function(result) {
            var data = result.value;
            if (typeof data === 'object') {
                if (data.status) {
                    swal(data.message, '', 'success');
                } else {
                    swal(data.message, '', 'error');
                }
            }
        });
    });

    $('.{{ $id }}-save').click(function () {
        var serialize = $('#{{ $id }}').nestable('serialize');

        $.post('{{ $url }}', {
                _order: JSON.stringify(serialize)
            },
            function(data){
                $.pjax.reload('#pjax-container');
                toastr.success('{{ admin_trans('admin.save_succeeded') }}');
            });
    });

    $('.{{ $id }}-refresh').click(function () {
        $.pjax.reload('#pjax-container');
        toastr.success('{{ admin_trans('admin.refresh_succeeded') }}');
    });

    $('.{{ $id }}-tree-tools').on('click', function(e){
        var action = $(this).data('action');
        if (action === 'expand') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse') {
            $('.dd').nestable('collapseAll');
        }
    });
</script>
