@if(Session::has('toastr'))
    @php
        $toastr     = Session::get('toastr');
        $type       = array_get($toastr->get('type'), 0, 'success');
        $message    = array_get($toastr->get('message'), 0, '');
    @endphp
    <script>
        toastr.{{$type}}('{!!  $message  !!}');
    </script>
@endif