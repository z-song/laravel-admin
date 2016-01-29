@foreach($js as $j)
    @if(strpos($j, 'http') !== false)
        <script src="{{ $j }}"></script>
    @else
        <script src="{{ asset ("/packages/admin/$j") }}"></script>
    @endif
@endforeach