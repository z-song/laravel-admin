<div class="card card-@color card-outline">

    <div class="card-header">

        <div class="btn-group">
            <button class="btn btn-@color btn-sm {{ $id }}-tree-tools" data-action="expand" title="{{ trans('admin.expand') }}">
                <i class="fa fa-plus-square-o"></i>&nbsp;{{ trans('admin.expand') }}
            </button>
            <button class="btn btn-@color btn-sm {{ $id }}-tree-tools" data-action="collapse" title="{{ trans('admin.collapse') }}">
                <i class="fa fa-minus-square-o"></i>&nbsp;{{ trans('admin.collapse') }}
            </button>
        </div>

        @if($useSave)
        <div class="btn-group">
            <button class="btn btn-@color btn-sm {{ $id }}-save" title="{{ trans('admin.save') }}"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;{{ trans('admin.save') }}</span></button>
        </div>
        @endif

        <div class="btn-group">
            {!! $tools !!}
        </div>

        @if($useCreate)
        <div class="btn-group float-right">
            <a class="btn btn-success btn-sm" href="{{ url($path) }}/create"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;{{ trans('admin.new') }}</span></a>
        </div>
        @endif

    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive no-padding">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
    <!-- /.card-body -->
</div>


<script require="nsetable">
    $('#{{ $id }}').nestable(@json($options));

    $('.tree_branch_delete').click(function() {
        var id = $(this).data('id');
        $.admin.swal.fire({
            title: "{{ admin_trans('admin.delete_confirm') }}",
            icon: "warning",
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
                        $.admin.toastr.success('{{ admin_trans('admin.delete_succeeded') }}');
                        resolve(data);
                    });
                });
            }
        }).then(function(result) {
            var data = result.value;
            if (typeof data === 'object') {
                if (data.status) {
                    $.admin.swal.fire(data.message, '', 'success');
                } else {
                    $.admin.swal.fire(data.message, '', 'error');
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
                $.admin.toastr.success('{{ admin_trans('admin.save_succeeded') }}');
            });
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
