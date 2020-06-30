@if(!empty($pjaxJs))
    @foreach($pjaxJs as $js)
    <script src="{{ $js }}"></script>
    @endforeach
@endif

@if(!empty($script))
<script data-exec-on-popstate type="text/javascript">$(function () {@foreach($script as $s) {!! $s !!} @endforeach});</script>
@endif
