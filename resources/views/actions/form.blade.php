<script>

$('{{ $selector }}').off('{{ $event }}').on('{{ $event }}', function() {
    var data = $(this).data();
    var $target = $(this);
    var $modal = $('#'+$(this).attr('modal'));

    Object.assign(data, @json($parameters));

    {!! $action_script !!}
    $modal.modal('show');
    $modal.find('form').off('submit').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var _promise = new Promise(function (resolve,reject) {
            Object.assign(data, {
                _action: '{{ $class }}',
            });

            var formData = new FormData(form);
            for (var key in data) {
                formData.append(key, data[key]);
            }

            $.ajax({
                method: '{{ $method }}',
                url: '{{ $url }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    resolve([data, $target]);
                    if (data.status === true) {
                    $modal.modal('hide');
                    }
                },
                error:function(request){
                    reject(request);
                }
            });
        });

        @if (!empty($confirm))
        var process = $.admin.swal({
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: '{{ trans('admin.submit') }}',
            cancelButtonText: '{{ trans('admin.cancel') }}',
            title: '{{ $confirm }}',
            text: '',
            preConfirm: function() {
                return _promise;
            }
        }).then(function(result) {
            if (typeof result.dismiss !== 'undefined') {
                return Promise.reject();
            }
            var result = result.value[0];

            if (typeof result.status === "boolean") {
                var response = result;
            } else {
                var response = result.value;
            }

            return [response, $target];
        });
        @else
        var process = _promise;
        @endif
        {!! $promise !!}
    });
});

</script>

<template>
    <div class="modal" tabindex="-1" role="dialog" id="{{ $modal_id }}">
        <div class="modal-dialog {{ $modal_size }}" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $title }}</h4>
                </div>
                <form>
                    <div class="modal-body">
                        @foreach($fields as $field)
                            {!! $field->render() !!}
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('admin.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('admin.submit') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>