<script>
$('{{ $selector }}').off('{{ $event }}').on('{{ $event }}', function() {
    var data = $(this).data();
    var $target = $(this);
    var url = $(this).attr('url') || '{{ $url }}';
    Object.assign(data, @json($parameters));

    {!! $action_script !!}

    var options = {
        icon: 'question',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: '{{ admin_trans('admin.submit') }}',
        cancelButtonText: '{{ admin_trans('admin.cancel') }}',
        confirmButtonColor: '#d33',
    };

    Object.assign(options, @json($options));

    options.preConfirm = function(input) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                method: '{{ $method }}',
                url: url,
                data: data
            }).done(function (data) {
                resolve(data);
            }).fail(function(request){
                reject(request);
            });
        });
    };

    $.admin.swal.fire(options).then(function(result) {
        if (typeof result.dismiss !== 'undefined') {
            return Promise.reject();
        }
        return [result.value, $target];
    }).then($.admin.action.then).catch($.admin.action.catch);
});
</script>
