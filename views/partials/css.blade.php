@foreach($css as $c)
<link rel="stylesheet" href="{{ asset("/bower_components/$c") }}">
@endforeach