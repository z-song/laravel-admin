@if(Session::has('success'))
    @php $success = Session::get('success');@endphp
    <script>
        toastr.success('{{ array_get($success->get('message'), 0) }}');
    </script>
@endif