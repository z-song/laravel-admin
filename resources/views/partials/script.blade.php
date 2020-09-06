<script data-exec-on-popstate>
$(function () {
    $.admin.loadAssets(@json($dep), @json($js), @json($css), function(){
        @foreach($script as $s) {!! $s !!} @endforeach
    });
});
</script>

