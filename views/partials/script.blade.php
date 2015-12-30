<script>
    $(function () {
        @foreach($script as $s)
            {!! $s !!}
        @endforeach
    });
</script>