<div>
    <span class="{{ $elementClass }}" data-inserted="0" data-key="{{ $key }}" data-name="{{ $name }}"
          data-toggle="collapse" data-target="#grid-collapse-{{ $name }}">
        <a href="javascript:void(0)"><i class="fa fa-angle-double-down"></i>&nbsp;&nbsp;{{ $value }}</a>
    </span>
    <template class="grid-expand-{{ $name }}">
        <tr style='background-color: #ecf0f5;'>
            <td colspan='100%' style='padding:0 !important; border:0;'>
                <div id="grid-collapse-{{ $name }}" class="collapse">
                    <div style="padding: 10px 10px 0 10px;" class="html">
                        @if($html)
                            {{ $html }}
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

<script>
    var expand = $('.{{ $elementClass }}');

    @if($async)

    var load = function (url, target) {
        $.get(url, function (data) {
            target.find('.html').html(data);
        });
    };

    expand.on('click', function (e) {
        var target = $(this);
        if (target.data('inserted') == '0') {
            var key  = target.data('key');
            var name = $(this).data('name');
            var row = $(this).closest('tr');

            row.after($('template.grid-expand-'+name).html());

            $(this).data('inserted', 1);

            load('{{ $url }}'+'&key='+key, $('#grid-collapse-'+name));
        }

        $("i", this).toggleClass("fa-angle-double-down fa-angle-double-up");
    });

    $(document).on('pjax:click', '.collapse a.pjax, .collapse a.pjax', function (e) {
        console.log(11111);
        // load($(this).attr('href'), $(this).parent('.collapse'));
        e.preventDefault();
        return false;
    }).on('pjax:submit', '.collapse .box-header form', function (e) {
        // load($(this).attr('action')+'&'+$(this).serialize(), $(this).parent('.collapse'));
        return false;
    });

    @else

    expand.on('click', function () {

        if ($(this).data('inserted') == '0') {

            var name = $(this).data('name');
            var row = $(this).closest('tr');

            row.after($('template.grid-expand-'+name).html());

            $(this).data('inserted', 1);
        }

        $("i", this).toggleClass("fa-angle-double-down fa-angle-double-up");
    });

    @endif

    @if ($expand)
        expand.trigger('click');
    @endif
</script>

@if($loadGrid)
<style>
    .collapse .grid-box .box-header:first-child {
        display: none;
    }
</style>
@endif