<span data-toggle="modal" data-target="#grid-modal-{{ $name }}" data-key="{{ $key }}">
   <a href="javascript:void(0)"><i class="fa fa-clone"></i>&nbsp;&nbsp;{{ $value }}</a>
</span>

<div class="modal grid-modal fade" id="grid-modal-{{ $name }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <div class="modal-body">
                {!! $html !!}
            </div>
        </div>
    </div>
</div>

@if($grid)
<style>
    .box.grid-box {
        box-shadow: none;
        border-top: none;
    }

    .grid-box .box-header:first-child {
        display: none;
    }
</style>
@endif

@if($async)
<script>
    var modal = $('.grid-modal');
    var modalBody = modal.find('.modal-body');

    var load = function (url) {

        modalBody.html("<div class='loading text-center' style='height:200px;'>\
                <i class='fa fa-spinner fa-pulse fa-3x fa-fw' style='margin-top: 80px;'></i>\
            </div>");

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
    }).on('submit', '.box-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        return false;
    });
</script>
@endif
