@if(Session::has('toastr'))
    @php
        $toastr     = Session::get('toastr');
        $type       = \Illuminate\Support\Arr::get($toastr->get('type'), 0, 'success');
        $message    = \Illuminate\Support\Arr::get($toastr->get('message'), 0, '');
        $options    = json_encode($toastr->get('options', []));
    @endphp
    <script>
        $(function () {
            $.admin.toastr.{{$type}}('{!!  $message  !!}', {!! $options !!});
        });
    </script>
@endif
