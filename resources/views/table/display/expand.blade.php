<div>
    <span class="{{ $elementClass }}" data-inserted="0" data-key="{{ $key }}" data-name="{{ $name }}"
          data-toggle="collapse" data-target="#table-collapse-{{ $name }}">
        <a href="javascript:void(0)"><i class="fa fa-angle-double-down"></i>&nbsp;&nbsp;{{ $value }}</a>
    </span>
    <template class="table-expand-{{ $name }}">
        <tr style='background-color: #ecf0f5;'>
            <td colspan='100%' style='padding:0 !important; border:0;'>
                <div id="table-collapse-{{ $name }}" class="collapse">
                    <div style="padding: 10px 10px 0 10px;" class="html">
                        @if($html)
                            {!! $html !!}
                        @else
                            <div class="loading text-center" style="padding: 20px 0px;">
                                <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </template>
</div>

<script selector=".{{ $elementClass }}" all="true">
    @if($async)
    var load = function (url, target) {
        $.get(url, function (data) {
            target.find('.html').html(data);
        });
    };

    $(this).on('click', function (e) {
        var target = $(this);
        if (target.data('inserted') == '0') {
            var key  = target.data('key');
            var name = $(this).data('name');
            var row = $(this).closest('tr');
            row.after($('template.table-expand-'+name).html());
            $(this).data('inserted', 1);
            load('{{ $url }}'+'&key='+key, $('#table-collapse-'+name));
        }
        $('i', this).toggleClass('fa-angle-double-down fa-angle-double-up');
    });

    $('.table-card').on('pjax:click click', '.collapse .page-item a, .card-footer a', function (e) {
        load($(this).attr('href'), $(this).closest('.collapse'));
        e.preventDefault();
        return false;
    }).on('submit', '.collapse .card-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize(), $(this).closest('.collapse'));
        e.preventDefault();
        return false;
    });
    @else
    $(this).on('click', function () {
        if ($(this).data('inserted') == '0') {
            var name = $(this).data('name');
            var row = $(this).closest('tr');
            row.after($('template.table-expand-'+name).html());
            $(this).data('inserted', 1);
        }
        $("i", this).toggleClass("fa-angle-double-down fa-angle-double-up");
    });
    @endif
    @if ($expand)
        $(this).trigger('click');
    @endif
</script>

@if($loadTable)
<style>
    .collapse .table-card .card-header:first-child {
        display: none;
    }
</style>
@endif
