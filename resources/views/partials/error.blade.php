@if(Session::has('error') || Session::has('errors'))
    <?php $error = Session::get('error');?>
    <?php $errors = Session::get('errors');?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        @if (!empty($error))
        <h4><i class="icon fa fa-ban"></i>{{ array_get($error->get('title'), 0) }}</h4>
        @endif
        @if (!empty($error))
        <p>{!!  array_get($error->get('message'), 0) !!}</p>
        @endif
        @if (!empty($errors))
            @foreach($errors->getBags() as $messageBag)
                @foreach($messageBag->toArray() as $message)
                    <p>{!!  array_get($message, 0) !!}</p>
                @endforeach
            @endforeach
        @endif
    </div>
@endif