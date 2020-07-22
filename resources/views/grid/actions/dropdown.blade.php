<div class="grid-dropdown-actions dropdown">
    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-ellipsis-v"></i>
    </a>
    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);left: -65px;top: 5px;">

        @foreach($default as $action)
            <li>{!! $action->render() !!}</li>
        @endforeach

        @if(!empty($custom))

            @if(!empty($default))
                <li class="divider"></li>
            @endif

            @foreach($custom as $action)
                <li>{!! $action->render() !!}</li>
            @endforeach
        @endif
    </ul>
</div>

<style>
    .grid-dropdown-actions {
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }
</style>

<script>
    $('.table-responsive').on('shown.bs.dropdown', function(e) {
        var t = $(this),
            m = $(e.target).find('.dropdown-menu'),
            tb = t.offset().top + t.height(),
            mb = m.offset().top + m.outerHeight(true),
            d = 20;
        if (t[0].scrollWidth > t.innerWidth()) {
            if (mb + d > tb) {
                t.css('padding-bottom', ((mb + d) - tb));
            }
        } else {
            t.css('overflow', 'visible');
        }
    }).on('hidden.bs.dropdown', function() {
        $(this).css({
            'padding-bottom': '',
            'overflow': ''
        });
    });
</script>

@if($dblclick)
<script>
    $('body').on('dblclick', 'table#{{ $table }}>tbody>tr', function(e) {
        $(this).find('.{{ $dblclick }}').trigger('click');
    });
</script>
@endif

@yield('child')
