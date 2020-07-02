<script data-exec-on-popstate>
$(function () {
    $.when($.admin.loadJs(@json($js)), $.admin.loadCss(@json($css))).done(function () {
        @foreach($script as $s) {!! $s !!} @endforeach
    });

    {{--$.admin.loadJs(@json($js)).then(function () {--}}
    {{--    console.log(123);--}}
    {{--    --}}{{--$.admin.loadCss(@json($css));--}}
    {{--}).done(function () {--}}
    {{--    @foreach($script as $s) {!! $s !!} @endforeach--}}
    {{--});--}}

    {{--$.admin.loadJs(@json($js))--}}
    {{--    .then(function () {--}}
    {{--        $.admin.loadCss(@json($css))--}}
    {{--    }).then(function(){--}}
    {{--        @foreach($script as $s) {!! $s !!} @endforeach--}}
    {{--    });--}}
});
</script>

