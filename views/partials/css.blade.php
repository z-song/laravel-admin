@foreach($css as $c)
<link rel="stylesheet" href="{{ asset("/packages/admin/$c") }}">
@endforeach