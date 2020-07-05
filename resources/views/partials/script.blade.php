<script data-exec-on-popstate>
$(function () {
    $.admin.loadAssets(@json($js), @json($css))
        .then(function(){
            @foreach($script as $s) {!! $s !!} @endforeach
        });
});
</script>

