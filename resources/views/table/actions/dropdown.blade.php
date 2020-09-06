<div class="table-dropdown-actions dropdown">
    <a href="javascript:void(0);" style="padding: 0 10px;" data-toggle="dropdown">
        <i class="fa fa-ellipsis-v"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);top: 5px;">

        @foreach($default as $action)
            {!! $action->render() !!}
        @endforeach

        @if(!empty($custom))

            @if(!empty($default))
                <div class="divider"></div>
            @endif

            @foreach($custom as $action)
               {!! $action->render() !!}
            @endforeach
        @endif
    </div>
</div>

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
