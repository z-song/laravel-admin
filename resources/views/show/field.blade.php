<div class="list-group-item ">
    <label class="">{{ $label }}</label>
    <div class="pull-left">
        @if ($escape)
            {{ $content }}
        @else
            {!! $content !!}
        @endif
    </div>
    <div class="clearfix"></div>
</div>
