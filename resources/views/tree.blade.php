<div class="card card-@color card-outline">

    <div class="card-header">

        <div class="btn-group">
            <button class="btn btn-@color btn-sm {{ $id }}-tree-tools" data-action="expand" title="{{ admin_trans('admin.expand') }}">
                <i class="fa fa-plus-square-o"></i>&nbsp;{{ admin_trans('admin.expand') }}
            </button>
            <button class="btn btn-@color btn-sm {{ $id }}-tree-tools" data-action="collapse" title="{{ admin_trans('admin.collapse') }}">
                <i class="fa fa-minus-square-o"></i>&nbsp;{{ admin_trans('admin.collapse') }}
            </button>
        </div>

        @if($useSave)
        <div class="btn-group">
            <button class="btn btn-@color btn-sm {{ $id }}-save" title="{{ admin_trans('admin.save') }}"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;{{ admin_trans('admin.save') }}</span></button>
        </div>
        @endif

        <div class="btn-group">
            {!! $tools !!}
        </div>

        @if($useCreate)
        <div class="btn-group float-right">
            <a class="btn btn-success btn-sm" href="{{ url($path) }}/create"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;{{ admin_trans('admin.new') }}</span></a>
        </div>
        @endif

    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
    <!-- /.card-body -->
</div>

<script require="nestable">
    $('#{{ $id }}').nestable(@json($options));

    $('.{{ $id }}-save').click(function () {
        var serialize = $('#{{ $id }}').nestable('serialize');
        $.post('{{ $url }}', {
            _order: JSON.stringify(serialize)
        },
        function(data){
            $.admin.reload('{{ admin_trans('admin.save_succeeded') }}');
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
