<script data-exec-on-popstate>
require(@json($requires), function ({{ implode(',', $exports) }}) {
    @foreach($script as $s)

{!! $s !!}
    @endforeach
});
</script>

