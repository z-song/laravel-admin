@if($modal)
    <a href="{{ $url }}" class="btn btn-sm btn-@color table-create-btn mr-2" data-toggle="modal" data-target="#table-create-modal">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;{{ admin_trans('admin.new') }}</span>
    </a>
<script>
    $('#table-create-modal').on('show.bs.modal', function (e) {
        if ($(this).data('loaded') != 1) {
            $(this).find('.modal-body').load($(e.relatedTarget).attr('href')+'?_modal');
            $(this).data('loaded', 1);
        }
    });
</script>

    <template>
        <div class="modal fade" tabindex="-1" role="dialog" id="table-create-modal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ admin_trans('admin.new') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    </template>
@else
    <a href="{{ $url }}" class="btn btn-sm btn-@color table-create-btn mr-2">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;{{ admin_trans('admin.new') }}</span>
    </a>
@endif
