<span data-toggle="modal" data-target="#table-modal-{{ $name }}" data-key="{{ $key }}">
   <a href="javascript:void(0)"><i class="fa fa-clone"></i>&nbsp;&nbsp;{{ $value }}</a>
</span>

<div class="modal table-modal fade {{ $mark }}" id="table-modal-{{ $name }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                {!! $html !!}
            </div>
        </div>
    </div>
</div>

@if($table)
<style>
    .card.table-card {
        box-shadow: none;
        border-top: none;
    }

    .table-card .card-header:first-child {
        display: none;
    }
</style>
@endif

@if($async)
<script>
    var modal = $('.{{ $mark }}');
    var modalBody = modal.find('.modal-body');

    var load = function (url) {
        $.get(url, function (data) {
            modalBody.html(data);
        });
    };

    modal.on('show.bs.modal', function (e) {
        var key = $(e.relatedTarget).data('key');
        load('{{ $url }}'+'&key='+key);
    }).on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('submit', '.card-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        return false;
    });
</script>
@endif
