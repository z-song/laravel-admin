@foreach($css as $c)
    <link rel="stylesheet" href="{{ admin_url("$c") }}">
@endforeach