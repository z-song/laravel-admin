@foreach($js as $j)
    @if(strpos($j, 'http') !== false)
        <script src="{{ $j }}"></script>
    @else
        <script src="{{ asset ("/bower_components/$j") }}"></script>
    @endif
@endforeach