<script>
$('{{ $selector }}').off('{{ $event }}').on('{{ $event }}', function() {
    var data = $(this).data();
    var $target = $(this);
    Object.assign(data, @json($parameters));

    {!! $action_script !!}

    new Promise(function (resolve,reject) {
        $.ajax({
            method: '{{ $method }}',
            url: '{{ $url }}',
            data: data,
            success: function (data) {
                resolve([data, $target]);
            },
            error:function(request){
                reject(request);
            }
        });
    }).then($.admin.action.then).catch($.admin.action.catch);
});
</script>
