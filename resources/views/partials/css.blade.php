@foreach($css as $c)
    <link rel="stylesheet" href="{{ admin_asset("$c") }}" @if (is_object($c) && $c->inPjax()) class="admin-css" @endif>
@endforeach