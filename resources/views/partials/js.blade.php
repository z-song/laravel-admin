@foreach($js as $j)
<script src="{{ admin_asset ("$j") }}" @if (is_object($j) && $j->inPjax()) class="admin-js" @endif></script>
@endforeach