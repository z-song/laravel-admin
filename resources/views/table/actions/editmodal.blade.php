<a href='{{ $url }}' class='{{ $selector }} dropdown-item' data-toggle="modal" data-target="#table-edit-modal">
    {{ $name }}
</a>

<script>
    $('#table-edit-modal').on('show.bs.modal', function (e) {
        $(this).find('.modal-body').load($(e.relatedTarget).attr('href')+'?_modal');
    });
</script>

<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="table-edit-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ admin_trans('admin.edit') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</template>
